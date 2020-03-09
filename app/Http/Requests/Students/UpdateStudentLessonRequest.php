<?php

namespace App\Http\Requests\students;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentLessonRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'busUse'=>'required|numeric',
          'pickup'=>'nullable|numeric',
          'pickupDetails'=>'nullable|string|max:255',
          'send'=>'nullable|numeric',
          'sendDetails'=>'nullable|string|max:255',
          'start_date'=>'nullable|date',
          'quit_date'=>'nullable|date'
        ];
    }
}
