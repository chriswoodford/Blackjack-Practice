<?php


/**
 * This class defines the structure of the 'quizes' table.
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
class QuizTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'Bj.map.QuizTableMap';

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
		$this->setName('quizes');
		$this->setPhpName('Quiz');
		$this->setClassname('Quiz');
		$this->setPackage('Bj');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('QUIZ_ID', 'Id', 'INTEGER', true, 11, null);
		$this->addColumn('NAME', 'Name', 'VARCHAR', true, 64, null);
		$this->addColumn('SLUG', 'Slug', 'VARCHAR', true, 64, null);
		$this->addColumn('DESCRIPTION', 'Description', 'CLOB', true, null, null);
		$this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
		$this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
		$this->addColumn('DELETED_AT', 'DeletedAt', 'TIMESTAMP', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('QuizQuestion', 'QuizQuestion', RelationMap::ONE_TO_MANY, array('quiz_id' => 'quiz_id', ), null, null);
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
			'timestampable' => array('add_columns' => 'true', 'create_column' => 'created_at', 'update_column' => 'updated_at', ),
			'soft_delete' => array('add_columns' => 'true', 'deleted_column' => 'deleted_at', ),
		);
	} // getBehaviors()

} // QuizTableMap
