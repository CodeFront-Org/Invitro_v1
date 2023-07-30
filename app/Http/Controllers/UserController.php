<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('app.staff',compact('label'));
    }

    public function customers(){
        $label="Customers";
        return view('app.customer',compact('label'));
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
            $user=User::where('email',$email)->first();
            if($status=='admin'){//add admin role
                $user->assignRole('admin');
            }elseif($status=='staff'){//add staff role
                $user->assignRole('staff');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}