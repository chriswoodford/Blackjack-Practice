<?php

class StrategyController extends Zend_Controller_Action
{

    public function indexAction()
    {

    }

    public function spanish21Action()
    {

        $quiz = QuizPeer::retrieveByPK(1);
        $director = new Blackjack_Spanish21Director();
        $this->view->form = $director->getDecisionQuiz($quiz);

    }

}
