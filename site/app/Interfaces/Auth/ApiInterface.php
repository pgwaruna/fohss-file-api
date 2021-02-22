<?php

/**
 * Page Title : AuthService .
 *
 * Filename : AuthInterface.php
 *
 * Description: User authentication functionality
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

namespace App\Interfaces\Auth;

use JWTAuth;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\Auth\ApiRequest;

interface ApiInterface
{

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 20/10/2020.
     *
     * create new API account
     * @category Pest-Disease-KB.
     *
     * @param ApiRequest $request Input - post data of user data
     *
     * @var
     *
     * @return array    |        | logged user data
     *
     * @throws Exception
     *
     * @uses
     *
     * @version 1.0.0
     *
     * @since .
     *
     */
    public function create(ApiRequest $request);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 20/10/2020.
     *
     * Update exisiting api account
     * @category Pest-Disease-KB.
     *
     * @param ApiRequest $request Input - post data of user data
     *
     * @var
     *
     * @return array    |        | valid token for the user
     *
     * @throws Exception
     *
     * @uses
     *
     * @version 1.0.0
     *
     * @since .
     *
     */
    public function update(ApiRequest $request, $id);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 20/10/2020.
     *
     * delete api account to the passed token
     * @category Pest-Disease-KB.
     *
     * @param ApiRequest $request Logs user out
     *
     * @var
     *
     * @return array    |        | valid token for the user
     *
     * @throws Exception
     *
     * @uses
     *
     * @version 1.0.0
     *
     * @since .
     *
     */
    public function delete($id);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 20/10/2020.
     *
     * view api data
     * @category Pest-Disease-KB.
     *
     * @var
     *
     * @return
     *
     * @throws Exception
     *
     * @uses
     *
     * @version 1.0.0
     *
     * @since .
     *
     */
    public function viewApi(ApiRequest $request);

    /**
     * @author Waruna Gamage.
     * @author Function Creation Date: 20/10/2020.
     *
     * View all apis
     * @category Pest-Disease-KB.
     *
     * @var
     *
     * @return
     *
     * @throws Exception
     *
     * @uses
     *
     * @version 1.0.0
     *
     * @since .
     *
     */
    public function viewAllApi();
}
