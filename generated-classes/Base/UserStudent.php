<?php

namespace Base;

use \Book as ChildBook;
use \BookQuery as ChildBookQuery;
use \Department as ChildDepartment;
use \DepartmentQuery as ChildDepartmentQuery;
use \Group as ChildGroup;
use \GroupQuery as ChildGroupQuery;
use \User as ChildUser;
use \UserQuery as ChildUserQuery;
use \UserStudent as ChildUserStudent;
use \UserStudentBook as ChildUserStudentBook;
use \UserStudentBookQuery as ChildUserStudentBookQuery;
use \UserStudentQuery as ChildUserStudentQuery;
use \Exception;
use \PDO;
use Map\UserStudentTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

abstract class UserStudent implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\UserStudentTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the user_id field.
     * @var        int
     */
    protected $user_id;

    /**
     * The value for the group_id field.
     * @var        int
     */
    protected $group_id;

    /**
     * The value for the dept_id field.
     * @var        int
     */
    protected $dept_id;

    /**
     * The value for the firstname field.
     * @var        string
     */
    protected $firstname;

    /**
     * The value for the lastname field.
     * @var        string
     */
    protected $lastname;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        Group
     */
    protected $aGroup;

    /**
     * @var        Department
     */
    protected $aDepartment;

    /**
     * @var        ObjectCollection|ChildUserStudentBook[] Collection to store aggregation of ChildUserStudentBook objects.
     */
    protected $collUserStudentBooks;
    protected $collUserStudentBooksPartial;

    /**
     * @var        ChildBook[] Collection to store aggregation of ChildBook objects.
     */
    protected $collBooks;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $booksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $userStudentBooksScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\UserStudent object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !empty($this->modifiedColumns);
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return in_array($col, $this->modifiedColumns);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return array_unique($this->modifiedColumns);
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (Boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (Boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            while (false !== ($offset = array_search($col, $this->modifiedColumns))) {
                array_splice($this->modifiedColumns, $offset, 1);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>UserStudent</code> instance.  If
     * <code>obj</code> is an instance of <code>UserStudent</code>, delegates to
     * <code>equals(UserStudent)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        $thisclazz = get_class($this);
        if (!is_object($obj) || !($obj instanceof $thisclazz)) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey()
            || null === $obj->getPrimaryKey())  {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        if (null !== $this->getPrimaryKey()) {
            return crc32(serialize($this->getPrimaryKey()));
        }

        return crc32(serialize(clone $this));
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return UserStudent The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return UserStudent The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [user_id] column value.
     *
     * @return   int
     */
    public function getUserId()
    {

        return $this->user_id;
    }

    /**
     * Get the [group_id] column value.
     *
     * @return   int
     */
    public function getGroupId()
    {

        return $this->group_id;
    }

    /**
     * Get the [dept_id] column value.
     *
     * @return   int
     */
    public function getDeptId()
    {

        return $this->dept_id;
    }

    /**
     * Get the [firstname] column value.
     *
     * @return   string
     */
    public function getFirstname()
    {

        return $this->firstname;
    }

    /**
     * Get the [lastname] column value.
     *
     * @return   string
     */
    public function getLastname()
    {

        return $this->lastname;
    }

    /**
     * Set the value of [user_id] column.
     *
     * @param      int $v new value
     * @return   \UserStudent The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = UserStudentTableMap::USER_ID;
        }

        if ($this->aUser !== null && $this->aUser->getUserId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setUserId()

    /**
     * Set the value of [group_id] column.
     *
     * @param      int $v new value
     * @return   \UserStudent The current object (for fluent API support)
     */
    public function setGroupId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->group_id !== $v) {
            $this->group_id = $v;
            $this->modifiedColumns[] = UserStudentTableMap::GROUP_ID;
        }

        if ($this->aGroup !== null && $this->aGroup->getGroupId() !== $v) {
            $this->aGroup = null;
        }


        return $this;
    } // setGroupId()

    /**
     * Set the value of [dept_id] column.
     *
     * @param      int $v new value
     * @return   \UserStudent The current object (for fluent API support)
     */
    public function setDeptId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->dept_id !== $v) {
            $this->dept_id = $v;
            $this->modifiedColumns[] = UserStudentTableMap::DEPT_ID;
        }

        if ($this->aDepartment !== null && $this->aDepartment->getDeptId() !== $v) {
            $this->aDepartment = null;
        }


        return $this;
    } // setDeptId()

    /**
     * Set the value of [firstname] column.
     *
     * @param      string $v new value
     * @return   \UserStudent The current object (for fluent API support)
     */
    public function setFirstname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->firstname !== $v) {
            $this->firstname = $v;
            $this->modifiedColumns[] = UserStudentTableMap::FIRSTNAME;
        }


        return $this;
    } // setFirstname()

    /**
     * Set the value of [lastname] column.
     *
     * @param      string $v new value
     * @return   \UserStudent The current object (for fluent API support)
     */
    public function setLastname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->lastname !== $v) {
            $this->lastname = $v;
            $this->modifiedColumns[] = UserStudentTableMap::LASTNAME;
        }


        return $this;
    } // setLastname()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
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
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserStudentTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserStudentTableMap::translateFieldName('GroupId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->group_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserStudentTableMap::translateFieldName('DeptId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->dept_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserStudentTableMap::translateFieldName('Firstname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->firstname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserStudentTableMap::translateFieldName('Lastname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lastname = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = UserStudentTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \UserStudent object", 0, $e);
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
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getUserId()) {
            $this->aUser = null;
        }
        if ($this->aGroup !== null && $this->group_id !== $this->aGroup->getGroupId()) {
            $this->aGroup = null;
        }
        if ($this->aDepartment !== null && $this->dept_id !== $this->aDepartment->getDeptId()) {
            $this->aDepartment = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserStudentTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserStudentQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aGroup = null;
            $this->aDepartment = null;
            $this->collUserStudentBooks = null;

            $this->collBooks = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see UserStudent::setDeleted()
     * @see UserStudent::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserStudentTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildUserStudentQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
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
     * Since this table was configured to reload rows on update, the object will
     * be reloaded from the database if an UPDATE operation is performed (unless
     * the $skipReload parameter is TRUE).
     *
     * Since this table was configured to reload rows on insert, the object will
     * be reloaded from the database if an INSERT operation is performed (unless
     * the $skipReload parameter is TRUE).
     *
     * @param      ConnectionInterface $con
     * @param      boolean $skipReload Whether to skip the reload for this object from database.
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null, $skipReload = false)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserStudentTableMap::DATABASE_NAME);
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
                $affectedRows = $this->doSave($con, $skipReload);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                UserStudentTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
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
     * @param      ConnectionInterface $con
     * @param      boolean $skipReload Whether to skip the reload for this object from database.
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con, $skipReload = false)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            $reloadObject = false;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->aGroup !== null) {
                if ($this->aGroup->isModified() || $this->aGroup->isNew()) {
                    $affectedRows += $this->aGroup->save($con);
                }
                $this->setGroup($this->aGroup);
            }

            if ($this->aDepartment !== null) {
                if ($this->aDepartment->isModified() || $this->aDepartment->isNew()) {
                    $affectedRows += $this->aDepartment->save($con);
                }
                $this->setDepartment($this->aDepartment);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    if (!$skipReload) {
                        $reloadObject = true;
                    }
                } else {
                    $this->doUpdate($con);
                    if (!$skipReload) {
                        $reloadObject = true;
                    }
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->booksScheduledForDeletion !== null) {
                if (!$this->booksScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk  = $this->getPrimaryKey();
                    foreach ($this->booksScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }

                    UserStudentBookQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->booksScheduledForDeletion = null;
                }

                foreach ($this->getBooks() as $book) {
                    if ($book->isModified()) {
                        $book->save($con);
                    }
                }
            } elseif ($this->collBooks) {
                foreach ($this->collBooks as $book) {
                    if ($book->isModified()) {
                        $book->save($con);
                    }
                }
            }

            if ($this->userStudentBooksScheduledForDeletion !== null) {
                if (!$this->userStudentBooksScheduledForDeletion->isEmpty()) {
                    \UserStudentBookQuery::create()
                        ->filterByPrimaryKeys($this->userStudentBooksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userStudentBooksScheduledForDeletion = null;
                }
            }

                if ($this->collUserStudentBooks !== null) {
            foreach ($this->collUserStudentBooks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

            if ($reloadObject) {
                $this->reload($con);
            }

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserStudentTableMap::USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'USER_ID';
        }
        if ($this->isColumnModified(UserStudentTableMap::GROUP_ID)) {
            $modifiedColumns[':p' . $index++]  = 'GROUP_ID';
        }
        if ($this->isColumnModified(UserStudentTableMap::DEPT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'DEPT_ID';
        }
        if ($this->isColumnModified(UserStudentTableMap::FIRSTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'FIRSTNAME';
        }
        if ($this->isColumnModified(UserStudentTableMap::LASTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'LASTNAME';
        }

        $sql = sprintf(
            'INSERT INTO userstudent_tbl (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'USER_ID':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case 'GROUP_ID':
                        $stmt->bindValue($identifier, $this->group_id, PDO::PARAM_INT);
                        break;
                    case 'DEPT_ID':
                        $stmt->bindValue($identifier, $this->dept_id, PDO::PARAM_INT);
                        break;
                    case 'FIRSTNAME':
                        $stmt->bindValue($identifier, $this->firstname, PDO::PARAM_STR);
                        break;
                    case 'LASTNAME':
                        $stmt->bindValue($identifier, $this->lastname, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserStudentTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getUserId();
                break;
            case 1:
                return $this->getGroupId();
                break;
            case 2:
                return $this->getDeptId();
                break;
            case 3:
                return $this->getFirstname();
                break;
            case 4:
                return $this->getLastname();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['UserStudent'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['UserStudent'][$this->getPrimaryKey()] = true;
        $keys = UserStudentTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getUserId(),
            $keys[1] => $this->getGroupId(),
            $keys[2] => $this->getDeptId(),
            $keys[3] => $this->getFirstname(),
            $keys[4] => $this->getLastname(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aGroup) {
                $result['Group'] = $this->aGroup->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDepartment) {
                $result['Department'] = $this->aDepartment->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collUserStudentBooks) {
                $result['UserStudentBooks'] = $this->collUserStudentBooks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param      string $name
     * @param      mixed  $value field value
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return void
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserStudentTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @param      mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setUserId($value);
                break;
            case 1:
                $this->setGroupId($value);
                break;
            case 2:
                $this->setDeptId($value);
                break;
            case 3:
                $this->setFirstname($value);
                break;
            case 4:
                $this->setLastname($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = UserStudentTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setUserId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setGroupId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDeptId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setFirstname($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setLastname($arr[$keys[4]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserStudentTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserStudentTableMap::USER_ID)) $criteria->add(UserStudentTableMap::USER_ID, $this->user_id);
        if ($this->isColumnModified(UserStudentTableMap::GROUP_ID)) $criteria->add(UserStudentTableMap::GROUP_ID, $this->group_id);
        if ($this->isColumnModified(UserStudentTableMap::DEPT_ID)) $criteria->add(UserStudentTableMap::DEPT_ID, $this->dept_id);
        if ($this->isColumnModified(UserStudentTableMap::FIRSTNAME)) $criteria->add(UserStudentTableMap::FIRSTNAME, $this->firstname);
        if ($this->isColumnModified(UserStudentTableMap::LASTNAME)) $criteria->add(UserStudentTableMap::LASTNAME, $this->lastname);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(UserStudentTableMap::DATABASE_NAME);
        $criteria->add(UserStudentTableMap::USER_ID, $this->user_id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return   int
     */
    public function getPrimaryKey()
    {
        return $this->getUserId();
    }

    /**
     * Generic method to set the primary key (user_id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setUserId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getUserId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \UserStudent (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUserId($this->getUserId());
        $copyObj->setGroupId($this->getGroupId());
        $copyObj->setDeptId($this->getDeptId());
        $copyObj->setFirstname($this->getFirstname());
        $copyObj->setLastname($this->getLastname());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getUserStudentBooks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserStudentBook($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
        }
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
     * @return                 \UserStudent Clone of current object.
     * @throws PropelException
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
     * Declares an association between this object and a ChildUser object.
     *
     * @param                  ChildUser $v
     * @return                 \UserStudent The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getUserId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this 1:1 relationship.
        if ($v !== null) {
            $v->setUserStudent($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildUser The associated ChildUser object.
     * @throws PropelException
     */
    public function getUser(ConnectionInterface $con = null)
    {
        if ($this->aUser === null && ($this->user_id !== null)) {
            $this->aUser = ChildUserQuery::create()->findPk($this->user_id, $con);
            // Because this foreign key represents a one-to-one relationship, we will create a bi-directional association.
            $this->aUser->setUserStudent($this);
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a ChildGroup object.
     *
     * @param                  ChildGroup $v
     * @return                 \UserStudent The current object (for fluent API support)
     * @throws PropelException
     */
    public function setGroup(ChildGroup $v = null)
    {
        if ($v === null) {
            $this->setGroupId(NULL);
        } else {
            $this->setGroupId($v->getGroupId());
        }

        $this->aGroup = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildGroup object, it will not be re-added.
        if ($v !== null) {
            $v->addUserStudent($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildGroup object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildGroup The associated ChildGroup object.
     * @throws PropelException
     */
    public function getGroup(ConnectionInterface $con = null)
    {
        if ($this->aGroup === null && ($this->group_id !== null)) {
            $this->aGroup = ChildGroupQuery::create()->findPk($this->group_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aGroup->addUserStudents($this);
             */
        }

        return $this->aGroup;
    }

    /**
     * Declares an association between this object and a ChildDepartment object.
     *
     * @param                  ChildDepartment $v
     * @return                 \UserStudent The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDepartment(ChildDepartment $v = null)
    {
        if ($v === null) {
            $this->setDeptId(NULL);
        } else {
            $this->setDeptId($v->getDeptId());
        }

        $this->aDepartment = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildDepartment object, it will not be re-added.
        if ($v !== null) {
            $v->addUserStudent($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildDepartment object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildDepartment The associated ChildDepartment object.
     * @throws PropelException
     */
    public function getDepartment(ConnectionInterface $con = null)
    {
        if ($this->aDepartment === null && ($this->dept_id !== null)) {
            $this->aDepartment = ChildDepartmentQuery::create()->findPk($this->dept_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDepartment->addUserStudents($this);
             */
        }

        return $this->aDepartment;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('UserStudentBook' == $relationName) {
            return $this->initUserStudentBooks();
        }
    }

    /**
     * Clears out the collUserStudentBooks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserStudentBooks()
     */
    public function clearUserStudentBooks()
    {
        $this->collUserStudentBooks = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserStudentBooks collection loaded partially.
     */
    public function resetPartialUserStudentBooks($v = true)
    {
        $this->collUserStudentBooksPartial = $v;
    }

    /**
     * Initializes the collUserStudentBooks collection.
     *
     * By default this just sets the collUserStudentBooks collection to an empty array (like clearcollUserStudentBooks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserStudentBooks($overrideExisting = true)
    {
        if (null !== $this->collUserStudentBooks && !$overrideExisting) {
            return;
        }
        $this->collUserStudentBooks = new ObjectCollection();
        $this->collUserStudentBooks->setModel('\UserStudentBook');
    }

    /**
     * Gets an array of ChildUserStudentBook objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUserStudent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildUserStudentBook[] List of ChildUserStudentBook objects
     * @throws PropelException
     */
    public function getUserStudentBooks($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserStudentBooksPartial && !$this->isNew();
        if (null === $this->collUserStudentBooks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserStudentBooks) {
                // return empty collection
                $this->initUserStudentBooks();
            } else {
                $collUserStudentBooks = ChildUserStudentBookQuery::create(null, $criteria)
                    ->filterByUserStudent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserStudentBooksPartial && count($collUserStudentBooks)) {
                        $this->initUserStudentBooks(false);

                        foreach ($collUserStudentBooks as $obj) {
                            if (false == $this->collUserStudentBooks->contains($obj)) {
                                $this->collUserStudentBooks->append($obj);
                            }
                        }

                        $this->collUserStudentBooksPartial = true;
                    }

                    $collUserStudentBooks->getInternalIterator()->rewind();

                    return $collUserStudentBooks;
                }

                if ($partial && $this->collUserStudentBooks) {
                    foreach ($this->collUserStudentBooks as $obj) {
                        if ($obj->isNew()) {
                            $collUserStudentBooks[] = $obj;
                        }
                    }
                }

                $this->collUserStudentBooks = $collUserStudentBooks;
                $this->collUserStudentBooksPartial = false;
            }
        }

        return $this->collUserStudentBooks;
    }

    /**
     * Sets a collection of UserStudentBook objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userStudentBooks A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildUserStudent The current object (for fluent API support)
     */
    public function setUserStudentBooks(Collection $userStudentBooks, ConnectionInterface $con = null)
    {
        $userStudentBooksToDelete = $this->getUserStudentBooks(new Criteria(), $con)->diff($userStudentBooks);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userStudentBooksScheduledForDeletion = clone $userStudentBooksToDelete;

        foreach ($userStudentBooksToDelete as $userStudentBookRemoved) {
            $userStudentBookRemoved->setUserStudent(null);
        }

        $this->collUserStudentBooks = null;
        foreach ($userStudentBooks as $userStudentBook) {
            $this->addUserStudentBook($userStudentBook);
        }

        $this->collUserStudentBooks = $userStudentBooks;
        $this->collUserStudentBooksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserStudentBook objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserStudentBook objects.
     * @throws PropelException
     */
    public function countUserStudentBooks(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserStudentBooksPartial && !$this->isNew();
        if (null === $this->collUserStudentBooks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserStudentBooks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserStudentBooks());
            }

            $query = ChildUserStudentBookQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserStudent($this)
                ->count($con);
        }

        return count($this->collUserStudentBooks);
    }

    /**
     * Method called to associate a ChildUserStudentBook object to this object
     * through the ChildUserStudentBook foreign key attribute.
     *
     * @param    ChildUserStudentBook $l ChildUserStudentBook
     * @return   \UserStudent The current object (for fluent API support)
     */
    public function addUserStudentBook(ChildUserStudentBook $l)
    {
        if ($this->collUserStudentBooks === null) {
            $this->initUserStudentBooks();
            $this->collUserStudentBooksPartial = true;
        }

        if (!in_array($l, $this->collUserStudentBooks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserStudentBook($l);
        }

        return $this;
    }

    /**
     * @param UserStudentBook $userStudentBook The userStudentBook object to add.
     */
    protected function doAddUserStudentBook($userStudentBook)
    {
        $this->collUserStudentBooks[]= $userStudentBook;
        $userStudentBook->setUserStudent($this);
    }

    /**
     * @param  UserStudentBook $userStudentBook The userStudentBook object to remove.
     * @return ChildUserStudent The current object (for fluent API support)
     */
    public function removeUserStudentBook($userStudentBook)
    {
        if ($this->getUserStudentBooks()->contains($userStudentBook)) {
            $this->collUserStudentBooks->remove($this->collUserStudentBooks->search($userStudentBook));
            if (null === $this->userStudentBooksScheduledForDeletion) {
                $this->userStudentBooksScheduledForDeletion = clone $this->collUserStudentBooks;
                $this->userStudentBooksScheduledForDeletion->clear();
            }
            $this->userStudentBooksScheduledForDeletion[]= clone $userStudentBook;
            $userStudentBook->setUserStudent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this UserStudent is new, it will return
     * an empty collection; or if this UserStudent has previously
     * been saved, it will retrieve related UserStudentBooks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in UserStudent.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildUserStudentBook[] List of ChildUserStudentBook objects
     */
    public function getUserStudentBooksJoinBook($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserStudentBookQuery::create(null, $criteria);
        $query->joinWith('Book', $joinBehavior);

        return $this->getUserStudentBooks($query, $con);
    }

    /**
     * Clears out the collBooks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addBooks()
     */
    public function clearBooks()
    {
        $this->collBooks = null; // important to set this to NULL since that means it is uninitialized
        $this->collBooksPartial = null;
    }

    /**
     * Initializes the collBooks collection.
     *
     * By default this just sets the collBooks collection to an empty collection (like clearBooks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initBooks()
    {
        $this->collBooks = new ObjectCollection();
        $this->collBooks->setModel('\Book');
    }

    /**
     * Gets a collection of ChildBook objects related by a many-to-many relationship
     * to the current object by way of the userstudent_book_tbl cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUserStudent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildBook[] List of ChildBook objects
     */
    public function getBooks($criteria = null, ConnectionInterface $con = null)
    {
        if (null === $this->collBooks || null !== $criteria) {
            if ($this->isNew() && null === $this->collBooks) {
                // return empty collection
                $this->initBooks();
            } else {
                $collBooks = ChildBookQuery::create(null, $criteria)
                    ->filterByUserStudent($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collBooks;
                }
                $this->collBooks = $collBooks;
            }
        }

        return $this->collBooks;
    }

    /**
     * Sets a collection of Book objects related by a many-to-many relationship
     * to the current object by way of the userstudent_book_tbl cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $books A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return ChildUserStudent The current object (for fluent API support)
     */
    public function setBooks(Collection $books, ConnectionInterface $con = null)
    {
        $this->clearBooks();
        $currentBooks = $this->getBooks();

        $this->booksScheduledForDeletion = $currentBooks->diff($books);

        foreach ($books as $book) {
            if (!$currentBooks->contains($book)) {
                $this->doAddBook($book);
            }
        }

        $this->collBooks = $books;

        return $this;
    }

    /**
     * Gets the number of ChildBook objects related by a many-to-many relationship
     * to the current object by way of the userstudent_book_tbl cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildBook objects
     */
    public function countBooks($criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        if (null === $this->collBooks || null !== $criteria) {
            if ($this->isNew() && null === $this->collBooks) {
                return 0;
            } else {
                $query = ChildBookQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByUserStudent($this)
                    ->count($con);
            }
        } else {
            return count($this->collBooks);
        }
    }

    /**
     * Associate a ChildBook object to this object
     * through the userstudent_book_tbl cross reference table.
     *
     * @param  ChildBook $book The ChildUserStudentBook object to relate
     * @return ChildUserStudent The current object (for fluent API support)
     */
    public function addBook(ChildBook $book)
    {
        if ($this->collBooks === null) {
            $this->initBooks();
        }

        if (!$this->collBooks->contains($book)) { // only add it if the **same** object is not already associated
            $this->doAddBook($book);
            $this->collBooks[] = $book;
        }

        return $this;
    }

    /**
     * @param    Book $book The book object to add.
     */
    protected function doAddBook($book)
    {
        $userStudentBook = new ChildUserStudentBook();
        $userStudentBook->setBook($book);
        $this->addUserStudentBook($userStudentBook);
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$book->getUserStudents()->contains($this)) {
            $foreignCollection   = $book->getUserStudents();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a ChildBook object to this object
     * through the userstudent_book_tbl cross reference table.
     *
     * @param ChildBook $book The ChildUserStudentBook object to relate
     * @return ChildUserStudent The current object (for fluent API support)
     */
    public function removeBook(ChildBook $book)
    {
        if ($this->getBooks()->contains($book)) {
            $this->collBooks->remove($this->collBooks->search($book));

            if (null === $this->booksScheduledForDeletion) {
                $this->booksScheduledForDeletion = clone $this->collBooks;
                $this->booksScheduledForDeletion->clear();
            }

            $this->booksScheduledForDeletion[] = $book;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->user_id = null;
        $this->group_id = null;
        $this->dept_id = null;
        $this->firstname = null;
        $this->lastname = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collUserStudentBooks) {
                foreach ($this->collUserStudentBooks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collBooks) {
                foreach ($this->collBooks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collUserStudentBooks instanceof Collection) {
            $this->collUserStudentBooks->clearIterator();
        }
        $this->collUserStudentBooks = null;
        if ($this->collBooks instanceof Collection) {
            $this->collBooks->clearIterator();
        }
        $this->collBooks = null;
        $this->aUser = null;
        $this->aGroup = null;
        $this->aDepartment = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserStudentTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
