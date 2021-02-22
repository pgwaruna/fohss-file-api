<?php
/**
 * Page Title : ChannelsInterface .
 *
 * Filename : ChannelsInterface.php
 *
 * Description: Channels functionality, A channel can have multiple api accounts, create update delete and search by id and search all
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
use App\Http\Requests\File\ChannelsRequest;

Interface ChannelsInterface
{
    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 14/01/2021.
     *
     * Channels
     * @category Hss-Website
     *
     * @param ChannelsRequest $request Input - post data of Channels
     *
     * @return array    |        | Created Channels data
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function create(ChannelsRequest $request);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: Date: 14/01/2021.
     *
     * Update Channels
     * @category Hss-Website
     *
     * @param ChannelsRequest $request Input - post data of Channels
     *
     * @return array    |        | Updated Channels data
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function update(ChannelsRequest $request, $id);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 14/01/2021.
     *
     * Channels delete
     * @category Hss-Website
     *
     * @param $id | Input - Id of the Channel to delete
     *
     * @return array    |        | Deleted Channels data
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
     * Channel name for given id
     * @category Hss-Website
     *
     * @param $id | Input - Channels id to view the channel name
     *
     * @return array    |        | Channel value data
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function getChannel($id);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 05/01/2021.
     *
     * View all Channels
     * @category Hss-Website
     *
     * @return array    |        | Channels
     *
     * @throws Exception
     *
     * @version 1.0.0
     *
     */
    public function getChannels();
}
