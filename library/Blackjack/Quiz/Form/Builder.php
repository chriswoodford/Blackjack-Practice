<?php

interface Blackjack_Quiz_Form_Builder
{

    /**
     * @return Blackjack_Quiz_Form
     */
    public function getQuiz();

    public function createQuiz($action, $method = 'post', array $options = array());

    public function buildQuestion($question, array $options = array());

    public function buildDisplayGroup($name = 'questions');

}
