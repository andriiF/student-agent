<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TopicResource;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $frontUser = $request->attributes->get('frontend_user');

        return TopicResource::collection($frontUser->topics->load('quizzes'));
    }
}
