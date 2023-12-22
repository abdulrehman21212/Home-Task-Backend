<?php

namespace App\Http\Controllers;

use App\Http\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleService;
   /**
     * Constructor to initialize the ArticleController.
     *
     * @param ArticleService $articleService The service responsible for managing articles.
     */
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Retrieves articles based on the provided request parameters.
     *
     * @param Request $request The incoming request.
     * @return \Illuminate\Http\JsonResponse JSON response containing the articles.
     */
    public function getArticles(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->articleService->getArticles($request),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

   /**
     * Filters articles based on the provided request parameters.
     *
     * @param Request $request The incoming request containing filter parameters.
     * @return \Illuminate\Http\JsonResponse JSON response containing the filtered articles.
     */
    public function filterArticles(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->articleService->filterArticles($request),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Retrieves authors of articles.
     *
     * @return \Illuminate\Http\JsonResponse JSON response containing the authors' information.
     */
    public function getAuthors()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->articleService->getAuthors()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Retrieves categories of articles.
     *
     * @return \Illuminate\Http\JsonResponse JSON response containing the article categories.
     */
    public function getCategories()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->articleService->getCategories()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Retrieves sources of articles.
     *
     * @return \Illuminate\Http\JsonResponse JSON response containing the article sources.
     */
    public function getSources()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->articleService->getSources()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
