<?php

namespace App\Http\Controllers\Api\Auth;

use App\Common\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Services\User\AuthService;
use Illuminate\Http\Request;


/**
 * @OA\Tag(name="Authentication", description="API Endpoints for Authentication")
 */
class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="accessToken", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->only('name', 'email', 'password'));

        if ($user) {
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            return ResponseHelper::success('Successfully registered', ['accessToken' => $token], 201);
        }

        return ResponseHelper::error('Unable to register user', 400);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Login an existing user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="accessToken", type="string"),
     *             @OA\Property(property="token_type", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $token = $this->authService->login($request->only('email', 'password'));

        if ($token) {
            return ResponseHelper::success('Login successful', ['accessToken' => $token, 'token_type' => 'Bearer'], 201);
        }

        return ResponseHelper::error('Unauthorized', 401);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Logout the current user",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return ResponseHelper::success('Successfully logged out', [], 200);
    }

    public function user(Request $request)
    {
        return ResponseHelper::success('User details retrieved successfully', $request->user(), 200);
    }
}
