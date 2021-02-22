<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\FileTypeRequest;
use App\Interfaces\File\FileTypeInterface;
use App\Models\FileType;
use Exception;
use App\Services\Enum\EnumService;
use Illuminate\Support\Facades\DB;

class FileTypeController extends Controller implements FileTypeInterface {


    /**
     * @var EnumService
     */
    private $enum;
    /**
     * @var false|\Tymon\JWTAuth\Contracts\JWTSubject
     */
    private $user;

    public function __construct() {
        $this->enum = new EnumService();
    }

    public function create(FileTypeRequest $request) {
        DB::beginTransaction();
        try {

            //If File type exists
            $fileTypeExist = FileType::where('mime_type', $request->mime_type)
                ->first();
            if ($fileTypeExist) {
                throw new Exception("mime_type_exists");
            }

            $type = new FileType();
            $type->mime_type = $request->mime_type;
            $type->type_image = $request->type_image;
            $type->save();
            DB::commit();

            logActivity($this->enum->WRITE, 'Create mime type ' . $request->mime_type, 1);
            return $this->successResponse('create_mime_type_ok', $type);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('create_mime_type_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function update(FileTypeRequest $request, $id) {
        DB::beginTransaction();
        try {

            $idValidation = checkDataType($id);
            if ($idValidation instanceof Exception) {
                throw new Exception("mime_type_id_should_be_a_number");
            }

            $typeExists = FileType::where('id', '=', $id)->first();
            if (!$typeExists) {
                throw new Exception("mime_type_id_not_exists");
            }

            $typeExists->mime_type = $request->mime_type;
            $typeExists->type_image = $request->type_image;
            $typeExists->save();
            DB::commit();

            logActivity($this->enum->WRITE, 'Update mime type ' . $typeExists->mime_type, 1);
            return $this->successResponse('update_mime_type_ok', $typeExists);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('update_mime_type_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }
    public function delete($id) {
        DB::beginTransaction();
        try {

            $idValidation = checkDataType($id);
            if ($idValidation instanceof Exception) {
                throw new Exception("mime_type_id_should_be_a_number");
            }

            $typeId = FileType::where('id', '=', $id)->first();
            if (!$typeId) {
                throw new Exception("mime_type_id_not_exists");
            }

            $typeId->delete();
            DB::commit();

            logActivity($this->enum->DELETE, 'Delete mime_type ' . $typeId, 1);
            $data = [];
            return $this->successResponse('delete_mime_type_ok', $data);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('delete_mime_type_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function getFileType($id) {
        try {

            if (!isset($id) ) {
                throw new Exception("mime_type_id_required");
            }

            // Check id is a number
            $idValidation = checkDataType($id);
            if ($idValidation instanceof Exception) {
                throw new Exception("mime_type_should_be_a_number");
            }

            // Check id exists in mime_type table
            $channelId = FileType::where('id', '=', $id)->first();
            if (!$channelId) {
                throw new Exception("mime_type_id_not_exists");
            }

            $mimeTypes = FileType::where('id', '=', $id)
                ->select(['mime_type','type_image'])
                ->get();

            logActivity($this->enum->READ, 'View mime_type '.$id, 1);
            return $this->successResponse('view_mime_type_ok',$mimeTypes);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('view_mime_type_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function getFileTypes() {
        try{
            $mimeTypes = FileType::select(['mime_type','type_image'])
                ->get();

            logActivity($this->enum->READ, 'View all mime_types', 1);
            return $this->successResponse('view_all_mime_types_ok',$mimeTypes);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('view_all_mime_types_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }
}
