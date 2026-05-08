<?php

namespace App\Console\Commands;

use App\Models\FrontendUser;
use App\Repositories\Quiz\AnswerRepository;
use App\Repositories\Quiz\QuestionRepository;
use App\Repositories\Quiz\QuizRepository;
use App\Repositories\Quiz\TopicRepository;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('import:quizz {path}')]
#[Description('Command description')]
class ImportQuizzData extends Command
{

    /**
     * Execute the console command.
     */
    public function handle(
        AnswerRepository   $answerRepository,
        QuizRepository     $quizRepository,
        QuestionRepository $questionRepository,
        TopicRepository    $topicRepository
    )
    {
        $path = $this->argument('path');
        $frontUser = FrontendUser::first();

        $data = json_decode(file_get_contents($path), true);


        \DB::transaction(function () use ($data, $answerRepository, $quizRepository, $questionRepository, $topicRepository, $frontUser) {

            $topic = $topicRepository->findOrCreate([
                'name' => 'Anatomy',
                'front_user_id' => $frontUser->uuid
            ]);

            foreach ($data as $quiz) {
                $quizData = $quiz['quiz'];

                $quiz = $quizRepository->create([
                    'name' => $quizData['name'],
                ]);
                foreach ($quizData['questions'] as $question) {
                    $questionRow = $questionRepository->create([
                        'name' => $question['name'],
                        'quiz_id' => $quiz->uuid,
                    ]);

                    foreach ($question['answers'] as $answer) {
                        $answerRepository->create([
                            'name' => $answer['name'],
                            'question_id' => $questionRow->uuid,
                            'is_correct' => $answer['is_correct'] ?? false,
                            'explanation' => $answer['explanation'] ?? "",
                        ]);
                    }
                }

                $topic->quizzes()->attach($quiz->uuid);
            }
        });

        return;
    }
}
