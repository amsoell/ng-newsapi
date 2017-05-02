<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Source;
use GuzzleHttp\Client;

class SourceController extends Controller
{
    public function index(Request $request) {
        $client = new Client();
        $req = $client->request('GET','https://newsapi.org/v1/sources', [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        $stream   = $req->getBody();
        $contents = json_decode($stream->getContents());
        $sources = collect($contents->sources);

        $sources->each(function ($source) {
            $ng_source = Source::updateOrCreate(['id' => $source->id],
            [
                'category'       => $source->category,
                'description'    => $source->description,
                'url'            => $source->url,
                'language'       => $source->language,
                'country'        => $source->country,
                'NG_Description' => 'xxx',
                'NG_Review'      => 'yyy',
            ]);
        });

        return Source::all();

    }

    public function show(Request $request, Source $source) {
        $client = new Client();
        $req = $client->request('GET','https://newsapi.org/v1/articles', [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
            'query' => [
                'source'       => $source->id,
                'apiKey'       => env('NEWSAPI_API_KEY'),
            ],
        ]);

        $stream   = $req->getBody();
        $contents = json_decode($stream->getContents());
        $articles = collect($contents->articles);

        $articles->each(function ($article) use ($source) {
            $ng_article = Article::updateOrCreate(['url' => $article->url],
            [
                'source_id'      => $source->id,
                'author'         => $article->author,
                'title'          => $article->title,
                'description'    => $article->description,
                'url'            => $article->url,
                'urlToImage'     => $article->urlToImage,
                'publishedAt'    => $article->publishedAt,
                'NG_Description' => 'www',
                'NG_Review'      => 'zzz',
            ]);
        });


        return Article::where('source_id', $source->id)->get();
    }
}
