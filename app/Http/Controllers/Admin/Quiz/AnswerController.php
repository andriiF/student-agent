<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Answer;
use App\Models\Quiz\Question;
use App\Services\Quiz\AnswerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AnswerController extends Controller
{
    public function __construct(protected AnswerService $answerService)
    {
    }

    public function index(Request $request): Response
    {
        $answers = $this->answerService->getPaginated($request->input('search'));

        return Inertia::render('answers/Index', [
            'answers' => $answers,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('answers/Create', [
            'questions' => Question::query()->orderBy('name')->get(['uuid', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'question_id' => ['required', 'string', 'exists:questions,uuid'],
            'is_correct' => ['boolean'],
            'is_active' => ['boolean'],
            'explanation' => ['nullable', 'string'],
            'order' => ['nullable', 'integer'],
        ]);

        $this->answerService->create($validated);

        return redirect()->route('answers.index')
            ->with('success', 'Answer created successfully.');
    }

    public function edit(Answer $answer): Response
    {
        return Inertia::render('answers/Edit', [
            'answer' => $answer,
            'questions' => Question::query()->orderBy('name')->get(['uuid', 'name']),
        ]);
    }

    public function update(Request $request, Answer $answer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'question_id' => ['required', 'string', 'exists:questions,uuid'],
            'is_correct' => ['boolean'],
            'is_active' => ['boolean'],
            'explanation' => ['nullable', 'string'],
            'order' => ['nullable', 'integer'],
        ]);

        $this->answerService->update($answer, $validated);

        return redirect()->route('answers.index')
            ->with('success', 'Answer updated successfully.');
    }

    public function destroy(Answer $answer): RedirectResponse
    {
        $this->answerService->delete($answer);

        return redirect()->route('answers.index')
            ->with('success', 'Answer deleted successfully.');
    }
}
