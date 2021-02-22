<?php

namespace App\Services\Enum;

class EnumService
{
    /**
     * @var mixed
     */
    public $active;
    /**
     * @var mixed
     */
    public $inactive;
    /**
     * @var mixed
     */
    public $show;
    /**
     * @var mixed
     */
    public $hide;
    /**
     * @var string
     */
    public $READ;
    /**
     * @var string
     */
    public $WRITE;
    /**
     * @var string
     */
    public $DELETE;
    /**
     * @var string
     */
    public $ERROR;
    /**
     * @var string
     */
    public $LOGIN;
    /**
     * @var string
     */
    public $LOGOUT;

    public function __construct()
    {

        $this->active = config("enum.common.active_status")['ACTIVE']; //return 1
        $this->inactive = config("enum.common.active_status")['INACTIVE'];//return 0

        $this->show = config("enum.common.display_backend")['SHOW']; //return 1
        $this->hide = config("enum.common.display_backend")['HIDE'];//return 0

        // Error codes for log table, dont change the variables and values
        $this->READ = "READ";
        $this->WRITE = "WRITE";
        $this->DELETE = "DELETE";
        $this->ERROR = "ERROR";
        $this->LOGIN = "LOGIN";
        $this->LOGOUT = "LOGOUT";
    }

    // Get all keys of the enum for check in request
    function getEnumKeys($enumKey)
    {
        $enumArray = config("enum." . $enumKey);
        $keys = [];

        foreach ($enumArray as $key => $value) {
            $keys[] = $key;
        }

        return $keys;
    }

    // Get the enum key when value given
    function getEnumKey($enumKey, $value)
    {
        $enumArray = config("enum." . $enumKey);
        $key = array_search($value, $enumArray);

        return $key;
    }

    // Get the enum value when key given
    function getEnumValue($enumKey, $key)
    {
        $enumArray = config("enum." . $enumKey);
        return $enumArray[$key];
    }

}
