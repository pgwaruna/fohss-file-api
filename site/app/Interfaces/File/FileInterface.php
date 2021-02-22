<?php
/**
 * Page Title : FileInterface .
 *
 * Filename : FileInterface.php
 *
 * Description: File functionality, create update delete and search by id and search all
 *
 * @package Govinena
 * @category Pest-Disease-KB.
 * @version Release: 1.0.0
 * @since File Creation Date: 18/01/2021.
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
use App\Http\Requests\File\FileRequest;
use App\Http\Requests\File\FileGetRequest;

Interface FileInterface
{
    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 18/01/2021.
     *
     * Create File get the channel id from the api token, Check WRITE access for token
     * @category Hss-Website
     *
     * @param FileRequest $request Input - post data of File
     *
     * @return array    |        | Created File data
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function createFile(FileRequest $request);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 18/01/2021.
     *
     * File delete by access token and channel id,
     * @category Hss-Website
     *
     * @param $access_token | Input - Access Token of the File to delete Check DELETE access for token
     *
     * @return array    |        | Empty array
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function deleteFile(FileGetRequest $request);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 18/01/2021.
     *
     * View File for given channel id and access token, get the channel id from the api token Check READ access for token
     * @category Hss-Website
     *
     * @param $access_token | Input - File id to view the File
     *
     * @return array    |        | File data array
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function getFile(FileGetRequest $request);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 18/01/2021.
     *
     * View all Files for given channel get the api token and get the channel id, and show all files related to channel
     * @category Hss-Website
     *
     * @return array    |        | Files array
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function getFiles();
}
