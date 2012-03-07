<?php

class Blackjack_Quiz_Form
{

    protected $_builder;

    protected $_action;

    protected $_method;

    protected $_options;

    public function __construct(Blackjack_Quiz_Form_Builder $builder)
    {

        $this->_builder = $builder;
        $this->_options = array();
        $this->_method = 'post';

    }

    public function setAction($action)
    {

        $this->_action = $action;

    }

    public function setMethod($method)
    {

        $this->_method = $method;

    }

    public function setOption($key, $value)
    {

        $this->_options[$key] = $value;

    }

    public function build(array $questions)
    {

        $this->_builder->createQuiz(
            $this->_action,
            $this->_method,
            $this->_options
        );

        foreach ($questions as $question) {
            $this->_builder->buildQuestion($question, $question->getOptions(), 3);
        }

        $this->_builder->buildDisplayGroup();

        return $this->_builder->getQuiz();

    }

}
