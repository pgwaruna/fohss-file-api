<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\FileGetRequest;
use App\Http\Requests\File\FileRequest;
use App\Interfaces\File\FileInterface;
use App\Models\ApiAccount;
use App\Models\ApiSetting;
use App\Models\ApiVsPermission;
use App\Models\Channel;
use App\Models\File;
use App\Models\FileType;
use Exception;
use App\Services\Enum\EnumService;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller implements FileInterface {


    /**
     * @var EnumService
     */
    private $enum;
    /**
     * @var false|\Tymon\JWTAuth\Contracts\JWTSubject
     */
    private $user;
    /**
     * @var string
     */
    private $system_url;
    /**
     * @var string
     */
    private $salt;

    public function __construct() {
        $this->enum = new EnumService();

        // need to create symlink to store files reading
        // docker-compose run --rm fileapi_artisan storage:link, so file canbe access with public_url/storage/file

        $url = globalSetting('SYSTEM_URL');
        $this->system_url = $url."/storage/";

        // Internal salt to unique file and generate file name
        $this->salt = "djf342@*dkfd_f443"; // Caution : DONT CHANGE THIS, IF CHANGED ALL FILES WILL NOT BE ACCESSIBLE
    }

    public function createFile(FileRequest $request) {
        DB::beginTransaction();
        try {

            // Check API token is exists, not expired, and active token
            $apiTokenExist = ApiAccount::select(['id','channel_id','apitoken','api_expire'])
                ->where('apitoken', $request->api_token)
                ->where('active_status',1)
                ->first();

            if (!$apiTokenExist) {
                throw new Exception("api_token_invalid_or_inactive_or_expired");
            }

            $permissions = ApiVsPermission::select(['api_id','permission_key'])
                ->where('api_id', $apiTokenExist->id)
                ->get();

            if (sizeof($permissions) == 0) {
                // if no permissions added for the API
                throw new Exception("no_permission_assigned_to_api_token");
            }

            // if has permissions chek API token has WRITE permisison
            $permissionArr=[];
            foreach ($permissions as $permission){
                $permissionArr[] = $permission->permission_key;
            }
            if(!in_array('WRITE', $permissionArr)) {
                throw new Exception("no_permission_to_write_file");
            }

            // Generating access token
            $access_token = $this->generateToken();

            // Get the Channel and cross check it is related to api token
            $channelId = Channel::where('channel_name', '=', $request->channel_name)->first('id');

            if (!$channelId) {
                throw new Exception("invalid_channel");
            }

            // Check given files name exists related to channel
            $fileNameExist = File::where('file_name', '=', $request->file_name)
                ->where('channel_id', $channelId->id)
                ->first();
            if ($fileNameExist) {
                throw new Exception("file_name_exists_in_current_channel");
            }

            // Generate Relative file path to save using api id and salting it
            // Path is "system_url/channel_id/api_id/filename.extension"

            $channelDir = md5($apiTokenExist->channel_id.$this->salt); // salt the api id for saving and encrypt
            $apiDir = md5($apiTokenExist->id.$this->salt); // salt the api id for saving and encrypt
            $permission = 0700; // set permission manually
            $relativePath = $channelDir."/".$apiDir;

            // Need to create .htaccess file manually to restrict direct folder access, and to access only files

            // Store the file data to the table
            $newFile = new File();

            $extension = $request->file('file')->extension();
            if (!$extension) {
                throw new Exception("file_extension_error");
            }

            if ($request->has('encrypt_name')) {
                if($request->encrypt_name == 1){
                    // Generate custom file name using file id with salt
                    $customFileName = md5($request->file_name.$this->salt).'.'. $extension;
                } else {
                    $customFileName = $request->file_name.'.'. $extension;
                }
            } else {
                $customFileName = $request->file_name.'.'. $extension;
            }

            // Get the mime type of the file and check exists in file types which is already supported to this type of a file
            $fileMimeExist = FileType::where('mime_type', '=', $request->file->getMimeType())->first('id');
            if (!$fileMimeExist) {
                throw new Exception("this_file_type_not_supported");
            }

            $newFile->file_name = $request->file_name;
            $newFile->file_path = $relativePath."/".$customFileName;
            $newFile->access_token = $access_token;
            $newFile->filetype_id = 1;//$request->filetype_id;
            $newFile->channel_id = $channelId->id;
            $newFile->active_status = $request->active_status;
            $newFile->delete_status = 1; // 1 - Active file, 0 - Deleted File
            $newFile->save();

            // Store the file
            $filePath = $request->file('file')->storeAs($relativePath, $customFileName,'public');
            $storedFullPath = $this->system_url.$filePath;

            $newFile->save();

            DB::commit();

            logActivity($this->enum->WRITE, 'Create File ' . $request->file_name, 1);

            if($request->path_enable == 1){
                return $this->successResponse('upload_file_ok',
                    [
                        'access_token' => $access_token,
                        'file_path' => $storedFullPath
                    ]
                );
            }

            return $this->successResponse('upload_file_ok',
                [
                    'access_token' => $access_token,
                ]
            );


        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('upload_file_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    function generateToken()
    {
        return md5(rand(1, 999999999) . microtime());
    }

    public function deleteFile(FileGetRequest $request) {
        DB::beginTransaction();
        try {

            // Check API token is exists, not expired, and active token
            $apiTokenExist = ApiAccount::select(['id','apitoken','api_expire'])
                ->where('apitoken', $request->api_token)
                ->where('active_status',1)
                ->first();

            if (!$apiTokenExist) {
                throw new Exception("api_token_invalid_or_inactive_or_expired");
            }

            $permissions = ApiVsPermission::select(['api_id','permission_key'])
                ->where('api_id', $apiTokenExist->id)
                ->get();

            if (sizeof($permissions) == 0) {
                // if no permissions added for the API
                throw new Exception("no_permission_assigned_to_api_token");
            }

            // if has permissions chek API token has READ permisison
            $permissionArr=[];
            foreach ($permissions as $permission){
                $permissionArr[] = $permission->permission_key;
            }
            if(!in_array('DELETE', $permissionArr)) {
                throw new Exception("no_permission_to_delete_file");
            }

            // Check access token is exists
            $accessTokenExist = File::where('access_token', $request->access_token)->first();
            if (!$accessTokenExist) {
                throw new Exception("access_token_invalid");
            }

            // Get channel id from channel name
            $channelId = Channel::where('channel_name', '=', $request->channel_name)->first('id');
            if (!$channelId) {
                throw new Exception("invalid_channel");
            }

            // Check API token is exists, not expired, and active token
            $fileExists = File::select(['id','access_token','file_path'])
                ->where('access_token', $request->access_token)
                ->where('delete_status',1)
                ->first();

            if (!$fileExists) {
                throw new Exception("file_not_exists");
            }

            // Physically Delete File
            $readpath = '/app/public/';
            unlink(storage_path($readpath.$fileExists->file_path));

            // Delete file record from the database
            $delFile = File::where('access_token', '=', $request->access_token)->first();
            $delFile->delete();
            DB::commit();

            logActivity($this->enum->READ, 'File delete for' . $request->access_token, 1);
            $emptyArr = [];
            return $this->successResponse('delete_file_ok', $emptyArr);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('file_delete_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function getFile(FileGetRequest $request) {
        try {

            // Check API token is exists, not expired, and active token
            $apiTokenExist = ApiAccount::select(['id','apitoken','api_expire'])
                ->where('apitoken', $request->api_token)
                ->where('active_status',1)
                ->first();

            if (!$apiTokenExist) {
                throw new Exception("api_token_invalid_or_inactive_or_expired");
            }

            $permissions = ApiVsPermission::select(['api_id','permission_key'])
                 ->where('api_id', $apiTokenExist->id)
                 ->get();

            if (sizeof($permissions) == 0) {
                // if no permissions added for the API
                throw new Exception("no_permission_assigned_to_api_token");
            }

            // if has permissions chek API token has READ permisison
            $permissionArr=[];
            foreach ($permissions as $permission){
                $permissionArr[] = $permission->permission_key;
            }
            if(!in_array('READ', $permissionArr)) {
                throw new Exception("no_permission_to_read_file");
            }

            // Check access token is exists
            $accessTokenExist = File::where('access_token', $request->access_token)->first();
            if (!$accessTokenExist) {
                throw new Exception("access_token_invalid");
            }

            // Get channel id from channel name
            $channelId = Channel::where('channel_name', '=', $request->channel_name)->first('id');
            if (!$channelId) {
                throw new Exception("invalid_channel");
            }

            $file = File::select(['id','file_name','file_path','filetype_id','created_at'])
                ->where('access_token', '=', $request->access_token)
                ->where('active_status',1)
                ->where('delete_status',1)
                ->first();

             if(!($request->file_action=='VIEW' || $request->file_action=='DOWNLOAD')){
                 throw new Exception("file_action_flag_VIEW_or_DOWNLOAD_required");
             }

            // View the file on the browser
            if($request->file_action=='VIEW') {
                logActivity($this->enum->READ, 'Read File from access token' . $request->access_token, 1);
                // View File
                return $this->successResponse('file_view_ok',
                    [
                        'file_name' => $file->file_name,
                        'file_path' => $this->system_url.$file->file_path,
                        'filetype_id' => $file->filetype_id,
                        'created_at' => $file->created_at
                    ]
                );
            }

            // Download the file as a stream
            if($request->file_action=='DOWNLOAD') {
                logActivity($this->enum->READ, 'Download File from access token' . $request->access_token, 1);
                $readpath = '/app/public/';
                return response()->download(storage_path($readpath.$file->file_path)); // Download File
            }

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('file_view_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function getFiles() {
//        try{
//            $mimeTypes = FileType::select(['mime_type','type_image'])
//                ->get();
//
//            logActivity($this->enum->READ, 'View all mime_types', 1);
//            return $this->successResponse('view_all_mime_types_ok',$mimeTypes);
//
//        } catch (Exception $exception) {
//            DB::rollBack();
//            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
//            return $this->errorResponse('view_all_mime_types_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
//        }
    }
}
