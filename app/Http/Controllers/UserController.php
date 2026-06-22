<?php 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use Illuminate\Validation\Rule; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;
 
class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $roles = Role::all();
        return view('backend/user/index', compact(  'roles',  'search' ));
    }
 
    function list(User $user) 
    {
        $query = $user;
        $query = $query->select(
        'roles.name as role_name', 
        'users.id',     
        'users.name', 
        'users.mobile',
        'users.email',    
        'users.status'    
        );
        $query = $query->leftJoin('roles', 'roles.id', '=', 'users.role_id');
        $data = $this->datatable(
            $query,
            function ($query) {
                $search = request('search.value') ?? '';
                if (!empty($search)) {
                    $query->orWhere('users.name', 'LIKE', "%{$search}%");  
                    $query->orWhere('users.email', 'LIKE', "%{$search}%");  
                    $query->orWhere('users.mobile', 'LIKE', "%{$search}%");
                    $query->orWhere('users.status', 'LIKE', "%{$search}%");  
                    $query->orWhere('roles.name', 'LIKE', "%{$search}%");  
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
                    'role_name' => $row->role_name,
                    'mobile' => $row->mobile,
                    'email' => $row->email,
                    'status' => ucwords( $row->status ),
                    'actions' => view('backend/user/actions', compact('row'))->render(),
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
            'name'     => [ 'required', 'max:100' ],
            'role_id'  => [ 'required', 'integer' ],
            'mobile'   => [ 'nullable', Rule::unique('users')->where(function($query) use ($request){
                $query->where('mobile_verified', 1);
            }), 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:10' ],
            'email'    => [ 'nullable', Rule::unique('users')->where(function($query) use ($request){
                $query->where('email_verified', 1);
            }), 'email', 'max:100' ],
            'password' => [ 'nullable', 'min:6' ],
            'status'   => [ 'required', 'max:10' ],
        ]);
 
        $validator->setAttributeNames([
            'mobile' => 'Mobile Number',
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only([ 'name', 'email', 'mobile', 'username', 'role_id',  'status']);
            
            $input['password'] = Hash::make($request->password);

            if (!empty($input['mobile'])) {
                $input['mobile_verified'] = true;
            } else {
                $input['mobile_verified'] = false;
            }

            if (!empty($input['email'])) {
                $input['email_verified'] = true;
            } else {
                $input['email_verified'] = false;
            }

            // Auto-generate a unique username if not provided
            if (empty($input['username'])) {
                $username = \Illuminate\Support\Str::slug($input['name']);
                if (empty($username)) {
                    if (!empty($input['email'])) {
                        $username = explode('@', $input['email'])[0];
                    } elseif (!empty($input['mobile'])) {
                        $username = $input['mobile'];
                    } else {
                        $username = 'user';
                    }
                }

                // Ensure username is unique in database
                $originalUsername = $username;
                $counter = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $originalUsername . $counter;
                    $counter++;
                }
                $input['username'] = $username;
            }
         
            User::create($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'User',
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
                'title' => 'User',
                'text' => 'Created successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
  
    public function edit(User $user)
    {
        $roles = Role::all();
  
        return response()->json([
            'jquery' => [
                [
                    'element' => '#edit-form .modal-content',
                    'method' => 'html',
                    'value' => view('backend/user/edit', compact(  'roles',  'user' ))->render(),
                ],
            ],
            'init' => ['#edit-form .modal-content'],
            'modal' => [
                'show' => '#edit-form'
            ]
        ]);
    }
  
    public function update(Request $request, User $user)
    {
        $validator = Validator::make(request()->all(), [
            'name' => [ 'required', 'max:100' ],
            'role_id' => [ 'required', 'integer' ],
            'email' => [ 'nullable', Rule::unique('users')->where(function($query) use ($request, $user){
                $query->where('id', '!=' ,$user->id)->where('email_verified', 1);
            }), 'email', 'max:100' ],
            'mobile' => [ 'nullable', Rule::unique('users')->where(function($query) use ($request, $user){
                $query->where('id', '!=' ,$user->id)->where('mobile_verified', 1);
            }), 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:10' ],
            'password' => [ 'nullable', 'min:6' ],
            'status' => [ 'required', 'max:10' ],
        ]);

        $validator->setAttributeNames([
            'mobile' => 'Mobile Number',
        ]);
  
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
  
        DB::beginTransaction();
        try {
            $input = $request->only([ 'name', 'email', 'mobile', 'role_id',  'status']);
  
            if($request->password){
                $input['password'] = Hash::make($request->password);
            }

            if (!empty($input['mobile'])) {
                $input['mobile_verified'] = true;
            } else {
                $input['mobile_verified'] = false;
            }

            if (!empty($input['email'])) {
                if ($input['role_id'] != 1) {
                    $input['email_verified'] = true;
                } else {
                    $input['email_verified'] = false;
                }
            } else {
                $input['email_verified'] = false;
            }
  
            $user->update($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'User',
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
                'title' => 'User',
                'text' => 'Updated successfully.',
            ],
            'datatable' => [
                'reload' => true,
            ],
        ]);
    }
 
    public function destroy(User $user)
    {
        try {
            $user->delete();
        } catch (\Exception $e) {
            return response()->json([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'User',
                    'text' => 'User can\'t be deleted! as it is in use',
                ],
            ]);
        }
        return response()->json([
            'datatable' => [
                'reload' => true,
            ],
            'alert' => [
                'icon' => 'success',
                'title' => 'User',
                'text' => 'Deleted successfully.',
            ],
        ]);
    }
 
    public function profile(Request $request)
    {
        $user = authUser();
        return view('backend/user/profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => [ 'required', 'max:100' ],
            'email' => [ 'nullable', 'email', 'max:100' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $input = $request->only([ 'name',  'email']);

            $user = authUser();
            $user->update($input);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'errors' => [
                    'name' => 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();
        return response()->json([
            'alert' => [
                'icon' => 'success',
                'title' => 'User',
                'text' => 'Updated successfully.',
            ],
        ]);
    }


    public function changePassword(Request $request)
    {
        return view('backend/user/change-password');
    }
    
    public function updatePassword(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'current-password' => [ 'required', 'min:6', new MatchOldPassword ],
            'password' => [ 'required', 'min:6' ],
        ]);
 
        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
 
        DB::beginTransaction();
        try {
            $user = authUser();

            $user->update(['password' => Hash::make($request->password)]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'errors' => [
                    'current-password' => 'Something went wrong.',
                ],
            ]);
        }
        DB::commit();

        return response()->json([
            'alert' => [
                'icon' => 'success',
                'title' => 'User',
                'text' => 'Updated successfully.',
            ],
        ]);
    }

}
