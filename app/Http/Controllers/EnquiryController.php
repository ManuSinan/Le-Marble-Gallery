<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
 
class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $products = Product::all();
        $users = User::all();
        return view('backend/enquiry/index', compact(  'products',  'users',  'search' ));
    }
 
    function list(Enquiry $enquiry) 
    {
        $query = $enquiry;
        $query = $query->select(
        'products.name as product_name', 
        'products.product_code as product_code', 
        'users.name as user_name', 
        'users.mobile as user_mobile', 
        'enquiries.id',     
        'enquiries.status'    
        );
        $query = $query->leftJoin('products', 'products.id', '=', 'enquiries.product_id');
        $query = $query->leftJoin('users', 'users.id', '=', 'enquiries.user_id');
        $data = $this->datatable(
            $query,
            function ($query) {

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
                        $query->orWhere('enquiries.id', 'LIKE', "%{$search}%");  
                        $query->orWhere('enquiries.status', 'LIKE', "%{$search}%");  
                        $query->orWhere('products.name', 'LIKE', "%{$search}%"); 
                        $query->orWhere('products.product_code', 'LIKE', "%{$search}%");  
                        $query->orWhere('users.name', 'LIKE', "%{$search}%");  
                        $query->orWhere('users.mobile', 'LIKE', "%{$search}%");  
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
                    'status' => ucwords( $row->status ),
                    'product_name' => $row->product_name,
                    'product_code' => $row->product_code,
                    'user_name' => $row->user_name,
                    'user_mobile' => $row->user_mobile,
                    ];
                }
                return $data;
            }
        );
        return response()->json($data);
    }
}
