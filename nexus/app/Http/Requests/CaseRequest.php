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
        // Detecta se é edição pela presença do ID do caso na URL
        $isEdit = $this->route()->hasParameter('case') || $this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH';
        
        return [
            'name' => 'required|string|max:100|unique:cases,name,' . ($this->route('case') ?? 0),
            'date' => $isEdit ? 'sometimes|nullable|date_format:d/m/Y' : 'required|date_format:d/m/Y',
            'prazo_dias' => $isEdit ? 'sometimes|nullable|in:3,5,7,15,30,60,90' : 'required|in:3,5,7,15,30,60,90',
            'adicionar_dias' => 'nullable|in:1,3,5,15,30,60,90',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'date.required' => 'O campo Data é obrigatório.',
            'date.date' => 'O campo Data deve ser uma data válida.',
            'date.date_format' => 'O campo Data deve estar no formato DD/MM/AAAA.',
            'prazo_dias.required' => 'O campo Prazo Dias é obrigatório.',
            'prazo_dias.in' => 'O campo Prazo Dias deve ser um dos valores: 3, 5, 7, 15, 30, 60, 90.',
        ];
    }
}
