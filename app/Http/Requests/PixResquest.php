<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class PixResquest extends FormRequest
{
    /**
     * @return RedirectResponse|true
     */
    public function authorize(): bool|RedirectResponse
    {
        if (!Gate::allows('pesquisa_pix')) {
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
            'key_pix' => 'required|min:11',
            'motivation' => 'required|min:10',
        ];
    }
}
