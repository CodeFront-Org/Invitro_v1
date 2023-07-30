<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $label="Staff";
        $data=User::select('id','first_name','last_name','email','contacts','role_type')->where('role_type',2)->latest()->get();
        return view('app.staff',compact('label','data'));
    }

    public function customers(){
        $label="Customers";
        $data=User::select('id','first_name','last_name','email','contacts','role_type')->where('role_type',2)->latest()->get();
        return view('app.customer',compact('label','data'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
$type=$request->type;
if($type==0){//Store staff
    $password="staff123";
    $status=$request->role;
    // Create new user
    $user = User::create([
        'first_name' => $request->f_name,
        'last_name' => $request->l_name,
        'contacts' => $request->contacts,
        'email' => $request->email,
        'password' => Hash::make($password),
        'role_type'=>$request->status=='0'?'1':'2',//Role For 2 staff 1 admin
    ]);
    //Add Role to the newly added staff Based on create condition
    if($user){
        //Log activity
        Log::channel('user_reg')->notice(Auth::user()->email." of DB id ".Auth::id()." Registered Staff ".$request->email." with role of ".$status);
        $user=User::where('email',$request->email)->first();
        if($status=='0'){//add admin role
            $user->assignRole('admin');
        }elseif($status=='1'){//add staff role
            $user->assignRole('staff');
        }
    }
}elseif($type==1){//store customer
    $password="customer123";
    $status=$request->role;
    // Create new user
    $user = User::create([
        'first_name' => $request->name,
        'last_name' => 'customer',
        'contacts' => $request->contacts,
        'email' => $request->email,
        'password' => Hash::make($password),
        'role_type'=>3,//Role For 2 staff 1 admin 3for Customer
    ]);
    //Add Role to the newly added staff Based on create condition
    if($user){
        //Log activity
        Log::channel('user_reg')->notice(Auth::user()->email." of DB id ".Auth::id()." Registered Customer ".$request->email." with role of ".$status);
        $user=User::where('email',$request->email)->first();
        if($status=='0'){//add admin role
            $user->assignRole('admin');
        }elseif($status=='1'){//add staff role
            $user->assignRole('staff');
        }
    }
}

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status=$request->role;
        if($status==0){$status='admin';}elseif($status==1){$status='staff';}
        $email=$request->email;
        //Add staff details to db
        $userUpdate=User::where('id',$id)->update([
            'first_name' => $request->f_name,
            'last_name' => $request->l_name,
            'contacts' => $request->contacts,
            'email' => $request->email,
            'role_type'=>$request->role=='0'?'1':'2',//Role For 2 staff 1 admin
        ]);
        //Update Role to the newly added staff Based on create condition
        if($userUpdate){
            //Log activity
            Log::channel('user_edit')->notice(Auth::user()->email." of DB id ".Auth::id()." Edited Staff ".$email." with role of ".$status);
            $user=User::where('id',$id)->first();
            $newRole = Role::where('name', $status)->first();// Retrieve the new role you want to assign to the user
            $user->syncRoles($newRole);// Update the user's roles
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
    }
}