<?php

namespace App\Http\Controllers\Case;

use App\Http\Controllers\Controller;
use App\Http\Requests\CaseRequest;
use App\Models\Cases\Cases;
use App\Models\Departament\Sector;
use App\Models\Departament\Unity;
use App\Models\Person\Person;
use App\Models\User;
use App\Services\CaseService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CaseController extends Controller
{
    /**
     * @var array|int[]
     */
    private array $exceptIds;

    public function __construct()
    {
        $this->exceptIds = [1, 2];
    }

    /**
     * @return Application|Factory|View|RedirectResponse
     */
    public function index(): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('caso')) {
            toast('Sem permissão!', 'info');
            return redirect()->route('dashboard');
        }

        $user = Auth::user();
        $caseUserIds = CaseService::getCaseUserIds($user);
        $cases = $user->sector->cases()->with(['user', 'unity', 'sector', 'persons'])->get()->merge(
            Cases::with(['user', 'unity', 'sector', 'persons'])->whereIn('id', $caseUserIds)->get()
        );

        return view('case.index', compact('cases', 'user', 'caseUserIds'));
    }

    /**
     * @return Application|Factory|View|RedirectResponse
     */
    public function create(): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('caso.cadastrar')) {
            toast('Sem permissão!', 'info');
            return back();
        }
        $user = Auth::user();
        $this->exceptIds[] = $user->id;
        $users = User::all()->except($this->exceptIds)->sort();
        $persons = Person::all();
        $unitys = Unity::all();
        $sectors = Sector::all();
        $cases = Cases::all();
        $case = new Cases();

        return view(
            'case.create',
            compact(
                'case',
                'users',
                'persons',
                'unitys',
                'sectors',
                'cases'
            )
        );
    }

    /**
     * @param CaseRequest $request
     * @return RedirectResponse
     */
    public function store(CaseRequest $request): RedirectResponse
    {
        try {
            $user = Auth::user();
            $identifier = CaseService::generateIdentifier($user);
            $request->merge(['identifier' => $identifier]);
            $dataCase = $request->except(['users_allowed', 'sectors_allowed', 'unitys_allowed', 'persons']);

            $merge = [
                'user_id' => $user->id,
                'unity_id' => $user->unity_id,
                'sector_id' => $user->sector_id
            ];

            $inputs = array_merge($dataCase, $merge);
            $case = Cases::create($inputs);
            $users = $request->input('users_allowed', []);
            $sectors = $request->input('sectors_allowed', []);
            $unitys = $request->input('unitys_allowed', []);
            $persons = $request->input('persons', []);
            $case->sectors()->sync($sectors);
            $case->unitys()->sync($unitys);
            $case->users()->sync($users);
            $case->persons()->sync($persons);

            toast("Caso {$request->input('name')} gravado com sucesso!", 'success');

            return redirect()->route('cases');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível criar o caso ' . $request->input('name'), 'error');

            return back();
        }
    }


    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($id): View|Factory|RedirectResponse|Application
    {
        try {
            $case = Cases::findOrFail($id);
            $user = Auth::user();
            if (!Gate::allows(
                    'caso.atualizar'
                ) || !($case->user_id == $user->id || ($case->sector_id == $user->sector_id && $user->coordinator))) {
                toast('Sem permissão!', 'info');
                return back();
            }
            if ($case->status === 'CONCLUIDO' || $case->status === 'ARQUIVADO') {
                toast('Caso já encontra-se concluído ou arquivado.', 'info');
                return redirect()->route('cases');
            }
            $cases = Cases::all();
            $this->exceptIds[] = $user->id;
            $users = User::all()->except($this->exceptIds)->sort();
            $persons = Person::all();
            $unitys = Unity::all();
            $sectors = Sector::all();
            $caseUsers = $case->users->modelKeys();
            $casePersons = $case->persons->modelKeys();
            $caseUnitys = $case->unitys->modelKeys();
            $caseSectors = $case->sectors->modelKeys();
            return view(
                'case.edit',
                compact(
                    'case',
                    'caseUsers',
                    'casePersons',
                    'caseUnitys',
                    'caseSectors',
                    'users',
                    'persons',
                    'unitys',
                    'sectors',
                    'cases',
                )
            );
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema, Não foi possível localizar o caso!', 'error');
            return back();
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $case = Cases::findOrFail($id);
            
            // Validação manual para edição (ignora campos protegidos)
            $request->validate([
                'name' => 'required|string|max:100|unique:cases,name,' . $id,
                'adicionar_dias' => 'nullable|in:1,3,5,15,30,60,90',
            ], [
                'name.required' => 'O campo PJE é obrigatório.',
                'name.unique' => 'Este PJE já está sendo utilizado.',
                'adicionar_dias.in' => 'O campo Adicionar Dias deve ser um dos valores: 1, 3, 5, 15, 30, 60, 90.',
            ]);
            
            // Preservar valores originais ANTES de qualquer processamento
            $originalDate = $case->date;
            $originalPrazoDias = $case->prazo_dias;
            
            $usersAllowed = $request->input('users_allowed');
            $unitysAllowed = $request->input('unitys_allowed');
            $sectorsAllowed = $request->input('sectors_allowed');
            $persons = $request->input('persons');
            $adicionarDias = $request->input('adicionar_dias');
            
            // Processar adição de dias ao prazo (única forma de alterar prazo_dias na edição)
            $novoPrazoDias = $originalPrazoDias;
            if ($adicionarDias && is_numeric($adicionarDias)) {
                $prazoAtual = (int)($originalPrazoDias ?? 0);
                $novoPrazoDias = $prazoAtual + (int)$adicionarDias;
            }
            
            // Criar array de dados para atualização, preservando campos protegidos
            $dataToUpdate = $request->except(['users_allowed', 'unitys_allowed', 'persons', 'adicionar_dias', 'date', 'prazo_dias']);
            $dataToUpdate['date'] = $originalDate;
            $dataToUpdate['prazo_dias'] = $novoPrazoDias;
            
            // Atualizar o caso
            $case->fill($dataToUpdate);
            $case->save();
            
            $keys = [];
            if ($sectorsAllowed) {
                foreach ($sectorsAllowed as $sector) {
                    $keys[] = $sector;
                }
            }
            $case->sectors()->sync($keys);
            $keys = [];
            if ($unitysAllowed) {
                foreach ($unitysAllowed as $unity) {
                    $keys[] = $unity;
                }
            }
            $case->unitys()->sync($keys);
            $keys = [];
            if ($usersAllowed) {
                foreach ($usersAllowed as $allowed) {
                    $keys[] = $allowed;
                }
            }
            $keys[] = $case->user_id;
            $case->users()->sync($keys);
            $keys = [];
            if ($persons) {
                foreach ($persons as $person) {
                    $keys[] = $person;
                }
            }
            $case->persons()->sync($keys);
            
            // Mensagem personalizada se dias foram adicionados
            if ($adicionarDias && is_numeric($adicionarDias)) {
                toast("Caso atualizado com sucesso! {$adicionarDias} dia(s) adicionado(s) ao prazo.", 'success');
            } else {
                toast('Caso atualizado com sucesso!', 'success');
            }
            
            return back();
        } catch (\Exception $exception) {
            toast('Erro de sistema. Não foi possível atualizar o caso!', 'error');
            Log::error($exception->getMessage());
            return back();
        }
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $case = Cases::findOrFail($id);
            $userId = Auth::id();
            if (!Gate::allows('caso.excluir') || $case->user_id !== $userId) {
                toast('Sem permissão!', 'info');
                return back();
            }
            $case->delete();
            toast('Caso deletado com sucesso!', 'success');
            return redirect()->route('cases');
        } catch (ModelNotFoundException $exception) {
            toast('Erro de sistema. Não foi possível excluir o caso!', 'error');
            Log::error($exception->getMessage());
            return redirect()->route('cases');
        }
    }
}
