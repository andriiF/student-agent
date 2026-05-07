<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Services\Quiz\QuestionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class QuestionController extends Controller
{
    public function __construct(protected QuestionService $questionService)
    {
    }

    public function index(Request $request): Response
    {
        $questions = $this->questionService->getPaginated($request->input('search'));

        return Inertia::render('questions/Index', [
            'questions' => $questions,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('questions/Create', [
            'quizzes' => Quiz::query()->orderBy('name')->get(['uuid', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'quiz_id' => ['required', 'string', 'exists:quizzes,uuid'],
        ]);

        $this->questionService->create($validated);

        return back()->with('success', 'Question created successfully.');
    }

    public function edit(Question $question): Response
    {
        return Inertia::render('questions/Edit', [
            'question' => $question,
            'quizzes' => Quiz::query()->orderBy('name')->get(['uuid', 'name']),
        ]);
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'quiz_id' => ['required', 'string', 'exists:quizzes,uuid'],
        ]);

        $this->questionService->update($question, $validated);

        return redirect()->route('questions.index')
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $this->questionService->delete($question);

        return redirect()->route('questions.index')
            ->with('success', 'Question deleted successfully.');
    }
}
