<?php

namespace App\Http\Requests\Waitings;

use Illuminate\Foundation\Http\FormRequest;

class CreateWaitingStudentRequest extends FormRequest
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
        'grade'=>'required|max:255',
        'jaName'=>'required|max:255',
        'kanaName'=>'required|max:255',
        'enName'=>'required|max:255',
        'tel1'=>'required|regex:/(0)[0-9]{8,9}/',
        'tel2'=>'nullable|regex:/(0)[0-9]{8,9}/',
        'email1'=>'required|email',
        'email2'=>'nullable|email',
        'address'=>'nullable|numeric',
        'addDetails'=>'nullable|string|max:255',
        'lesson'=>'required|numeric',
        'busUse'=>'nullable|numeric',
        'addDetails'=>'nullable|string|max:255',
        'pickup'=>'nullable|numeric',
        'pickupDetails'=>'nullable|string|max:255',
        'send'=>'nullable|numeric',
        'sendDetails'=>'nullable|string|max:255',
        'note'=>'nullable|max:255',
        'province'=>'nullable|max:255'
      ];
    }
}
