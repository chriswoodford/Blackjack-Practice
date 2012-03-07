<?php

require 'Bj/om/BaseQuizQuestionOption.php';


/**
 * Skeleton subclass for representing a row from the 'quiz_question_options' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    Bj
 */
class QuizQuestionOption extends BaseQuizQuestionOption
    implements Blackjack_Quiz_Question_Option
{

    public function __toString()
    {

        return (string)$this->getText();

    }

} // QuizQuestionOption
