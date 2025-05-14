<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CaseProducereRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        if (!Gate::allows('tramitacao')) {
            return false;
        }
        return true;
    }

    /**
     * @return string[]
     */
    public function rules()
    {
        return [
            'unity_id' => 'required',
            'sector_id' => 'required',
            'limit_date' => 'required',
            'request' => 'required|min:5'
        ];
    }
}
