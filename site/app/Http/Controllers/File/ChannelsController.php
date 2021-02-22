<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\ChannelsRequest;
use App\Interfaces\File\ChannelsInterface;
use App\Models\ApiVsPermission;
use App\Models\Channel;
use Exception;
use App\Services\Enum\EnumService;
use Illuminate\Support\Facades\DB;

class ChannelsController extends Controller implements ChannelsInterface {


    /**
     * @var EnumService
     */
    private $enum;
    /**
     * @var false|\Tymon\JWTAuth\Contracts\JWTSubject
     */
    private $user;

    public function __construct() {
        $this->enum = new EnumService();
    }

    public function create(ChannelsRequest $request) {
        DB::beginTransaction();
        try {

            //If Channel exists
            $channelExist = Channel::where('channel_name', $request->channel_name)
                ->first();
            if ($channelExist) {
                throw new Exception("channel_name_exists");
            }

            $channel = new Channel();
            $channel->channel_name = $request->channel_name;
            $channel->save();
            DB::commit();

            logActivity($this->enum->WRITE, 'Create channel ' . $request->channel_name, 1);
            return $this->successResponse('create_channel_ok', $channel);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('create_channel_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function update(ChannelsRequest $request, $id) {
        DB::beginTransaction();
        try {

            $idValidation = checkDataType($id);

            if ($idValidation instanceof Exception) {
                throw new Exception("channel_id_should_be_a_number");
            }

            $channelExists = Channel::where('id', '=', $id)->first();
            if (!$channelExists) {
                throw new Exception("channel_id_not_exists");
            }

            $channelExists->channel_name = $request->channel_name;
            $channelExists->save();
            DB::commit();

            logActivity($this->enum->WRITE, 'Update channel ' . $channelExists->channel_name, 1);
            return $this->successResponse('update_channel_ok', $channelExists);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('update_channel_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }
    public function delete($id) {
        DB::beginTransaction();
        try {

            $idValidation = checkDataType($id);

            if ($idValidation instanceof Exception) {
                throw new Exception("channel_id_should_be_a_number");
            }

            $channelId = Channel::where('id', '=', $id)->first();
            if (!$channelId) {
                throw new Exception("channel_id_not_exists");
            }

            $channelId->delete();
            DB::commit();

            logActivity($this->enum->DELETE, 'Delete channel ' . $channelId, 1);
            $data = [];
            return $this->successResponse('delete_channel_ok', $data);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('delete_channel_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function getChannel($id) {
        try {

            if (!isset($id) ) {
                throw new Exception("channel_id_required");
            }

            // Check id is a number
            $idValidation = checkDataType($id);
            if ($idValidation instanceof Exception) {
                throw new Exception("channel_id_should_be_a_number");
            }

            // Check id exists in channel table
            $channelId = Channel::where('id', '=', $id)->first();
            if (!$channelId) {
                throw new Exception("channel_id_not_exists");
            }

            $channel = Channel::where('id', '=', $id)
                ->select(['channel_name'])
                ->get();

            logActivity($this->enum->READ, 'View channel '.$id, 1);
            return $this->successResponse('view_channel_ok',$channel);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('view_channel_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }

    public function getChannels() {
        try{
            $channels = Channel::select(['channel_name'])
                ->get();

            logActivity($this->enum->READ, 'View all channels', 1);
            return $this->successResponse('view_all_channels_ok',$channels);

        } catch (Exception $exception) {
            DB::rollBack();
            logActivity($this->enum->ERROR, $exception->getMessage(), 0);
            return $this->errorResponse('view_all_channels_fail_'.$exception->getMessage(),getStatusCodes('EXCEPTION'));
        }
    }
}
