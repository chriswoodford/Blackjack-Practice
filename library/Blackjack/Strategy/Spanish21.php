<?php

class Blackjack_Strategy_Spanish21 implements Blackjack_Strategy
{

    protected $pairsMatrix;

    protected $softMatrix;

    public function __construct()
    {

        $this->pairsMatrix = $this->getPairsMatrix();
        $this->softMatrix = $this->getSoftMatrix();

    }

    public function decide(Blackjack_Hand $hand, $dealer)
    {

        if ($hand->isAPair()) {

            $value = $this->decidePair($hand, $dealer);

            if ($value !== null) {
                return $value;
            }

        }

        if ($hand->isSoft()) {

            return $this->decideSoft($hand, $dealer);

        }

echo 'SOFT: |' . $hand->getSoftTotal() . '|' . PHP_EOL;
echo 'HARD: |' . $hand->getHardTotal() . '|' . PHP_EOL;

    }

    protected function decideSoft(Blackjack_Hand $hand, $dealer)
    {

        // hit 16 vs 6 with 4 or more cards
        if ($hand->getSoftTotal() == 16
            && count($hand) >= 4
            && $dealer == 6
        ) {
            return 'H';
        }



        return $this->softMatrix[$hand->getSoftTotal()][$dealer];

    }

    protected function decidePair(Blackjack_Hand $hand, $dealer)
    {

        switch ($hand->getHardTotal()) {

            // never splits 4s, 5s, or 10s
            case 8:
            case 10:
            case 20:
                return null;

            // hit with suited 7s
            case 14:

                if ($hand->isSuited()) {
                    return 'H';
                }

            default:
                return $this->pairsMatrix[$hand->getHardTotal()][$dealer];

        }

    }

    protected function getSoftMatrix()
    {

        return array(
            12 => array_fill(2, 9, 'H') + array('A' => 'H'),
            13 => array_fill(2, 9, 'H') + array('A' => 'H'),
            14 => array_fill(2, 9, 'H') + array('A' => 'H'),
            15 => array_fill(2, 9, 'H') + array('A' => 'H'),
            16 => array(6 => 'D') + array_fill(2, 9, 'H') + array('A' => 'H'),
            17 => array_fill(4, 3, 'D') + array_fill(2, 9, 'H') + array('A' => 'H'),
            18 => array_fill(4, 3, 'D') + array_fill(2, 7, 'S') + array_fill(9, 2, 'H') + array('A' => 'H'),
            19 => array_fill(2, 9, 'S') + array('A' => 'S'),
            20 => array_fill(2, 9, 'S') + array('A' => 'S'),
            21 => array_fill(2, 9, 'S') + array('A' => 'S'),
        );

    }

    protected function getPairsMatrix()
    {

        return array(
            2 => array_fill(2, 9, 'P') + array('A' => 'P'),
            4 => array_fill(2, 7, 'P') + array_fill(9, 2, 'H') + array('A' => 'H'),
            6 => array_fill(2, 7, 'P') + array_fill(9, 2, 'H') + array('A' => 'H'),
            12 => array_fill(4, 3, 'P') + array_fill(2, 9, 'H') + array('A' => 'H'),
            14 => array_fill(2, 6, 'P') + array_fill(8, 3, 'H') + array('A' => 'H'),
            16 => array_fill(2, 9, 'P') + array('A' => 'P'),
            18 => array_fill(3, 4, 'P') + array(8 => 'P', 9 => 'P') + array(2 => 'S', 7 => 'S', 10 => 'S', 'A' => 'S'),
        );

    }

}
