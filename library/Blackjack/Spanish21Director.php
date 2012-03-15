<?php

class Blackjack_Spanish21Director
{


    /**
     * get a decision quiz
     * @param Blackjack_Quiz $quiz
     * @return Zend_Form
     */
    public function getDecisionQuiz(Blackjack_Quiz $quiz)
    {

        // no 10's in the spanish 21 deck
        $builder = new Blackjack_Quiz_Form_Builder_DecisionBuilder(
            array('J', 'Q', 'K')
        );

        $form = new Blackjack_Quiz_Form($builder);
        $form->setOption('id', 'decision-quiz-form');

        $quiz = new Blackjack_DecisionQuiz($quiz);

        // shuffle the questions
        $questions = $quiz->getQuestions();
        shuffle($questions);

        return $form->build($questions);

    }

}