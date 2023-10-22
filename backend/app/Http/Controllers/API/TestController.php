<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResultResource;
use App\Http\Resources\TestResource;
use App\Models\Result;
use App\Models\Option;
use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::with('subject')
            ->whereDoesntHave('answers', function ($query) {
                $query->where('user_id', auth('api')->id());
            })->withCount('questions')->get();

        return TestResource::collection($tests);
    }

    public function start(Test $test)
    {
        $result = Result::firstOrCreate(
            ['user_id' => auth('api')->id(), 'test_id' => $test->id],
            ['ends_at' => now()->addMinutes($test->duration), 'values' => []]
        );

        abort_if($result->ends_at->isPast(), 416, 'You have already written this examination');

        $test->loadCount('questions');

        return response()->json([
            'test' => new TestResource($test),
            'ends_at' => $result->ends_at
        ]);
    }

    public function submit(Request $request, Test $test)
    {
        $answers = $request->collect('answers');
        $option_ids = $answers->pluck('option_id');
        $options = Option::whereIn('id', $option_ids)->get();

        $mark = 0;
        foreach ($options as $option) {
            if ($option?->is_correct) $mark++;
        }

        $score = ($mark / $answers->count()) * $test->base_score;

        Result::query()
            ->where('user_id', auth('api')->id())
            ->where('test_id', $test->id)
            ->update(['values' => $answers, 'score' => $score]);

        return response()->json([
            'message' => 'Congrats! Test submitted successfully.'
        ]);
    }

    public function results(Test $test)
    {
        $result = Result::query()
            ->where('user_id', auth('api')->id())
            ->where('test_id', $test->id)
            ->with('test.questions.options')
            ->first();

        abort_if(!$result, 404, 'You have not written this examination');

        return new ResultResource($result);
    }
}
