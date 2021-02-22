<?php

use App\Http\Controllers\Common\SettingController;
use App\Models\ApiSetting;
use App\Models\ApiActivity;
use App\Services\Enum\EnumService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

if (!function_exists('logActivity')) {

    /**
     * @author Nuwan Ishara.
     * @author Function Creation Date: 27/03/2019.
     *
     * Save activity
     * @category Helper functions.
     *
     * @param string $activity Input - Activity Description
     * @param integer $status Input - Activity status [default - 0 fail, 1 - success]
     * @param integer $imi Input - IMEI number
     * @param string $devName Input - Device name
     * @param string $latitude Input - location
     * @param string $longitude Input - location
     * @param string $type Input - READ,WRITE,DELETE,ERROR(Exceptions)
     * @var
     *
     * @return -
     *
     * @throws Exception
     *  No data found
     *
     * @uses
     *
     * @version 1.0.0
     *
     *
     */

    function logActivity($type, $activity, $status = 0, $latitude = '00', $longitude = '00') {
        // READ - All read operations
        // WRITE - Write operations (add and update)
        // DELETE - Delete operations
        // ERROR - Exceptions
        // LOGIN - User login
        // LOGOUT - User logout

        $log = new ApiActivity();
        $log->type = $type;
//        $log->uidx = auth()->check() ? auth()->user()->id : null;
//        $log->user_name = auth()->check() ? auth()->user()->email : null;
        $log->activity = $activity;
        $log->url = Request::fullUrl();

        $controllerAndAction = explode('@', Route::getCurrentRoute()->getActionName());
        $log->controller_name = $controllerAndAction[0];
        $log->action_name = $controllerAndAction[1];
        $log->parameters = Request::url();

        $log->status = $status;

        $log->ip_addr = Request::ip();
        $log->date_time = now();

        $log->ua_browser = Request::header('user-agent');
        $log->latitude = $latitude;
        $log->longitude = $longitude;

        $log->save();
    }
}

if (!function_exists('getStatusCodes')) {

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 20/10/2020.
     *
     *
     * get status codes
     * @category Helper functions.
     *
     * @param string $statusString Input - Name of the status
     *
     * @var
     *
     * @return string $settingValue Output status code
     *
     * @throws
     *
     *
     * @uses
     *
     * @version 1.0.0
     *
     *
     */
    function getStatusCodes($statusString) {
        switch ($statusString) {

            case 'VALIDATION_ERROR' :
                $settingValue = Response::HTTP_BAD_REQUEST; // 400
                break;
            case 'EXCEPTION' :
                $settingValue = Response::HTTP_BAD_REQUEST; // 400
                break;
            case 'SUCCESS' :
                $settingValue = Response::HTTP_OK; // 200
                break;
            case 'UNAUTHORIZED' :
                $settingValue = Response::HTTP_UNAUTHORIZED; // 401
                break;
            case 'AUTH_ERROR' :
                $settingValue = Response::HTTP_FORBIDDEN; // 403
                break;
            default:
                $settingValue = Response::HTTP_BAD_REQUEST; // 400
                break;
        }
        return $settingValue;
    }
}

if (!function_exists('validateApiToken')) {

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 31/10/2020.
     *
     *
     * Validate api token for every service
     * @category Helper functions.
     *
     * @param string $apitoken Input - Name of the status
     *
     * @var
     *
     * @return string $settingValue Output status code
     *
     * @throws
     *
     *
     * @uses
     *
     * @version 1.0.0
     *
     *
     */
    function validateApiToken($apitoken)
    {
        // Validate API token
        try {
            $tokenValid = \App\Models\ApiAccount::where('apitoken', '=', $apitoken)
                ->first();

            if (!$tokenValid) {
                return false;
            } else {
                return true;
            }

        } catch (Exception $exception) {
            logActivity('Error ' . $exception->getMessage(), 0);
            return false;
        }
    }

}

if (!function_exists('globalSetting')) {

    /**
     * @param string $settingname Input - Name of the setting
     *
     * @return string $settingValue Output status code
     *
     * @category Helper functions.
     *
     * @author Waruna Gamage.
     * @author Function Creation Date: 31/10/2020.
     *
     *
     * Get system setting for internal use
     * @version 1.0.0
     */

    function globalSetting($settingName) {
        $settingValue=null;
        $setting = new SettingController();

        $settingData = $setting->getSetting($settingName);
        if (isset($settingData->getData()->data[0]->setting_value)) {
            $settingValue = $settingData->getData()->data[0]->setting_value;
        }
        return $settingValue;
    }
}

if (!function_exists('getLoggedUser')) {

    /**
     * @return Object $loggedUser Get logged user
     *
     * @category Helper functions.
     *
     * @author Waruna Gamage.
     * @author Function Creation Date: 01/01/2021.
     *
     * Get User for internal use
     * @version 1.0.0
     */

    function getLoggedUser() {
        $loggedUser = auth()->user();
        return $loggedUser;
    }
}

if (!function_exists('checkDataType')) {
    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 01/01/2021.
     *
     *
     * check the variable value's type
     * @category Helper functions.
     *
     * @param string $variable Input - type of the reference(1-  invoice, 2- grn, 3 - expense)
     * @param string $requiredType Input - type of the reference(numeric - variable in string contains only the numbers,  integer - variable strictly integer )
     *
     *
     * @var
     *
     * @return
     *
     * @throws Exception
     * INVALID_DATA_TYPE
     *
     * @uses
     *
     * @version 1.0.0
     *
     *
     */
    function checkDataType($variable, $requiredType = 'numeric') {
        try {
            if ($requiredType == 'integer') {
                if (!is_int($variable)) {
                    throw new Exception("INVALID_DATA_TYPE", getStatusCodes('VALIDATION_ERROR'));
                }
            }

            if ($requiredType == 'numeric') {
                if (!is_numeric($variable)) {
                    throw new Exception("INVALID_DATA_TYPE", getStatusCodes('VALIDATION_ERROR'));
                }
            }

            return true;
        } catch (Exception $exception) {
            addToLog($exception->getMessage());
            return $exception;
        }
    }
}

