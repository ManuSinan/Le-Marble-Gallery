<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Location;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
 
class LocationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $states = State::all();
        return view('backend/location/index', compact(  'search', 'states' ));
    }
 
    function list(Location $location) 
    {
        $query = $location;
        $query = $query->select(
        'locations.id',     
        'locations.name',        
        'locations.minimum_cart_amount',     
        'locations.delivery_charge'    
        );
        $data = $this->datatable(
            $query,
            function ($query) {
                $search = request('search.value') ?? '';
                if (!empty($search)) {
                    $query->orWhere('locations.id', 'LIKE', "%{$search}%");  
                    $query->orWhere('locations.name', 'LIKE', "%{$search}%");  
                    $query->orWhere('locations.local_name', 'LIKE', "%{$search}%");  
                    $query->orWhere('locations.minimum_cart_amount', 'LIKE', "%{$search}%");  
                    $query->orWhere('locations.delivery_charge', 'LIKE', "%{$search}%");  
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
                    'minimum_cart_amount' => $row->minimum_cart_amount,
                    'delivery_charge' => $row->delivery_charge,
                    'actions' => view('backend/location/actions', compact('row'))->render(),
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
            'state_id' => [ 'nullable', 'integer' ],
            'minimum_cart_amount' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' ],
            'delivery_charge' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' ],
            'delivery_cart_amount' => [ 'nullable', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            
            $input = $request->only([ 'name',  'local_name', 'state_id', 'minimum_cart_amount', 'delivery_cart_amount',  'delivery_charge' ]);
 
            Location::create($input);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Location',
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
                'title' => 'Location',
                'text' => 'Created successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
 
    public function edit(Location $location)
    {   

        $states = State::all();
 
        return response()->json([
            'jquery' => [
                [
                    'element' => '#edit-form .modal-content',
                    'method' => 'html',
                    'value' => view('backend/location/edit', compact(  'location', 'states' ))->render(),
                ],
            ],
            'init' => ['#edit-form .modal-content'],
            'modal' => [
                'show' => '#edit-form'
            ]
        ]);
    }
 
    public function update(Request $request, Location $location)
    {
        $validator = Validator::make(request()->all(), [
            'name' => [ 'required', 'max:100' ],
            'local_name' => [ 'nullable', 'max:100' ],
            'state_id' => [ 'nullable', 'integer' ],
            'minimum_cart_amount' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' ],
            'delivery_charge' => [ 'required', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' ],
            'delivery_cart_amount' => [ 'nullable', 'regex:/^\d+(\.\d{1,3})?$/', 'lt:99999999.999' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only([ 'name',  'local_name', 'state_id', 'minimum_cart_amount',  'delivery_charge', 'delivery_cart_amount' ]);
 
            $location->update($input);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Location',
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
                'title' => 'Location',
                'text' => 'Updated successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
 
    public function destroy(Location $location)
    {
        try {
            $location->delete();
        } catch (\Exception $e) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Location',
                    'text' => 'Location can\'t be deleted! as it is in use',
                ],
            ]);
        }
        return response()->json([
            'datatable' => [
                'reload' => true,
            ],
            'alert' => [
                'icon' => 'success',
                'title' => 'Location',
                'text' => 'Deleted successfully.',
            ],
        ]);
    }
}
