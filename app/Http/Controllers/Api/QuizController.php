<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Quiz\QuizRequest;
use App\Http\Requests\Api\Quiz\TopicRequest;
use App\Http\Resources\Api\QuizEditResource;
use App\Http\Resources\Api\QuizResource;
use App\Http\Resources\Api\TopicResource;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\Topic;
use App\Services\Quiz\QuizService;
use App\Services\Quiz\TopicService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(protected QuizService $quizService, protected TopicService $topicService)
    {
    }

    public function index(Request $request)
    {
        abort(404);
    }

    public function show(Quiz $quiz)
    {
        return QuizEditResource::make($quiz->load(['questions', 'topics']));
    }

    public function store(QuizRequest $request)
    {
        $data = $request->validated();

        $this->quizService->create($data);

        return response()->json(['message' => 'Topic created successfully']);
    }

    public function update(TopicRequest $request, Quiz $quiz)
    {
        $data = $request->validated();

        $this->quizService->update($quiz, $data);

        return response()->json(['message' => 'Topic updated successfully']);
    }
}
