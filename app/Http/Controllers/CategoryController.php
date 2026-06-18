<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;
 
class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categories = Category::whereNull('parent_id')->get();
    
        $search = $request->get('search');
        return view('backend/category/index', compact(  'search', 'categories' ));
    }
 
    function list(Category $category) 
    {
        $query = $category;
        $query = $query->select(
        'categories.id',     
        'categories.name'               
        );
        $query = $query->whereNull('categories.parent_id');
        $data = $this->datatable(
            $query,
            function ($query) {
                $search = request('search.value') ?? '';
                if (!empty($search)) {
                    $query->orWhere('categories.name', 'LIKE', "%{$search}%");  
                    $query->orWhere('categories.slug', 'LIKE', "%{$search}%");  
                    $query->orWhere('categories.local_name', 'LIKE', "%{$search}%");  
                    $query->orWhere('categories.priority', 'LIKE', "%{$search}%");  
                }
            },
            function ($rows, $totalFiltered, $totalData) {
                $data = [];
                $start = request('start') ?? 0;
                $order = request('order.0.dir') ?? 'desc';
                $count = $totalFiltered - $start;
                $start = $start + 1;
                foreach ($rows as $row) {
                    $subCount = Category::where('parent_id', $row->id)->count();
                    $data[] = [
                    'id' => $order == 'desc' ? $start++ : $count--, 
                    'name' => $row->name,
                    'subcategories' => '<a href="' . route('subcategory.index', $row->id) . '" class="badge bg-blue-lt">' . $subCount . ' Sub-categories</a>',
                    'actions' => view('backend/category/actions', compact('row'))->render(),
                    ];
                }
                return $data;
            }
        );
        return response()->json($data);
    }
 
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:100'],
            'slug' => ['required', 'unique:categories,slug', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'max:255'],
            'local_name' => ['nullable', 'max:100'],
            'image' => ['nullable', 'file', 'mimes:jpeg,jpg,png', 'max:10240'],
            'priority' => ['required', 'integer'],
            'description' => ['nullable'],
        ]);
    
        if (!$validator->passes()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        DB::beginTransaction();
        try {
            $input = $request->only(['name', 'slug', 'local_name', 'priority', 'description']);
            $input['parent_id'] = null;
    
            // Handle Image Upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $directory = Str::slug($request->name, '-');
                $fileName = fileName($file->extension());
    
                Storage::disk('public')->putFileAs('category/' . $directory . '/original', $file, $fileName);
    
                $large = $this->imageResizeAndSave($file, 'category/' . $directory . '/large/' . $fileName, 400, 400);
                $base = $this->imageResizeAndSave($file, 'category/' . $directory . '/base/' . $fileName, 200, 200);
    
                if ($large && $base) {
                    $input['image'] = 'category/' . $directory . '/base/' . $fileName;
                } else {
                    return response()->json([
                        'errors' => ['image' => ['Unable to process image.']],
                    ]);
                }
            }
    
            $category = Category::create($input);
    
            setOption('category_' . $category->id . '_meta_title', $request->meta_title);
            setOption('category_' . $category->id . '_meta_description', $request->meta_description);
            setOption('category_' . $category->id . '_meta_keywords', $request->meta_keywords);
    
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Category',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
    
        DB::commit();
        return response()->json([
            'reset' => true,
            'modal' => ['hide' => '#create-form'],
            'alert' => [
                'icon' => 'success',
                'title' => 'Category',
                'text' => 'Created successfully.',
            ],
            'datatable' => ['reload' => true],
        ]);
    }
 
    public function edit(Category $category)
    {
 
        $categories = Category::whereNull('parent_id')->get();

        return response()->json([
            'jquery' => [
                [
                    'element' => '#edit-form .modal-content',
                    'method' => 'html',
                    'value' => view('backend/category/edit', compact('category', 'categories'))->render(),
                ],
            ],
            'init' => ['#edit-form .modal-content'],
            'modal' => [
                'show' => '#edit-form',
            ],
        ]);
    }
    
 
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make(request()->all(), [
            'name' => [ 'required', 'max:100' ],
            'slug' => ['required', 'unique:categories,slug,' . $category->id . ',id', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',  'max:255'],
            'local_name' => [ 'nullable', 'max:100' ],
            'image' => [ 'nullable', 'file', 'mimes:jpeg,jpg,png', 'max: 10240' ],
            'priority' => [ 'required', 'integer' ],
            'description' => [ 'nullable'],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only(['name', 'slug', 'local_name', 'priority', 'description']);
            $input['parent_id'] = null;
 
                
            if($request->hasFile('image')){ 
                
                $file = $request->file('image');
                $directory = Str::slug($request->name, '-');
                $fileName = fileName( $file->extension() );
                
                Storage::disk('public')->putFileAs('category/' .  $directory . '/original', $file, $fileName);
 
                $large = $this->imageResizeAndSave($file, 'category/' .  $directory . '/large/' . $fileName, 400, 400);
                $base = $this->imageResizeAndSave($file, 'category/'. $directory . '/base/' . $fileName, 200, 200);
     
                if( $large &&  $base){
                    $input['image'] = 'category/'. $directory . '/base/' . $fileName;
                }else{
                    return response()->json([
                        'errors' => [
                            'image' => ['Unable to process image.']
                        ],
                    ]); 
                }
            }
  
            $category->update($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Category',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();

        setOption( 'category_' . $category->id . '_meta_title', $request->meta_title);
        setOption( 'category_' . $category->id . '_meta_description', $request->meta_description);
        setOption( 'category_' . $category->id . '_meta_keywords', $request->meta_keywords);

        return response()->json([
            'reset' => true,
            'modal' => [
                'hide' => '#edit-form',
            ],
            'alert' => [
                'icon' => 'success',
                'title' => 'Category',
                'text' => 'Updated successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
 
    public function destroy(Category $category)
    {
        try {
            $category->delete();
        } catch (\Exception $e) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Category',
                    'text' => 'Category can\'t be deleted! as it is in use',
                ],
            ]);
        }
        return response()->json([
            'datatable' => [
                'reload' => true,
            ],
            'alert' => [
                'icon' => 'success',
                'title' => 'Category',
                'text' => 'Deleted successfully.',
            ],
        ]);
    }

    // ─── Subcategory Methods ──────────────────────────────────────────────────

    public function subcategoryIndex(Category $category)
    {
        $search = request()->get('search');
        return view('backend/category/subcategory/index', compact('category', 'search'));
    }

    public function subcategoryList(Category $category)
    {
        $query = Category::select('categories.id', 'categories.name')
            ->where('categories.parent_id', $category->id);

        $data = $this->datatable(
            $query,
            function ($query) {
                $search = request('search.value') ?? '';
                if (!empty($search)) {
                    $query->where('categories.name', 'LIKE', "%{$search}%");
                }
            },
            function ($rows, $totalFiltered, $totalData) use ($category) {
                $data = [];
                $start = request('start') ?? 0;
                $order = request('order.0.dir') ?? 'desc';
                $count = $totalFiltered - $start;
                $start = $start + 1;
                foreach ($rows as $row) {
                    $data[] = [
                        'id'      => $order == 'desc' ? $start++ : $count--,
                        'name'    => $row->name,
                        'actions' => view('backend/category/subcategory/actions', compact('row', 'category'))->render(),
                    ];
                }
                return $data;
            }
        );
        return response()->json($data);
    }

    public function subcategoryStore(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'max:100'],
            'slug'     => ['required', 'unique:categories,slug', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'max:255'],
            'priority' => ['required', 'integer'],
            'image'    => ['nullable', 'file', 'mimes:jpeg,jpg,png', 'max:10240'],
        ]);

        if (!$validator->passes()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();
        try {
            $input = $request->only(['name', 'slug', 'local_name', 'priority', 'description']);
            $input['parent_id'] = $category->id;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $directory = Str::slug($request->name, '-');
                $fileName = fileName($file->extension());
                Storage::disk('public')->putFileAs('category/' . $directory . '/original', $file, $fileName);
                $large = $this->imageResizeAndSave($file, 'category/' . $directory . '/large/' . $fileName, 400, 400);
                $base  = $this->imageResizeAndSave($file, 'category/' . $directory . '/base/' . $fileName, 200, 200);
                if ($large && $base) {
                    $input['image'] = 'category/' . $directory . '/base/' . $fileName;
                } else {
                    return response()->json(['errors' => ['image' => ['Unable to process image.']]]);
                }
            }

            $subcategory = Category::create($input);
            setOption('category_' . $subcategory->id . '_meta_title', $request->meta_title);
            setOption('category_' . $subcategory->id . '_meta_description', $request->meta_description);
            setOption('category_' . $subcategory->id . '_meta_keywords', $request->meta_keywords);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['alert' => ['icon' => 'error', 'title' => 'Sub-category', 'text' => 'Something went wrong.']]);
        }

        DB::commit();
        return response()->json([
            'reset'     => true,
            'modal'     => ['hide' => '#subcategory-create-form'],
            'alert'     => ['icon' => 'success', 'title' => 'Sub-category', 'text' => 'Created successfully.'],
            'datatable' => ['reload' => true],
        ]);
    }

    public function subcategoryEdit(Category $category, Category $subcategory)
    {
        return response()->json([
            'jquery' => [
                [
                    'element' => '#subcategory-edit-form .modal-content',
                    'method'  => 'html',
                    'value'   => view('backend/category/subcategory/edit', compact('category', 'subcategory'))->render(),
                ],
            ],
            'init'  => ['#subcategory-edit-form .modal-content'],
            'modal' => ['show' => '#subcategory-edit-form'],
        ]);
    }

    public function subcategoryUpdate(Request $request, Category $category, Category $subcategory)
    {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'max:100'],
            'slug'     => ['required', 'unique:categories,slug,' . $subcategory->id . ',id', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'max:255'],
            'priority' => ['required', 'integer'],
            'image'    => ['nullable', 'file', 'mimes:jpeg,jpg,png', 'max:10240'],
        ]);

        if (!$validator->passes()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();
        try {
            $input = $request->only(['name', 'slug', 'local_name', 'priority', 'description']);
            $input['parent_id'] = $category->id;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $directory = Str::slug($request->name, '-');
                $fileName = fileName($file->extension());
                Storage::disk('public')->putFileAs('category/' . $directory . '/original', $file, $fileName);
                $large = $this->imageResizeAndSave($file, 'category/' . $directory . '/large/' . $fileName, 400, 400);
                $base  = $this->imageResizeAndSave($file, 'category/' . $directory . '/base/' . $fileName, 200, 200);
                if ($large && $base) {
                    $input['image'] = 'category/' . $directory . '/base/' . $fileName;
                } else {
                    return response()->json(['errors' => ['image' => ['Unable to process image.']]]);
                }
            }

            $subcategory->update($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['alert' => ['icon' => 'error', 'title' => 'Sub-category', 'text' => 'Something went wrong.']]);
        }
        DB::commit();

        setOption('category_' . $subcategory->id . '_meta_title', $request->meta_title);
        setOption('category_' . $subcategory->id . '_meta_description', $request->meta_description);
        setOption('category_' . $subcategory->id . '_meta_keywords', $request->meta_keywords);

        return response()->json([
            'reset'     => true,
            'modal'     => ['hide' => '#subcategory-edit-form'],
            'alert'     => ['icon' => 'success', 'title' => 'Sub-category', 'text' => 'Updated successfully.'],
            'datatable' => ['reload' => true],
        ]);
    }

    public function subcategoryDestroy(Category $category, Category $subcategory)
    {
        try {
            $subcategory->delete();
        } catch (\Exception $e) {
            return response()->json(['alert' => ['icon' => 'error', 'title' => 'Sub-category', 'text' => "Sub-category can't be deleted! as it is in use"]]);
        }
        return response()->json([
            'datatable' => ['reload' => true],
            'alert'     => ['icon' => 'success', 'title' => 'Sub-category', 'text' => 'Deleted successfully.'],
        ]);
    }
}
