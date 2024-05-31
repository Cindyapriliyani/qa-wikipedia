<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use routes\QuestionAnsweringApi;

class QuestionAnsweringController extends Controller
{
    public function answer(Request $request)
    {
        $question = $request->input('question');
        $context = $request->input('context');

        $client = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:5000/api/answer', [
            'json' => [
                'question' => $question,
                'context' => $context,
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        return response()->json(['answer' => $data['answer']]);
    }
}
