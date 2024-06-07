<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function getAnswer(Request $request)
    {
        $question = $request->input('question');
        $context = $request->input('context');

        $answer = $this->findAnswerInContext($question, $context);

        return response()->json(['answer' => $answer]);
    }

    private function findAnswerInContext($question, $context)
    {
        $sentences = explode('.', $context);
        foreach ($sentences as $sentence) {
            if (stripos($sentence, $question) !== false) {
                return trim($sentence);
            }
        }
        return ;
    }
}
