<?php

class Blackjack_Spanish21Director
{


    public function getDecisionQuiz(Blackjack_Quiz $quiz)
    {

        $builder = new Blackjack_Quiz_Form_Builder_DecisionBuilder(
            array('J', 'Q', 'K')
        );

        $form = new Blackjack_Quiz_Form($builder);
        $form->setOption('id', 'decision-quiz');

        $quiz = new Blackjack_DecisionQuiz($quiz);
        return $form->build($quiz->getQuestions());

    }

}