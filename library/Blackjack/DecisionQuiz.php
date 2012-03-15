<?php

class Blackjack_DecisionQuiz
{

    /** @var Blackjack_Quiz */
    protected $quiz;

    /**
     * initialize the decision quiz decorator
     * @param unknown_type $quiz
     */
    public function __construct(Blackjack_Quiz $quiz)
    {

        $this->quiz = $quiz;

    }

    /**
     * decorate the base method to add options for a
     * decision quiz
     * @return array
     */
    public function getQuestions()
    {

        $questions = array();

        foreach ($this->quiz->getQuestions() as $question) {

            $question->addOption('S', 'Stand');
            $question->addOption('H', 'Hit');
            $question->addOption('P', 'Split');
            $question->addOption('D', 'Double Down');

            $questions[] = $question;

        }

        return $questions;

    }

}
