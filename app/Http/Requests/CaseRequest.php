<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 10/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CaseRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        if (!Gate::allows('caso.cadastrar') || !Gate::allows('caso.atualizar')) {
            return false;
        }
        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:cases,name,' . ($this->id ?? 0),
            'phase' => 'required',
            'type_id' => 'required'
        ];
    }
}
