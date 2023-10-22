<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Test;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $quiz = Quiz::query()
            ->with(['questions' => fn($query) => $query->inRandomOrder()->with('options')])
            ->find($request->input('quiz_id'));

        return QuestionResource::collection($quiz->questions);
    }

//    public function store(Request $request)
//    {
//    }
//
//    public function show(Question $question)
//    {
//    }
//
//    public function update(Request $request, Question $question)
//    {
//    }
//
//    public function destroy(Question $question)
//    {
//    }
}
