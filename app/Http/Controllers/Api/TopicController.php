<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Quiz\TopicRequest;
use App\Http\Resources\Api\TopicResource;
use App\Models\Quiz\Topic;
use App\Services\Quiz\TopicService;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function __construct(protected TopicService $topicService)
    {
    }

    public function index(Request $request)
    {
        $frontUser = $request->attributes->get('frontend_user');

        return TopicResource::collection($frontUser->topics->load('quizzes'));
    }

    public function show(Topic $topic)
    {
        return TopicResource::make($topic->load('quizzes'));
    }

    public function store(TopicRequest $request)
    {
        $frontUser = $request->attributes->get('frontend_user');
        $data = $request->validated();
        $data['front_user_id'] = $frontUser->uuid;

        $this->topicService->create($data);

        return response()->json(['message' => 'Topic created successfully']);
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $frontUser = $request->attributes->get('frontend_user');
        $data = $request->validated();
        $data['front_user_id'] = $frontUser->uuid;


        $this->topicService->update($topic, $data);

        return response()->json(['message' => 'Topic created successfully']);
    }
}
