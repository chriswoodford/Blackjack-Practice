<?php

class Blackjack_Strategy_Spanish21 implements Blackjack_Strategy
{

    protected $pairsMatrix;

    protected $softMatrix;

    protected $hardMatrix;

    protected $hardCountMatrix;

    public function __construct()
    {

        $this->pairsMatrix = $this->getPairsMatrix();
        $this->softMatrix = $this->getSoftMatrix();
        $this->hardMatrix = $this->getHardMatrix();
        $this->hardCountMatrix = $this->getHardCountMatrix();

    }

    public function decide(Blackjack_Hand $hand, Blackjack_Hand $dealer)
    {

        $dealer = $dealer->getHardTotal();

        if ($dealer == 1) {
            $dealer = 'A';
        }

        if ($hand->isAPair()) {

            $value = $this->decidePair($hand, $dealer);

            if ($value !== null) {
                return $value;
            }

        }

        if ($hand->isSoft()) {

            return $this->decideSoft($hand, $dealer);

        }

        return $this->decideHard($hand, $dealer);

    }

    protected function decideHard(Blackjack_Hand $hand, $dealer)
    {

        // hit if any 6-7-8 is possible
        if ($hand->sixSevenEightIsPossible()
            && (($hand->getHardTotal() == 14 && in_array($dealer, array(4, 5, 6)))
            || ($hand->getHardTotal() == 15 && in_array($dealer, array(2, 3))))
        ) {

            return 'H';

        } elseif ($hand->sixSevenEightIsPossible()
            && $hand->isSuited()
            && $hand->getHardTotal() == 15
            && $dealer == 4
        ) {

            return 'H';

        }

        if ($this->checkCountToHit(
            $hand->getHardTotal(), count($hand), $dealer
        )) {
            return 'H';
        }

        return $this->hardMatrix[$hand->getHardTotal()][$dealer];

    }

    protected function decideSoft(Blackjack_Hand $hand, $dealer)
    {

        if ($this->checkCountToHit(
            $hand->getSoftTotal(), count($hand), $dealer
        )) {
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

    protected function checkCountToHit($total, $count, $dealer)
    {

        if (!array_key_exists($total, $this->hardCountMatrix)
            || !array_key_exists($dealer, $this->hardCountMatrix[$total])
        ) {
            return false;
        }

        return $count >= $this->hardCountMatrix[$total][$dealer]
            ? true : false;

    }

    protected function getHardMatrix()
    {

        return array(
            4 => array_fill(2, 9, 'H') + array('A' => 'H'),
            5 => array_fill(2, 9, 'H') + array('A' => 'H'),
            6 => array_fill(2, 9, 'H') + array('A' => 'H'),
            7 => array_fill(2, 9, 'H') + array('A' => 'H'),
            8 => array_fill(2, 9, 'H') + array('A' => 'H'),
            9 => array(6 => 'D') + array_fill(2, 9, 'H') + array('A' => 'H'),
            10 => array_fill(2, 7, 'D') + array_fill(9, 2, 'H') + array('A' => 'H'),
            11 => array_fill(2, 9, 'D') + array('A' => 'D'),
            12 => array_fill(2, 9, 'H') + array('A' => 'H'),
            13 => array_fill(2, 9, 'H') + array('A' => 'H'),
            14 => array_fill(4, 3, 'S') + array_fill(2, 9, 'H') + array('A' => 'S'),
            15 => array_fill(2, 5, 'S') + array_fill(7, 4, 'H') + array('A' => 'S'),
            16 => array_fill(2, 5, 'S') + array_fill(7, 4, 'H') + array('A' => 'S'),
            17 => array_fill(2, 9, 'S') + array('A' => 'R'),
            18 => array_fill(2, 9, 'S') + array('A' => 'S'),
            19 => array_fill(2, 9, 'S') + array('A' => 'S'),
            20 => array_fill(2, 9, 'S') + array('A' => 'S'),
            21 => array_fill(2, 9, 'S') + array('A' => 'S'),
        );

    }

    protected function getHardCountMatrix()
    {

        return array(
            9 => array(6 => 4),
            10 => array(2 => 5, 3 => 5, 7 => 3, 8 => 4),
            11 => array_fill(3, 4, 5) + array_fill(2, 8, 4) + array(10 => 3, 'A' => 3),
            14 => array(4 => 4, 5 => 5, 6 => 4),
            15 => array(2 => 4, 3 => 5, 4 => 5, 5 => 6, 6 => 6),
            16 => array(2 => 5, 3 => 6, 4 => 6),
            17 => array_fill(8, 3, 6),
            18 => array(10 => 6),
            19 => array(10 => 6),
            20 => array(10 => 6),
            21 => array(10 => 6),
        );

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
