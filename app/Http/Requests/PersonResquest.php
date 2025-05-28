<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class PersonResquest extends FormRequest
{
    /**
     * @return RedirectResponse|true
     */
    public function authorize(): bool|RedirectResponse
    {
        if (!Gate::allows('pessoa.cadastrar') || !Gate::allows('pessoa.atualizar')) {
            toast('Sem permissão!', 'info');
            return back();
        }
        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        $personId = $this->route('id'); // Para updates, pega o ID da rota
        
        return [
            'name' => 'required|min:3',
            'cpf' => [
                Rule::unique('persons', 'cpf')
                    ->whereNotNull('cpf')
                    ->ignore($personId)
            ],
            'voter_registration' => [
                Rule::unique('persons', 'voter_registration')
                    ->whereNotNull('voter_registration')
                    ->ignore($personId)
            ],
            'vcard' => 'mimetypes:text/vcard'
        ];
    }
}
