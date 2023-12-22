<?php

namespace App\Http\Services;

use App\Models\Article;

class ArticleService
{

    public function filterArticles($request)
    {
        $keyword = $request->input('keyword');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $category = $request->input('category');
        $source = $request->input('source');
        $limit = 10;
        $page = $request->input('page', 1);
        $query = Article::query();
        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%$keyword%")
                    ->orWhere('description', 'LIKE', "%$keyword%");
            });
        }
        if ($startDate && $endDate) {
            $query->whereBetween('published_at', [$startDate, $endDate]);
        }
        if ($category) {
            $query->where('section', 'LIKE', "%$category%");
        }
        if ($source) {
            $query->where('source', 'LIKE', "%$source%");
        }
        $totalResults = $query->count();
        $offset = max(0, ($page - 1) * $limit);
        $results = $query->offset($offset)
            ->limit($limit)
            ->get();
        return [
            'data' => $results,
            'total' => $totalResults,
            'limit' => $limit,
            'offset' => $offset,
            'page' => $page,
        ];
    }

    public function getAuthors()
    {
        $data = Article::distinct()
            ->where('author', '!=', '')
            ->distinct()
            ->pluck('author');
        return $data;
    }
    public function getCategories()
    {
        $data = Article::whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->pluck('section');
        return $data;
    }
    public function getSources()
    {
        $data = Article::whereNotNull('source')
            ->where('source', '!=', '')
            ->distinct()
            ->pluck('source');
        return $data;
    }

    public function getArticles($request)
    {
        $limit = 10;
        $page = $request->input('page', 1);
        $offset = $page * $limit;
        $query =  Article::query();
        $totalResults = $query->count();
        $offset = $page * $limit;
        $results = $query->offset($offset)
            ->limit($limit)
            ->get();
        return [
            'data' => $results,
            'total' => $totalResults,
            'limit' => $limit,
            'offset' => $offset,
            'page' => $page,
        ];
    }
}
