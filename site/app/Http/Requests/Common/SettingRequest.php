<?php
namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Services\Enum\EnumService;

class SettingRequest extends FormRequest
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
            'setting_key' => 'required|min:2|max:50',
            'setting_value' => 'required|min:1|max:255',
            'description' => 'required|min:5|max:255',
            'show_front' => 'required|in:'.$this->enum->show.','.$this->enum->hide.'',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {

        return [
            'setting_key.required' => 'setting_key_required',
            'setting_key.max' => 'setting_key_too_long',
            'setting_key.min' => 'setting_key_too_short',

            'setting_value.required' => 'setting_value_required',
            'setting_value.max' => 'setting_value_too_long',
            'setting_value.min' => 'setting_value_too_short',

            'description.required' => 'description_required',
            'description.max' => 'description_too_long',
            'description.min' => 'description_too_short',

            'show_front.required' => 'show_front_required',
            'show_front.in' => 'show_front_invalid',
        ];
    }
}
