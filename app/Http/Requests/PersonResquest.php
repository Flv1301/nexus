<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 05/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

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
        return [
            'name' => 'required|min:3',
            'cpf' => Rule::unique('persons')->whereNotNull('cpf'),
            'voter_registration' => Rule::unique('persons')->whereNotNull('voter_registration'),
            'vcard' => 'mimetypes:text/vcard'
        ];
    }
}
