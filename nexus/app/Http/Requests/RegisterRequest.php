<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 06/02/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        if (Gate::allows('usuario.cadastrar') || Gate::allows('usuario.atualizar')) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:5|max:100|string|unique:users,name,' . ($this->id ?? 0),
            'nickname' => 'required|min:3|max:100|string|unique:users,nickname,' . ($this->id ?? 0),
            'registration' => 'required|numeric|unique:users,registration,' . ($this->id ?? 0),
            'cpf' => 'required|unique:users,cpf,' . ($this->id ?? 0),
            'birth_date' => 'required',
            'unity_id' => 'required',
            'sector_id' => 'required',
            'email' => 'required|string|email|max:255|unique:users,email,' . ($this->id ?? 0),
            'password' => [
                'required' => Rule::requiredIf(fn() => $this->getMethod() != 'PUT')
            ],
            'office' => 'required',
            'role' => 'required',
            'district' => 'required_with_all:address',
            'city' => 'required_with_all:address,district',
            'state' => 'required_with_all:address,district,city',
            'uf' => 'required_with_all:address,district,city, state',
            'ddd' => 'required_with_all:telephone',
        ];
    }
}
