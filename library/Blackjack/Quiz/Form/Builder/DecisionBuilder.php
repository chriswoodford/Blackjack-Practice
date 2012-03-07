<?php

class Blackjack_Quiz_Form_Builder_DecisionBuilder
    extends Blackjack_Quiz_Form_Builder_GenericBuilder
    implements Blackjack_Quiz_Form_Builder
{

    protected function buildOption($element, $value, $option)
    {

        $button = $this->buildButton($option);
        $element->addMultiOption($value, $button);

    }

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

    protected function buildCard($value)
    {

        $suit = $this->getRandomSuit();

        if ($value == 10) {
            $value = $this->getRandom10Card();
        }

        return '<span class="playing-card ' . $suit . '-' . $value . '">'
            . $value . '</span>';

    }

    protected function buildButton(Blackjack_Quiz_Question_Option $option)
    {

        $class = 'btn';

        switch ($option->getValue()) {

            case 'S':
                $class .= ' btn-danger';
                break;

            case 'H':
                $class .= ' btn-success';
                break;

            case 'D':
                $class .= ' btn-warning';
                break;

            case 'P':
                $class .= ' btn-primary';
                break;

        }

		return '<a class="' . $class . '">' . (string)$option . '</a>';

    }

}
