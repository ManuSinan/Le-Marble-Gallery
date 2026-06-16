<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
 
class TaxController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        return view('backend/tax/index', compact(  'search' ));
    }
 
    function list(Tax $tax) 
    {
        $query = $tax;
        $query = $query->select(
        'taxs.id',     
        'taxs.name',         
        'taxs.short',        
        'taxs.percentage'    
        );
        $data = $this->datatable(
            $query,
            function ($query) {
                $search = request('search.value') ?? '';
                if (!empty($search)) {
                    $query->orWhere('taxs.name', 'LIKE', "%{$search}%");  
                    $query->orWhere('taxs.local_name', 'LIKE', "%{$search}%");  
                    $query->orWhere('taxs.short', 'LIKE', "%{$search}%");  
                    $query->orWhere('taxs.local_short', 'LIKE', "%{$search}%");  
                    $query->orWhere('taxs.percentage', 'LIKE', "%{$search}%");  
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
                    'short' => $row->short,
                    'percentage' => $row->percentage,
                    'actions' => view('backend/tax/actions', compact('row'))->render(),
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
            'local_name' => [ 'nullable', 'max:100' ],
            'short' => [ 'required', 'max:10' ],
            'local_short' => [ 'nullable', 'max:30' ],
            'percentage' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:999.99' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only([ 'name',  'local_name',  'short',  'local_short',  'percentage' ]);

            Tax::create($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Tax',
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
                'title' => 'Tax',
                'text' => 'Created successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
 
    public function edit(Tax $tax)
    {
 
        return response()->json([
            'jquery' => [
                [
                    'element' => '#edit-form .modal-content',
                    'method' => 'html',
                    'value' => view('backend/tax/edit', compact(  'tax' ))->render(),
                ],
            ],
            'init' => ['#edit-form .modal-content'],
            'modal' => [
                'show' => '#edit-form'
            ]
        ]);
    }
 
    public function update(Request $request, Tax $tax)
    {
        $validator = Validator::make(request()->all(), [
            'name' => [ 'required', 'max:100' ],
            'local_name' => [ 'nullable', 'max:100' ],
            'short' => [ 'required', 'max:10' ],
            'local_short' => [ 'nullable', 'max:30' ],
            'percentage' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:999.99' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only([ 'name',  'local_name',  'short',  'local_short',  'percentage' ]);

            $tax->update($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Tax',
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
                'title' => 'Tax',
                'text' => 'Updated successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
 
    public function destroy(Tax $tax)
    {
        try {
            $tax->delete();
        } catch (\Exception $e) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Tax',
                    'text' => 'Tax can\'t be deleted! as it is in use',
                ],
            ]);
        }
        return response()->json([
            'datatable' => [
                'reload' => true,
            ],
            'alert' => [
                'icon' => 'success',
                'title' => 'Tax',
                'text' => 'Deleted successfully.',
            ],
        ]);
    }
}