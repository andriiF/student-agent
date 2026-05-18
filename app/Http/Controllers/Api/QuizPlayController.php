<?php

namespace App\Http\Controllers\Api;

use App\DTO\AnswerStoreDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Quiz\QuizPlayRequest;
use App\Http\Resources\Api\QuizPlayAnswersResource;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\Topic;
use App\Services\Quiz\QuizService;
use Illuminate\Http\Request;

class QuizPlayController extends Controller
{
    public function __construct(protected QuizService $quizService)
    {
    }

    public function show(Request $request, Quiz $quiz)
    {
        $frontUser = $request->attributes->get('frontend_user');

        return new QuizPlayAnswersResource($this->quizService->getQuizPlayByUser($frontUser, $quiz));
    }

    public function saveAnswer(QuizPlayRequest $request, Quiz $quiz)
    {
        $frontUser = $request->attributes->get('frontend_user');

        $this->quizService->saveAnswer($frontUser, $quiz, $request->toDTO());

        return response()->json(['message' => 'Answer saved successfully']);
    }

    public function destroy(Request $request, Quiz $quiz)
    {
        $frontUser = $request->attributes->get('frontend_user');

        $this->quizService->destroyQuizPlayByUser($frontUser, $quiz);

        return response()->json(['message' => 'Topic deleted successfully']);
    }
}
