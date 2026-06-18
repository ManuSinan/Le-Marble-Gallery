<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        return view('backend/brand/index', compact(  'search' ));
    }
 
    function list(Brand $brand) 
    {
        $query = $brand;
        $query = $query->select(
        'brands.id',     
        'brands.name'         
        );
        $data = $this->datatable(
            $query,
            function ($query) {
                $search = request('search.value') ?? '';
                if (!empty($search)) {
                    $query->orWhere('brands.name', 'LIKE', "%{$search}%");  
                    $query->orWhere('brands.slug', 'LIKE', "%{$search}%");  
                    $query->orWhere('brands.image', 'LIKE', "%{$search}%");  
                    $query->orWhere('brands.priority', 'LIKE', "%{$search}%");  
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
                    'name' => $row->name,
                    'actions' => view('backend/brand/actions', compact('row'))->render(),
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
            'slug' => ['required', 'unique:brands,slug', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'max:255'],
            'image' => [ 'nullable', 'file', 'mimes:jpeg,jpg,png', 'max: 10240' ],
            'priority' => [ 'required', 'integer' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only([ 'name', 'slug', 'priority']);
 
 

            if($request->hasFile('image')){ 
                
                $file = $request->file('image');
                $directory = Str::slug($request->name, '-');
                $fileName = fileName( $file->extension() );
                
                Storage::disk('public')->putFileAs('brand/' .  $directory . '/original', $file, $fileName);

                $large = $this->imageResizeAndSave($file, 'brand/'. $directory . '/large/' . $fileName, 560, 400);
                $base = $this->imageResizeAndSave($file, 'brand/'. $directory . '/base/' . $fileName, 200, 150);
     
                if($large && $base){
                    $input['image'] = 'brand/'. $directory . '/base/' . $fileName;
                }else{
                    return response()->json([
                        'errors' => [
                            'image' => ['Unable to process image.']
                        ],
                    ]); 
                }
            }

            Brand::create($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Brand',
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
                'title' => 'Brand',
                'text' => 'Created successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
 
    public function edit(Brand $brand)
    {
 
        return response()->json([
            'jquery' => [
                [
                    'element' => '#edit-form .modal-content',
                    'method' => 'html',
                    'value' => view('backend/brand/edit', compact(  'brand' ))->render(),
                ],
            ],
            'init' => ['#edit-form .modal-content'],
            'modal' => [
                'show' => '#edit-form'
            ]
        ]);
    }
 
    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make(request()->all(), [
            'name' => [ 'required', 'max:100' ],
            'slug' => ['required', 'unique:brands,slug,' . $brand->id . ',id', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',  'max:255'],
            'image' => [ 'nullable', 'file', 'mimes:jpeg,jpg,png', 'max: 10240' ],
            'priority' => [ 'required', 'integer' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only([ 'name',  'slug', 'priority' ]);
 
               
            if($request->hasFile('image')){ 
                
                $file = $request->file('image');
                $directory = Str::slug($request->name, '-');
                $fileName = fileName( $file->extension() );
                
                Storage::disk('public')->putFileAs('brand/' .  $directory . '/original', $file, $fileName);

                $large = $this->imageResizeAndSave($file, 'brand/'. $directory . '/large/' . $fileName, 560, 400);
                $base = $this->imageResizeAndSave($file, 'brand/'. $directory . '/base/' . $fileName, 200, 150);
     
                if($large && $base){
                    $input['image'] = 'brand/'. $directory . '/base/' . $fileName;
                }else{
                    return response()->json([
                        'errors' => [
                            'image' => ['Unable to process image.']
                        ],
                    ]); 
                }
            }
            
            $brand->update($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Brand',
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
                'title' => 'Brand',
                'text' => 'Updated successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
 
    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();
        } catch (\Exception $e) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Brand',
                    'text' => 'Brand can\'t be deleted because it is in use.',
                ],
            ]);
        }
        return response()->json([
            'datatable' => [
                'reload' => true,
            ],
            'alert' => [
                'icon' => 'success',
                'title' => 'Brand',
                'text' => 'Deleted successfully.',
            ],
        ]);
    }
}
