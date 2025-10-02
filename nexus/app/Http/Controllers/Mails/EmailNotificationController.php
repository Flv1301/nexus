<?php

namespace App\Http\Controllers\Mails;

use App\Enums\TypeEmailNotificationsEnum;
use App\Http\Controllers\Controller;
use App\Models\Emails\EmailNotification;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailNotificationController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $usersNotifications = EmailNotification::with('user')->get();
        return view('notification.email_notification.index', compact('usersNotifications'));
    }

    /**
     * @return Factory|View|Application
     */
    public function create(): Factory|View|Application
    {
        $users = User::with(['unity', 'sector'])->orderBy('name')->get();
        $types = TypeEmailNotificationsEnum::cases();
        return view('notification.email_notification.create', compact('users', 'types'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'type' => 'required|string',
            'users' => 'required|array',
        ], [
            'type.required' => 'O campo Tipo é obrigatório.',
            'users.required' => 'Pelo menos um usuário deve ser selecionado.',
        ]);

        $type = $validatedData['type'];
        $userIds = $validatedData['users'];

        foreach ($userIds as $userId) {
            EmailNotification::updateOrCreate(
                ['user_id' => $userId, 'type' => $type],
                ['status' => $request->boolean('status', false)]
            );
        }

        toast('Gravado com sucesso!', 'success');
        return redirect()->back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $emailNotification = EmailNotification::findOrFail($id);
            $emailNotification->delete();

            toast('Deletado com sucesso!.', 'success');
            return back();
        } catch (ModelNotFoundException $exception) {
            Log::error($exception->getMessage());
            toast('Não foi possível deletar o notificado!', 'error');
            return back();
        }
    }
}
