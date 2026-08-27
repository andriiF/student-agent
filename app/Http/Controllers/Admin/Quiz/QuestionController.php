<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Quiz\QuestionRequest;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Services\Quiz\QuestionService;
use App\Services\Quiz\QuizService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class QuestionController extends Controller
{
    public function __construct(
        protected QuestionService $questionService,
        protected QuizService     $quizService
    )
    {
    }

    public function index(Request $request): Response
    {
        abort(404);
    }

    public function create(): Response
    {
        abort(404);
    }

    public function store(QuestionRequest $request): RedirectResponse
    {
        $this->questionService->create($request->validated());

        return back()->with('success', 'Question created successfully.');
    }

    public function edit(Question $question): Response
    {
        return Inertia::render('questions/Edit', [
            'question' => $question->load('answers', 'quiz.topics'),
            'quizzes' => $this->quizService->getAllOrdered(['uuid', 'name']),
        ]);
    }

    public function update(QuestionRequest $request, Question $question): RedirectResponse
    {
        $this->questionService->update($question, $request->validated());

        return redirect()->back()
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $this->authorize('delete', $question);

        $this->questionService->delete($question);

        return redirect()->route('questions.index')
            ->with('success', 'Question deleted successfully.');
    }
}
