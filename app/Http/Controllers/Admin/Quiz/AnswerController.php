<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Quiz\AnswerRequest;
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
        abort(404);
    }

    public function create(): Response
    {
        abort(404);
    }

    public function store(AnswerRequest $request): RedirectResponse
    {
        $this->answerService->create($request->validated());

        return back()->with('success', 'Answer created successfully.');
    }

    public function edit(Answer $answer): Response
    {
        return Inertia::render('answers/Edit', [
            'answer' => $answer->load('question.quiz.topics'),
            'questions' => Question::query()->orderBy('name')->get(['uuid', 'name']),
        ]);
    }

    public function update(AnswerRequest $request, Answer $answer): RedirectResponse
    {
        $this->answerService->update($answer, $request->validated());

        return redirect()->back()
            ->with('success', 'Answer updated successfully.');
    }

    public function destroy(Answer $answer): RedirectResponse
    {
        $this->answerService->delete($answer);

        return redirect()->route('answers.index')
            ->with('success', 'Answer deleted successfully.');
    }
}
