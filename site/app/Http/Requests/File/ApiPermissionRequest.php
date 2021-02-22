<?php
namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Services\Enum\EnumService;

class ApiPermissionRequest extends FormRequest
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
            'api_id' => 'required|integer|exists:api_accounts,id',
            'permission_key' => 'required|string|min:2|max:20|exists:permission,permission_key'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {

        return [
            'api_id.required' => 'api_id_required',
            'api_id.integer' => 'api_id_should_be_a_number',
            'api_id.exists' => 'api_id_not_exists',
            'permission_key.required' => 'permission_key_required',
            'permission_key.string' => 'permission_key_should_be_a_string',
            'permission_key.min' => 'permission_key_too_short',
            'permission_key.max' => 'permission_key_too_long',
            'permission_key.exists' => 'permission_key_not_exists',
        ];
    }
}
