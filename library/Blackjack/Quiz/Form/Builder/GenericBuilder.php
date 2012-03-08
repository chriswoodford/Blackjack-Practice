<?php

class Blackjack_Quiz_Form_Builder_GenericBuilder
    implements Blackjack_Quiz_Form_Builder
{


    protected $_form;

    /** @var integer */
    protected $_numberOfQuestions;

    /** @var array */
    protected $tenOptions;

    /**
     * initialize the builder
     * @param array $tenOptions
     */
    public function __construct(array $tenOptions = null)
    {

        $this->_numberOfQuestions = 0;

        if (is_null($tenOptions)) {
            $tenOptions = array('10', 'K', 'Q', 'J');
        }

        $this->tenOptions = $tenOptions;

    }

    /**
     * (non-PHPdoc)
     * @see Blackjack_Quiz_Form_Builder::getQuiz()
     */
    public function getQuiz()
    {

        return $this->_form;

    }

    /**
     * (non-PHPdoc)
     * @see Blackjack_Quiz_Form_Builder::createQuiz()
     */
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

    /**
     * (non-PHPdoc)
     * @see Blackjack_Quiz_Form_Builder::buildQuestion()
     */
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

    /**
     * (non-PHPdoc)
     * @see Blackjack_Quiz_Form_Builder::buildDisplayGroup()
     */
    public function buildDisplayGroup($name = 'questions')
    {

        $elementNames = array();

        foreach ($this->_form->getElements() as $element) {

            $elementNames[] = $element->getName();

        }

        $this->_form->addDisplayGroup($elementNames, $name);

    }

    /**
     * build a question option
     * @param unknown_type $element
     * @param string $value
     * @param unknown_type $option
     */
    protected function buildOption($element, $value, $option)
    {

        $element->addMultiOption($value, '<span>' . (string)$option . '</span>');

    }

    /**
     * build the form element
     * @param string $name
     * @param unknown_type $question
     */
    protected function buildElement($name, $question)
    {

    	$element = new Able_Form_Bootstrap_Element_MultipleChoice($name);
    	$element->setQuestion((string)$question);

    	return $element;

    }

    /**
     * get a random suit
     * @return string
     */
    protected function getRandomSuit()
    {

        $suits = array('clubs', 'spades', 'hearts', 'diamonds');
        return $suits[rand(0, 3)];

    }

    /**
     * get a random 10-value card (10, J, Q, or K)
     * @return string
     */
    protected function getRandom10Card()
    {

        $options = $this->get10CardOptions();
        return $options[rand(0, (count($options) - 1))];

    }

    /**
     * get the 10-value card options
     * @return array
     */
    protected function get10CardOptions()
    {

        return $this->tenOptions;

    }

    /**
     * build the html for displaying a card
     * @param string $value
     */
    protected function buildCard($value)
    {

        $suit = $this->getRandomSuit();

        if ($value == 10) {
            $value = $this->getRandom10Card();
        }

        return '<span class="playing-card ' . $suit . '-' . $value . '">'
            . $value . '</span>';

    }

    /**
     * build the html for an answer button
     * @param Blackjack_Quiz_Question_Option $option
     */
    protected function buildButton(Blackjack_Quiz_Question_Option $option)
    {

        $class = 'btn';

        switch ($option->getValue()) {

            case 'S':
                $class .= ' btn-danger stand';
                break;

            case 'H':
                $class .= ' btn-success hit';
                break;

            case 'D':
                $class .= ' btn-warning double';
                break;

            case 'P':
                $class .= ' btn-primary split';
                break;

        }

		return '<a class="' . $class . '">' . (string)$option . '</a>';

    }

}
