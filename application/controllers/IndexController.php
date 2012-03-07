<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {

        $quiz = QuizPeer::retrieveByPK(1);
        $director = new Blackjack_Spanish21Director();
        $this->view->form = $director->getDecisionQuiz($quiz);

    }


}

