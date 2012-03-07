<?php

class Blackjack_DecisionQuiz
{

    protected $quiz;

    public function __construct(Blackjack_Quiz $quiz)
    {

        $this->quiz = $quiz;

    }

    public function getQuestions()
    {

        foreach ($this->quiz->getQuestions() as $question) {

            $question->addOption('S', 'Stand');
            $question->addOption('H', 'Hit');
            $question->addOption('P', 'Split');
            $question->addOption('D', 'Double Down');

        }

        return $this->quiz->getQuestions();

    }

}
