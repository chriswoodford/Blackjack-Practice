<?php

interface Blackjack_Quiz_Question
{

    /**
     * get all of the potential answers for this question
     * @return array
     */
    public function getOptions();

    /**
     * add a new potential answer
     * @param string $value
     * @param string $text
     */
    public function addOption($value, $text);

}