<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 06/04/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers;

use App\Models\Data\Image;
use App\Models\Support;
use App\Models\SupportResponse;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SupportController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $supports = Support::orderBy('created_at', 'asc')->get();
        return view('support.index', compact('supports'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        $support = new Support();
        return view('support.create', compact('support'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['title' => 'required|min:5|max:50|string', 'description' => 'required|min:10|string']);
        try {
            $data = $request->only('title', 'description');
            $data['user_id'] = Auth::id();
            $support = Support::create($data);
            $this->uploadImage($request, $support);
            toast('Solicitação enviada com sucesso.', 'success');
            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível gravar a solicitação', 'error');
            return back();
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id): View|Factory|Application
    {
        $support = Support::find($id);
        return view('support.show', compact('support'));
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($id): View|Factory|RedirectResponse|Application
    {
        try {
            $support = Support::findOrFail($id);
            return view('support.edit', compact('support'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível localizar a solicitação,', 'error');
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
        $request->validate(['title' => 'required|min:5|max:50|string', 'description' => 'required|min:10|string']);
        try {
            $data = $request->only('title', 'description');
            $support = Support::findOrFail($id);
            $support->fill($data);
            $support->update();
            $this->uploadImage($request, $support);
            toast('Solicitação atualizada com sucesso.', 'success');
            return back();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível atualizar a solicitação', 'error');
            return back();
        }
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $support = Support::find($id);
        $support->delete();
        toast('Solicitação deletada com sucesso', 'success');
        return back();
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function torespond($id): View|Factory|RedirectResponse|Application
    {
        try {
            $support = Support::findOrFail($id);
            $support_response = new SupportResponse();
            $users = User::permission('suporte resposta')->get();
            return view('support.torespond', compact('support', 'support_response', 'users'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema. Não foi possível localizar a solicitação!', 'error');
            return back();
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function response(Request $request, $id): RedirectResponse
    {
        $request->validate(['response' => 'required|min:10', 'status' => 'required']);
        try {
            $request->request->add(['support_id' => $id, 'user_id' => Auth::id()]);
            $response = SupportResponse::create($request->except('redirect_user_id', 'status'));
            $support = Support::find($id);
            $support->status = $request->status;
            $support->update();
            $this->uploadImage($request, $response);
            toast('Resposta enviada com sucesso!', 'success');
            return redirect()->route('supports');
        } catch (\Exception $exception) {
            toast('Erro de sistema. Não foi possível realizar a resposta', 'error');
            return back();
        }
    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function responseShow($id): Factory|View|Application
    {
        $response = SupportResponse::find($id);
        return view('support.response', compact('response'));
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function history($id): View|Factory|Application
    {
        $support = Support::find($id);
        $responses = $support->responses;
        return view('support.history', compact('support','responses'));
    }

    /**
     * @param Request $request
     * @param Support|SupportResponse $model
     * @return void
     */
    private function uploadImage(Request $request, Support|SupportResponse $model): void
    {
        if ($request->hasFile('files')) {
            foreach ($request->file() as $files) {
                foreach ($files as $file) {
                    $filename = date('YmdHi') . '-' . $file->getClientOriginalName();
                    $path = $file->storeAs('images', $filename);
                    $image = new Image(['description' => $filename, 'path' => $path]);
                    $image->save();
                    $model->images()->attach($image->id);
                }
            }
        }
    }
}
