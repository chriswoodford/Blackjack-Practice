<?php

class DecideController extends Zeus_Rest_BaseController
{

    public function indexAction()
    {

        $hand = new Blackjack_Hand(
            explode(',', $this->_getParam('hand'))
        );

        $dealer = $this->_getParam('dealer');
        $strategy = $this->_getParam('strategy', 'blackjack');

        $decisionMaker = Blackjack_DecisionMaker::factory($strategy);
        $result = $decisionMaker->decide($hand, $dealer);

echo 'RESULT: |' . $result . '|';
die;

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
