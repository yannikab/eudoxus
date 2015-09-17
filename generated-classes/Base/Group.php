<?php

namespace Base;

use \Faq as ChildFaq;
use \FaqQuery as ChildFaqQuery;
use \Group as ChildGroup;
use \GroupQuery as ChildGroupQuery;
use \UserPublisher as ChildUserPublisher;
use \UserPublisherQuery as ChildUserPublisherQuery;
use \UserSecretariat as ChildUserSecretariat;
use \UserSecretariatQuery as ChildUserSecretariatQuery;
use \UserStudent as ChildUserStudent;
use \UserStudentQuery as ChildUserStudentQuery;
use \Exception;
use \PDO;
use Map\GroupTableMap;
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

abstract class Group implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\GroupTableMap';


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
     * The value for the group_id field.
     * @var        int
     */
    protected $group_id;

    /**
     * The value for the alias field.
     * @var        string
     */
    protected $alias;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * @var        ObjectCollection|ChildUserStudent[] Collection to store aggregation of ChildUserStudent objects.
     */
    protected $collUserStudents;
    protected $collUserStudentsPartial;

    /**
     * @var        ObjectCollection|ChildUserPublisher[] Collection to store aggregation of ChildUserPublisher objects.
     */
    protected $collUserPublishers;
    protected $collUserPublishersPartial;

    /**
     * @var        ObjectCollection|ChildUserSecretariat[] Collection to store aggregation of ChildUserSecretariat objects.
     */
    protected $collUserSecretariats;
    protected $collUserSecretariatsPartial;

    /**
     * @var        ObjectCollection|ChildFaq[] Collection to store aggregation of ChildFaq objects.
     */
    protected $collFaqs;
    protected $collFaqsPartial;

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
    protected $userStudentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $userPublishersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $userSecretariatsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $faqsScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Group object.
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
     * Compares this with another <code>Group</code> instance.  If
     * <code>obj</code> is an instance of <code>Group</code>, delegates to
     * <code>equals(Group)</code>.  Otherwise, returns <code>false</code>.
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
     * @return Group The current object, for fluid interface
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
     * @return Group The current object, for fluid interface
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
     * Get the [group_id] column value.
     *
     * @return   int
     */
    public function getGroupId()
    {

        return $this->group_id;
    }

    /**
     * Get the [alias] column value.
     *
     * @return   string
     */
    public function getAlias()
    {

        return $this->alias;
    }

    /**
     * Get the [name] column value.
     *
     * @return   string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Set the value of [group_id] column.
     *
     * @param      int $v new value
     * @return   \Group The current object (for fluent API support)
     */
    public function setGroupId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->group_id !== $v) {
            $this->group_id = $v;
            $this->modifiedColumns[] = GroupTableMap::GROUP_ID;
        }


        return $this;
    } // setGroupId()

    /**
     * Set the value of [alias] column.
     *
     * @param      string $v new value
     * @return   \Group The current object (for fluent API support)
     */
    public function setAlias($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alias !== $v) {
            $this->alias = $v;
            $this->modifiedColumns[] = GroupTableMap::ALIAS;
        }


        return $this;
    } // setAlias()

    /**
     * Set the value of [name] column.
     *
     * @param      string $v new value
     * @return   \Group The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = GroupTableMap::NAME;
        }


        return $this;
    } // setName()

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


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : GroupTableMap::translateFieldName('GroupId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->group_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : GroupTableMap::translateFieldName('Alias', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alias = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : GroupTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = GroupTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \Group object", 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(GroupTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildGroupQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collUserStudents = null;

            $this->collUserPublishers = null;

            $this->collUserSecretariats = null;

            $this->collFaqs = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Group::setDeleted()
     * @see Group::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildGroupQuery::create()
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
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
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
                GroupTableMap::addInstanceToPool($this);
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
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->userStudentsScheduledForDeletion !== null) {
                if (!$this->userStudentsScheduledForDeletion->isEmpty()) {
                    \UserStudentQuery::create()
                        ->filterByPrimaryKeys($this->userStudentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userStudentsScheduledForDeletion = null;
                }
            }

                if ($this->collUserStudents !== null) {
            foreach ($this->collUserStudents as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userPublishersScheduledForDeletion !== null) {
                if (!$this->userPublishersScheduledForDeletion->isEmpty()) {
                    \UserPublisherQuery::create()
                        ->filterByPrimaryKeys($this->userPublishersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userPublishersScheduledForDeletion = null;
                }
            }

                if ($this->collUserPublishers !== null) {
            foreach ($this->collUserPublishers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userSecretariatsScheduledForDeletion !== null) {
                if (!$this->userSecretariatsScheduledForDeletion->isEmpty()) {
                    \UserSecretariatQuery::create()
                        ->filterByPrimaryKeys($this->userSecretariatsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userSecretariatsScheduledForDeletion = null;
                }
            }

                if ($this->collUserSecretariats !== null) {
            foreach ($this->collUserSecretariats as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->faqsScheduledForDeletion !== null) {
                if (!$this->faqsScheduledForDeletion->isEmpty()) {
                    \FaqQuery::create()
                        ->filterByPrimaryKeys($this->faqsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->faqsScheduledForDeletion = null;
                }
            }

                if ($this->collFaqs !== null) {
            foreach ($this->collFaqs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

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

        $this->modifiedColumns[] = GroupTableMap::GROUP_ID;
        if (null !== $this->group_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . GroupTableMap::GROUP_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(GroupTableMap::GROUP_ID)) {
            $modifiedColumns[':p' . $index++]  = 'GROUP_ID';
        }
        if ($this->isColumnModified(GroupTableMap::ALIAS)) {
            $modifiedColumns[':p' . $index++]  = 'ALIAS';
        }
        if ($this->isColumnModified(GroupTableMap::NAME)) {
            $modifiedColumns[':p' . $index++]  = 'NAME';
        }

        $sql = sprintf(
            'INSERT INTO group_tbl (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'GROUP_ID':
                        $stmt->bindValue($identifier, $this->group_id, PDO::PARAM_INT);
                        break;
                    case 'ALIAS':
                        $stmt->bindValue($identifier, $this->alias, PDO::PARAM_STR);
                        break;
                    case 'NAME':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setGroupId($pk);

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
        $pos = GroupTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getGroupId();
                break;
            case 1:
                return $this->getAlias();
                break;
            case 2:
                return $this->getName();
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
        if (isset($alreadyDumpedObjects['Group'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Group'][$this->getPrimaryKey()] = true;
        $keys = GroupTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getGroupId(),
            $keys[1] => $this->getAlias(),
            $keys[2] => $this->getName(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collUserStudents) {
                $result['UserStudents'] = $this->collUserStudents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserPublishers) {
                $result['UserPublishers'] = $this->collUserPublishers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserSecretariats) {
                $result['UserSecretariats'] = $this->collUserSecretariats->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFaqs) {
                $result['Faqs'] = $this->collFaqs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = GroupTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setGroupId($value);
                break;
            case 1:
                $this->setAlias($value);
                break;
            case 2:
                $this->setName($value);
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
        $keys = GroupTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setGroupId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setAlias($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(GroupTableMap::DATABASE_NAME);

        if ($this->isColumnModified(GroupTableMap::GROUP_ID)) $criteria->add(GroupTableMap::GROUP_ID, $this->group_id);
        if ($this->isColumnModified(GroupTableMap::ALIAS)) $criteria->add(GroupTableMap::ALIAS, $this->alias);
        if ($this->isColumnModified(GroupTableMap::NAME)) $criteria->add(GroupTableMap::NAME, $this->name);

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
        $criteria = new Criteria(GroupTableMap::DATABASE_NAME);
        $criteria->add(GroupTableMap::GROUP_ID, $this->group_id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return   int
     */
    public function getPrimaryKey()
    {
        return $this->getGroupId();
    }

    /**
     * Generic method to set the primary key (group_id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setGroupId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getGroupId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Group (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setAlias($this->getAlias());
        $copyObj->setName($this->getName());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getUserStudents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserStudent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserPublishers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserPublisher($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserSecretariats() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserSecretariat($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFaqs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFaq($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setGroupId(NULL); // this is a auto-increment column, so set to default value
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
     * @return                 \Group Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('UserStudent' == $relationName) {
            return $this->initUserStudents();
        }
        if ('UserPublisher' == $relationName) {
            return $this->initUserPublishers();
        }
        if ('UserSecretariat' == $relationName) {
            return $this->initUserSecretariats();
        }
        if ('Faq' == $relationName) {
            return $this->initFaqs();
        }
    }

    /**
     * Clears out the collUserStudents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserStudents()
     */
    public function clearUserStudents()
    {
        $this->collUserStudents = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserStudents collection loaded partially.
     */
    public function resetPartialUserStudents($v = true)
    {
        $this->collUserStudentsPartial = $v;
    }

    /**
     * Initializes the collUserStudents collection.
     *
     * By default this just sets the collUserStudents collection to an empty array (like clearcollUserStudents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserStudents($overrideExisting = true)
    {
        if (null !== $this->collUserStudents && !$overrideExisting) {
            return;
        }
        $this->collUserStudents = new ObjectCollection();
        $this->collUserStudents->setModel('\UserStudent');
    }

    /**
     * Gets an array of ChildUserStudent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildUserStudent[] List of ChildUserStudent objects
     * @throws PropelException
     */
    public function getUserStudents($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserStudentsPartial && !$this->isNew();
        if (null === $this->collUserStudents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserStudents) {
                // return empty collection
                $this->initUserStudents();
            } else {
                $collUserStudents = ChildUserStudentQuery::create(null, $criteria)
                    ->filterByGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserStudentsPartial && count($collUserStudents)) {
                        $this->initUserStudents(false);

                        foreach ($collUserStudents as $obj) {
                            if (false == $this->collUserStudents->contains($obj)) {
                                $this->collUserStudents->append($obj);
                            }
                        }

                        $this->collUserStudentsPartial = true;
                    }

                    $collUserStudents->getInternalIterator()->rewind();

                    return $collUserStudents;
                }

                if ($partial && $this->collUserStudents) {
                    foreach ($this->collUserStudents as $obj) {
                        if ($obj->isNew()) {
                            $collUserStudents[] = $obj;
                        }
                    }
                }

                $this->collUserStudents = $collUserStudents;
                $this->collUserStudentsPartial = false;
            }
        }

        return $this->collUserStudents;
    }

    /**
     * Sets a collection of UserStudent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userStudents A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildGroup The current object (for fluent API support)
     */
    public function setUserStudents(Collection $userStudents, ConnectionInterface $con = null)
    {
        $userStudentsToDelete = $this->getUserStudents(new Criteria(), $con)->diff($userStudents);


        $this->userStudentsScheduledForDeletion = $userStudentsToDelete;

        foreach ($userStudentsToDelete as $userStudentRemoved) {
            $userStudentRemoved->setGroup(null);
        }

        $this->collUserStudents = null;
        foreach ($userStudents as $userStudent) {
            $this->addUserStudent($userStudent);
        }

        $this->collUserStudents = $userStudents;
        $this->collUserStudentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserStudent objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserStudent objects.
     * @throws PropelException
     */
    public function countUserStudents(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserStudentsPartial && !$this->isNew();
        if (null === $this->collUserStudents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserStudents) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserStudents());
            }

            $query = ChildUserStudentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGroup($this)
                ->count($con);
        }

        return count($this->collUserStudents);
    }

    /**
     * Method called to associate a ChildUserStudent object to this object
     * through the ChildUserStudent foreign key attribute.
     *
     * @param    ChildUserStudent $l ChildUserStudent
     * @return   \Group The current object (for fluent API support)
     */
    public function addUserStudent(ChildUserStudent $l)
    {
        if ($this->collUserStudents === null) {
            $this->initUserStudents();
            $this->collUserStudentsPartial = true;
        }

        if (!in_array($l, $this->collUserStudents->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserStudent($l);
        }

        return $this;
    }

    /**
     * @param UserStudent $userStudent The userStudent object to add.
     */
    protected function doAddUserStudent($userStudent)
    {
        $this->collUserStudents[]= $userStudent;
        $userStudent->setGroup($this);
    }

    /**
     * @param  UserStudent $userStudent The userStudent object to remove.
     * @return ChildGroup The current object (for fluent API support)
     */
    public function removeUserStudent($userStudent)
    {
        if ($this->getUserStudents()->contains($userStudent)) {
            $this->collUserStudents->remove($this->collUserStudents->search($userStudent));
            if (null === $this->userStudentsScheduledForDeletion) {
                $this->userStudentsScheduledForDeletion = clone $this->collUserStudents;
                $this->userStudentsScheduledForDeletion->clear();
            }
            $this->userStudentsScheduledForDeletion[]= clone $userStudent;
            $userStudent->setGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related UserStudents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildUserStudent[] List of ChildUserStudent objects
     */
    public function getUserStudentsJoinUser($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserStudentQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getUserStudents($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related UserStudents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildUserStudent[] List of ChildUserStudent objects
     */
    public function getUserStudentsJoinDepartment($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserStudentQuery::create(null, $criteria);
        $query->joinWith('Department', $joinBehavior);

        return $this->getUserStudents($query, $con);
    }

    /**
     * Clears out the collUserPublishers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserPublishers()
     */
    public function clearUserPublishers()
    {
        $this->collUserPublishers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserPublishers collection loaded partially.
     */
    public function resetPartialUserPublishers($v = true)
    {
        $this->collUserPublishersPartial = $v;
    }

    /**
     * Initializes the collUserPublishers collection.
     *
     * By default this just sets the collUserPublishers collection to an empty array (like clearcollUserPublishers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserPublishers($overrideExisting = true)
    {
        if (null !== $this->collUserPublishers && !$overrideExisting) {
            return;
        }
        $this->collUserPublishers = new ObjectCollection();
        $this->collUserPublishers->setModel('\UserPublisher');
    }

    /**
     * Gets an array of ChildUserPublisher objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildUserPublisher[] List of ChildUserPublisher objects
     * @throws PropelException
     */
    public function getUserPublishers($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserPublishersPartial && !$this->isNew();
        if (null === $this->collUserPublishers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserPublishers) {
                // return empty collection
                $this->initUserPublishers();
            } else {
                $collUserPublishers = ChildUserPublisherQuery::create(null, $criteria)
                    ->filterByGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserPublishersPartial && count($collUserPublishers)) {
                        $this->initUserPublishers(false);

                        foreach ($collUserPublishers as $obj) {
                            if (false == $this->collUserPublishers->contains($obj)) {
                                $this->collUserPublishers->append($obj);
                            }
                        }

                        $this->collUserPublishersPartial = true;
                    }

                    $collUserPublishers->getInternalIterator()->rewind();

                    return $collUserPublishers;
                }

                if ($partial && $this->collUserPublishers) {
                    foreach ($this->collUserPublishers as $obj) {
                        if ($obj->isNew()) {
                            $collUserPublishers[] = $obj;
                        }
                    }
                }

                $this->collUserPublishers = $collUserPublishers;
                $this->collUserPublishersPartial = false;
            }
        }

        return $this->collUserPublishers;
    }

    /**
     * Sets a collection of UserPublisher objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userPublishers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildGroup The current object (for fluent API support)
     */
    public function setUserPublishers(Collection $userPublishers, ConnectionInterface $con = null)
    {
        $userPublishersToDelete = $this->getUserPublishers(new Criteria(), $con)->diff($userPublishers);


        $this->userPublishersScheduledForDeletion = $userPublishersToDelete;

        foreach ($userPublishersToDelete as $userPublisherRemoved) {
            $userPublisherRemoved->setGroup(null);
        }

        $this->collUserPublishers = null;
        foreach ($userPublishers as $userPublisher) {
            $this->addUserPublisher($userPublisher);
        }

        $this->collUserPublishers = $userPublishers;
        $this->collUserPublishersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserPublisher objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserPublisher objects.
     * @throws PropelException
     */
    public function countUserPublishers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserPublishersPartial && !$this->isNew();
        if (null === $this->collUserPublishers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserPublishers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserPublishers());
            }

            $query = ChildUserPublisherQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGroup($this)
                ->count($con);
        }

        return count($this->collUserPublishers);
    }

    /**
     * Method called to associate a ChildUserPublisher object to this object
     * through the ChildUserPublisher foreign key attribute.
     *
     * @param    ChildUserPublisher $l ChildUserPublisher
     * @return   \Group The current object (for fluent API support)
     */
    public function addUserPublisher(ChildUserPublisher $l)
    {
        if ($this->collUserPublishers === null) {
            $this->initUserPublishers();
            $this->collUserPublishersPartial = true;
        }

        if (!in_array($l, $this->collUserPublishers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserPublisher($l);
        }

        return $this;
    }

    /**
     * @param UserPublisher $userPublisher The userPublisher object to add.
     */
    protected function doAddUserPublisher($userPublisher)
    {
        $this->collUserPublishers[]= $userPublisher;
        $userPublisher->setGroup($this);
    }

    /**
     * @param  UserPublisher $userPublisher The userPublisher object to remove.
     * @return ChildGroup The current object (for fluent API support)
     */
    public function removeUserPublisher($userPublisher)
    {
        if ($this->getUserPublishers()->contains($userPublisher)) {
            $this->collUserPublishers->remove($this->collUserPublishers->search($userPublisher));
            if (null === $this->userPublishersScheduledForDeletion) {
                $this->userPublishersScheduledForDeletion = clone $this->collUserPublishers;
                $this->userPublishersScheduledForDeletion->clear();
            }
            $this->userPublishersScheduledForDeletion[]= clone $userPublisher;
            $userPublisher->setGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related UserPublishers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildUserPublisher[] List of ChildUserPublisher objects
     */
    public function getUserPublishersJoinUser($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserPublisherQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getUserPublishers($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related UserPublishers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildUserPublisher[] List of ChildUserPublisher objects
     */
    public function getUserPublishersJoinPublisher($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserPublisherQuery::create(null, $criteria);
        $query->joinWith('Publisher', $joinBehavior);

        return $this->getUserPublishers($query, $con);
    }

    /**
     * Clears out the collUserSecretariats collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserSecretariats()
     */
    public function clearUserSecretariats()
    {
        $this->collUserSecretariats = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserSecretariats collection loaded partially.
     */
    public function resetPartialUserSecretariats($v = true)
    {
        $this->collUserSecretariatsPartial = $v;
    }

    /**
     * Initializes the collUserSecretariats collection.
     *
     * By default this just sets the collUserSecretariats collection to an empty array (like clearcollUserSecretariats());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserSecretariats($overrideExisting = true)
    {
        if (null !== $this->collUserSecretariats && !$overrideExisting) {
            return;
        }
        $this->collUserSecretariats = new ObjectCollection();
        $this->collUserSecretariats->setModel('\UserSecretariat');
    }

    /**
     * Gets an array of ChildUserSecretariat objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildUserSecretariat[] List of ChildUserSecretariat objects
     * @throws PropelException
     */
    public function getUserSecretariats($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserSecretariatsPartial && !$this->isNew();
        if (null === $this->collUserSecretariats || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserSecretariats) {
                // return empty collection
                $this->initUserSecretariats();
            } else {
                $collUserSecretariats = ChildUserSecretariatQuery::create(null, $criteria)
                    ->filterByGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserSecretariatsPartial && count($collUserSecretariats)) {
                        $this->initUserSecretariats(false);

                        foreach ($collUserSecretariats as $obj) {
                            if (false == $this->collUserSecretariats->contains($obj)) {
                                $this->collUserSecretariats->append($obj);
                            }
                        }

                        $this->collUserSecretariatsPartial = true;
                    }

                    $collUserSecretariats->getInternalIterator()->rewind();

                    return $collUserSecretariats;
                }

                if ($partial && $this->collUserSecretariats) {
                    foreach ($this->collUserSecretariats as $obj) {
                        if ($obj->isNew()) {
                            $collUserSecretariats[] = $obj;
                        }
                    }
                }

                $this->collUserSecretariats = $collUserSecretariats;
                $this->collUserSecretariatsPartial = false;
            }
        }

        return $this->collUserSecretariats;
    }

    /**
     * Sets a collection of UserSecretariat objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userSecretariats A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildGroup The current object (for fluent API support)
     */
    public function setUserSecretariats(Collection $userSecretariats, ConnectionInterface $con = null)
    {
        $userSecretariatsToDelete = $this->getUserSecretariats(new Criteria(), $con)->diff($userSecretariats);


        $this->userSecretariatsScheduledForDeletion = $userSecretariatsToDelete;

        foreach ($userSecretariatsToDelete as $userSecretariatRemoved) {
            $userSecretariatRemoved->setGroup(null);
        }

        $this->collUserSecretariats = null;
        foreach ($userSecretariats as $userSecretariat) {
            $this->addUserSecretariat($userSecretariat);
        }

        $this->collUserSecretariats = $userSecretariats;
        $this->collUserSecretariatsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserSecretariat objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserSecretariat objects.
     * @throws PropelException
     */
    public function countUserSecretariats(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserSecretariatsPartial && !$this->isNew();
        if (null === $this->collUserSecretariats || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserSecretariats) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserSecretariats());
            }

            $query = ChildUserSecretariatQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGroup($this)
                ->count($con);
        }

        return count($this->collUserSecretariats);
    }

    /**
     * Method called to associate a ChildUserSecretariat object to this object
     * through the ChildUserSecretariat foreign key attribute.
     *
     * @param    ChildUserSecretariat $l ChildUserSecretariat
     * @return   \Group The current object (for fluent API support)
     */
    public function addUserSecretariat(ChildUserSecretariat $l)
    {
        if ($this->collUserSecretariats === null) {
            $this->initUserSecretariats();
            $this->collUserSecretariatsPartial = true;
        }

        if (!in_array($l, $this->collUserSecretariats->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserSecretariat($l);
        }

        return $this;
    }

    /**
     * @param UserSecretariat $userSecretariat The userSecretariat object to add.
     */
    protected function doAddUserSecretariat($userSecretariat)
    {
        $this->collUserSecretariats[]= $userSecretariat;
        $userSecretariat->setGroup($this);
    }

    /**
     * @param  UserSecretariat $userSecretariat The userSecretariat object to remove.
     * @return ChildGroup The current object (for fluent API support)
     */
    public function removeUserSecretariat($userSecretariat)
    {
        if ($this->getUserSecretariats()->contains($userSecretariat)) {
            $this->collUserSecretariats->remove($this->collUserSecretariats->search($userSecretariat));
            if (null === $this->userSecretariatsScheduledForDeletion) {
                $this->userSecretariatsScheduledForDeletion = clone $this->collUserSecretariats;
                $this->userSecretariatsScheduledForDeletion->clear();
            }
            $this->userSecretariatsScheduledForDeletion[]= clone $userSecretariat;
            $userSecretariat->setGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related UserSecretariats from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildUserSecretariat[] List of ChildUserSecretariat objects
     */
    public function getUserSecretariatsJoinUser($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserSecretariatQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getUserSecretariats($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related UserSecretariats from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildUserSecretariat[] List of ChildUserSecretariat objects
     */
    public function getUserSecretariatsJoinDepartment($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserSecretariatQuery::create(null, $criteria);
        $query->joinWith('Department', $joinBehavior);

        return $this->getUserSecretariats($query, $con);
    }

    /**
     * Clears out the collFaqs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFaqs()
     */
    public function clearFaqs()
    {
        $this->collFaqs = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFaqs collection loaded partially.
     */
    public function resetPartialFaqs($v = true)
    {
        $this->collFaqsPartial = $v;
    }

    /**
     * Initializes the collFaqs collection.
     *
     * By default this just sets the collFaqs collection to an empty array (like clearcollFaqs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFaqs($overrideExisting = true)
    {
        if (null !== $this->collFaqs && !$overrideExisting) {
            return;
        }
        $this->collFaqs = new ObjectCollection();
        $this->collFaqs->setModel('\Faq');
    }

    /**
     * Gets an array of ChildFaq objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildFaq[] List of ChildFaq objects
     * @throws PropelException
     */
    public function getFaqs($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFaqsPartial && !$this->isNew();
        if (null === $this->collFaqs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFaqs) {
                // return empty collection
                $this->initFaqs();
            } else {
                $collFaqs = ChildFaqQuery::create(null, $criteria)
                    ->filterByGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFaqsPartial && count($collFaqs)) {
                        $this->initFaqs(false);

                        foreach ($collFaqs as $obj) {
                            if (false == $this->collFaqs->contains($obj)) {
                                $this->collFaqs->append($obj);
                            }
                        }

                        $this->collFaqsPartial = true;
                    }

                    $collFaqs->getInternalIterator()->rewind();

                    return $collFaqs;
                }

                if ($partial && $this->collFaqs) {
                    foreach ($this->collFaqs as $obj) {
                        if ($obj->isNew()) {
                            $collFaqs[] = $obj;
                        }
                    }
                }

                $this->collFaqs = $collFaqs;
                $this->collFaqsPartial = false;
            }
        }

        return $this->collFaqs;
    }

    /**
     * Sets a collection of Faq objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $faqs A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildGroup The current object (for fluent API support)
     */
    public function setFaqs(Collection $faqs, ConnectionInterface $con = null)
    {
        $faqsToDelete = $this->getFaqs(new Criteria(), $con)->diff($faqs);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->faqsScheduledForDeletion = clone $faqsToDelete;

        foreach ($faqsToDelete as $faqRemoved) {
            $faqRemoved->setGroup(null);
        }

        $this->collFaqs = null;
        foreach ($faqs as $faq) {
            $this->addFaq($faq);
        }

        $this->collFaqs = $faqs;
        $this->collFaqsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Faq objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Faq objects.
     * @throws PropelException
     */
    public function countFaqs(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFaqsPartial && !$this->isNew();
        if (null === $this->collFaqs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFaqs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFaqs());
            }

            $query = ChildFaqQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGroup($this)
                ->count($con);
        }

        return count($this->collFaqs);
    }

    /**
     * Method called to associate a ChildFaq object to this object
     * through the ChildFaq foreign key attribute.
     *
     * @param    ChildFaq $l ChildFaq
     * @return   \Group The current object (for fluent API support)
     */
    public function addFaq(ChildFaq $l)
    {
        if ($this->collFaqs === null) {
            $this->initFaqs();
            $this->collFaqsPartial = true;
        }

        if (!in_array($l, $this->collFaqs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddFaq($l);
        }

        return $this;
    }

    /**
     * @param Faq $faq The faq object to add.
     */
    protected function doAddFaq($faq)
    {
        $this->collFaqs[]= $faq;
        $faq->setGroup($this);
    }

    /**
     * @param  Faq $faq The faq object to remove.
     * @return ChildGroup The current object (for fluent API support)
     */
    public function removeFaq($faq)
    {
        if ($this->getFaqs()->contains($faq)) {
            $this->collFaqs->remove($this->collFaqs->search($faq));
            if (null === $this->faqsScheduledForDeletion) {
                $this->faqsScheduledForDeletion = clone $this->collFaqs;
                $this->faqsScheduledForDeletion->clear();
            }
            $this->faqsScheduledForDeletion[]= clone $faq;
            $faq->setGroup(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->group_id = null;
        $this->alias = null;
        $this->name = null;
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
            if ($this->collUserStudents) {
                foreach ($this->collUserStudents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserPublishers) {
                foreach ($this->collUserPublishers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserSecretariats) {
                foreach ($this->collUserSecretariats as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFaqs) {
                foreach ($this->collFaqs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collUserStudents instanceof Collection) {
            $this->collUserStudents->clearIterator();
        }
        $this->collUserStudents = null;
        if ($this->collUserPublishers instanceof Collection) {
            $this->collUserPublishers->clearIterator();
        }
        $this->collUserPublishers = null;
        if ($this->collUserSecretariats instanceof Collection) {
            $this->collUserSecretariats->clearIterator();
        }
        $this->collUserSecretariats = null;
        if ($this->collFaqs instanceof Collection) {
            $this->collFaqs->clearIterator();
        }
        $this->collFaqs = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(GroupTableMap::DEFAULT_STRING_FORMAT);
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