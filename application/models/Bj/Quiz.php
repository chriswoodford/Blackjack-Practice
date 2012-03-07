<?php

require 'Bj/om/BaseQuiz.php';


/**
 * Skeleton subclass for representing a row from the 'quizes' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    Bj
 */
class Quiz extends BaseQuiz implements Blackjack_Quiz
{

    public function getQuestions()
    {

        return $this->getQuizQuestions();

    }

} // Quiz
