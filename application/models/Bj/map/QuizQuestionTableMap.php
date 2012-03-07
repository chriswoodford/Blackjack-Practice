<?php


/**
 * This class defines the structure of the 'quiz_questions' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    Bj.map
 */
class QuizQuestionTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'Bj.map.QuizQuestionTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
	  // attributes
		$this->setName('quiz_questions');
		$this->setPhpName('QuizQuestion');
		$this->setClassname('QuizQuestion');
		$this->setPackage('Bj');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('QUESTION_ID', 'Id', 'INTEGER', true, 11, null);
		$this->addForeignKey('QUIZ_ID', 'QuizId', 'INTEGER', 'quizes', 'QUIZ_ID', true, 11, null);
		$this->addColumn('TEXT', 'Text', 'CLOB', true, null, null);
		$this->addColumn('DELETED_AT', 'DeletedAt', 'TIMESTAMP', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('Quiz', 'Quiz', RelationMap::MANY_TO_ONE, array('quiz_id' => 'quiz_id', ), null, null);
    $this->addRelation('QuizQuestionOption', 'QuizQuestionOption', RelationMap::ONE_TO_MANY, array('question_id' => 'question_id', ), null, null);
	} // buildRelations()

	/**
	 * 
	 * Gets the list of behaviors registered for this table
	 * 
	 * @return array Associative array (name => parameters) of behaviors
	 */
	public function getBehaviors()
	{
		return array(
			'soft_delete' => array('add_columns' => 'true', 'deleted_column' => 'deleted_at', ),
		);
	} // getBehaviors()

} // QuizQuestionTableMap
