<?php

namespace App\Http\Controllers\API;

use App\Filters\Quiz\ByAccess;
use App\Filters\Quiz\ByProgram;
use App\Filters\Quiz\ByProgramStatus;
use App\Filters\Quiz\ByWritten;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\QuizStoreRequest;
use App\Http\Resources\QuizResource;
use App\Models\Program;
use App\Models\Quiz;
use App\Models\Response;
use App\Models\Result;
use App\Models\Option;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Retrieve a paginated list of all available quizzes
     */
    public function index()
    {
        $quizzes = Quiz::query()
            ->filter([
                ByWritten::class,
                ByAccess::class,
                ByProgram::class,
                ByProgramStatus::class
            ])
            ->withCount('questions')->get();

        return QuizResource::collection($quizzes)->additional([
            'message' => 'Quizzes retrieved successfully'
        ]);
    }

    /**
     * Create a new quiz
     */
    public function store(QuizStoreRequest $request)
    {
        $this->authorize('create', [
            Quiz::class,
            $request->input('program_id')
        ]);

        $quiz = Quiz::create($request->validated());

        return response()->json(['message' => 'Quiz created successfully'], 201);
    }

    /**
     * Update a quiz details
     */
    public function update(QuizStoreRequest $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        $quiz->update($request->validated());

        return response()->json(['message' => 'Quiz updated successfully']);
    }

    /**
     * Delete a quiz
     */
    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);

        $quiz->delete();

        return response()->json(['message' => 'Quiz deleted successfully']);
    }

    /**
     * Start a quiz
     */
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

//        if (!$result->wasRecentlyCreated) {
//            $result->increment('attempts');
//
//            abort_if(
//                $result->attempts >= $quiz->max_attempts,
//                416,
//                "You have already attempted more than allowed"
//            );
//        }

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
            'message' => 'Congrats! Your responses have been submitted successfully.'
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
