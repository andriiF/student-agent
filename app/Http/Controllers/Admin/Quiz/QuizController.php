<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Quiz\QuizRequest;
use App\Http\Resources\Admin\TopicResource;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\Topic;
use App\Services\Quiz\QuizService;
use App\Services\Quiz\TopicService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class QuizController extends Controller
{
    public function __construct(protected QuizService $quizService, protected TopicService $topicService)
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

    public function store(QuizRequest $request): RedirectResponse
    {
        $this->quizService->create($request->validated());

        return back()->with('success', 'Quiz created successfully.');
    }

    public function edit(Quiz $quiz): Response
    {
        $topics = $this->topicService->get();

        return Inertia::render('quizzes/Edit', [
            'quiz' => $quiz->load('topics:uuid,name', 'questions.answers'),
            'topics' => TopicResource::collection($topics)->collection,
        ]);
    }

    public function update(QuizRequest $request, Quiz $quiz): RedirectResponse
    {
        $this->quizService->update($quiz, $request->validated());

        return redirect()->back()
            ->with('success', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $this->quizService->delete($quiz);

        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz deleted successfully.');
    }
}
