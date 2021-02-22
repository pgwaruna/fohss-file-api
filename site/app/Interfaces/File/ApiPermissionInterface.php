<?php
/**
 * Page Title : ApiPermissionInterface .
 *
 * Filename : ApiPermissionInterface.php
 *
 * Description: Api Permission functionality, create update delete and search by id and search all
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
 */

namespace App\Interfaces\File;

use Exception;
use App\Http\Requests\File\ApiPermissionRequest;

Interface ApiPermissionInterface
{
    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 14/01/2021.
     *
     * Api Permission Assignment
     * @category Hss-Website
     *
     * @param ApiPermissionRequest $request Input - post data of Api Permission Assignment
     *
     * @return array    |        | Created Api Permission data
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function create(ApiPermissionRequest $request);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 14/01/2021.
     *
     * Api Permission Un assignment
     * @category Hss-Website
     *
     * @param $id | Input - Id of the Api Permission to un assign
     *
     * @return array    |        | Deleted assignment data
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function delete($id);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 05/01/2021.
     *
     * Api Permission value for given id
     * @category Hss-Website
     *
     * @param $id | Input - Api Permission id to view the assignment
     *
     * @return array    |        | Api Permission value data
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function getApiPermissionAssign($id);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 05/01/2021.
     *
     * View all Api Permission assigns
     * @category Hss-Website
     *
     * @return array    |        | Api Permission assigns
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function getAllApiPermissionAssigns();
}
