<?php

class Blackjack_Quiz_Form_Builder_DecisionBuilder
    extends Blackjack_Quiz_Form_Builder_GenericBuilder
    implements Blackjack_Quiz_Form_Builder
{

    /**
     * (non-PHPdoc)
     * @see Blackjack_Quiz_Form_Builder_GenericBuilder::buildOption()
     */
    protected function buildOption($element, $value, $option)
    {

        $button = $this->buildButton($option);
        $element->addMultiOption($value, $button);

    }

    /**
     * (non-PHPdoc)
     * @see Blackjack_Quiz_Form_Builder_GenericBuilder::buildElement()
     */
    protected function buildElement($name, $question)
    {

    	$element = new Able_Form_Bootstrap_Element_MultipleChoice($name);

    	$data = split(' ', (string)$question);
    	$yourCards = split(',', $data[0]);
    	$dealerCard = $data[2];

    	$html = '';

    	foreach ($yourCards as $value) {
    	    $html .= $this->buildCard($value);
    	}

    	$html .= '<strong>VS</strong>'
    		. $this->buildCard($dealerCard);

    	$element->setQuestion($html);

    	return $element;

    }

}
