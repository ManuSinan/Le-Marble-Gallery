<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Tax;
use App\Models\ProductTax;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\ImageManagerStatic as Image;
 
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        return view('backend/product/index', compact('search'));
    }
 
    function list(Product $product) 
    {
        $query = $product;
        $query = $query->select(
        'products.id',     
        'products.name',     
        'brands.name as brand_name', 
        'categories.name as category_name',  
        'products.stock_status', 
        'products.product_code',   
        'products.stock_available', 
        'units.type as unit_type', 
        'units.name as unit_name',       
        'products.selling_price',
        'products.status'    
        );
        $query = $query->leftJoin('attributes', 'attributes.id', '=', 'products.attribute_id');
        $query = $query->leftJoin('brands', 'brands.id', '=', 'products.brand_id');
        $query = $query->leftJoin('categories', 'categories.id', '=', 'products.category_id');
        $query = $query->leftJoin('units', 'units.id', '=', 'products.unit_id');
        $data = $this->datatable(
            $query,
            function ($query) {
                $search = request('search.value') ?? '';
                if (!empty($search)) {
                    $search = preg_replace('!\s+!', ' ', $search);

                    if(substr( strtoupper($search), 0, 2) == '::'){
                        $search = substr($search, 2);
                        $query->where('products.product_code', $search); 
                    }elseif(substr( strtoupper($search), 0, 1) == ':'){
                        $search = substr($search, 1);
                        $query->where('products.id', $search);   
                    }else{
                        $query->orWhere('products.name', 'LIKE', "%{$search}%");  
                        $query->orWhere('products.local_name', 'LIKE', "%{$search}%");  
                        $query->orWhere('products.description', 'LIKE', "%{$search}%");  
                        $query->orWhere('products.local_description', 'LIKE', "%{$search}%");  
                        $query->orWhere('products.combination_key', 'LIKE', "%{$search}%");  
                        $query->orWhere('products.product_code', 'LIKE', "%{$search}%");  
                        $query->orWhere('products.keywords', 'LIKE', "%{$search}%");  
                        $query->orWhere('products.status', 'LIKE', "%{$search}%");  
                        $query->orWhere('attributes.name', 'LIKE', "%{$search}%");  
                        $query->orWhere('brands.name', 'LIKE', "%{$search}%");  
                        $query->orWhere('categories.name', 'LIKE', "%{$search}%"); 
                        $match = '+"' . $search . '"'; 
                        $query->orWhereRaw('MATCH (magic_search) AGAINST (? IN BOOLEAN MODE)', [ $match ]);
                    }
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
                    'product_code' => $row->product_code,
                    'name' => Str::limit($row->name, 26, '..'),
                    'brand_name' => $row->brand_name,
                    'category_name' => $row->category_name,
                    'stock_available' => $row->stock_status == 'unlimited' ? 'Unlimited' : $row->unit_type .': ' . $row->stock_available . ' ' . $row->unit_name ,
                    'selling_price' => priceFormat($row->selling_price),
                    'status' => ucwords( $row->status ),
                    'actions' => view('backend/product/actions', compact('row'))->render(),
                    ];
                }
                return $data;
            }
        );
        return response()->json($data);
    }
 

    public function stock(Request $request)
    {
        $search = $request->get('search');
        return view('backend/product/stock', compact('search'));
    }
 
    function stocklist(Product $product) 
    {
        $query = $product;
        $query = $query->select(
        'products.id',     
        'products.name',     
        'brands.name as brand_name', 
        'categories.name as category_name',  
        'products.stock_status',     
        'products.stock_available', 
        'units.type as unit_type', 
        'units.name as unit_name',       
        'products.selling_price',
        'products.status'    
        );
        $query = $query->leftJoin('attributes', 'attributes.id', '=', 'products.attribute_id');
        $query = $query->leftJoin('brands', 'brands.id', '=', 'products.brand_id');
        $query = $query->leftJoin('categories', 'categories.id', '=', 'products.category_id');
        $query = $query->leftJoin('units', 'units.id', '=', 'products.unit_id');
        $data = $this->datatable(
            $query,
            function ($query) {
                
                
                $query->where(function($query){
                    $search = request('search.value') ?? '';
                    $search = preg_replace('!\s+!', ' ', $search);

                    if (!empty($search)) {
                        if(substr( strtoupper($search), 0, 2) == '::'){
                            $search = substr($search, 2);
                            $query->where('products.product_code', $search); 
                        }elseif(substr( strtoupper($search), 0, 1) == ':'){
                            $search = substr($search, 1);
                            $query->where('products.id', $search);   
                        }else{
                            $query->orWhere('products.name', 'LIKE', "%{$search}%");  
                            $query->orWhere('products.local_name', 'LIKE', "%{$search}%");  
                            $query->orWhere('products.description', 'LIKE', "%{$search}%");  
                            $query->orWhere('products.local_description', 'LIKE', "%{$search}%");  
                            $query->orWhere('products.combination_key', 'LIKE', "%{$search}%");  
                            $query->orWhere('products.product_code', 'LIKE', "%{$search}%");  
                            $query->orWhere('products.keywords', 'LIKE', "%{$search}%");  
                            $query->orWhere('categories.name', 'LIKE', "%{$search}%"); 
                            $match = '+"' . $search . '"'; 
                            $query->orWhereRaw('MATCH (magic_search) AGAINST (? IN BOOLEAN MODE)', [ $match ]);
                        }                  
                    }
                });

                $query->where('products.stock_status', 'limited');
                $query->whereIn('products.status', ['published', 'active']);

                $max = request('max') ?? '';

                if($max != ''){
                    $query->where('products.stock_available', '<=', $max);
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
                    'product_code' => $row->product_code,
                    'name' => Str::limit($row->name, 26, '..'),
                    'category_name' => $row->category_name,
                    'stock_available' => $row->stock_available,
                    'unit' =>  view('backend/product/stock-actions', compact('row'))->render()
                    ];
                }
                return $data;
            }
        );
        return response()->json($data);
    }


    public function create(Request $request)
    {
        $attributes = Attribute::all();
        $brands = Brand::all();
        $categories = Category::whereNull('parent_id')->get();
        $units = Unit::all();
        $taxs = Tax::all();

        $groups = Product::distinct()->where('combination_key', '!=', null)->get(['combination_key']);;

        return view('backend/product/create', compact(  'attributes',  'brands',  'categories',  'units',  'taxs', 'groups' ));
    }
 
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => [ 'required', 'max:100' ],
            'slug' => ['required', 'unique:products,slug', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'max:255'],
            'attribute_id' => [ 'nullable', 'integer' ],
            'brand_id' => [ 'nullable', 'integer' ],
            'category_id' => [ 'required', 'integer' ],
            'unit_id' => [ 'required', 'integer' ],
            'product_code' => [ 'nullable', 'unique:products,product_code', 'max:255' ],
            'stock_status' => [ 'required', 'max:10' ],
            'stock_available' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99' ],
            'minimum_quantity' => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99' ],
            'price' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' ],
            'selling_price' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' , 'lte:price'],
 
            'variable_product' => [ 'required'],
 
            'intra_state_tax' => ['array'],
            'intra_state_tax.*' => [ 'required', 'integer' ],

            'inter_state_tax' => ['array'],
            'inter_state_tax.*' => [ 'required', 'integer' ],

            'description' => [ 'nullable' ],
            'keywords' => [ 'nullable' ],
            // Allow more real-world image formats; keep server-side resizing best-effort.
            'image' => [ 'nullable', 'file', 'mimes:jpeg,jpg,png,webp,gif', 'max: 10240' ],

            'gallery_image_1' => [ 'nullable', 'file', 'mimes:jpeg,jpg,png,webp,gif', 'max: 10240' ],
            'gallery_image_2' => [ 'nullable', 'file', 'mimes:jpeg,jpg,png,webp,gif', 'max: 10240' ],
            'gallery_image_3' => [ 'nullable', 'file', 'mimes:jpeg,jpg,png,webp,gif', 'max: 10240' ],
 
            'combination_key' => [ 'nullable', 'max:255' ],
            'priority' => [ 'required', 'integer' ],
            'status' => [ 'required', 'max:10' ],
            
            'variants' => ['array'],
            'variants.*' => ['required', 'integer'],
            
            'local_name' => [ 'nullable', 'max:100' ],
            'local_description' => [ 'nullable' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

 
        $input = $request->only([ 'name', 'slug', 'attribute_id', 'brand_id', 'category_id', 'attribute_id', 'unit_id', 'product_code', 'stock_status', 'stock_available', 'minimum_quantity',  'price',  'selling_price',  'description',  'keywords',  'priority', 'status', 'combination_key', 'local_name', 'local_description', 'rich_description', 'gallery_video' ]);
      
        
        if($request->variable_product == 'yes' && $input['attribute_id'] == ''){
            return response()->json([
                'errors' => [
                    'attribute_id' => ['The Combination Attributes field is required.']
                ],
            ]); 
        }

        if($request->variable_product == 'yes' && $input['combination_key'] == ''){
            return response()->json([
                'errors' => [
                    'combination_key' => ['The combination name field is required.']
                ],
            ]); 
        }

        if($request->stock_status == 'limited' &&  $request->stock_available == null){
            return response()->json([
                'errors' => [
                    'stock_available' => ['The stock available field is required.']
                ],
            ]); 
        } 

        $unit = Unit::find($input['unit_id']);

        if($unit){

            if( $unit->stepper > $input['minimum_quantity'] )
            return response()->json([
                'errors' => [
                    'minimum_quantity' => ['The minimum quantity must greater than or equal to unit stepper.']
                ],
            ]); 

        }else{
            return response()->json([
                'errors' => [
                    'unit_id' => ['The invalid unit.']
                ],
            ]); 
        }
 
        if($input['combination_key']){
            $combination = Product::where('combination_key', $input['combination_key'])->first();
            if($combination){
                 if($combination->attribute_id !=  $request->attribute_id){
                 return response()->json([
                     'errors' => [
                         'attribute_id' => ['The Attribute not matching with combination key.']
                     ],
                 ]); 
                }else{


                    if($request->variants){

                        $getCombinations = [];
                        $productVariants = ProductVariant::where('combination_key', $input['combination_key'])->get();
                        foreach($productVariants as $productVariant){
                            $getCombinations[$productVariant->product_id][] = $productVariant->variant_id . '-' . $productVariant->variant_option_id;
                        }

                        $preCombination = [];
                        foreach($getCombinations as $combination){
                            $preCombination[] = implode('-', $combination);
                        }

                        $currentCombination = [];
                        foreach($request->variants as $variant => $option){
                            $currentCombination[] = $variant . '-' . $option;
                        }

                        $currentCombination = implode('-', $currentCombination);
 
                        if(in_array($currentCombination, $preCombination)){
                            return response()->json([
                                'errors' => [
                                    'attribute_id' => ['The Attribute matching combination already exists.']
                                ],
                            ]); 
                        }
                    }
 
                }
            }
        }

        DB::beginTransaction();
        try {

            $ensureProductImageDirs = function (string $directory): void {
                $disk = Storage::disk('public');
                $disk->makeDirectory('product/' . $directory . '/original');
                $disk->makeDirectory('product/' . $directory . '/large');
                $disk->makeDirectory('product/' . $directory . '/base');
            };

            if($request->hasFile('image')){ 
                
                $file = $request->file('image');
                $directory = Str::slug($request->slug, '-');
                $fileName = fileName( $file->extension() );
                
                $ensureProductImageDirs($directory);
                $originalPath = 'product/' .  $directory . '/original/' . $fileName;
                Storage::disk('public')->putFileAs('product/' .  $directory . '/original', $file, $fileName);
 
                $large = $this->imageResizeAndSave($file, 'product/' .  $directory . '/large/' . $fileName, 800, 600);
                $base = $this->imageResizeAndSave($file, 'product/'. $directory . '/base/' . $fileName, 400, 300);
     
                if( $large &&  $base){
                    $input['image'] = 'product/'. $directory . '/base/' . $fileName;
                }else{
                    // Don't block saving the product if resizing fails; keep original file.
                    $input['image'] = $originalPath;
                }
            }


            if($request->hasFile('gallery_image_1')){ 
                
                $file = $request->file('gallery_image_1');
                $directory = Str::slug($request->slug, '-');
                $fileName = fileName( $file->extension() );
                
                $ensureProductImageDirs($directory);
                $originalPath = 'product/' .  $directory . '/original/' . $fileName;
                Storage::disk('public')->putFileAs('product/' .  $directory . '/original', $file, $fileName);
 
                $large = $this->imageResizeAndSave($file, 'product/' .  $directory . '/large/' . $fileName, 800, 600);
                $base = $this->imageResizeAndSave($file, 'product/'. $directory . '/base/' . $fileName, 400, 300);
     
                if( $large &&  $base){
                    $input['gallery_image_1'] = 'product/'. $directory . '/base/' . $fileName;
                }else{
                    $input['gallery_image_1'] = $originalPath;
                }
            }


            if($request->hasFile('gallery_image_2')){ 
                
                $file = $request->file('gallery_image_2');
                $directory = Str::slug($request->slug, '-');
                $fileName = fileName( $file->extension() );
                
                $ensureProductImageDirs($directory);
                $originalPath = 'product/' .  $directory . '/original/' . $fileName;
                Storage::disk('public')->putFileAs('product/' .  $directory . '/original', $file, $fileName);
 
                $large = $this->imageResizeAndSave($file, 'product/' .  $directory . '/large/' . $fileName, 800, 600);
                $base = $this->imageResizeAndSave($file, 'product/'. $directory . '/base/' . $fileName, 400, 300);
     
                if( $large &&  $base){
                    $input['gallery_image_2'] = 'product/'. $directory . '/base/' . $fileName;
                }else{
                    $input['gallery_image_2'] = $originalPath;
                }
            }


            if($request->hasFile('gallery_image_3')){ 
                
                $file = $request->file('gallery_image_3');
                $directory = Str::slug($request->slug, '-');
                $fileName = fileName( $file->extension() );
                
                $ensureProductImageDirs($directory);
                $originalPath = 'product/' .  $directory . '/original/' . $fileName;
                Storage::disk('public')->putFileAs('product/' .  $directory . '/original', $file, $fileName);
 
                $large = $this->imageResizeAndSave($file, 'product/' .  $directory . '/large/' . $fileName, 800, 600);
                $base = $this->imageResizeAndSave($file, 'product/'. $directory . '/base/' . $fileName, 400, 300);
     
                if( $large &&  $base){
                    $input['gallery_image_3'] = 'product/'. $directory . '/base/' . $fileName;
                }else{
                    $input['gallery_image_3'] = $originalPath;
                }
            }

            $product = Product::create($input);
            Cache::forget('prodata');
            setOption( 'product_' . $product->id . '_private_note', $request->private_note);
            setOption( 'product_' . $product->id . '_meta_title', $request->meta_title);
            setOption( 'product_' . $product->id . '_meta_description', $request->meta_description);
            setOption( 'product_' . $product->id . '_meta_keywords', $request->meta_keywords);
            
            setOption( 'product_' . $product->id . '_related', $request->related);
            setOption( 'product_' . $product->id . '_fbt', $request->fbt);

        
            if($request->intra_state_tax){
                foreach($request->intra_state_tax as $intraStateTax){
                    ProductTax::create([
                        'product_id' => $product->id,
                        'tax_id' => $intraStateTax,
                        'type' => 'intra_state_tax'
                    ]);
                }
            }

            if($request->inter_state_tax){
                foreach($request->inter_state_tax as $interStateTax){
                    ProductTax::create([
                        'product_id' => $product->id,
                        'tax_id' => $interStateTax,
                        'type' => 'inter_state_tax'
                    ]);
                }
            }
 
            if($request->variants){
                foreach($request->variants as $variant => $option){
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'combination_key' => $product->combination_key,
                        'variant_id' => $variant,
                        'variant_option_id' => $option
                    ]);
                }
            }


            $keyword = $product->name;
            
            if($product->category && $product->category->name){
                $keyword .= ' '.  Str::singular($product->category->name);
            }
             
            if($product->brand && $product->brand->name){
                $keyword .= ' '. $product->brand->name;
            }
 
            $combinations = new \Combinations( array_unique ( explode(' ', $keyword) ) );

            $product->update([
                'magic_search' => $combinations->generate()
            ]);
 
        } catch (\Exception $e) {
            DB::rollback();
            report($e);

            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Book',
                    'text' => config('app.debug')
                        ? ('Something went wrong: ' . $e->getMessage())
                        : 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();
        
        return response()->json([
            'reset' => true,
            'alert' => [
                'icon' => 'success',
                'title' => 'Book',
                'text' => 'Created successfully.',
                'redirect' => route('product'),
            ],
        ]);
    }
 
    public function edit(Product $product)
    {
        $attributes = Attribute::all();
        $brands = Brand::all();
        $categories = Category::whereNull('parent_id')->get();
        $units = Unit::all();
        $taxs = Tax::all();
 
        $groups = Product::distinct()->where('attribute_id', $product->attribute_id)->where('combination_key', '!=', null)->get(['combination_key']);
 
        return view('backend/product/edit', compact(  'attributes',  'brands',  'categories',  'units', 'taxs', 'product', 'groups' ));
    }
 
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make(request()->all(), [
            'name' => [ 'required', 'max:100' ],
            'slug' => ['required', 'unique:products,slug,' . $product->id . ',id', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',  'max:255'],
            'attribute_id' => [ 'nullable', 'integer' ],
            'brand_id' => [ 'nullable', 'integer' ],
            'category_id' => [ 'required', 'integer' ],
            'unit_id' => [ 'required', 'integer' ],
            'product_code' => [ 'nullable', 'unique:products,product_code,' . $product->id . ',id', 'max:255' ],
            'stock_status' => [ 'required', 'max:10' ],
            'stock_available' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99' ],
            'minimum_quantity' => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99' ],
            'price' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' ],
            'selling_price' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' , 'lte:price'],


            'intra_state_tax' => ['array'],
            'intra_state_tax.*' => [ 'required', 'integer' ],

            'inter_state_tax' => ['array'],
            'inter_state_tax.*' => [ 'required', 'integer' ],

            'description' => [ 'nullable' ],
            'keywords' => [ 'nullable' ],
            // Allow more real-world image formats; keep server-side resizing best-effort.
            'image' => [ 'nullable', 'file', 'mimes:jpeg,jpg,png,webp,gif', 'max: 10240' ],

            'gallery_image_1' => [ 'nullable', 'file', 'mimes:jpeg,jpg,png,webp,gif', 'max: 10240' ],
            'gallery_image_2' => [ 'nullable', 'file', 'mimes:jpeg,jpg,png,webp,gif', 'max: 10240' ],
            'gallery_image_3' => [ 'nullable', 'file', 'mimes:jpeg,jpg,png,webp,gif', 'max: 10240' ],

            'variable_product' => [ 'required'],

            'combination_key' => [ 'nullable', 'max:255' ],
            'priority' => [ 'required', 'integer' ],
            'status' => [ 'required', 'max:10' ],
            
            'variants' => ['array'],
            'variants.*' => ['required', 'integer'],
            

            'local_name' => [ 'nullable', 'max:100' ],
            'local_description' => [ 'nullable' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        $input = $request->only([ 'name', 'slug', 'attribute_id', 'brand_id', 'category_id', 'attribute_id', 'unit_id', 'product_code', 'stock_status', 'stock_available', 'minimum_quantity',  'price',  'selling_price',  'description',  'keywords',  'priority', 'status', 'combination_key', 'local_name', 'local_description', 'rich_description', 'gallery_video' ]);
      
        if($request->variable_product == 'yes' && $input['attribute_id'] == ''){
            return response()->json([
                'errors' => [
                    'attribute_id' => ['The Combination Attributes field is required.']
                ],
            ]); 
        }

        if($request->variable_product == 'yes' && $input['combination_key'] == ''){
            return response()->json([
                'errors' => [
                    'combination_key' => ['The combination name field is required.']
                ],
            ]); 
        }


        if($request->stock_status == 'limited' &&  $request->stock_available == null){
            return response()->json([
                'errors' => [
                    'stock_available' => ['The stock available field is required.']
                ],
            ]); 
        } 

        $unit = Unit::find($input['unit_id']);

        if($unit){

            if( $unit->stepper > $input['minimum_quantity'] )
            return response()->json([
                'errors' => [
                    'minimum_quantity' => ['The minimum quantity must greater than or equal to unit stepper.']
                ],
            ]); 

        }else{
            return response()->json([
                'errors' => [
                    'unit_id' => ['The invalid unit.']
                ],
            ]); 
        }
 
        if($input['combination_key']){
            $combination = Product::where('combination_key', $input['combination_key'])->first();
            if($combination){
                 if($combination->attribute_id !=  $request->attribute_id){
                 return response()->json([
                     'errors' => [
                         'attribute_id' => ['The Attribute not matching with combination key.']
                     ],
                 ]); 
                }else{

                    if($request->variants){

                        $getCombinations = [];
                        $productVariants = ProductVariant::where('combination_key', $input['combination_key'])->where('product_id', '!=' , $product->id)->get();
                        foreach($productVariants as $productVariant){
                            $getCombinations[$productVariant->product_id][] = $productVariant->variant_id . '-' . $productVariant->variant_option_id;
                        }

                        $preCombination = [];
                        foreach($getCombinations as $combination){
                            $preCombination[] = implode('-', $combination);
                        }

                        $currentCombination = [];
                        foreach($request->variants as $variant => $option){
                            $currentCombination[] = $variant . '-' . $option;
                        }

                        $currentCombination = implode('-', $currentCombination);
 
                        if(in_array($currentCombination, $preCombination)){
                            return response()->json([
                                'errors' => [
                                    'attribute_id' => ['The Attribute matching combination already exists.']
                                ],
                            ]); 
                        }
                    }
 
                }
            }
        }
 
        DB::beginTransaction();
        try {

            $ensureProductImageDirs = function (string $directory): void {
                $disk = Storage::disk('public');
                $disk->makeDirectory('product/' . $directory . '/original');
                $disk->makeDirectory('product/' . $directory . '/large');
                $disk->makeDirectory('product/' . $directory . '/base');
            };

            if($request->hasFile('image')){ 
                
                $file = $request->file('image');
                $directory = Str::slug($request->slug, '-');
                $fileName = fileName( $file->extension() );
                
                $originalPath = 'product/' .  $directory . '/original/' . $fileName;
                $ensureProductImageDirs($directory);
                Storage::disk('public')->putFileAs('product/' .  $directory . '/original', $file, $fileName);
 
                $large = $this->imageResizeAndSave($file, 'product/' .  $directory . '/large/' . $fileName, 800, 600);
                $base = $this->imageResizeAndSave($file, 'product/'. $directory . '/base/' . $fileName, 400, 300);
     
                if( $large &&  $base){
                    $input['image'] = 'product/'. $directory . '/base/' . $fileName;
                }else{
                    $input['image'] = $originalPath;
                }
            }


            if($request->hasFile('gallery_image_1')){ 
                
                $file = $request->file('gallery_image_1');
                $directory = Str::slug($request->slug, '-');
                $fileName = fileName( $file->extension() );
                
                $originalPath = 'product/' .  $directory . '/original/' . $fileName;
                $ensureProductImageDirs($directory);
                Storage::disk('public')->putFileAs('product/' .  $directory . '/original', $file, $fileName);
 
                $large = $this->imageResizeAndSave($file, 'product/' .  $directory . '/large/' . $fileName, 800, 600);
                $base = $this->imageResizeAndSave($file, 'product/'. $directory . '/base/' . $fileName, 400, 300);
     
                if( $large &&  $base){
                    $input['gallery_image_1'] = 'product/'. $directory . '/base/' . $fileName;
                }else{
                    $input['gallery_image_1'] = $originalPath;
                }
            }


            if($request->hasFile('gallery_image_2')){ 
                
                $file = $request->file('gallery_image_2');
                $directory = Str::slug($request->slug, '-');
                $fileName = fileName( $file->extension() );
                
                $originalPath = 'product/' .  $directory . '/original/' . $fileName;
                $ensureProductImageDirs($directory);
                Storage::disk('public')->putFileAs('product/' .  $directory . '/original', $file, $fileName);
 
                $large = $this->imageResizeAndSave($file, 'product/' .  $directory . '/large/' . $fileName, 800, 600);
                $base = $this->imageResizeAndSave($file, 'product/'. $directory . '/base/' . $fileName, 400, 300);
     
                if( $large &&  $base){
                    $input['gallery_image_2'] = 'product/'. $directory . '/base/' . $fileName;
                }else{
                    $input['gallery_image_2'] = $originalPath;
                }
            }


            if($request->hasFile('gallery_image_3')){ 
                
                $file = $request->file('gallery_image_3');
                $directory = Str::slug($request->slug, '-');
                $fileName = fileName( $file->extension() );
                
                $originalPath = 'product/' .  $directory . '/original/' . $fileName;
                $ensureProductImageDirs($directory);
                Storage::disk('public')->putFileAs('product/' .  $directory . '/original', $file, $fileName);
 
                $large = $this->imageResizeAndSave($file, 'product/' .  $directory . '/large/' . $fileName, 800, 600);
                $base = $this->imageResizeAndSave($file, 'product/'. $directory . '/base/' . $fileName, 400, 300);
     
                if( $large &&  $base){
                    $input['gallery_image_3'] = 'product/'. $directory . '/base/' . $fileName;
                }else{
                    $input['gallery_image_3'] = $originalPath;
                }
            }
 
            $product->update($input);
            Cache::forget('prodata');

            setOption( 'product_' . $product->id . '_private_note', $request->private_note);
            setOption( 'product_' . $product->id . '_meta_title', $request->meta_title);
            setOption( 'product_' . $product->id . '_meta_description', $request->meta_description);
            setOption( 'product_' . $product->id . '_meta_keywords', $request->meta_keywords);
            setOption( 'product_' . $product->id . '_related', $request->related);
            setOption( 'product_' . $product->id . '_fbt', $request->fbt);
            
            if($request->intra_state_tax){
                $intraStateTaxDeleteIds = array_diff($product->intraStateTax->pluck('tax_id')->all(), $request->intra_state_tax);
 
                ProductTax::whereIn('tax_id', $intraStateTaxDeleteIds)->where('product_id', $product->id)->where('type', 'intra_state_tax')->delete();
     
                foreach($request->intra_state_tax as $intraStateTax){
                    
                    if( !in_array( $intraStateTax, $product->intraStateTax->pluck('tax_id')->all() ) ){
                        ProductTax::create([
                            'product_id' => $product->id,
                            'tax_id' => $intraStateTax,
                            'type' => 'intra_state_tax'
                        ]);
                    }
                }
            }else{
                
                $intraStateTaxDeleteIds = $product->intraStateTax->pluck('tax_id')->all();
 
                ProductTax::whereIn('tax_id', $intraStateTaxDeleteIds)->where('product_id', $product->id)->where('type', 'intra_state_tax')->delete();
            }

            if($request->inter_state_tax){
                $interStateTaxDeleteIds = array_diff($product->interStateTax->pluck('tax_id')->all(), $request->inter_state_tax);
 
                ProductTax::whereIn('tax_id', $interStateTaxDeleteIds)->where('product_id', $product->id)->where('type', 'inter_state_tax')->delete();
     
                foreach($request->inter_state_tax as $interStateTax){
                    
                    if( !in_array( $interStateTax, $product->interStateTax->pluck('tax_id')->all() ) ){
                        ProductTax::create([
                            'product_id' => $product->id,
                            'tax_id' => $interStateTax,
                            'type' => 'inter_state_tax'
                        ]);
                    }
                }
            }else{
                $interStateTaxDeleteIds = $product->interStateTax->pluck('tax_id')->all();
 
                ProductTax::whereIn('tax_id', $interStateTaxDeleteIds)->where('product_id', $product->id)->where('type', 'inter_state_tax')->delete();  
            }
 
            ProductVariant::where('product_id', $product->id)->delete();
 
            if($request->variants){
                foreach($request->variants as $variant => $option){
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'combination_key' => $product->combination_key,
                        'variant_id' => $variant,
                        'variant_option_id' => $option
                    ]);
                }
            }

            $keyword = $product->name;
            
            if($product->category && $product->category->name){
                $keyword .= ' ' .  Str::singular($product->category->name);
            }
             
            if($product->brand && $product->brand->name){
                $keyword .= ' '. $product->brand->name;
            }
 
            $combinations = new \Combinations( array_unique ( explode(' ', $keyword) ) );

            $product->update([
                'magic_search' => $combinations->generate()
            ]);

            notify($product->id);

        } catch (\Exception $e) {
            DB::rollback();
            report($e);
 
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Book',
                    'text' => config('app.debug')
                        ? ('Something went wrong: ' . $e->getMessage())
                        : 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();

        webhookEvents('product/update', $product->id);

        return response()->json([
            'alert' => [
                'icon' => 'success',
                'title' => 'Book',
                'text' => 'Updated successfully.',
            ],
        ]);
    }


    public function copy(Product $product)
    {
        $attributes = Attribute::all();
        $brands = Brand::all();
        $categories = Category::all();
        $units = Unit::all();
        $taxs = Tax::all();

        $groups = Product::distinct()->where('combination_key', '!=', null)->get(['combination_key']);;
 
        return view('backend/product/copy', compact(  'attributes',  'brands',  'categories',  'units', 'taxs', 'product', 'groups' ));
    }

 
    public function destroy(Product $product)
    {
 
        DB::beginTransaction();
        try {
            
            ProductTax::where('product_id', $product->id)->delete();
            ProductVariant::where('product_id', $product->id)->delete();
            $product->delete();

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Book',
                    'text' => 'Book can\'t be deleted! as it is in use',
                ],
            ]);
        }
        DB::commit();
        
        return response()->json([
            'datatable' => [
                'reload' => true,
            ],
            'alert' => [
                'icon' => 'success',
                'title' => 'Book',
                'text' => 'Deleted successfully.',
            ],
        ]);
    }

    public function attribute(Request $request)
    {

        $id = $request->value ?? null;
        $groups = null;   
        $attribute = null;    
        if($id){
            $attribute =  Attribute::find($id);
            $groups = Product::distinct()->where('attribute_id', $attribute->id)->where('combination_key', '!=', null)->get(['combination_key']);
        }

        return response()->json([
            'jquery' => [
                [
                    'element' => '#variants',
                    'method' => 'html',
                    'value' => view('backend/product/attribute', compact( 'attribute' ))->render(),
                ],

                [
                    'element' => '#combination-name-existing',
                    'method' => 'html',
                    'value' => view('backend/product/combination', compact( 'groups' ))->render(),
                ],
            ],
            'init' => ['#variants', '#combination-name-existing'],
        ]);
    }

    public function unit(Request $request)
    {

        $id = $request->value ?? null;

        $unit = null;    
        if($id){
            $unit =  Unit::find($id);
        }
 
        return response()->json([
            'jquery' => [
                [
                    'element' => '.stock-unit',
                    'method' => 'html',
                    'value' =>  'in ' . $unit->name,
                ],
                [
                    'element' => '#minimum_quantity',
                    'method' => 'val',
                    'value' => $unit->stepper,
                ],
                [
                    'element' => '.price-unit',
                    'method' => 'html',
                    'value' => 'for '. $unit->type . ': ' . $unit->stepper . ' ' . $unit->name,
                ],
            ],
        ]);
    }

}
