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
        $data=User::select('id','first_name','last_name','email','contacts','role_type')->latest()->get();
        return view('app.staff',compact('label','data'));
    }

    public function customers(){
        $label="Customers";
        $data=User::select('id','first_name','last_name','email','contacts','role_type')->where('role_type',3)->latest()->get();
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
             if($status==0){
                 $role_type=1;
             }elseif($status==1){
                 $role_type=2;
             }elseif($status==2){
                 $role_type=3;
             }
             // Create new user
             $user = User::create([
                 'first_name' => $request->f_name,
                 'last_name' => $request->l_name,
                 'contacts' => $request->contacts,
                 'email' => $request->email,
                 'password' => Hash::make($password),
                 'role_type'=>$role_type,//Role 2 staff 1 admin
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
                 }elseif($status=='2'){//add stock card role
                     $user->assignRole('card');
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
                     $user->assignRole('customer');
                 }elseif($status=='1'){//add staff role
                     $user->assignRole('customer');
                 }
             }
         }
 
     }
 
 
 
 
 
 
 
 
 
 
 
         public function update(Request $request, $id)
     {
         $type=$request->type;
         if($type==0){//edit staff
             $status=$request->role;
             if($status==0){$status='admin';}elseif($status==1){$status='staff';}elseif($status==2){$status='card';}
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
         }elseif($type==1){//Edit customer
             $status='customer';
             $email=$request->email;
             //Add staff details to db
             $userUpdate=User::where('id',$id)->update([
                 'first_name' => $request->f_name,
                 'contacts' => $request->contacts,
                 'email' => $request->email,
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
 
     }



     /*
    public function storeXX(Request $request)
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
                'role_type'=>$request->role=='0'?'1':'2',//Role 2 staff 1 admin
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
                    $user->assignRole('customer');
                }elseif($status=='1'){//add staff role
                    $user->assignRole('customer');
                }
            }
        }

    }
        */

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
    /*
    public function updateXX(Request $request, $id)
    {
        $type=$request->type;
        if($type==0){//edit staff
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
        }elseif($type==1){//Edit customer
            $status='customer';
            $email=$request->email;
            //Add staff details to db
            $userUpdate=User::where('id',$id)->update([
                'first_name' => $request->f_name,
                'contacts' => $request->contacts,
                'email' => $request->email,
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

    }
    */
    public function profile(){
$id=Auth::id();
$label="My Profile";
$user=User::find($id);
$f_name=$user->first_name;
$l_name=$user->last_name;
$email=$user->email;
$contacts=$user->contacts;
        return view('app.profile',compact('label','f_name','l_name','email','contacts'));
    }


    public function updateProfile(Request $request){
$type=$request->type;
$id=Auth::id();
if($type=='details'){//Update Details
      $input=$request->all();
      $file= $request->file("file"); //Handler to be used in file operations
      if($file){
    //Delete Old Image
        $id=Auth::id();

if(Media::where('user_id',Auth::id())->exists()){  //New user
        $get_file=User::findOrFail($id)->photo->path;
        $url='images/users/profile/'.$get_file;
        if(file_exists($url)){
            @unlink($url); //Removes the file
        }
        User::findOrFail($id)->photo->delete();
}
    //Add New File image
        $fileName=time()."_".$id."_".$file->getClientOriginalName();
        $file->move('images/users/profile/',$fileName);
        $input['path']=$fileName;

    //Put Updated data to Database
    $path=$input['path'];
    $name=$input['name'];
    $email=$input['email'];
    $contacts=$input['contacts'];
    User::where('id',$id)->update(['name'=>$name,'email'=>$email,'contacts'=>$contacts]);

    $user_pic=User::find($id);
    $pic_data=new Media(['path'=>$path]);
    $user_pic->photo()->save($pic_data);
    session()->flash('message','Profile Updated Succesfully');
        //Storing profile picture session
        Session::flush(); //Removing any session set
        $pic=User::find(Auth::id())->photo->path;
        $pic_id=Auth::id();
        $key='pic'.$pic_id;
        Session::put($key,$pic);
    return back();
    }else{ //When there is no image
    //Put Updated data to Database
    $name=$input['name'];
    $email=$input['email'];
    $contacts=$input['contacts'];
    //$path=$input['image_path'];
    User::where('id',$id)->update(['name'=>$name,'email'=>$email,'contacts'=>$contacts]);
    session()->flash('message','Records Updated Succesfully');
        //Storing profile picture session
    
    return back();

    }

}
else{ //Update Password
return $request;

}
    }





    public function password(Request $request){
    $data= $request;
    $old=$data->oldpsw;
    $new=$data->newpsw;

$id=Auth::id();
$dbpsw=User::where('id',$id)->pluck('password')->first();
if(Hash::check($old,$dbpsw)){
$newpsw=Hash::make($new);
User::where('id',$id)->update(['password'=>$newpsw]);
session()->flash('message','success');
return back();
}else{
session()->flash('error','Old Password Enterd Is Incorrect');
return back();
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
        User::find($id)->delete();//Delete wheter coming from staff or customer
    }
}