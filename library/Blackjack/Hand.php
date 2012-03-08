<?php

class Blackjack_Hand implements Countable
{

    /** @var array */
    protected $cards;

    /** @var array */
    protected $valueMap;

    public function __construct($firstCard, $secondCard = null)
    {

        $this->valueMap = array(
            'A' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 7,
            '8' => 8,
            '9' => 9,
            '10' => 10,
            'J' => 10,
            'Q' => 10,
            'K' => 10,
        );

        if (is_array($firstCard)) {
            $this->cards = $firstCard;
        } else {

            $this->cards = array($firstCard);

            if (!is_null($secondCard)) {
                $this->cards[] = $secondCard;
            }

        }

    }

    public function count()
    {

        return count($this->cards);

    }

    public function addCard($card)
    {

        $this->cards[] = $card;

    }

    public function getSoftTotal()
    {

        if (!in_array('A', $this->cards)) {
            return null;
        }

        $hardTotal = $this->getHardTotal();
        return $hardTotal + 10;

    }

    public function getHardTotal()
    {

        $value = 0;

        foreach ($this->cards as $card) {
            $value += $this->valueMap[$card];
        }

        return $value;

    }

    public function isAPair()
    {

        return count($this->cards) == 2
            && $this->cards[0] === $this->cards[1];

    }

    public function isSuited()
    {

        return false;

    }

    public function isSoft()
    {

        return $this->getSoftTotal() >= 12
            && $this->getSoftTotal() <= 21;

    }

}