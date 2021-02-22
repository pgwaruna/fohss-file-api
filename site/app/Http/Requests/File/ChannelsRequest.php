<?php
namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Services\Enum\EnumService;

class ChannelsRequest extends FormRequest
{
    /**
     * @var EnumService|mixed
     */
    private $enum;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all()
            ],
                getStatusCodes('VALIDATION_ERROR'))
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'channel_name' => 'required|string|min:2|max:20'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {

        return [
            'channel_name.required' => 'channel_name_required',
            'channel_name.string' => 'channel_name_should_be_a_string',
            'channel_name.min' => 'channel_name_too_short',
            'channel_name.max' => 'channel_name_too_long',
        ];
    }
}
