<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Quiz\QuizRequest;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\Topic;
use App\Services\Quiz\QuizService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class QuizController extends Controller
{
    public function __construct(protected QuizService $quizService)
    {
    }

    public function index(Request $request): Response
    {
        $quizzes = $this->quizService->getPaginated($request->input('search'));

        return Inertia::render('quizzes/Index', [
            'quizzes' => $quizzes,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('quizzes/Create', [
            'topics' => Topic::query()->orderBy('name')->get(['uuid', 'name']),
        ]);
    }

    public function store(QuizRequest $request): RedirectResponse
    {
        $this->quizService->create($request->validated());

        return back()->with('success', 'Quiz created successfully.');
    }

    public function edit(Quiz $quiz): Response
    {
        return Inertia::render('quizzes/Edit', [
            'quiz' => $quiz->load('topics:uuid,name', 'questions.answers'),
            'topics' => Topic::query()->orderBy('name')->get(['uuid', 'name']),
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
