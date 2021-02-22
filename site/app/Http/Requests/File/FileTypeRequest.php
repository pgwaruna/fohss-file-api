<?php
namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Services\Enum\EnumService;

class FileTypeRequest extends FormRequest
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
            'mime_type' => 'required|string|min:2|max:100'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {

        return [
            'mime_type.required' => 'mime_type_required',
            'mime_type.string' => 'mime_type_should_be_a_string',
            'mime_type.min' => 'mime_type_too_short',
            'mime_type.max' => 'mime_type_too_long',
        ];
    }
}
