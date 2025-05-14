<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 24/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers\Case;

use App\Http\Controllers\Controller;
use App\Http\Requests\CaseAttachmentRequest;
use App\Http\Requests\CaseProducereRequest;
use App\Models\Cases\CaseFile;
use App\Models\Cases\CaseProcedure;
use App\Models\Cases\CaseProcedureResponse;
use App\Models\Cases\Cases;
use App\Services\CaseService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CaseProcedureController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:tramitacao');
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): Factory|View|Application
    {
        $user = Auth::user();
        $procedures = CaseProcedure::where('user_id', $user->id)->orWhere('request_user_id', $user->id)->get();

        return view('case.procedure.index', compact('procedures', 'user'));
    }

    /**
     * @param CaseProducereRequest $resquest
     * @param $id
     * @return RedirectResponse
     */
    public function create(CaseProducereRequest $resquest, $id): RedirectResponse
    {
        try {
            $user = Auth::user();
            $case = Cases::findOrFail($id);
            $files = $resquest->input('files_procedure');
            $inputs = $resquest->only(['unity_id', 'sector_id', 'user_id', 'limit_date', 'request']);
            $inputs ['case_id'] = $case->id;
            $inputs ['request_user_id'] = $user->id;
            $inputs ['request_unity_id'] = $user->unity_id;
            $inputs ['request_sector_id'] = $user->sector_id;
            $procedure = $case->procedures()->create($inputs);
            if (!empty($files)) {
                foreach ($files as $file) {
                    $casefile = CaseFile::find($file);
                    $procedure->files()->attach($casefile->id);
                }
            }
            toast('Tramitação efetuada com sucesso!', 'success');
            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Não foi possível realizar a tramitação', 'error');
            return back();
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function find(Request $request): array
    {
        $procedure = CaseProcedure::where('id', $request->id)->first();
        $files = [];
        foreach ($procedure->files as $file) {
            $document = ($file->file_type)::find($file->file_id);
            $document['case_file_id'] = $file->id;
            $files[] = $document;
        }
        return ['procedure' => $procedure, 'files' => $files];
    }

    /**
     * @param CaseAttachmentRequest $resquest
     * @return RedirectResponse
     */
    public function response(CaseAttachmentRequest $resquest): RedirectResponse
    {
        $resquest->validate(['response' => 'required|min:3', 'status' => 'required']);
        try {
            $procedure = CaseProcedure::findOrFail($resquest->case_procedure_id);
            $procedure->status = $resquest->status;
            $procedure->save();
            $response = CaseProcedureResponse::create($resquest->only(['response', 'case_procedure_id', 'status']));
            if ($resquest->hasFile('files')) {
                if ($ids = CaseService::attachment($resquest, $procedure->case_id)) {
                    $response->files()->attach($ids);
                }
            }
            toast('Resposta enviada com sucesso!', 'success');
            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema!. Não foi possível responder!', 'error');
            return back();
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function responses($id): View|Factory|Application
    {
        $procedure = CaseProcedure::find($id);
        return view('case.procedure.response.index', compact('procedure'));
    }

    /**
     * @param $id
     * @return array
     */
    public function responseView($id): array
    {
        $response = CaseProcedureResponse::find($id);
        $user = $response->procedure->user;
        $files = [];
        foreach ($response->files as $file) {
            $document = ($file->file_type)::find($file->file_id);
            $document['case_file_id'] = $file->id;
            $files[] = $document->setVisible(['id', 'name', 'created_at', 'case_file_id']);
        }
        return [
            'response' => $response->setVisible(['id', 'response']),
            'files' => $files,
            'user' => $user->setVisible(['nickname'])
        ];
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $procedure = CaseProcedure::find($id);
            if ($procedure->responses->count()){
                toast('Tramitação já possui resposta e não pode ser excluída!', 'info');
                return back();
            }
            $procedure->delete();
            toast('Tramitação excluída com sucesso!', 'success');
            return back();
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível excluir a tramitação!', 'error');
            return back();
        }
    }
}
