<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Data\Address;
use App\Models\Person\Person;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    /**
     * Show the form for editing the specified address.
     *
     * @param int $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($id): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('pessoa.atualizar')) {
            toast('Sem permissão!', 'info');
            return back();
        }

        try {
            $address = Address::findOrFail($id);
            
            // Buscar a pessoa associada a este endereço
            $person = Person::whereHas('address', function($query) use ($id) {
                $query->where('address_id', $id);
            })->first();
            
            if (!$person) {
                toast('Pessoa não encontrada para este endereço.', 'error');
                return back();
            }

            return view('address.edit', compact('address', 'person'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Endereço não localizado', 'error');
            return back();
        }
    }

    /**
     * Update the specified address in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        if (!Gate::allows('pessoa.atualizar')) {
            toast('Sem permissão!', 'info');
            return back();
        }

        try {
            $address = Address::findOrFail($id);
            
            $validated = $request->validate([
                'code' => 'nullable|string|max:10',
                'address' => 'required|string|max:255',
                'number' => 'nullable|string|max:10',
                'district' => 'nullable|string|max:100',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'uf' => 'nullable|string|max:2',
                'complement' => 'nullable|string|max:255',
                'reference_point' => 'nullable|string|max:255',
                'observacao' => 'nullable|string|max:1000',
                'data_do_dado' => 'nullable|date',
                'fonte_do_dado' => 'nullable|string|max:255',
            ]);

            $address->update($validated);
            
            toast('Endereço atualizado com sucesso!', 'success');
            
            // Buscar a pessoa para retornar à página correta
            $person = Person::whereHas('address', function($query) use ($id) {
                $query->where('address_id', $id);
            })->first();
            
            if ($person) {
                return redirect()->route('person.show', $person->id);
            }
            
            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível atualizar o endereço', 'error');
            return back();
        }
    }

    /**
     * Remove the specified address from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        if (!Gate::allows('pessoa.excluir')) {
            toast('Sem permissão!', 'info');
            return back();
        }

        try {
            $address = Address::findOrFail($id);
            
            // Buscar a pessoa antes de excluir o endereço
            $person = Person::whereHas('address', function($query) use ($id) {
                $query->where('address_id', $id);
            })->first();
            
            // Remover a associação da tabela pivot antes de excluir o endereço
            if ($person) {
                $person->address()->detach($id);
            }
            
            $address->delete();
            
            toast('Endereço excluído com sucesso', 'success');
            
            if ($person) {
                return redirect()->route('person.show', $person->id);
            }
            
            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível excluir o endereço', 'error');
            return back();
        }
    }
} 