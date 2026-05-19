<?php

namespace App\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class AnswerStoreDTO extends DataTransferObject
{
    public string|array $answer_id;
    public string $question_id;
}
