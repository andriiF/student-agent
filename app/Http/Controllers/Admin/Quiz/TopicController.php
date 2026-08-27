<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Quiz\TopicRequest;
use App\Http\Resources\FrontendUserResource;
use App\Models\FrontendUser;
use App\Models\Quiz\Topic;
use App\Services\FrontendUserService;
use App\Services\Quiz\TopicService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TopicController extends Controller
{
    public function __construct(protected TopicService        $topicService,
                                protected FrontendUserService $frontendUserService)
    {
    }

    public function index(Request $request): Response
    {
        $topics = $this->topicService->getPaginated(search: $request->input('search'), with:['frontendUser']);

        return Inertia::render('topics/Index', [
            'topics' => $topics,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        $frontendUsers = $this->frontendUserService->get();

        return Inertia::render('topics/Create', [
            'frontendUsers' => FrontendUserResource::collection($frontendUsers)
        ]);
    }

    public function store(TopicRequest $request): RedirectResponse
    {
        $this->topicService->create($request->validated());

        return redirect()->route('topics.index')
            ->with('success', 'Topic created successfully.');
    }

    public function edit(Topic $topic): Response
    {
        $frontendUsers = $this->frontendUserService->get();

        return Inertia::render('topics/Edit', [
            'topic' => $topic->load('quizzes.questions'),
            'frontendUsers' => FrontendUserResource::collection($frontendUsers)
        ]);
    }

    public function update(TopicRequest $request, Topic $topic): RedirectResponse
    {
        $this->topicService->update($topic, $request->validated());

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
