<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\LogHelper;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthApiResource;
use App\Services\Api\AccessDataService;
use App\Services\Api\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController
{
    public function __construct(protected AccessDataService $accessService, protected UserService $userService)
    {
    }
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Autenticação de usuário",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="remember", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário autenticado com sucesso.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", ref="#/components/schemas/AuthApiResource"),
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas ou usuário inativo.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Credenciais inválidas!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Usuário não encontrado!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Erro interno do sistema.")
     *         )
     *     )
     * )
     */
    public function login(AuthRequest $request): JsonResponse
    {
        try {
            $service = app(UserService::class);
            $user = $service->findByEmail($request->email);

            if (!$user) {
                LogHelper::logActivity('login', 'Login', "Usuário não encontrado:  {$request->email}");

                return response()->json(['error' => 'Usuário não encontrado!'], ResponseAlias::HTTP_NOT_FOUND);
            }

            if (!$user->status) {
                LogHelper::logActivity('login', 'Login', "Usuário inativo:  {$request->email}");

                return response()->json(['error' => 'Usuário inativo'], ResponseAlias::HTTP_UNAUTHORIZED);
            }

            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
                'status' => 1
            ];

            if (!Auth::attempt($credentials, $request->remember ?? false)) {
                LogHelper::logActivity('login', 'Login', "Credenciais do Usuário {$request->email} Inválidas");

                return response()->json(['error' => 'Credenciais inválidas!'], ResponseAlias::HTTP_UNAUTHORIZED);
            }

            $user->tokens()->delete();
            $token = $user->createToken($user->registration)->plainTextToken;

            $this->accessService->identifyAccessUser($request, $user);
            LogHelper::logActivity('login', 'Login', 'Login de Usuário: ' . $request->email);

            return response()->json([
                'user' => AuthApiResource::make($user),
                'token' => $token
            ]);

        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json(['error' => 'Erro interno do sistema.', ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Logout do usuário autenticado",
     *     tags={"Autenticação"},
     *     @OA\Response(
     *         response=204,
     *         description="Logout realizado com sucesso. Nenhum conteúdo retornado."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autenticado.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Não autenticado.")
     *         )
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::user()->tokens()->delete();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json('', ResponseAlias::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Post(
     *     path="/refresh-token",
     *     summary="Renovar o token de acesso",
     *     tags={"Autenticação"},
     *     @OA\Response(
     *         response=200,
     *         description="Token renovado com sucesso.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autenticado.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Não autenticado.")
     *         )
     *     )
     * )
     */
    public function refreshToken(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        $newToken = $user->createToken($user->registration)->plainTextToken;

        return response()->json(['token' => $newToken]);
    }
}
