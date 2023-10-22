<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\Models\Response;
use App\Models\Result;
use App\Models\Option;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::query()
            ->whereDoesntHave('results', function ($query) {
                $query->where('user_id', auth('api')->id());
            })->withCount('questions')->get();

        return QuizResource::collection($quizzes);
    }

    public function start(Quiz $quiz)
    {
        $result = Result::firstOrCreate(
            ['user_id' => auth('api')->id(), 'quiz_id' => $quiz->id],
            ['finished_at' => now()->addMinutes($quiz->duration)]
        );

        abort_if(
            $result->finished_at->isPast(),
            416,
            'You have already written this quiz'
        );

        $quiz->loadCount('questions');

        return response()->json([
            'quiz' => new QuizResource($quiz),
            'ends_at' => $result->finished_at
        ]);
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $responses = $request->collect('responses');
        $quiz->loadCount('questions');

        $option_ids = $responses->pluck('option_id');
        $correct = Option::whereIn('id', $option_ids)->sum('is_correct');

        $score = ($correct / $quiz->questions_count) * $quiz->base_score;

        Result::query()
            ->where('user_id', auth('api')->id())
            ->where('quiz_id', $quiz->id)
            ->update(['score' => $score]);

        $responses->transform(function ($response) use ($quiz) {
            $response['quiz_id'] = $quiz->id;
            $response['user_id'] = auth('api')->id();
            return $response;
        });

        Response::insert($responses->all());

        return response()->json([
            'message' => 'Congrats! Quiz submitted successfully.'
        ]);
    }

    public function results(Quiz $quiz)
    {
        $quiz->load([
            'questions.options',
            'result' => fn($query) => $query->where('user_id', auth('api')->id()),
            'responses' => fn($query) => $query->where('user_id', auth('api')->id())
        ]);

        abort_if(!$quiz->result, 404, 'You have not written this examination');

        return new QuizResource($quiz);
    }
}
