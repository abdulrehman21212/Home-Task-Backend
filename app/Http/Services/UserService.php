<?php
namespace App\Http\Services;

use App\Models\Article;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserService{


    public function updateUserSettings($request){
        $preferenceData = json_encode([
            'sources' => $request->input('sources', []),
            'categories' => $request->input('categories', []),
            'authors' => $request->input('authors', []),
        ]);
        $userSettings = UserSetting::updateOrCreate(
            ['user_id' => Auth::user()->id],
            ['preference' => $preferenceData]
        );

        $data = [];
        $data['id'] = $userSettings->user->id;
        $data['name'] = $userSettings->user->name;
        $data['email'] = $userSettings->user->email;
        $data['preference'] = $userSettings->preference;
        return $data;
    }
    public function getUserSettings($userId){
        return UserSetting::where('user_id',$userId)->first();
    }

    public function getPrefferedArticles($request)
    {
        $limit = 10;
        $page = $request->input('page', 1);
        $userSettings = $this->getUserSettings(Auth::user()->id);
        if ($userSettings) {
            $preferences = $userSettings->preference;
            $query = Article::query();
            if (isset($preferences['sources'])) {
                foreach ($preferences['sources'] as $source) {
                    $query->orWhere('source', 'like', '%' . $source . '%');
                }
            }
            if (isset($preferences['categories'])) {
                foreach ($preferences['categories'] as $category) {
                    $query->orWhere('section', 'like', '%' . $category . '%');
                }
            }
            if (isset($preferences['authors'])) {
                foreach ($preferences['authors'] as $author) {
                    $query->orWhere('author', 'like', '%' . $author . '%');
                }
            }
            $totalResults = $query->count();
            // $offset = max(0, ($page - 1) * $limit);

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

        return false;
    }


}
