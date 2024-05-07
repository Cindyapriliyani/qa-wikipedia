<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    public function home()
    {
        return view('home.index');
    }
}


class ApiController extends Controller
{
    public function fetchDataFromFlaskApi()
    {
        $client = new Client();
        $response = $client->request('GET', 'http://localhost:5000/api/wikipedia/PYTHON_ARTICLE_TITLE');
        $data = json_decode($response->getBody()->getContents(), true);

        return view('python-article.article', ['articleText' => $data['text']]);
    }
}