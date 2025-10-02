<?php

namespace App\Http\Controllers;

use App\Models\LetterControl;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LetterControlController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $listLetterControl = $this->getListLetterControl();
        return view('letter_control.index', compact('listLetterControl'));
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate(['recipient' => 'required|string|max:100', 'subject' => 'required|string|max:250',], [
            'recipient.required' => 'Campo Destinatário é obrigatório',
            'subject.required' => 'Campo Assunto é obrigatório',
        ]);

        try {
            $user = Auth::user();
            DB::beginTransaction();
            $lastLetterControl = LetterControl::where('year', date('Y'))
                ->lockForUpdate()
                ->orderByDesc('number')
                ->first();

            $year = date('Y');

            $letterControl = new LetterControl();
            $letterControl->fill([
                'user_id' => $user->id,
                'unity_id' => $user->unity_id,
                'sector_id' => $user->sector_id,
                'recipient' => Str::upper($request->recipient),
                'subject' => Str::upper($request->subject),
                'year' => $year,
                'number' => $lastLetterControl ? $lastLetterControl->number + 1 : 1,
            ]);
            $letterControl->save();
            DB::commit();

            toast("Nº {$letterControl->number} gerado!", 'success');
            return redirect()->route('letter.control.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            toast('Erro de sistema!', 'error');
            return back()->withInput();
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($id): View|Factory|RedirectResponse|Application
    {
        try {
            $letterControl = LetterControl::findOrFail($id);
            $listLetterControl = $this->getListLetterControl();

            return view('letter_control.index', compact('letterControl', 'listLetterControl'));
        } catch (ModelNotFoundException $exception) {
            toast('Registro não encontrado.', 'error');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema!', 'error');
        }

        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'recipient' => 'required|string|max:100',
            'subject' => 'required|string|max:250',
        ], [
            'recipient.required' => 'Campo Destinatário é obrigatório',
            'subject.required' => 'Campo Assunto é obrigatório',
        ]);

        try {
            $letterControl = LetterControl::findOrFail($request->id);
            $letterControl->fill([
                'recipient' => Str::upper($request->recipient),
                'subject' => Str::upper($request->subject),
            ]);
            $letterControl->save();

            toast('Dados atualizados com sucesso.', 'success');
            return redirect()->route('letter.control.index');
        } catch (ModelNotFoundException $exception) {
            toast('Registro não encontrado.', 'error');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro ao atualizar os dados.', 'error');
        }

        return back()->withInput();
    }

    /**
     * @return mixed
     */
    private function getListLetterControl(): mixed
    {
        $user = Auth::user();
        return LetterControl::where('unity_id', $user->unity_id)->orderBy('id', 'desc')->get();
    }
}
