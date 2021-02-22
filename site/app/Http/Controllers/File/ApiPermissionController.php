<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Models\ApiVsPermission;
use App\Models\Permission;
use Exception;
use App\Interfaces\File\ApiPermissionInterface;
use App\Http\Requests\File\ApiPermissionRequest;
use App\Services\Enum\EnumService;
use Illuminate\Support\Facades\DB;

class ApiPermissionController extends Controller implements ApiPermissionInterface {


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

    public function create(ApiPermissionRequest $request) {
        DB::beginTransaction();
        try {

            //If role privilege assignment exists
            $apiPermissionExist = ApiVsPermission::where('api_id', $request->api_id)
                ->where('permission_key', $request->permission_key)
                ->first();
            if ($apiPermissionExist) {
                throw new Exception("api_permission_assignment_exists");
            }

            $apiPermission = new ApiVsPermission();
            $apiPermission->api_id = $request->api_id;
            $apiPermission->permission_key = $request->permission_key;
            $apiPermission->save();
            DB::commit();

            logActivity($this->enum->WRITE, 'Create api permission assignment ' . $apiPermission->api_id, 1);
            return $this->successResponse('create_api_permission_assignment_ok', $apiPermission);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('create_api_permission_assignment_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function delete($id) {
        DB::beginTransaction();
        try {

            $idValidation = checkDataType($id);

            if ($idValidation instanceof Exception) {
                throw new Exception("api_permission_id_should_be_a_number");
            }

            $apiPermissionId = ApiVsPermission::where('id', '=', $id)->first();
            if (!$apiPermissionId) {
                throw new Exception("api_permission_assign_id_not_exists");
            }

            $apiPermissionId->delete();
            DB::commit();

            logActivity($this->enum->DELETE, 'Delete api permission assignment ' . $apiPermissionId, 1);
            $data = [];
            return $this->successResponse('delete_api_permission_assignment_ok', $data);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('delete_api_permission_assignment_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function getApiPermissionAssign($id) {
        try {

            if (!isset($id) ) {
                throw new Exception("api_permission_id_required");
            }

            // Check id is a number
            $idValidation = checkDataType($id);
            if ($idValidation instanceof Exception) {
                throw new Exception("api_permission_id_should_be_a_number");
            }

            // Check id exists in api permission assign table
            $apiPermissionId = ApiVsPermission::where('id', '=', $id)->first();
            if (!$apiPermissionId) {
                throw new Exception("api_permission_id_not_exists");
            }

            // fetch role privilege assign by a is

            $privilageAssign = ApiVsPermission::where('id', '=', $id)
                ->select(['api_id','permission_key'])
                ->with('api_account:id,system_name')
                ->get();

            logActivity($this->enum->READ, 'View all api permission assignment', 1);
            return $this->successResponse('view_all_api_permission_assignments_ok',$privilageAssign);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('view_all_api_permission_assignments_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function getApiPermissionAssignByApiId($apiid) {
        try {

            if (!isset($apiid) ) {
                throw new Exception("api_id_required");
            }

            // Check api id is a number
            $idValidation = checkDataType($apiid);
            if ($idValidation instanceof Exception) {
                throw new Exception("api_id_should_be_a_number");
            }

            // Check api id exists in api permission assign table
            $apiId = ApiVsPermission::where('api_id', '=', $apiid)->first();
            if (!$apiId) {
                throw new Exception("api_id_not_exists");
            }


            $privilageAssign = ApiVsPermission::where('api_id', '=', $apiid)
                ->select(['permission_key'])
                ->get();

            logActivity($this->enum->READ, 'View api permission assignment for api id '.$apiid, 1);
            return $this->successResponse('permission_assignments_for_api_id_ok',$privilageAssign);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('permission_assignments_for_api_id_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function getAllApiPermissionAssigns() {
        try{
            $apiPermissionAssign = ApiVsPermission::select(['api_id','permission_key'])
                ->with('api_account:id,system_name')
                ->get();

            logActivity($this->enum->READ, 'View all api permission assignments', 1);
            return $this->successResponse('view_all_api_permission_assignments_ok',$apiPermissionAssign);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('view_all_api_permission_assignments_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }
}
