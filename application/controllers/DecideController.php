<?php

class DecideController extends Zeus_Rest_RestfulController
{

    public function indexAction()
    {

        $hand = new Blackjack_Hand(
            explode(',', $this->_getParam('hand'))
        );

        $dealer = $this->_getParam('dealer');
        $strategy = $this->_getParam('strategy', 'blackjack');

        $decisionMaker = Blackjack_DecisionMaker::factory($strategy);
        $this->view->result = $decisionMaker->decide($hand, $dealer);

    }

    public function getAction()
    {}

    public function postAction()
    {}

    public function putAction()
    {}

    public function deleteAction()
    {}

}
