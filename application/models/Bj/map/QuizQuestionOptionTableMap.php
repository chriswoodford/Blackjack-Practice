<?php


/**
 * This class defines the structure of the 'quiz_question_options' table.
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
class QuizQuestionOptionTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'Bj.map.QuizQuestionOptionTableMap';

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
		$this->setName('quiz_question_options');
		$this->setPhpName('QuizQuestionOption');
		$this->setClassname('QuizQuestionOption');
		$this->setPackage('Bj');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('OPTION_ID', 'Id', 'INTEGER', true, 11, null);
		$this->addForeignKey('QUESTION_ID', 'QuestionId', 'INTEGER', 'quiz_questions', 'QUESTION_ID', true, 11, null);
		$this->addColumn('VALUE', 'Value', 'VARCHAR', true, 2, null);
		$this->addColumn('TEXT', 'Text', 'VARCHAR', true, 255, null);
		$this->addColumn('DELETED_AT', 'DeletedAt', 'TIMESTAMP', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('Question', 'QuizQuestion', RelationMap::MANY_TO_ONE, array('question_id' => 'question_id', ), null, null);
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

} // QuizQuestionOptionTableMap
