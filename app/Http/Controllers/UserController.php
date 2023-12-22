<?php

namespace App\Http\Controllers;

use App\Http\Services\ArticleService;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    protected $userService, $articleService;

    /**
     * Constructor to initialize the UserController.
     *
     * @param UserService   $userService    The service responsible for managing user-related operations.
     * @param ArticleService $articleService The service responsible for managing article-related operations.
     */
    public function __construct(UserService $userService, ArticleService $articleService)
    {
        $this->userService = $userService;
        $this->articleService = $articleService;
    }


        /**
     * Updates user preferences/settings based on the provided request.
     *
     * @param Request $request The incoming request containing user settings/preferences.
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure of user settings update.
     */
    public function updateUserSettings(Request $request)
    {
        try {
            if ($this->userService->updateUserSettings($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'user preferences updated',
                    'data'=>$this->userService->updateUserSettings($request)
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

       /**
     * Retrieves articles preferred by the user based on the request or returns all articles if no preferences set.
     *
     * @param Request $request The incoming request for user's preferred articles.
     * @return \Illuminate\Http\JsonResponse JSON response containing the user's preferred articles or all articles.
     */
    public function getUserArticles(Request $request)
    {
        try {
            $articles = $this->userService->getPrefferedArticles($request);
            if ($articles) {
                return response()->json([
                    'success' => true,
                    'data'=> $articles
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'data'=> $this->articleService->getArticles($request)
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
