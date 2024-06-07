<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use routes\QuestionAnsweringApi;

class QuestionAnsweringController extends Controller
{
    public function getAnswer(Request $request)
    {
        //validasi input
        $request->validate([
            'question' => 'required|string',
            'keyword' => 'required|string',
        ]);

        // mengirim permintaan ke API FastAPI
        $response = Http::post('http://127.0.0.1:8000/predict/', [
            'question' => $request->input('question'),
            'keyword' => $request->input('keyword'),
        ]);

        // mengambil data jawaban dari API
        $data = $response->json();

        //mengembalikan view dengan jawaban
        return view('home', ['answer' => $data['answer'], 'context' => $data['context']]);
    }
}