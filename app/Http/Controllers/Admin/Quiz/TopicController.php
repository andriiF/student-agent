<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Models\FrontendUser;
use App\Models\Quiz\Topic;
use App\Services\Quiz\TopicService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TopicController extends Controller
{
    public function __construct(protected TopicService $topicService)
    {
    }

    public function index(Request $request): Response
    {
        $topics = $this->topicService->getPaginated($request->input('search'));

        return Inertia::render('topics/Index', [
            'topics' => $topics,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('topics/Create', [
            'frontendUsers' => FrontendUser::query()->orderBy('firstname')->get(['uuid', 'firstname', 'lastname', 'email']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'front_user_id' => ['required', 'string', 'exists:frontend_users,uuid'],
        ]);

        $this->topicService->create($validated);

        return redirect()->route('topics.index')
            ->with('success', 'Topic created successfully.');
    }

    public function edit(Topic $topic): Response
    {
        return Inertia::render('topics/Edit', [
            'topic' => $topic->load('quizzes.questions'),
            'frontendUsers' => FrontendUser::query()->orderBy('firstname')->get(['uuid', 'firstname', 'lastname', 'email']),
        ]);
    }

    public function update(Request $request, Topic $topic): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'front_user_id' => ['required', 'string', 'exists:frontend_users,uuid'],
        ]);

        $this->topicService->update($topic, $validated);

        return redirect()->route('topics.index')
            ->with('success', 'Topic updated successfully.');
    }

    public function destroy(Topic $topic): RedirectResponse
    {
        $this->topicService->delete($topic);

        return redirect()->route('topics.index')
            ->with('success', 'Topic deleted successfully.');
    }
}
