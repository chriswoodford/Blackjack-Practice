<?php

/**
 * Base class that represents a row from the 'quiz_questions' table.
 *
 * 
 *
 * @package    Bj.om
 */
abstract class BaseQuizQuestion extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        QuizQuestionPeer
	 */
	protected static $peer;

	/**
	 * The value for the question_id field.
	 * @var        int
	 */
	protected $question_id;

	/**
	 * The value for the quiz_id field.
	 * @var        int
	 */
	protected $quiz_id;

	/**
	 * The value for the text field.
	 * @var        string
	 */
	protected $text;

	/**
	 * The value for the deleted_at field.
	 * @var        string
	 */
	protected $deleted_at;

	/**
	 * @var        Quiz
	 */
	protected $aQuiz;

	/**
	 * @var        array QuizQuestionOption[] Collection to store aggregation of QuizQuestionOption objects.
	 */
	protected $collQuizQuestionOptions;

	/**
	 * @var        Criteria The criteria used to select the current contents of collQuizQuestionOptions.
	 */
	private $lastQuizQuestionOptionCriteria = null;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Get the [question_id] column value.
	 * 
	 * @return     int
	 */
	public function getId()
	{
		return $this->question_id;
	}

	/**
	 * Get the [quiz_id] column value.
	 * 
	 * @return     int
	 */
	public function getQuizId()
	{
		return $this->quiz_id;
	}

	/**
	 * Get the [text] column value.
	 * 
	 * @return     string
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * Get the [optionally formatted] temporal [deleted_at] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getDeletedAt($format = 'Y-m-d H:i:s')
	{
		if ($this->deleted_at === null) {
			return null;
		}


		if ($this->deleted_at === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->deleted_at);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->deleted_at, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Set the value of [question_id] column.
	 * 
	 * @param      int $v new value
	 * @return     QuizQuestion The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->question_id !== $v) {
			$this->question_id = $v;
			$this->modifiedColumns[] = QuizQuestionPeer::QUESTION_ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [quiz_id] column.
	 * 
	 * @param      int $v new value
	 * @return     QuizQuestion The current object (for fluent API support)
	 */
	public function setQuizId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->quiz_id !== $v) {
			$this->quiz_id = $v;
			$this->modifiedColumns[] = QuizQuestionPeer::QUIZ_ID;
		}

		if ($this->aQuiz !== null && $this->aQuiz->getId() !== $v) {
			$this->aQuiz = null;
		}

		return $this;
	} // setQuizId()

	/**
	 * Set the value of [text] column.
	 * 
	 * @param      string $v new value
	 * @return     QuizQuestion The current object (for fluent API support)
	 */
	public function setText($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->text !== $v) {
			$this->text = $v;
			$this->modifiedColumns[] = QuizQuestionPeer::TEXT;
		}

		return $this;
	} // setText()

	/**
	 * Sets the value of [deleted_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     QuizQuestion The current object (for fluent API support)
	 */
	public function setDeletedAt($v)
	{
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->deleted_at !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->deleted_at !== null && $tmpDt = new DateTime($this->deleted_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->deleted_at = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = QuizQuestionPeer::DELETED_AT;
			}
		} // if either are not null

		return $this;
	} // setDeletedAt()

	/**
	 * Indicates whether the columns in this object are only set to default values.
	 *
	 * This method can be used in conjunction with isModified() to indicate whether an object is both
	 * modified _and_ has some values set which are non-default.
	 *
	 * @return     boolean Whether the columns in this object are only been set with default values.
	 */
	public function hasOnlyDefaultValues()
	{
		// otherwise, everything was equal, so return TRUE
		return true;
	} // hasOnlyDefaultValues()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (0-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
	 * @param      int $startcol 0-based offset column which indicates which restultset column to start with.
	 * @param      boolean $rehydrate Whether this object is being re-hydrated from the database.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->question_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->quiz_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->text = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->deleted_at = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 4; // 4 = QuizQuestionPeer::NUM_COLUMNS - QuizQuestionPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating QuizQuestion object", $e);
		}
	}

	/**
	 * Checks and repairs the internal consistency of the object.
	 *
	 * This method is executed after an already-instantiated object is re-hydrated
	 * from the database.  It exists to check any foreign keys to make sure that
	 * the objects related to the current object are correct based on foreign key.
	 *
	 * You can override this method in the stub class, but you should always invoke
	 * the base method from the overridden method (i.e. parent::ensureConsistency()),
	 * in case your model changes.
	 *
	 * @throws     PropelException
	 */
	public function ensureConsistency()
	{

		if ($this->aQuiz !== null && $this->quiz_id !== $this->aQuiz->getId()) {
			$this->aQuiz = null;
		}
	} // ensureConsistency

	/**
	 * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
	 *
	 * This will only work if the object has been saved and has a valid primary key set.
	 *
	 * @param      boolean $deep (optional) Whether to also de-associated any related objects.
	 * @param      PropelPDO $con (optional) The PropelPDO connection to use.
	 * @return     void
	 * @throws     PropelException - if this object is deleted, unsaved or doesn't have pk match in db
	 */
	public function reload($deep = false, PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(QuizQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = QuizQuestionPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aQuiz = null;
			$this->collQuizQuestionOptions = null;
			$this->lastQuizQuestionOptionCriteria = null;

		} // if (deep)
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      PropelPDO $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(QuizQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			// soft_delete behavior
			if (!empty($ret) && QuizQuestionPeer::isSoftDeleteEnabled()) {
				$this->setDeletedAt(time());
				$this->save();
				$con->commit();
				return;
			}
			if ($ret) {
				QuizQuestionPeer::doDelete($this, $con);
				$this->postDelete($con);
				$this->setDeleted(true);
				$con->commit();
			} else {
				$con->commit();
			}
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Persists this object to the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All modified related objects will also be persisted in the doSave()
	 * method.  This method wraps all precipitate database operations in a
	 * single transaction.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(QuizQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			if ($isInsert) {
				$ret = $ret && $this->preInsert($con);
			} else {
				$ret = $ret && $this->preUpdate($con);
			}
			if ($ret) {
				$affectedRows = $this->doSave($con);
				if ($isInsert) {
					$this->postInsert($con);
				} else {
					$this->postUpdate($con);
				}
				$this->postSave($con);
				QuizQuestionPeer::addInstanceToPool($this);
			} else {
				$affectedRows = 0;
			}
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Performs the work of inserting or updating the row in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave(PropelPDO $con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;

			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aQuiz !== null) {
				if ($this->aQuiz->isModified() || $this->aQuiz->isNew()) {
					$affectedRows += $this->aQuiz->save($con);
				}
				$this->setQuiz($this->aQuiz);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = QuizQuestionPeer::QUESTION_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = QuizQuestionPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += QuizQuestionPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collQuizQuestionOptions !== null) {
				foreach ($this->collQuizQuestionOptions as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			$this->alreadyInSave = false;

		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aQuiz !== null) {
				if (!$this->aQuiz->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aQuiz->getValidationFailures());
				}
			}


			if (($retval = QuizQuestionPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collQuizQuestionOptions !== null) {
					foreach ($this->collQuizQuestionOptions as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(QuizQuestionPeer::DATABASE_NAME);

		if ($this->isColumnModified(QuizQuestionPeer::QUESTION_ID)) $criteria->add(QuizQuestionPeer::QUESTION_ID, $this->question_id);
		if ($this->isColumnModified(QuizQuestionPeer::QUIZ_ID)) $criteria->add(QuizQuestionPeer::QUIZ_ID, $this->quiz_id);
		if ($this->isColumnModified(QuizQuestionPeer::TEXT)) $criteria->add(QuizQuestionPeer::TEXT, $this->text);
		if ($this->isColumnModified(QuizQuestionPeer::DELETED_AT)) $criteria->add(QuizQuestionPeer::DELETED_AT, $this->deleted_at);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(QuizQuestionPeer::DATABASE_NAME);

		$criteria->add(QuizQuestionPeer::QUESTION_ID, $this->question_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (question_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of QuizQuestion (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setQuizId($this->quiz_id);

		$copyObj->setText($this->text);

		$copyObj->setDeletedAt($this->deleted_at);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getQuizQuestionOptions() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addQuizQuestionOption($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setId(NULL); // this is a auto-increment column, so set to default value

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     QuizQuestion Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     QuizQuestionPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new QuizQuestionPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Quiz object.
	 *
	 * @param      Quiz $v
	 * @return     QuizQuestion The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setQuiz(Quiz $v = null)
	{
		if ($v === null) {
			$this->setQuizId(NULL);
		} else {
			$this->setQuizId($v->getId());
		}

		$this->aQuiz = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Quiz object, it will not be re-added.
		if ($v !== null) {
			$v->addQuizQuestion($this);
		}

		return $this;
	}


	/**
	 * Get the associated Quiz object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Quiz The associated Quiz object.
	 * @throws     PropelException
	 */
	public function getQuiz(PropelPDO $con = null)
	{
		if ($this->aQuiz === null && ($this->quiz_id !== null)) {
			$this->aQuiz = QuizPeer::retrieveByPk($this->quiz_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aQuiz->addQuizQuestions($this);
			 */
		}
		return $this->aQuiz;
	}

	/**
	 * Clears out the collQuizQuestionOptions collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addQuizQuestionOptions()
	 */
	public function clearQuizQuestionOptions()
	{
		$this->collQuizQuestionOptions = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collQuizQuestionOptions collection (array).
	 *
	 * By default this just sets the collQuizQuestionOptions collection to an empty array (like clearcollQuizQuestionOptions());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initQuizQuestionOptions()
	{
		$this->collQuizQuestionOptions = array();
	}

	/**
	 * Gets an array of QuizQuestionOption objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this QuizQuestion has previously been saved, it will retrieve
	 * related QuizQuestionOptions from storage. If this QuizQuestion is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array QuizQuestionOption[]
	 * @throws     PropelException
	 */
	public function getQuizQuestionOptions($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(QuizQuestionPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collQuizQuestionOptions === null) {
			if ($this->isNew()) {
			   $this->collQuizQuestionOptions = array();
			} else {

				$criteria->add(QuizQuestionOptionPeer::QUESTION_ID, $this->question_id);

				QuizQuestionOptionPeer::addSelectColumns($criteria);
				$this->collQuizQuestionOptions = QuizQuestionOptionPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(QuizQuestionOptionPeer::QUESTION_ID, $this->question_id);

				QuizQuestionOptionPeer::addSelectColumns($criteria);
				if (!isset($this->lastQuizQuestionOptionCriteria) || !$this->lastQuizQuestionOptionCriteria->equals($criteria)) {
					$this->collQuizQuestionOptions = QuizQuestionOptionPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastQuizQuestionOptionCriteria = $criteria;
		return $this->collQuizQuestionOptions;
	}

	/**
	 * Returns the number of related QuizQuestionOption objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related QuizQuestionOption objects.
	 * @throws     PropelException
	 */
	public function countQuizQuestionOptions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(QuizQuestionPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collQuizQuestionOptions === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(QuizQuestionOptionPeer::QUESTION_ID, $this->question_id);

				$count = QuizQuestionOptionPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(QuizQuestionOptionPeer::QUESTION_ID, $this->question_id);

				if (!isset($this->lastQuizQuestionOptionCriteria) || !$this->lastQuizQuestionOptionCriteria->equals($criteria)) {
					$count = QuizQuestionOptionPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collQuizQuestionOptions);
				}
			} else {
				$count = count($this->collQuizQuestionOptions);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a QuizQuestionOption object to this object
	 * through the QuizQuestionOption foreign key attribute.
	 *
	 * @param      QuizQuestionOption $l QuizQuestionOption
	 * @return     void
	 * @throws     PropelException
	 */
	public function addQuizQuestionOption(QuizQuestionOption $l)
	{
		if ($this->collQuizQuestionOptions === null) {
			$this->initQuizQuestionOptions();
		}
		if (!in_array($l, $this->collQuizQuestionOptions, true)) { // only add it if the **same** object is not already associated
			array_push($this->collQuizQuestionOptions, $l);
			$l->setQuestion($this);
		}
	}

	/**
	 * Resets all collections of referencing foreign keys.
	 *
	 * This method is a user-space workaround for PHP's inability to garbage collect objects
	 * with circular references.  This is currently necessary when using Propel in certain
	 * daemon or large-volumne/high-memory operations.
	 *
	 * @param      boolean $deep Whether to also clear the references on all associated objects.
	 */
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
			if ($this->collQuizQuestionOptions) {
				foreach ((array) $this->collQuizQuestionOptions as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collQuizQuestionOptions = null;
			$this->aQuiz = null;
	}

	// soft_delete behavior
	
	/**
	 * Bypass the soft_delete behavior and force a hard delete of the current object
	 */
	public function forceDelete(PropelPDO $con = null)
	{
		QuizQuestionPeer::disableSoftDelete();
		$this->delete($con);
	}
	
	/**
	 * Undelete a row that was soft_deleted
	 *
	 * @return     int The number of rows affected by this update and any referring fk objects' save() operations.
	 */
	public function unDelete(PropelPDO $con = null)
	{
		$this->setDeletedAt(null);
		return $this->save($con);
	}
} // BaseQuizQuestion
