<?php

interface Blackjack_Quiz_Form_Builder
{

    /**
     * get the built quiz
     * @return Blackjack_Quiz_Form
     */
    public function getQuiz();

    /**
     * intialize the quiz
     * @param string $action
     * @param string $method
     * @param array $options
     */
    public function createQuiz($action, $method = 'post', array $options = array());

    /**
     * build a quiz question
     * @param string $question
     * @param array $options
     */
    public function buildQuestion($question, array $options = array());

    /**
     * build a form display group
     * @param string $name
     */
    public function buildDisplayGroup($name = 'questions');

}
