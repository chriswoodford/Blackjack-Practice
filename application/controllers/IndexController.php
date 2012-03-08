<?php

class IndexController extends Zend_Controller_Action
{

    public function indexAction()
    {

        $quiz = QuizPeer::retrieveByPK(1);
        $director = new Blackjack_Spanish21Director();
        $this->view->form = $director->getDecisionQuiz($quiz);

    }


}
