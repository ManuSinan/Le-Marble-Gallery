<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\VariantOption;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
 
class VariantOptionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $variants = Variant::all();
        return view('backend/variant-option/index', compact(  'variants',  'search' ));
    }
 
    function list(VariantOption $variantOption) 
    {
        $query = $variantOption;
        $query = $query->select(
        'variants.name as variant_name', 
        'variant_options.id',     
        'variant_options.value'      
        );
        $query = $query->leftJoin('variants', 'variants.id', '=', 'variant_options.variant_id');
        $data = $this->datatable(
            $query,
            function ($query) {


                $query->where(function($query){
                    $search = request('search.value') ?? '';
                    if (!empty($search)) {
                        $query->orWhere('variant_options.value', 'LIKE', "%{$search}%");  
                        $query->orWhere('variant_options.local_value', 'LIKE', "%{$search}%");  
                        $query->orWhere('variants.name', 'LIKE', "%{$search}%");  
                    }
                });

                $filterVariant = request('filter_variant') ?? null;
                if($filterVariant){
                    $query->where('variant_options.variant_id', $filterVariant); 
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
                    'variant_name' => $row->variant_name,
                    'value' => $row->value,
                    'actions' => view('backend/variant-option/actions', compact('row'))->render(),
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
            'variant_id' => [ 'required', 'integer' ],
            'value' => [ 'required', 'max:100' ],
            'local_value' => [ 'nullable', 'max:100' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only([ 'variant_id',  'value',  'local_value' ]);
 
    
            VariantOption::create($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Variant Option',
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
                'title' => 'Variant Option',
                'text' => 'Created successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
 
    public function edit(VariantOption $variantOption)
    {
        $variants = Variant::all();
 
        return response()->json([
            'jquery' => [
                [
                    'element' => '#edit-form .modal-content',
                    'method' => 'html',
                    'value' => view('backend/variant-option/edit', compact(  'variants',  'variantOption' ))->render(),
                ],
            ],
            'init' => ['#edit-form .modal-content'],
            'modal' => [
                'show' => '#edit-form'
            ]
        ]);
    }
 
    public function update(Request $request, VariantOption $variantOption)
    {
        $validator = Validator::make(request()->all(), [
            'variant_id' => [ 'required', 'integer' ],
            'value' => [ 'required', 'max:100' ],
            'local_value' => [ 'nullable', 'max:100' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only([ 'variant_id',  'value',  'local_value' ]);
 
    
            $variantOption->update($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Variant Option',
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
                'title' => 'Variant Option',
                'text' => 'Updated successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
 
    public function destroy(VariantOption $variantOption)
    {
        try {
            $variantOption->delete();
        } catch (\Exception $e) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Variant Option',
                    'text' => 'Variant Option can\'t be deleted! as it is in use',
                ],
            ]);
        }
        return response()->json([
            'datatable' => [
                'reload' => true,
            ],
            'alert' => [
                'icon' => 'success',
                'title' => 'Variant Option',
                'text' => 'Deleted successfully.',
            ],
        ]);
    }
}
