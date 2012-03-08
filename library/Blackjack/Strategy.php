<?php

interface Blackjack_Strategy
{

    public function decide(Blackjack_Hand $hand, $dealer);

}
