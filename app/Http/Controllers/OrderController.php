<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
 
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $salesmanRole = \App\Models\Role::where('name', 'Salesman')->first();
        $salesmen = $salesmanRole ? User::where('role_id', $salesmanRole->id)->get() : User::where('role_id', 4)->get();
        return view('backend/order/index', compact('search', 'salesmen'));
    }
 
    function list(Order $order) 
    {
        $query = $order;
        $query = $query->select(
        'users.name as name', 
        'users.email as user_email', 
        'orders.id',          
        'orders.address_location',     
        'orders.canceled_amount',     
        'orders.final_amount',
        'orders.created_at',     
        'orders.status'    
        );
        $query = $query->leftJoin('users', 'users.id', '=', 'orders.user_id');
        $data = $this->datatable(
            $query,
            function ($query) {
                $query->where(function($query){
                    $search = request('search.value') ?? '';
                    if (!empty($search)) {
                        $searchArray = explode('-',$search);
                        $searchId = $searchArray[0] ?? $search;

                        if (!empty($search)) {
                            $query->orWhere('orders.id', 'LIKE', "%{$searchId}%");  
                            $query->orWhere('orders.address_type', 'LIKE', "%{$search}%");  
                            $query->orWhere('orders.address_name', 'LIKE', "%{$search}%");  
                            $query->orWhere('orders.address_mobile', 'LIKE', "%{$search}%");  
                            $query->orWhere('orders.address_line_1', 'LIKE', "%{$search}%");  
                            $query->orWhere('orders.address_line_2', 'LIKE', "%{$search}%");  
                            $query->orWhere('orders.address_line_3', 'LIKE', "%{$search}%");  
                            $query->orWhere('orders.address_location', 'LIKE', "%{$search}%");  
                            $query->orWhere('orders.discount_code', 'LIKE', "%{$search}%");  
                            $query->orWhere('orders.status', 'LIKE', "%{$search}%");  
                            $query->orWhere('users.name', 'LIKE', "%{$search}%");  
                            $query->orWhere('users.email', 'LIKE', "%{$search}%");
                        }
                    }
                });

                if ($salesmanId = request('salesman_id')) {
                    $query->where('orders.user_id', $salesmanId);
                }

                if (!hasPermission('order.full.access')) {
                    $authUser = authUser();
                    if($authUser){
                        $query->where('orders.assign_user_id', $authUser->id);
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

                    $createdAt = \Carbon\Carbon::parse( $row->created_at );

                    $dt = '';
                    if($createdAt->isSameDay(now())){
                        $dt = $createdAt->diffForHumans();
                    }else{
                        $dt = $createdAt->format('d M Y'); 
                    }

                    $data[] = [
                    'id' => $order == 'desc' ? $start++ : $count--, 
                    'ref_no' => $row->id  . '-' . $createdAt->format('dmy'),
                    'name' => $row->name,
                    'user_email' => $row->user_email,
                    'address_location' => $row->address_location,
                    'final_amount' =>  priceFormat($row->final_amount),
                    'datetime' => $dt,
                    'status' =>  view('backend/order/actions', compact('row'))->render(),
                    ];
                }
                return $data;
            }
        );
        return response()->json($data);
    }
 
    public function view(Order $order)
    {

        if (!hasPermission('order.full.access')) {
            $authUser = authUser();
            if($authUser && $authUser->id != $order->assign_user_id){
                abort(403);
            }
        }

        $users = User::where('role_id', '!=', 1)->get();
        return view('backend/order/view', compact(  'users',  'order' ));
    }
 
    public function update(Request $request, Order $order)
    {

        if (!hasPermission('order.full.access')) {
            $authUser = authUser();
            if($authUser && $authUser->id != $order->assign_user_id){
                return response()->json([
                    'alert' => [
                        'icon' => 'error',
                        'title' => 'Order',
                        'text' => 'Permission denied.',
                    ],
                ]);
            }
        }

        $validator = Validator::make(request()->all(), [
            'public_note' => [ 'nullable' ],
            'assign_user_id' => [ 'nullable', 'integer' ],
            'status' => [ 'required', 'max:10' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only(['public_note', 'status' ]);

            $input['order_id'] = $order->id;

            OrderStatus::create($input);

            $order->update([
                'status' => $input['status'],
                'assign_user_id' => $request->assign_user_id ?? null
            ]);

            $createdAt = \Carbon\Carbon::parse( $order->created_at );
            
            if($order->user->fcm){
                $res = sendPushNotification('Hi ' . $order->user->name . ', Your order #' . $order->id . '-' . $createdAt->format('dmy') . ' status updated to ' .  ucfirst( str_replace('-', ' ', $order->status)  )  , $order->user->fcm);
            }
      
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Order',
                    'text' => 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();
        return response()->json([
            'alert' => [
                'icon' => 'success',
                'title' => 'Order',
                'text' => 'Updated successfully.',
                'redirect' => route('order.view', ['order' => $order->id]),
            ],
        ]);
    }
 
 
}
