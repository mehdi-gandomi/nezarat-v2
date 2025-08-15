<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\InspectionOrder;

class InspectionOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'claim_date_start' => 'required|date',
            'claim_date_end' => 'required|date|after:claim_date_start',
            'claim_no' => 'required|string|max:255',
            'claim_file' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ];

        // Add custom validation for active status
        if ($this->input('status') === 'active') {
            $rules['status'] .= '|unique_active_order';
        }

        return $rules;
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'user_id' => 'کاربر',
            'claim_date_start' => 'تاریخ شروع',
            'claim_date_end' => 'تاریخ پایان',
            'claim_no' => 'شماره ادعا',
            'claim_file' => 'فایل ادعا',
            'status' => 'وضعیت',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'claim_date_end.after' => 'تاریخ پایان باید بعد از تاریخ شروع باشد.',
            'status.unique_active_order' => 'یک دستور بازرسی فعال وجود دارد. لطفاً ابتدا آن را غیرفعال کنید.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
        public function withValidator($validator)
    {
        $validator->addExtension('unique_active_order', function ($attribute, $value, $parameters, $validator) {
            $existingActive = InspectionOrder::where('status', 'active')
                                           ->where('claim_date_end', '>=', now()->toDateString());

            // Exclude current order if we're updating
            $currentId = $this->route('id');
            if ($currentId) {
                $existingActive->where('id', '!=', $currentId);
            }

            return !$existingActive->exists();
        });
    }
}
