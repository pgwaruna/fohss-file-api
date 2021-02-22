<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends FormRequest
{
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
                'status' => 'fail',
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
            'channel_id' => 'required|integer|exists:channels,id',
            'system_name' => 'required|min:2|max:20',
            'apitoken' => 'required|min:20|max:100',
            'contact_mob' => 'required|min:10|max:10',
            'contact_email' => 'required|min:2|max:100',
            'api_expire' => 'required',
            'active_status' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {

        return [
            'channel_id.required' => 'channel_id_required',
            'channel_id.integer' => 'channel_id_should_be_integer',
            'channel_id.exists' => 'channel_id_not_exists',

            'system_name.required' => 'system_name_required',
            'system_name.max' => 'system_name_too_long',
            'system_name.min' => 'system_name_too_short',

            'apitoken.required' => 'token_required',
            'apitoken.max' => 'token_too_long',
            'apitoken.min' => 'token_too_short',

            'contact_mob.required' => 'contact_mob_required',
            'contact_mob.max' => 'contact_mob_too_long',
            'contact_mob.min' => 'contact_mob_too_short',

            'contact_email.required' => 'contact_email_required',
            'contact_email.min' => 'contact_email_too_short',
            'contact_email.max' => 'contact_email_too_long',

            'api_expire.required' => 'api_expire_required',
            'active_status.required' => 'active_status_required',

        ];
    }
}
