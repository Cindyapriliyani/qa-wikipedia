<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WikipediaQAController extends Controller
{
    public function index()
    {
        return view('qa');
    }

    public function qa(Request $request)
    {
        $client = new Client();
        $response = $client->post('http://localhost:5001/qa', [
            'json' => [
                'query' => $request->query
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return view('qa', ['result' => $result['result']]);

    }
}
