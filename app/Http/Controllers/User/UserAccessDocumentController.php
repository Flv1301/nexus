<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 10/08/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers\User;

use App\Enums\TypeDocumentEnum;
use App\Enums\TypeEmailNotificationsEnum;
use App\Http\Controllers\Controller;
use App\Models\User\UserAccessDocument;
use App\Notifications\UserAccessDocumentNotification;
use App\Services\NotificationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PDF;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserAccessDocumentController extends Controller
{
    /**
     * @return mixed
     */
    public function download(): mixed
    {
        $user = Auth::user();
        $pdf = PDF::loadView('pdf.access_contract', compact('user'));

        return $pdf->stream('Termo de Responsabilidade.pdf');
    }

    /**
     * @param $id
     * @return BinaryFileResponse
     */
    public function view($id): BinaryFileResponse
    {
        $document = UserAccessDocument::find($id);
        return response()->file(storage_path('app/' . $document->path));
    }

    /**
     * @return Factory|View|Application
     */
    public function show(): Factory|View|Application
    {
        $user = Auth::user();
        return view('contract.user_disclaimer_show', compact('user'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse|void
     */
    public function upload(Request $request)
    {
        $request->validate([
            'document' => [
                'required',
                'file',
                'mimes:pdf',
                'max:5120',
            ],
        ], [
            'document.max' => 'Tamanho máximo permitido de 5MB!',
            'document.required' => 'O campo Documento é obrigatório!',
            'document.mimes' => 'Formato de arquivo inválido. Selecione um arquivo PDF!',
            'document.file' => 'Falha de arquivo!',
        ]);

        try {
            $userAuth = Auth::user();
            $document = $userAuth->documents->where('type', TypeDocumentEnum::TDR->value)->where('agree', false)->count();

            if ($document) {
                toast('Documentação Pendente!', 'info');
                return back();
            }

            if ($request->hasFile('document') && $request->file('document')->isValid()) {
                $path = $request->file('document')->store('user/documents');
                UserAccessDocument::create([
                    'user_id' => $userAuth->id,
                    'name' => $request->file('document')->getClientOriginalName(),
                    'type' => TypeDocumentEnum::TDR->value,
                    'path' => $path,
                ]);

                toast('Arquivo enviado com sucesso! Aguarde a validação para liberação do acesso.', 'success');
                NotificationService::send(
                    TypeEmailNotificationsEnum::DOCUMENT_RELEASE->value,
                    new UserAccessDocumentNotification($userAuth)
                );

                return back();
            }
        } catch (\Swift_TransportException $exception) {
            Log::error($exception->getMessage());
            toast('Arquivo enviado com sucesso! Aguarde a validação para liberação do acesso.', 'success');
            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro ao enviar o documento!', 'error');
            return back()->withInput();
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function documentValidate(Request $request): RedirectResponse
    {
        try {
            $userDocument = UserAccessDocument::findOrFail($request->id);
            $user = Auth::user();
            $userDocument->analyst_id = $user->id;
            $userDocument->unity_id = $user->unity_id;
            $userDocument->sector_id = $user->sector_id;
            $userDocument->agree = $request->input('agree', false);
            $userDocument->observation = $request->observation;
            $userDocument->update();
            toast('Atualização realizada!', 'success');
            return back();
        } catch (ModelNotFoundException|\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Não foi possível localizar a documentação', 'error');
            return back()->withInput();
        }
    }
}
