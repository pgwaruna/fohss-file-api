<?php
/**
 * Page Title : SettingInterface .
 *
 * Filename : SettingInterface.php
 *
 * Description: Common settings functionality, create update delete and search by id and search all
 *
 * @package Govinena
 * @category Pest-Disease-KB.
 * @version Release: 1.0.0
 * @since File Creation Date: 20/10/2020.
 *
 * @author
 * - Development Group : Widya Team
 * - Company : Widya.
 *
 * @copyright  Copyright © 2020 WIdya.
 *
 *
 */

namespace App\Interfaces\Common;

use Exception;
use App\Http\Requests\Common\SettingRequest;

Interface SettingInterface
{
    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 24/12/2020.
     *
     * Create common setting
     * @category Hss-Website
     *
     * @param SettingRequest $request Input - post data of common settings
     *
     * @return array    |        | Created setting data
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function create(SettingRequest $request);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 24/12/2020.
     *
     * Update common setting
     * @category Hss-Website
     *
     * @param SettingRequest $request Input - post data of common settings
     *
     * @return array    |        | Updated setting data
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function update(SettingRequest $request, $id);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 24/12/2020.
     *
     * Delete common setting
     * @category Hss-Website
     *
     * @param $id | Input - Id of the setting to delete
     *
     * @return array    |        | Updated setting data
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function delete($id);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 24/12/2020.
     *
     * View setting value for given setting name
     * @category Hss-Website
     *
     * @param $byName | Input - Setting name to view value of the setting
     * @param $byId | Input - Setting id to view value of the setting
     *
     * @return array    |        | setting value data
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function getSetting($byName);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 24/12/2020.
     *
     * View setting value for given setting name
     * @category Hss-Website
     *
     * @return array    |        | All setting data (Active and Inactive)
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function viewAll();
}
