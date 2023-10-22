<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Test;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $test = Test::query()
            ->with(['questions' => fn($query) => $query->inRandomOrder()->with('options')])
            ->find($request->input('test_id'));

        return QuestionResource::collection($test->questions);
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