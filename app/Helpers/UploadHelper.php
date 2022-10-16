<?php
    namespace App\Helpers;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Validation\Rule;
    use Illuminate\Support\Facades\Storage;

    class UploadHelper
    {
        //request() is laravel request helper
        //$_FILES
        public static string $DISK = 'public';
        public static string $DISK_LINK = 'files';

        public static function upload($upload_dir='',$fileRule=['image'],$max_size=1024): array
        {

            $uploaded_files=UploadHelper::getUploadValidation($upload_dir,$fileRule,$max_size);
            if($uploaded_files['status'])
            {
                foreach ($_FILES as $key=>$file){
                    //single file attached
                    if(is_string($file['name']) && (strlen($file['name'])>0)){
                        try
                        {
                            unset($file['tmp_name']);
                            $file['name_uploaded']=UploadHelper::getUploadFileName($upload_dir,request()->file($key)->getClientOriginalName());
                            $file['path'] = UploadHelper::$DISK_LINK.'/'.request()->file($key)->storeAs($upload_dir,$file['name_uploaded'],UploadHelper::$DISK);
                            $uploaded_files['uploaded_files'][$key]=$file;
                        }
                        catch (\Exception $ex)
                        {
                            $uploaded_files['status']=false;
                            $uploaded_files['errors'][$key]=$ex->getMessage();
                        }

                    }
                }
            }
            return $uploaded_files;
        }
        public static function getUploadValidation($upload_dir='',$fileRule=['image'],$max_size=1024): array
        {
            $fileRule[]='max:'.$max_size;
            $uploaded_files=['status'=>true,'errors'=>[],'uploaded_files'=>[]];
            if(sizeof($_FILES)>0)
            {
                foreach ($_FILES as $key=>$file){
                    //single file attached
                    if(is_string($file['name']) && (strlen($file['name'])>0)){
                        $validation_rule=array();
                        $validation_rule[$key]=$fileRule;
                        $validator = Validator::make(request()->all(),$validation_rule);
                        if ($validator->fails()) {
                            $uploaded_files['status']=false;
                            $uploaded_files['errors'][$key]=$validator->errors()->toArray()[$key];
                        }
                    }
                    //else if(is_array($file['name']) //skip multiple upload
                    //else //skip file not attached
                }
            }
            return $uploaded_files;
        }
        public static function getUploadFileName($dir,$file): string
        {
            $pathInfo=pathinfo($file);
            $ext= $pathInfo['extension'] ?? '';
            $filename=$pathInfo['filename']?:'';
            $filename_new=$filename.($ext?'.'.$ext:'');
            $index=1;
            while(Storage::disk(UploadHelper::$DISK)->exists($dir.'/'.$filename_new)) {
                $filename_new=$filename.($index++).($ext?'.'.$ext:'');
            }
            return $filename_new;
        }
    }
