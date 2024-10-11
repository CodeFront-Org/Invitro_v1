<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\Media;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id=Auth::id();
        $label="My Profile";
        $user=User::find($id);
        $first_name=$user->first_name;
        $middle_name=$user->middle_name;
        $last_name=$user->last_name;
        $email=$user->email;
        $contacts=$user->contacts;
        return view('app.profile',compact('label','id','first_name','middle_name','last_name','email','contacts'));
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
        //
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

    public function pswUpdate(Request $request){
        $type=$request->type1;
        if($type=='psw'){ //Code for updating password
            $old=$request->oldpsw;
            $new=$request->newpsw;
            $user = Auth::user();
            $dbpsw=User::where('id',$request->id)->pluck('password')->first();
                if(Hash::check($old,$user->password)){
                    $newpsw=Hash::make($new);
                    User::where('id',$user->id)->update(['password'=>$newpsw]);
                    return "1";
                }else{
                    return '0';
                }
        }
        /******************************************************************************************Updating Profile Info *****************************/
        elseif($type=='profile'){

        }
        return $request;
    }


    public function update(Request $request, $id)
    {
        $type=$request->type1;
        /*********************This code is not being used. One above is used for update psw while here profile info is updated*****************************/
        if($type=='psw'){ //Code for updating password
            $old=$request->oldpsw;
            $new=$request->newpsw;
            $dbpsw=User::where('id',$id)->pluck('password')->first();
                if(Hash::check($old,$dbpsw)){
                    $newpsw=Hash::make($new);
                    User::where('id',$id)->update(['password'=>$newpsw]);
                    return "1";
                }else{
                    return '0';
                }

        }
        /******************************************************************************************Updating Profile Info *****************************/
        elseif($type=='profile'){ //Code for updating profile information

            $first_name=$request->first_name;
            $middle_name=$request->middle_name;
            $last_name=$request->last_name;
            $email=$request->email;
            $contacts=$request->contacts;
            $contacts_db=substr($contacts,-9); //Filter contacts to MPESA format
            $mpesa_contacts='254'.$contacts_db;


      $input=$request->all();
      $file= $request->file("file"); //Handler to be used in file operations
      if($file){//Delete Old Image
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
            // $name=$input['name'];
            // $email=$input['email'];
            // $contacts=$input['contacts'];
            User::where('id',$id)->update([
                'first_name'=>$first_name,
                'middle_name'=>$middle_name,
                'last_name'=>$last_name,
                'email'=>$email,
                'contacts'=>$contacts,
                //'mpesa_contact'=>$mpesa_contacts
            ]);

            $user_pic=User::find($id);
            $pic_data=new Media(['path'=>$path]);
            User::find(Auth::id())->update(['path'=>$path]);
            $user_pic->photo()->save($pic_data);
            session()->flash('message','Profile Updated Succesfully');
            //Storing profile picture session
            // Session::flush(); //Removing any session set
            // $pic=User::find(Auth::id())->photo->path;
            // $pic_id=Auth::id();
            // $key='pic'.$pic_id;
            // Session::put($key,$pic);
            return back();

    }else{ //When there is no image
        //Put Updated data to Database
        User::where('id',$id)->update([
            'first_name'=>$first_name,
            'middle_name'=>$middle_name,
            'last_name'=>$last_name,
            'email'=>$email,
            'contacts'=>$contacts,
            //'mpesa_contact'=>$mpesa_contacts
        ]);
        session()->flash('message','Records Updated Succesfully');

        return back();

    }









        }
        return $request;
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
