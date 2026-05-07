export interface FrontendUser {
    uuid: string;
    firstname: string;
    lastname: string;
    phone: string | null;
    email: string;
}

export interface Answer {
    uuid: string;
    name: string;
    question_id: string;
    is_correct: boolean;
    is_active: boolean;
    explanation: string | null;
    order: number | null;
    question: Question,
}

export interface Question {
    uuid: string;
    name: string;
    quiz_id: string;
    quiz:Quiz,
    answers?: Answer[];
}

export interface Quiz {
    uuid: string;
    name: string;
    questions?: Question[];
    topics: Topic[];
}

export interface Topic {
    uuid: string;
    name: string;
    front_user_id: string;
    quizzes?: Quiz[];
}
