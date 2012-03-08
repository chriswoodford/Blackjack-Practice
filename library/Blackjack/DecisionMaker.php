<?php

class Blackjack_DecisionMaker
{

    protected $strategy;

    public function __construct(Blackjack_Strategy $strategy)
    {

        $this->strategy = $strategy;

    }

    public function decide(Blackjack_Hand $hand, $dealer)
    {

        return $this->strategy->decide($hand, $dealer);

    }

    public static function factory($strategy)
    {

        switch (strtolower($strategy)) {

            case 'spanish21':
                $strategy = new Blackjack_Strategy_Spanish21();
                break;

            case 'classic':
            case 'blackjack':
            default:
                $strategy = new Blackjack_Strategy_Classic();
                break;
        }

        return new Blackjack_DecisionMaker($strategy);

    }

}
