<?php

namespace App\Http\Controllers\Case;

use App\Http\Controllers\Controller;
use App\Http\Requests\CaseRequest;
use App\Models\Cases\Cases;
use App\Models\Cases\CaseFile;
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
        
        // Mostrar apenas os casos compartilhados com o usuário (que têm o ícone do olho)
        $cases = Cases::with(['user', 'unity', 'sector', 'persons'])
            ->whereIn('id', $caseUserIds)
            ->get();

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
        
        // Carregar usuários disponíveis para compartilhamento (exceto o usuário atual)
        $allUsers = User::all();
        $users = $allUsers->where('id', '!=', $user->id);
        
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
            $dataCase = $request->except(['users_allowed', 'sectors_allowed', 'unitys_allowed', 'persons', 'case_files', 'file_names', 'file_types']);

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

            // Processar arquivos enviados junto com o caso
            $uploadedFiles = $this->processUploadedFiles($request, $case);
            $uploadMessage = '';
            if ($uploadedFiles > 0) {
                $uploadMessage = " e {$uploadedFiles} arquivo(s) anexado(s)";
            }

            toast("Caso {$request->input('name')} gravado com sucesso{$uploadMessage}!", 'success');

            return redirect()->route('cases');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível criar o caso ' . $request->input('name'), 'error');

            return back();
        }
    }

    /**
     * Processar arquivos enviados no formulário
     * 
     * @param Request $request
     * @param Cases $case
     * @return int Número de arquivos processados
     */
    private function processUploadedFiles($request, $case): int
    {
        $uploadedFilesCount = 0;
        
        if ($request->hasFile('case_files')) {
            $files = $request->file('case_files');
            $fileNames = $request->input('file_names', []);
            $fileTypes = $request->input('file_types', []);
            
            foreach ($files as $index => $file) {
                if ($file && $file->isValid()) {
                    try {
                        // Determinar o nome e tipo do arquivo
                        $fileName = $fileNames[$index] ?? null;
                        $fileType = $fileTypes[$index] ?? 'document';
                        
                        // Usar o serviço existente para salvar o arquivo
                        $attachment = CaseService::storage($file, CaseService::fileName($case, $file), $fileType, $case);
                        
                        if ($attachment) {
                            // Criar o registro do arquivo
                            $user = Auth::user();
                            CaseFile::create([
                                'case_id' => $case->id,
                                'user_id' => $user->id,
                                'unity_id' => $user->unity_id,
                                'sector_id' => $user->sector_id,
                                'file_type' => config('file.file_type.' . $fileType),
                                'file_layout' => config('file.file_type_layout.' . $fileType),
                                'file_alias' => config('file.file_alias.' . $fileType),
                                'file_id' => $attachment->id,
                                'name' => $fileName,
                            ]);
                            
                            $uploadedFilesCount++;
                        }
                    } catch (\Exception $e) {
                        Log::error("Erro ao processar arquivo {$index}: " . $e->getMessage());
                    }
                }
            }
        }
        
        return $uploadedFilesCount;
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
            $caseUserIds = CaseService::getCaseUserIds($user);
            
            // Verificar permissões: criador, coordenador do setor ou caso compartilhado
            $hasAccess = $case->user_id == $user->id || 
                        ($case->sector_id == $user->sector_id && $user->coordinator) || 
                        $caseUserIds->contains($case->id);
            
            if (!Gate::allows('caso.atualizar') || !$hasAccess) {
                toast('Sem permissão!', 'info');
                return back();
            }
            if ($case->status === 'CONCLUIDO' || $case->status === 'ARQUIVADO') {
                toast('Caso já encontra-se concluído ou arquivado.', 'info');
                return redirect()->route('cases');
            }
            $cases = Cases::all();
            $allUsers = User::all();
            $users = $allUsers->where('id', '!=', $user->id); // Excluir apenas o usuário atual
            $persons = Person::all();
            $unitys = Unity::all();
            $sectors = Sector::all();
            $caseUsers = $case->users->modelKeys();
            $casePersons = $case->persons->modelKeys();
            $caseUnitys = $case->unitys->modelKeys();
            $caseSectors = $case->sectors->modelKeys();
            
            // Buscar arquivos do caso para exibir na aba de arquivos
            $files = [];
            if ($case->files->count()) {
                foreach ($case->files as $file) {
                    $document = ($file->file_type)::find($file->file_id);
                    if ($document) {
                        $document->user_id = $file->user_id;
                        $document->procedure = $file->procedures->where('request_user_id', Auth::id())->count();
                        $document->alias = $file->name;
                        $document->file_alias = config('file.file_alias_pt_BR.' . $file->file_alias);
                        $files[$file->id] = $document;
                    }
                }
            }
            
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
                    'files'
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
            $dataToUpdate = $request->except(['users_allowed', 'unitys_allowed', 'persons', 'adicionar_dias', 'date', 'prazo_dias', 'case_files', 'file_names', 'file_types']);
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
            
            // Processar novos arquivos enviados
            $uploadedFiles = $this->processUploadedFiles($request, $case);
            
            // Mensagem personalizada baseada nas ações realizadas
            $messages = [];
            if ($adicionarDias && is_numeric($adicionarDias)) {
                $messages[] = "{$adicionarDias} dia(s) adicionado(s) ao prazo";
            }
            if ($uploadedFiles > 0) {
                $messages[] = "{$uploadedFiles} arquivo(s) anexado(s)";
            }
            
            $extraMessage = count($messages) > 0 ? ' (' . implode(', ', $messages) . ')' : '';
            toast("Caso atualizado com sucesso{$extraMessage}!", 'success');
            
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
