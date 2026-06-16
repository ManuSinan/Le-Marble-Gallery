<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\BannerSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;

class BannerSliderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        return view('backend/banner-slider/index', compact(  'search' ));
    }
 
    function list(BannerSlider $bannerSlider) 
    {
        $query = $bannerSlider;
        $query = $query->select(
        'banner_sliders.id',     
        'banner_sliders.name',     
        'banner_sliders.image',    
        );
        $data = $this->datatable(
            $query,
            function ($query) {
                $search = request('search.value') ?? '';
                if (!empty($search)) {
                    $query->orWhere('banner_sliders.name', 'LIKE', "%{$search}%");  
                    $query->orWhere('banner_sliders.image', 'LIKE', "%{$search}%");
                }
            },
            function ($rows, $totalFiltered, $totalData) {
                $data = [];
                $start = request('start') ?? 0;
                $order = request('order.0.dir') ?? 'desc';
                $count = $totalFiltered - $start;
                $start = $start + 1;
                foreach ($rows as $row) {
                    $data[] = [
                    'id' => $order == 'desc' ? $start++ : $count--, 
                    'name' => view('backend/banner-slider/list-image', compact('row'))->render(),
                    'actions' => view('backend/banner-slider/actions', compact('row'))->render(),
                    ];
                }
                return $data;
            }
        );
        return response()->json($data);
    }
 
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => [ 'required', 'max:100' ],
            'image' => [ 'required', 'file', 'image', 'max: 10240' ],
            'priority' => [ 'required', 'integer' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only([ 'name',   'priority']);
 
            if($request->hasFile('image')){ 
                
                $file = $request->file('image');
                $directory = Str::slug($request->name, '-');
                $fileName = fileName( $file->extension() );
                
                Storage::disk('public')->putFileAs('banner/' .  $directory . '/original', $file, $fileName);
 
                $large = $this->imageResizeAndSave($file, 'banner/' .  $directory . '/large/' . $fileName, 2400, 800);
                $base = $this->imageResizeAndSave($file, 'banner/'. $directory . '/base/' . $fileName, 1200, 400);
     
                if( $large &&  $base){
                    $input['image'] = 'banner/'. $directory . '/base/' . $fileName;
                }else{
                    return response()->json([
                        'errors' => [
                            'image' => ['Unable to process image.']
                        ],
                    ]); 
                }
            }

            BannerSlider::create($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Banner Slider',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();
        return response()->json([
            'reset' => true,
            'modal' => [
                'hide' => '#create-form',
            ],
            'alert' => [
                'icon' => 'success',
                'title' => 'Banner Slider',
                'text' => 'Created successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
 
    public function edit(BannerSlider $bannerSlider)
    {
        return response()->json([
            'jquery' => [
                [
                    'element' => '#edit-form .modal-content',
                    'method' => 'html',
                    'value' => view('backend/banner-slider/edit', compact(  'bannerSlider' ))->render(),
                ],
            ],
            'init' => ['#edit-form .modal-content'],
            'modal' => [
                'show' => '#edit-form'
            ]
        ]);
    }
 
    public function update(Request $request, BannerSlider $bannerSlider)
    {
        $validator = Validator::make(request()->all(), [
            'name' => [ 'required', 'max:100' ],
            'image' => [ 'nullable', 'file', 'image', 'max: 10240' ],
            'priority' => [ 'required', 'integer' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only([ 'name',   'priority' ]);

            if($request->hasFile('image')){ 
                
                $file = $request->file('image');
                $directory = Str::slug($request->name, '-');
                $fileName = fileName( $file->extension() );
                
                Storage::disk('public')->putFileAs('banner/' .  $directory . '/original', $file, $fileName);
 
                $large = $this->imageResizeAndSave($file, 'banner/' .  $directory . '/large/' . $fileName, 2400, 800);
                $base = $this->imageResizeAndSave($file, 'banner/'. $directory . '/base/' . $fileName, 1200, 400);
     
                if( $large &&  $base){
                    $input['image'] = 'banner/'. $directory . '/base/' . $fileName;
                }else{
                    return response()->json([
                        'errors' => [
                            'image' => ['Unable to process image.']
                        ],
                    ]); 
                }
            }
  
            $bannerSlider->update($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Banner Slider',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();
        return response()->json([
            'reset' => true,
            'modal' => [
                'hide' => '#edit-form',
            ],
            'alert' => [
                'icon' => 'success',
                'title' => 'Banner Slider',
                'text' => 'Updated successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
 
    public function destroy(BannerSlider $bannerSlider)
    {
        try {
            $bannerSlider->delete();
        } catch (\Exception $e) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Banner Slider',
                    'text' => 'Banner Slider can\'t be deleted! as it is in use',
                ],
            ]);
        }
        return response()->json([
            'datatable' => [
                'reload' => true,
            ],
            'alert' => [
                'icon' => 'success',
                'title' => 'Banner Slider',
                'text' => 'Deleted successfully.',
            ],
        ]);
    }
}
