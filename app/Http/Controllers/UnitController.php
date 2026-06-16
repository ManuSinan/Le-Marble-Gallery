<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
 
class UnitController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        return view('backend/unit/index', compact(  'search' ));
    }
 
    function list(Unit $unit) 
    {
        $query = $unit;
        $query = $query->select(
        'units.id',     
        'units.type',          
        'units.name',     
        'units.stepper'    
        );
        $data = $this->datatable(
            $query,
            function ($query) {
                $search = request('search.value') ?? '';
                if (!empty($search)) {
                    $query->orWhere('units.type', 'LIKE', "%{$search}%");  
                    $query->orWhere('units.local_type', 'LIKE', "%{$search}%");
                    $query->orWhere('units.name', 'LIKE', "%{$search}%");  
                    $query->orWhere('units.local_name', 'LIKE', "%{$search}%");
                    $query->orWhere('units.stepper', 'LIKE', "%{$search}%");  
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
                    'type' => $row->type,
                    'local_type' => $row->local_type,
                    'name' => $row->name,
                    'local_name' => $row->local_name,
                    'stepper' => $row->stepper,
                    'actions' => view('backend/unit/actions', compact('row'))->render(),
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
            'type' => [ 'required', 'max:25' ],
            'local_type' => [ 'nullable', 'max:25' ],
            'name' => [ 'required', 'max:25' ],
            'local_name' => [ 'nullable', 'max:25' ],
            'stepper' => [ 'required', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99', 'gt:0' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only([ 'type',  'local_type',  'name',  'local_name',  'stepper',  ]);
 
      
            Unit::create($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Unit',
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
                'title' => 'Unit',
                'text' => 'Created successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
 
    public function edit(Unit $unit)
    {
 
        return response()->json([
            'jquery' => [
                [
                    'element' => '#edit-form .modal-content',
                    'method' => 'html',
                    'value' => view('backend/unit/edit', compact(  'unit' ))->render(),
                ],
            ],
            'init' => ['#edit-form .modal-content'],
            'modal' => [
                'show' => '#edit-form'
            ]
        ]);
    }
 
    public function update(Request $request, Unit $unit)
    {
        $validator = Validator::make(request()->all(), [
            'type' => [ 'required', 'max:25' ],
            'local_type' => [ 'nullable', 'max:25' ],
            'name' => [ 'required', 'max:25' ],
            'local_name' => [ 'nullable', 'max:25' ],
            'stepper' => [ 'required', 'regex:/^\d+(\.\d{1,2})?$/', 'lt:99999999.99', 'gt:0'],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only([ 'type',  'local_type',  'name',  'local_name',  'stepper',  ]);
 
      
            $unit->update($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Unit',
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
                'title' => 'Unit',
                'text' => 'Updated successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
 
    public function destroy(Unit $unit)
    {
        try {
            $unit->delete();
        } catch (\Exception $e) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Unit',
                    'text' => 'Unit can\'t be deleted! as it is in use',
                ],
            ]);
        }
        return response()->json([
            'datatable' => [
                'reload' => true,
            ],
            'alert' => [
                'icon' => 'success',
                'title' => 'Unit',
                'text' => 'Deleted successfully.',
            ],
        ]);
    }
}
