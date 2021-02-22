<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ApiRequest;
use App\Interfaces\Auth\ApiInterface;
use App\Models\ApiAccount;
use App\Services\Enum\EnumService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller implements ApiInterface
{

    /**
     * @var EnumService
     */
    private $enum;

    public function __construct() {
        $this->enum = new EnumService();
    }

    /**
     * {@inheritdoc}
     */
    public function create(ApiRequest $request)
    {
        DB::beginTransaction();
        try {
            $api = new ApiAccount();
            $api->user_id = 1;
            $api->channel_id = $request->channel_id;
            $api->system_name = $request->system_name;
            $api->apitoken = $request->apitoken;
            $api->contact_mob = $request->contact_mob;
            $api->contact_email = $request->contact_email;
            $api->api_expire = $request->api_expire;
            $api->active_status = 1;
            $api->created_at = now();
            $api->modified_by = 1;
            $api->created_by = 1;
            $api->save();
            DB::commit();

            logActivity($this->enum->WRITE, 'api_account_create_sucess',1);

            return response()->json([
                'status' => 'success',
                'message' => 'api_create_success',
                'data' => $api
            ], getStatusCodes('SUCCESS'));

        } catch (Exception $exception) {
            DB::rollBack();

            logActivity($this->enum->ERROR,'api_create_fail '.$exception->getMessage(),0);
            return response()->json([
                'status' => 'error',
                'messge' => 'api_create_fail',
            ], getStatusCodes('EXCEPTION'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update(ApiRequest $request, $id)
    {
        DB::beginTransaction();
        try {

            $updateAc = ApiAccount::where('id', '=', $id)->first();

            // validate token is available fo edit
            if (!$updateAc) {
                throw new Exception("account_id_not_available", getStatusCodes('EXCEPTION'));
            }

            // validate system_name already exists
            $systemNameExist = ApiAccount::where('system_name', '=', $request->system_name)
                ->first();
            if ($systemNameExist) {
                throw new Exception("system_name_already_exists", getStatusCodes('EXCEPTION'));
            }

            $updateAc->user_id = 1;
            $updateAc->channel_id = $request->channel_id;
            $updateAc->system_name = $request->system_name;
            $updateAc->apitoken = $request->apitoken;
            $updateAc->contact_mob = $request->contact_mob;
            $updateAc->contact_email = $request->contact_email;
            $updateAc->api_expire = $request->api_expire;
            $updateAc->active_status = 1;
            $updateAc->modified_by = 1;
            $updateAc->save();
            DB::commit();

            logActivity($this->enum->WRITE, 'api_account_update_sucess',1);

            return response()->json([
                'status' => 'success',
                'message' => 'api_update_success',
                'data' => $updateAc
            ], getStatusCodes('SUCCESS'));

        } catch (Exception $exception) {
            DB::rollBack();

            logActivity($this->enum->ERROR,'api_update_fail '.$exception->getMessage(),0);
            return response()->json([
                'status' => 'error',
                'message' => 'api_update_fail',
            ], getStatusCodes('EXCEPTION'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {

            $delAc = ApiAccount::where('id', '=', $id)->first();

            if (!$delAc) {
                throw new Exception("api_account_with_id".$id."_not_available", getStatusCodes('EXCEPTION'));
            }

            $delAc->delete();

            DB::commit();

            logActivity($this->enum->DELETE,"api_account_with_id".$id."_Deleted", 1);

            return response()->json([
                'status' => 'success',
                'message' => 'api_account_delete_ok'
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()], $exception->getCode());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function viewApi(ApiRequest $request)
    {}

    /**
     * {@inheritdoc}
     */
    public function viewAllApi()
    {
        try{
            $apiAccounts = ApiAccount::select(['id','channel_id','user_id','system_name','apitoken','contact_mob','contact_email','api_expire','active_status','created_at','created_by','modified_at','modified_by'])
                -> get();//

            if (sizeof($apiAccounts) == 0) {
                logActivity('no_records_available',1);
                // if no account send empty array
                $empty_array = (object)['data' => []];
                return response()->json([
                    'status' => 'sucess',
                    'message' => 'no_records_available',
                    'data' => $empty_array
                ]);
            }

            logActivity($this->enum->READ,"view_all_api_accounts_ok", 1);

            return response()->json([
                'status' => 'success',
                'message' => 'view_all_api_accounts_ok',
                'data' => $apiAccounts->toArray()
            ]);
        } catch (Exception $exception) {
            logActivity($this->enum->ERROR,'api_records_view_error'.$exception->getMessage(),0);
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()], $exception->getCode());
        }
    }
}
