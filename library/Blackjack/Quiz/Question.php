<?php

interface Blackjack_Quiz_Question
{

    public function getOptions();

    public function addOption($value, $text);

}