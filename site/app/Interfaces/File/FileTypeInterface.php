<?php
/**
 * Page Title : FileTypeInterface .
 *
 * Filename : FileTypeInterface.php
 *
 * Description: File Type functionality create update delete and search by id and search all
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
use App\Http\Requests\File\FileTypeRequest;

Interface FileTypeInterface
{
    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 18/01/2021.
     *
     * Create File mime types of files
     * @category Hss-Website
     *
     * @param FileTypeRequest $request Input - post data of file types
     *
     * @return array    |        | Created file types data
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function create(FileTypeRequest $request);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: Date: 18/01/2021.
     *
     * Update File mime types
     * @category Hss-Website
     *
     * @param FileTypeRequest $request Input - post data of File mime types
     *
     * @return array    |        | Updated File mime types data
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function update(FileTypeRequest $request, $id);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 18/01/2021.
     *
     * File mime types delete
     * @category Hss-Website
     *
     * @param $id | Input - Id of the File mime types to delete
     *
     * @return array    |        | Empty array
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function delete($id);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 18/01/2021.
     *
     * File mime type for given id
     * @category Hss-Website
     *
     * @param $id | Input - File mime type id to view the File mime type
     *
     * @return array    |        | File mime type data
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function getFileType($id);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 18/01/2021.
     *
     * View all File mime types
     * @category Hss-Website
     *
     * @return array    |        | File mime types
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function getFileTypes();
}
