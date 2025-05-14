<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/12/2022
 * @copyright NIP CIBER-LAB @2022
 */

namespace App\Http\Controllers\Case;

use App\Http\Controllers\Controller;
use App\Http\Requests\CaseAttachmentRequest;
use App\Models\Cases\CaseFile;
use App\Models\Cases\Cases;
use App\Models\Departament\Unity;
use App\Services\CaseService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CaseAnalysisController extends Controller
{
    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function show($id): View|Factory|RedirectResponse|Application
    {
        $user = Auth::user();
        $case = Cases::findOrFail($id);
        $caseIds = CaseService::getCaseUserIds($user);
        $isAuthorized = Gate::allows('caso.ler') &&
            ($caseIds->contains($id) ||
                ($case->user_id == $user->id ||
                    ($case->sector_id == $user->sector_id && $user->coordinator)));

        if (!$isAuthorized) {
            toast('Sem permissão!', 'info');
            return back();
        }

        try {
            $unitys = Unity::all();
            $procedures = $case->procedures;
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

            return view('case.analysis.index', compact('user', 'case', 'files', 'unitys', 'procedures'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema! Não foi possível recuperar o caso', 'error');
            return back();
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function file($id): View|Factory|RedirectResponse|Application
    {
        if (!Gate::allows('caso.anexar')) {
            toast('Sem permissão!', 'info');
            return back();
        }
        try {
            $file = CaseFile::find($id);
            $file = ($file->file_type)::find($file->file_id);

            if ($file->view == 'documents') {
                $read = Storage::get($file->path);
                $mime = Storage::mimeType($file->path);
                $href = encrypt($file->path);
                return view('case.analysis_files.' . $file->view, compact('read', 'mime', 'href'));
            }

            return view('case.analysis_files.' . $file->view, compact('file'));
        } catch (\Exception $exception) {
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
            $caseFile = CaseFile::findOrFail($id);

            if (self::deleteFile($caseFile)) {
                $caseFile->delete();
                toast('Arquivo excluído com sucesso!', 'success');
                return back();
            }

            toast('Não foi possível excluir o arquivo!', 'error');

            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Não foi possível localizar o arquivo!', 'error');

            return back();
        }
    }

    /**
     * @param CaseAttachmentRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function attachment(CaseAttachmentRequest $request, $id): RedirectResponse
    {
        try {
            if (!Gate::allows('caso.anexar')) {
                toast('Sem permissão!', 'info');
                return back();
            }

            if (CaseService::attachment($request, $id)) {
                toast('Arquivo anexado com sucesso!', 'success');
                return back();
            }
            return back();
        }catch (\Exception $exception){
            toast($exception->getMessage(), 'error');
            return back();
        }
    }

    /**
     * @param CaseFile $caseFile
     * @return bool
     */
    public static function deleteFile(CaseFile $caseFile): bool
    {
        try {
            $file = $caseFile->file_type::findOrFail($caseFile->file_id);

            if ($caseFile->file_alias === 'documents') {
                Storage::delete($file->path);
            }

            return $file->delete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }
    }
}
