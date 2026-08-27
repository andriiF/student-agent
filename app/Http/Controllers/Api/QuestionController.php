<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Quiz\QuestionRequest;
use App\Http\Resources\Api\QuestionResource;
use App\Models\Quiz\Question;
use App\Services\Quiz\AnswerService;
use App\Services\Quiz\QuestionService;
use App\Services\Quiz\QuizService;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct(protected QuizService     $quizService,
                                protected QuestionService $questionService,
                                protected AnswerService   $answerService
    )
    {
    }

    public function index(Request $request)
    {
        abort(404);
    }

    public function show(Question $question)
    {
        return new QuestionResource($question->load(['answers']));
    }

    public function store(QuestionRequest $request)
    {
        $data = $request->validated();
        $frontUser = $request->attributes->get('frontend_user');

        $question = $this->questionService->create([
            'name' => $data['name'],
            'quiz_id' => $data['quiz'],
            'front_user_id' => $frontUser->uuid,
        ]);


        if (!empty($data['answers'])) {
            foreach ($data['answers'] as $answer) {
                $this->answerService->create([
                    'question_id' => $question->uuid,
                    'name' => $answer['name'],
                    'is_correct' => $answer['is_correct'],
                    'is_active' => $answer['is_active'],
                    'explanation' => $answer['explanation'],
                ]);
            }
        }

        return response()->json(['message' => 'Question created successfully']);
    }

    public function update(QuestionRequest $request, Question $question)
    {
        $data = $request->validated();

        $this->questionService->update($question, $data);
        $this->questionService->updateOrDeleteAnswers($question, $data['answers'] ?? []);

        return response()->json(['message' => 'Question created successfully']);
    }

    public function destroy(Request $request, Question $question)
    {
        $frontUser = $request->attributes->get('frontend_user');

        $this->authorizeForUser($frontUser, 'delete', $question);

        $this->questionService->delete($question);

        return response()->json(['message' => 'Question deleted successfully']);
    }
}
