<?php
namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Services\Enum\EnumService;

class FileGetRequest extends FormRequest
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
        $this->enum = new EnumService();

        return [
            'api_token' => 'required|string|min:40|max:40',
            'access_token' => 'required|string|min:2|max:100',
            'channel_name' => 'required|string|min:2|max:50|exists:channels,channel_name'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {

        return [
            'api_token.required' => 'api_token_required',
            'api_token.string' => 'api_token_invalid',
            'api_token.min' => 'api_token_invalid',
            'api_token.max' => 'api_token_invalid',

            'access_token.required' => 'faccess_token_required',
            'access_token.string' => 'access_token_invalid',
            'access_token.min' => 'access_token_invalid',
            'access_token.max' => 'access_token_invalid',

            'channel_name.required' => 'channel_name_required',
            'channel_name.string' => 'channel_name_should_be_a_string',
            'channel_name.min' => 'channel_name_invalid',
            'channel_name.max' => 'channel_name_invalid',
            'channel_name.exists' => 'invalid_channel',
        ];
    }
}
