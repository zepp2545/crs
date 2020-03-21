<?php

namespace App\Http\Requests\Students;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            'lesson'=>'required',
            'grade'=>'required|max:255',
            'jaName'=>'required|max:255',
            'kanaName'=>'required|max:255',
            'enName'=>'required|max:255',
            'tel1'=>'required|regex:/(0)[0-9]{8,9}/',
            'tel2'=>'nullable|regex:/(0)[0-9]{8,9}/',
            'email1'=>'required|email',
            'email2'=>'nullable|email',
            'address'=>'required|numeric',
            'addDetails'=>'nullable|string|max:255',
            'note'=>'nullable|max:255',
            'province'=>'nullable|max:255'
          ];
    }
}
