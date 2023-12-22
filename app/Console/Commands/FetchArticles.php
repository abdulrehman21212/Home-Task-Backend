<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class FetchArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to fetch the data from Newsapi and Newyork Times api';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // News api
        $apiKeyNews = env('NEWS_API_SECRET_KEY');
        $clientNews = new Client();
        $responseNews = $clientNews->get('https://newsapi.org/v2/top-headlines', [
            'query' => [
                'apiKey' => $apiKeyNews,
                'country' => 'us',
            ]
        ]);
        $dataNews = json_decode($responseNews->getBody(), true);
        foreach ($dataNews['articles'] as $article) {
            Article::updateOrCreate(
                ['title' => $article['title']], // Check if the title exists
                [
                    'author' => $article['author'],
                    'description' => $article['description'],
                    'url' => $article['url'],
                    'image_url' => $article['urlToImage'],
                    'published_at' => date('Y-m-d H:i:s', strtotime($article['publishedAt'])),
                    'source' => 'newsapi.org',
                    'section' => 'Top Headlines',
                    'sub_section' => null,
                ]
            );
        }

        // New york Times api
        $section = 'arts';
        $apiKeyNYT = env('NEW_YORK_TIMES_SECRET_KEY');
        $clientNYT = new Client([
            'base_uri' => 'https://api.nytimes.com/svc/topstories/v2/',
        ]);
        $responseNYT = $clientNYT->request('GET', $section . '.json', [
            'query' => [
                'api-key' => $apiKeyNYT,
            ],
        ]);
        $dataNYT = json_decode($responseNYT->getBody(), true);
        foreach ($dataNYT['results'] as $articleNYT) {
            Article::updateOrCreate(
                ['title' => $articleNYT['title']],
                [
                    'author' => $articleNYT['byline'],
                    'description' => $articleNYT['abstract'],
                    'url' => $articleNYT['url'],
                    'image_url' => $articleNYT['multimedia'][0]['url'] ?? null,
                    'published_at' => date('Y-m-d H:i:s', strtotime($articleNYT['published_date'])),
                    'source' => 'New York Times',
                    'section' => $section,
                    'sub_section' => null,
                ]
            );
        }
        $this->info('Articles fetched and stored successfully.');
    }
}
