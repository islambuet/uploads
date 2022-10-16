<?php
namespace App\Http\Controllers;

use App\Helpers\UploadHelper;
use App\Http\Controllers\RootController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UploadController extends Controller
{

    public function saveFiles(Request $request): \Illuminate\Http\JsonResponse
    {
        //check auth validation

        $upload_dir=$request->input('upload_dir','');
        $max_size=$request->input('max_size',1024*3);
        if($request->input('type','image')=='file'){
            $upload_rules[]='mimes:png,jpg,jpeg,bmp,gif,doc,docx,xls,xlsx,pdf,csv,txt';
        }
        else{
            $upload_rules[]='image';
        }
        $uploadedFiles=UploadHelper::upload($upload_dir,$upload_rules,$max_size);
        if($uploadedFiles['status']){
            return response()->json(['error' => '', 'uploaded_files' =>$uploadedFiles['uploaded_files']]);
        }
        else{
            //No need to Format messages
            return response()->json(['error' => 'VALIDATION_FAILED', 'messages' =>$uploadedFiles['errors']]);
        }
    }

}

