<?php

require 'Bj/om/BaseQuizQuestion.php';


/**
 * Skeleton subclass for representing a row from the 'quiz_questions' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    Bj
 */
class QuizQuestion extends BaseQuizQuestion implements Blackjack_Quiz_Question
{

    public function __toString()
    {

        return (string)$this->getText();

    }

    public function addOption($value, $text)
    {

        $option = new QuizQuestionOption();
        $option->setValue($value);
        $option->setText($text);

        $this->addQuizQuestionOption($option);

    }

    public function getOptions()
    {
        if (is_array($this->collQuizQuestionOptions)
            && !empty($this->collQuizQuestionOptions)
        ) {

            return $this->collQuizQuestionOptions;

        }

        return $this->getQuizQuestionOptions();

    }

} // QuizQuestion
