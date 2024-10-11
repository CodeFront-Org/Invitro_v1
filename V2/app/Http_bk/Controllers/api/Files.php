<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Files extends Controller
{

/*************************************************************** Upload File *********************************************************************** */
    public function upload(){
      $input=$request->all();
      $file= $request->file("logbookfile"); //Handler to be used in file operations
        if($file){  //Check if form was submitted with the file
            //Delete Old Image if it exists
            $id=Auth::id();
            if(Document::where('motor_id',$motor_id)->where('document','logbook')->exists()){  //Check if doc exists
                $get_file=Motor::findOrFail($motor_id)->photo->path; //Get file_name or path of file in db column defined as path so that you can define it in url below
                $url='images/logbooks/supplier/'.$get_file;
                if(file_exists($url)){
                    @unlink($url); //Removes the file
                }
                Motor::findOrFail($motor_id)->photo->delete(); //Delete 
            }
            //Add New File image
            $fileName=time()."_".$motor_id."_".$file->getClientOriginalName();
            $file->move('images/logbooks/supplier/',$fileName);
            $input['path']=$fileName;

            //Put Updated data to Database
            $path=$input['path'];
            $user_pic=Motor::find($motor_id);
            $pic_data=new Document(['path'=>$path,'staff_id'=>$id,'document'=>'logbook']);
            $user_pic->photo()->save($pic_data);
         }else{  // Here no file was submitted with the form or no upload made in the frontend

            //return "no file uploads";

        }
    }

/*************************************************************** Download File *********************************************************************** */
    public function download(Request $request){
        $data=$request;
        $docname=$data->docname;
        $path=$data->doc;

        if($docname=='driver_id'){ //when downloading many docs, check if its the correct doc type to be deleted 
            $filepath = public_path('images/id/driver/'.$path);
            return Response()->download($filepath);
        }
    }


/*************************************************************** Delete File *********************************************************************** */
    public function delete(){
        if(Document::where('motor_id',$motor_id)->where('document','logbook')->exists()){ 
            $get_file=Motor::findOrFail($motor_id)->photo->path;
            $url='images/logbooks/supplier/'.$get_file;
            if(file_exists($url)){
                @unlink($url); //Removes the file
            }
            Motor::findOrFail($motor_id)->photo->delete();
        }
    }


}
