<?php

class Blackjack_Quiz_Form_Builder_GenericBuilder
    implements Blackjack_Quiz_Form_Builder
{

    protected $_form;

    protected $_numberOfQuestions;

    protected $tenOptions;

    public function __construct(array $tenOptions = null)
    {

        $this->_numberOfQuestions = 0;

        if (is_null($tenOptions)) {
            $tenOptions = array('10', 'K', 'Q', 'J');
        }

        $this->tenOptions = $tenOptions;

    }

    public function getQuiz()
    {

        return $this->_form;

    }

    public function createQuiz($action, $method = 'post', array $options = array())
    {

        if (!array_key_exists('class', $options)) {
            $options['class'] = 'form-stacked';
        }

    	$this->_form = new Able_Form_Bootstrap_Form();
    	$this->_form->setAction($action);
    	$this->_form->setMethod($method);

    	foreach ($options as $key => $value) {

    	    if (!$key || !$value) {
    	        continue;
    	    }

    	    $this->_form->setAttrib($key, $value);

    	}

    }

    public function buildQuestion($question, array $options = array(), $default = null)
    {

        $name = 'question' . $this->_numberOfQuestions;

    	$element = $this->buildElement($name, $question);

    	foreach ($options as $value => $option) {

    	    $this->buildOption($element, $value, $option);

    	}

    	$this->_form->addElement($element);

    	$this->_numberOfQuestions++;

    }

    public function buildDisplayGroup($name = 'questions')
    {

        $elementNames = array();

        foreach ($this->_form->getElements() as $element) {

            $elementNames[] = $element->getName();

        }

        $this->_form->addDisplayGroup($elementNames, $name);

    }

    protected function buildOption($element, $value, $option)
    {

        $element->addMultiOption($value, '<span>' . (string)$option . '</span>');

    }

    protected function buildElement($name, $question)
    {

    	$element = new Able_Form_Bootstrap_Element_MultipleChoice($name);
    	$element->setQuestion((string)$question);

    	return $element;

    }

    protected function getRandomSuit()
    {

        $suits = array('clubs', 'spades', 'hearts', 'diamonds');
        return $suits[rand(0, 3)];

    }

    protected function getRandom10Card()
    {

        $options = $this->get10CardOptions();
        return $options[rand(0, (count($options) - 1))];

    }

    protected function get10CardOptions()
    {

        return $this->tenOptions;

    }

}
