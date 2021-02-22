<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\ApiSetting;
use Exception;
use App\Interfaces\Common\SettingInterface;
use App\Http\Requests\Common\SettingRequest;
use App\Services\Enum\EnumService;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller implements SettingInterface {


    /**
     * @var EnumService
     */
    private $enum;

    public function __construct() {
        $this->enum = new EnumService();
    }

    public function create(SettingRequest $request) {
        DB::beginTransaction();
        try {
            $settingExist = ApiSetting::where('setting_key', $request->setting_key)->first();

            //If Settings name Exists
            if ($settingExist) {
                throw new Exception("setting_key_exists");
            }

            $setting = new ApiSetting();
            $setting->setting_key = $request->setting_key;
            $setting->setting_value = $request->setting_value;
            $setting->description = $request->description;
            $setting->show_front = $request->show_front;
            $setting->modified_by = 1;
            $setting->modified_at = now();
            $setting->save();
            DB::commit();

            logActivity($this->enum->WRITE, 'Create Setting ' . $setting->setting_key, 1);
            return $this->successResponse('create_setting_ok', $setting);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('create_setting_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function update(SettingRequest $request, $id) {
        DB::beginTransaction();
        try {

            $idValidation = checkDataType($id);

            if ($idValidation instanceof Exception) {
                throw new Exception("setting_id_should_be_a_number");
            }

            $settingext = ApiSetting::where('id', '=', $id)->first();

            if (!$settingext) {
                throw new Exception("setting_id_not_exists");
            }

            $settingext->setting_key = $request->setting_key;
            $settingext->setting_value = $request->setting_value;
            $settingext->description = $request->description;
            $settingext->show_front = $request->show_front;
            $settingext->modified_by = 1;
            $settingext->modified_at = now();
            $settingext->save();
            DB::commit();

            logActivity($this->enum->WRITE, 'Update Setting ' . $settingext->sys_setting_name, 1);
            return $this->successResponse('update_setting_ok', $settingext);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('update_setting_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function delete($id) {
        DB::beginTransaction();
        try {

            $idValidation = checkDataType($id);

            if ($idValidation instanceof Exception) {
                throw new Exception("setting_id_should_be_a_number");
            }

            $settingId = ApiSetting::where('id', '=', $id)->first();

            $settingId->delete();
            DB::commit();

            logActivity($this->enum->DELETE, 'Delete Setting ' . $settingId, 1);
            $settingext = [];
            return $this->successResponse('delete_setting_ok', $settingext);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('delete_setting_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function getSetting($byName) {
        try {
            if (!isset($byName)) {
                throw new Exception("setting_name_or_id_required");
            }

            // fetch setting by a name
            $settings = ApiSetting::where('setting_key', '=', $byName)
                ->get(['setting_key', 'setting_value']);

            if (sizeof($settings) == 0) {
                throw new Exception("setting_key_not_available");
            }

            logActivity($this->enum->READ, 'Get Setting value for ' . $byName, 1);
            return $this->successResponse('get_setting_ok', $settings);

        } catch (Exception $exception) {
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('get_setting_fail'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function viewAll() {
        $settings = ApiSetting::all();
        logActivity($this->enum->READ, 'View all settings', 1);
        return $this->successResponse('view_all_settings_ok',$settings);
    }
}
