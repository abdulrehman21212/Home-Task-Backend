<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\AuthService;

class AuthenticationController extends Controller
{
    protected $authService;
    /**
     * Constructor to initialize the AuthenticationController.
     *
     * @param AuthService $authService The service responsible for authentication.
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
  /**
     * Handles the registration process for a new user.
     *
     * @param RegisterRequest $request The incoming registration request containing:
     *   - 'name' (required): The user's name.
     *   - 'email' (required, unique): The user's email address.
     *   - 'password' (required, minimum length 8): The user's password.
     *
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure of registration.
     */
    public function register(RegisterRequest $request)
    {
        try {
            // Attempt user registration through the AuthService.
            $user = $this->authService->register($request);
            $token = $user->createToken('token-name')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User has been registered successfully',
                'data' => $user,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handles the login process for a user.
     *
     * @param LoginRequest $request The incoming login request containing:
     *   - 'email' (required, exists in users table): The user's email address.
     *   - 'password' (required): The user's password.
     *
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure of login.
     */
    public function login(LoginRequest $request)
    {
        try {
            if($this->authService->login($request)){
                return response()->json([
                    'success' => true,
                    'message' => 'User logged in successfully',
                    'data' => $this->authService->login($request),
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'error' => 'invalid credentials',
                    'data' => $this->authService->login($request),
                ],422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed. Something went wrong.',
                'error' => $e->getMessage(),
            ], 401);
        }
    }
}
