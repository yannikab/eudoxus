<?php

namespace Base;

use \Book as ChildBook;
use \BookQuery as ChildBookQuery;
use \Course as ChildCourse;
use \CourseBook as ChildCourseBook;
use \CourseBookQuery as ChildCourseBookQuery;
use \CourseQuery as ChildCourseQuery;
use \Publisher as ChildPublisher;
use \PublisherQuery as ChildPublisherQuery;
use \UserStudent as ChildUserStudent;
use \UserStudentBook as ChildUserStudentBook;
use \UserStudentBookQuery as ChildUserStudentBookQuery;
use \UserStudentQuery as ChildUserStudentQuery;
use \Exception;
use \PDO;
use Map\BookTableMap;
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

abstract class Book implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\BookTableMap';


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
     * The value for the book_id field.
     * @var        int
     */
    protected $book_id;

    /**
     * The value for the publisher_id field.
     * @var        int
     */
    protected $publisher_id;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the author field.
     * @var        string
     */
    protected $author;

    /**
     * The value for the pages field.
     * @var        int
     */
    protected $pages;

    /**
     * The value for the isbn field.
     * @var        string
     */
    protected $isbn;

    /**
     * The value for the available field.
     * @var        boolean
     */
    protected $available;

    /**
     * The value for the cover field.
     * @var        resource
     */
    protected $cover;

    /**
     * @var        Publisher
     */
    protected $aPublisher;

    /**
     * @var        ObjectCollection|ChildCourseBook[] Collection to store aggregation of ChildCourseBook objects.
     */
    protected $collCourseBooks;
    protected $collCourseBooksPartial;

    /**
     * @var        ObjectCollection|ChildUserStudentBook[] Collection to store aggregation of ChildUserStudentBook objects.
     */
    protected $collUserStudentBooks;
    protected $collUserStudentBooksPartial;

    /**
     * @var        ChildCourse[] Collection to store aggregation of ChildCourse objects.
     */
    protected $collCourses;

    /**
     * @var        ChildUserStudent[] Collection to store aggregation of ChildUserStudent objects.
     */
    protected $collUserStudents;

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
    protected $coursesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $userStudentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $courseBooksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $userStudentBooksScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Book object.
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
     * Compares this with another <code>Book</code> instance.  If
     * <code>obj</code> is an instance of <code>Book</code>, delegates to
     * <code>equals(Book)</code>.  Otherwise, returns <code>false</code>.
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
     * @return Book The current object, for fluid interface
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
     * @return Book The current object, for fluid interface
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
     * Get the [book_id] column value.
     *
     * @return   int
     */
    public function getBookId()
    {

        return $this->book_id;
    }

    /**
     * Get the [publisher_id] column value.
     *
     * @return   int
     */
    public function getPublisherId()
    {

        return $this->publisher_id;
    }

    /**
     * Get the [code] column value.
     *
     * @return   string
     */
    public function getCode()
    {

        return $this->code;
    }

    /**
     * Get the [title] column value.
     *
     * @return   string
     */
    public function getTitle()
    {

        return $this->title;
    }

    /**
     * Get the [author] column value.
     *
     * @return   string
     */
    public function getAuthor()
    {

        return $this->author;
    }

    /**
     * Get the [pages] column value.
     *
     * @return   int
     */
    public function getPages()
    {

        return $this->pages;
    }

    /**
     * Get the [isbn] column value.
     *
     * @return   string
     */
    public function getIsbn()
    {

        return $this->isbn;
    }

    /**
     * Get the [available] column value.
     *
     * @return   boolean
     */
    public function getAvailable()
    {

        return $this->available;
    }

    /**
     * Get the [cover] column value.
     *
     * @return   resource
     */
    public function getCover()
    {

        return $this->cover;
    }

    /**
     * Set the value of [book_id] column.
     *
     * @param      int $v new value
     * @return   \Book The current object (for fluent API support)
     */
    public function setBookId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->book_id !== $v) {
            $this->book_id = $v;
            $this->modifiedColumns[] = BookTableMap::BOOK_ID;
        }


        return $this;
    } // setBookId()

    /**
     * Set the value of [publisher_id] column.
     *
     * @param      int $v new value
     * @return   \Book The current object (for fluent API support)
     */
    public function setPublisherId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->publisher_id !== $v) {
            $this->publisher_id = $v;
            $this->modifiedColumns[] = BookTableMap::PUBLISHER_ID;
        }

        if ($this->aPublisher !== null && $this->aPublisher->getPublisherId() !== $v) {
            $this->aPublisher = null;
        }


        return $this;
    } // setPublisherId()

    /**
     * Set the value of [code] column.
     *
     * @param      string $v new value
     * @return   \Book The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[] = BookTableMap::CODE;
        }


        return $this;
    } // setCode()

    /**
     * Set the value of [title] column.
     *
     * @param      string $v new value
     * @return   \Book The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = BookTableMap::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [author] column.
     *
     * @param      string $v new value
     * @return   \Book The current object (for fluent API support)
     */
    public function setAuthor($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->author !== $v) {
            $this->author = $v;
            $this->modifiedColumns[] = BookTableMap::AUTHOR;
        }


        return $this;
    } // setAuthor()

    /**
     * Set the value of [pages] column.
     *
     * @param      int $v new value
     * @return   \Book The current object (for fluent API support)
     */
    public function setPages($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->pages !== $v) {
            $this->pages = $v;
            $this->modifiedColumns[] = BookTableMap::PAGES;
        }


        return $this;
    } // setPages()

    /**
     * Set the value of [isbn] column.
     *
     * @param      string $v new value
     * @return   \Book The current object (for fluent API support)
     */
    public function setIsbn($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->isbn !== $v) {
            $this->isbn = $v;
            $this->modifiedColumns[] = BookTableMap::ISBN;
        }


        return $this;
    } // setIsbn()

    /**
     * Sets the value of the [available] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param      boolean|integer|string $v The new value
     * @return   \Book The current object (for fluent API support)
     */
    public function setAvailable($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->available !== $v) {
            $this->available = $v;
            $this->modifiedColumns[] = BookTableMap::AVAILABLE;
        }


        return $this;
    } // setAvailable()

    /**
     * Set the value of [cover] column.
     *
     * @param      resource $v new value
     * @return   \Book The current object (for fluent API support)
     */
    public function setCover($v)
    {
        // Because BLOB columns are streams in PDO we have to assume that they are
        // always modified when a new value is passed in.  For example, the contents
        // of the stream itself may have changed externally.
        if (!is_resource($v) && $v !== null) {
            $this->cover = fopen('php://memory', 'r+');
            fwrite($this->cover, $v);
            rewind($this->cover);
        } else { // it's already a stream
            $this->cover = $v;
        }
        $this->modifiedColumns[] = BookTableMap::COVER;


        return $this;
    } // setCover()

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


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : BookTableMap::translateFieldName('BookId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->book_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : BookTableMap::translateFieldName('PublisherId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : BookTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : BookTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : BookTableMap::translateFieldName('Author', TableMap::TYPE_PHPNAME, $indexType)];
            $this->author = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : BookTableMap::translateFieldName('Pages', TableMap::TYPE_PHPNAME, $indexType)];
            $this->pages = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : BookTableMap::translateFieldName('Isbn', TableMap::TYPE_PHPNAME, $indexType)];
            $this->isbn = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : BookTableMap::translateFieldName('Available', TableMap::TYPE_PHPNAME, $indexType)];
            $this->available = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : BookTableMap::translateFieldName('Cover', TableMap::TYPE_PHPNAME, $indexType)];
            if (null !== $col) {
                $this->cover = fopen('php://memory', 'r+');
                fwrite($this->cover, $col);
                rewind($this->cover);
            } else {
                $this->cover = null;
            }
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = BookTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \Book object", 0, $e);
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
        if ($this->aPublisher !== null && $this->publisher_id !== $this->aPublisher->getPublisherId()) {
            $this->aPublisher = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(BookTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildBookQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPublisher = null;
            $this->collCourseBooks = null;

            $this->collUserStudentBooks = null;

            $this->collCourses = null;
            $this->collUserStudents = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Book::setDeleted()
     * @see Book::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(BookTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildBookQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(BookTableMap::DATABASE_NAME);
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
                BookTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aPublisher !== null) {
                if ($this->aPublisher->isModified() || $this->aPublisher->isNew()) {
                    $affectedRows += $this->aPublisher->save($con);
                }
                $this->setPublisher($this->aPublisher);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                // Rewind the cover LOB column, since PDO does not rewind after inserting value.
                if ($this->cover !== null && is_resource($this->cover)) {
                    rewind($this->cover);
                }

                $this->resetModified();
            }

            if ($this->coursesScheduledForDeletion !== null) {
                if (!$this->coursesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk  = $this->getPrimaryKey();
                    foreach ($this->coursesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }

                    CourseBookQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->coursesScheduledForDeletion = null;
                }

                foreach ($this->getCourses() as $course) {
                    if ($course->isModified()) {
                        $course->save($con);
                    }
                }
            } elseif ($this->collCourses) {
                foreach ($this->collCourses as $course) {
                    if ($course->isModified()) {
                        $course->save($con);
                    }
                }
            }

            if ($this->userStudentsScheduledForDeletion !== null) {
                if (!$this->userStudentsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk  = $this->getPrimaryKey();
                    foreach ($this->userStudentsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }

                    UserStudentBookQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->userStudentsScheduledForDeletion = null;
                }

                foreach ($this->getUserStudents() as $userStudent) {
                    if ($userStudent->isModified()) {
                        $userStudent->save($con);
                    }
                }
            } elseif ($this->collUserStudents) {
                foreach ($this->collUserStudents as $userStudent) {
                    if ($userStudent->isModified()) {
                        $userStudent->save($con);
                    }
                }
            }

            if ($this->courseBooksScheduledForDeletion !== null) {
                if (!$this->courseBooksScheduledForDeletion->isEmpty()) {
                    \CourseBookQuery::create()
                        ->filterByPrimaryKeys($this->courseBooksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->courseBooksScheduledForDeletion = null;
                }
            }

                if ($this->collCourseBooks !== null) {
            foreach ($this->collCourseBooks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
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

        $this->modifiedColumns[] = BookTableMap::BOOK_ID;
        if (null !== $this->book_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . BookTableMap::BOOK_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(BookTableMap::BOOK_ID)) {
            $modifiedColumns[':p' . $index++]  = 'BOOK_ID';
        }
        if ($this->isColumnModified(BookTableMap::PUBLISHER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'PUBLISHER_ID';
        }
        if ($this->isColumnModified(BookTableMap::CODE)) {
            $modifiedColumns[':p' . $index++]  = 'CODE';
        }
        if ($this->isColumnModified(BookTableMap::TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'TITLE';
        }
        if ($this->isColumnModified(BookTableMap::AUTHOR)) {
            $modifiedColumns[':p' . $index++]  = 'AUTHOR';
        }
        if ($this->isColumnModified(BookTableMap::PAGES)) {
            $modifiedColumns[':p' . $index++]  = 'PAGES';
        }
        if ($this->isColumnModified(BookTableMap::ISBN)) {
            $modifiedColumns[':p' . $index++]  = 'ISBN';
        }
        if ($this->isColumnModified(BookTableMap::AVAILABLE)) {
            $modifiedColumns[':p' . $index++]  = 'AVAILABLE';
        }
        if ($this->isColumnModified(BookTableMap::COVER)) {
            $modifiedColumns[':p' . $index++]  = 'COVER';
        }

        $sql = sprintf(
            'INSERT INTO book_tbl (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'BOOK_ID':
                        $stmt->bindValue($identifier, $this->book_id, PDO::PARAM_INT);
                        break;
                    case 'PUBLISHER_ID':
                        $stmt->bindValue($identifier, $this->publisher_id, PDO::PARAM_INT);
                        break;
                    case 'CODE':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case 'TITLE':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case 'AUTHOR':
                        $stmt->bindValue($identifier, $this->author, PDO::PARAM_STR);
                        break;
                    case 'PAGES':
                        $stmt->bindValue($identifier, $this->pages, PDO::PARAM_INT);
                        break;
                    case 'ISBN':
                        $stmt->bindValue($identifier, $this->isbn, PDO::PARAM_STR);
                        break;
                    case 'AVAILABLE':
                        $stmt->bindValue($identifier, (int) $this->available, PDO::PARAM_INT);
                        break;
                    case 'COVER':
                        if (is_resource($this->cover)) {
                            rewind($this->cover);
                        }
                        $stmt->bindValue($identifier, $this->cover, PDO::PARAM_LOB);
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
        $this->setBookId($pk);

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
        $pos = BookTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getBookId();
                break;
            case 1:
                return $this->getPublisherId();
                break;
            case 2:
                return $this->getCode();
                break;
            case 3:
                return $this->getTitle();
                break;
            case 4:
                return $this->getAuthor();
                break;
            case 5:
                return $this->getPages();
                break;
            case 6:
                return $this->getIsbn();
                break;
            case 7:
                return $this->getAvailable();
                break;
            case 8:
                return $this->getCover();
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
        if (isset($alreadyDumpedObjects['Book'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Book'][$this->getPrimaryKey()] = true;
        $keys = BookTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getBookId(),
            $keys[1] => $this->getPublisherId(),
            $keys[2] => $this->getCode(),
            $keys[3] => $this->getTitle(),
            $keys[4] => $this->getAuthor(),
            $keys[5] => $this->getPages(),
            $keys[6] => $this->getIsbn(),
            $keys[7] => $this->getAvailable(),
            $keys[8] => $this->getCover(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPublisher) {
                $result['Publisher'] = $this->aPublisher->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCourseBooks) {
                $result['CourseBooks'] = $this->collCourseBooks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = BookTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setBookId($value);
                break;
            case 1:
                $this->setPublisherId($value);
                break;
            case 2:
                $this->setCode($value);
                break;
            case 3:
                $this->setTitle($value);
                break;
            case 4:
                $this->setAuthor($value);
                break;
            case 5:
                $this->setPages($value);
                break;
            case 6:
                $this->setIsbn($value);
                break;
            case 7:
                $this->setAvailable($value);
                break;
            case 8:
                $this->setCover($value);
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
        $keys = BookTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setBookId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setPublisherId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setCode($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setTitle($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setAuthor($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setPages($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setIsbn($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setAvailable($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setCover($arr[$keys[8]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BookTableMap::DATABASE_NAME);

        if ($this->isColumnModified(BookTableMap::BOOK_ID)) $criteria->add(BookTableMap::BOOK_ID, $this->book_id);
        if ($this->isColumnModified(BookTableMap::PUBLISHER_ID)) $criteria->add(BookTableMap::PUBLISHER_ID, $this->publisher_id);
        if ($this->isColumnModified(BookTableMap::CODE)) $criteria->add(BookTableMap::CODE, $this->code);
        if ($this->isColumnModified(BookTableMap::TITLE)) $criteria->add(BookTableMap::TITLE, $this->title);
        if ($this->isColumnModified(BookTableMap::AUTHOR)) $criteria->add(BookTableMap::AUTHOR, $this->author);
        if ($this->isColumnModified(BookTableMap::PAGES)) $criteria->add(BookTableMap::PAGES, $this->pages);
        if ($this->isColumnModified(BookTableMap::ISBN)) $criteria->add(BookTableMap::ISBN, $this->isbn);
        if ($this->isColumnModified(BookTableMap::AVAILABLE)) $criteria->add(BookTableMap::AVAILABLE, $this->available);
        if ($this->isColumnModified(BookTableMap::COVER)) $criteria->add(BookTableMap::COVER, $this->cover);

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
        $criteria = new Criteria(BookTableMap::DATABASE_NAME);
        $criteria->add(BookTableMap::BOOK_ID, $this->book_id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return   int
     */
    public function getPrimaryKey()
    {
        return $this->getBookId();
    }

    /**
     * Generic method to set the primary key (book_id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setBookId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getBookId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Book (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPublisherId($this->getPublisherId());
        $copyObj->setCode($this->getCode());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setAuthor($this->getAuthor());
        $copyObj->setPages($this->getPages());
        $copyObj->setIsbn($this->getIsbn());
        $copyObj->setAvailable($this->getAvailable());
        $copyObj->setCover($this->getCover());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCourseBooks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCourseBook($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserStudentBooks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserStudentBook($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setBookId(NULL); // this is a auto-increment column, so set to default value
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
     * @return                 \Book Clone of current object.
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
     * Declares an association between this object and a ChildPublisher object.
     *
     * @param                  ChildPublisher $v
     * @return                 \Book The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPublisher(ChildPublisher $v = null)
    {
        if ($v === null) {
            $this->setPublisherId(NULL);
        } else {
            $this->setPublisherId($v->getPublisherId());
        }

        $this->aPublisher = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPublisher object, it will not be re-added.
        if ($v !== null) {
            $v->addBook($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPublisher object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildPublisher The associated ChildPublisher object.
     * @throws PropelException
     */
    public function getPublisher(ConnectionInterface $con = null)
    {
        if ($this->aPublisher === null && ($this->publisher_id !== null)) {
            $this->aPublisher = ChildPublisherQuery::create()->findPk($this->publisher_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPublisher->addBooks($this);
             */
        }

        return $this->aPublisher;
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
        if ('CourseBook' == $relationName) {
            return $this->initCourseBooks();
        }
        if ('UserStudentBook' == $relationName) {
            return $this->initUserStudentBooks();
        }
    }

    /**
     * Clears out the collCourseBooks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCourseBooks()
     */
    public function clearCourseBooks()
    {
        $this->collCourseBooks = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCourseBooks collection loaded partially.
     */
    public function resetPartialCourseBooks($v = true)
    {
        $this->collCourseBooksPartial = $v;
    }

    /**
     * Initializes the collCourseBooks collection.
     *
     * By default this just sets the collCourseBooks collection to an empty array (like clearcollCourseBooks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCourseBooks($overrideExisting = true)
    {
        if (null !== $this->collCourseBooks && !$overrideExisting) {
            return;
        }
        $this->collCourseBooks = new ObjectCollection();
        $this->collCourseBooks->setModel('\CourseBook');
    }

    /**
     * Gets an array of ChildCourseBook objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildBook is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildCourseBook[] List of ChildCourseBook objects
     * @throws PropelException
     */
    public function getCourseBooks($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCourseBooksPartial && !$this->isNew();
        if (null === $this->collCourseBooks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCourseBooks) {
                // return empty collection
                $this->initCourseBooks();
            } else {
                $collCourseBooks = ChildCourseBookQuery::create(null, $criteria)
                    ->filterByBook($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCourseBooksPartial && count($collCourseBooks)) {
                        $this->initCourseBooks(false);

                        foreach ($collCourseBooks as $obj) {
                            if (false == $this->collCourseBooks->contains($obj)) {
                                $this->collCourseBooks->append($obj);
                            }
                        }

                        $this->collCourseBooksPartial = true;
                    }

                    $collCourseBooks->getInternalIterator()->rewind();

                    return $collCourseBooks;
                }

                if ($partial && $this->collCourseBooks) {
                    foreach ($this->collCourseBooks as $obj) {
                        if ($obj->isNew()) {
                            $collCourseBooks[] = $obj;
                        }
                    }
                }

                $this->collCourseBooks = $collCourseBooks;
                $this->collCourseBooksPartial = false;
            }
        }

        return $this->collCourseBooks;
    }

    /**
     * Sets a collection of CourseBook objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $courseBooks A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildBook The current object (for fluent API support)
     */
    public function setCourseBooks(Collection $courseBooks, ConnectionInterface $con = null)
    {
        $courseBooksToDelete = $this->getCourseBooks(new Criteria(), $con)->diff($courseBooks);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->courseBooksScheduledForDeletion = clone $courseBooksToDelete;

        foreach ($courseBooksToDelete as $courseBookRemoved) {
            $courseBookRemoved->setBook(null);
        }

        $this->collCourseBooks = null;
        foreach ($courseBooks as $courseBook) {
            $this->addCourseBook($courseBook);
        }

        $this->collCourseBooks = $courseBooks;
        $this->collCourseBooksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CourseBook objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related CourseBook objects.
     * @throws PropelException
     */
    public function countCourseBooks(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCourseBooksPartial && !$this->isNew();
        if (null === $this->collCourseBooks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCourseBooks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCourseBooks());
            }

            $query = ChildCourseBookQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByBook($this)
                ->count($con);
        }

        return count($this->collCourseBooks);
    }

    /**
     * Method called to associate a ChildCourseBook object to this object
     * through the ChildCourseBook foreign key attribute.
     *
     * @param    ChildCourseBook $l ChildCourseBook
     * @return   \Book The current object (for fluent API support)
     */
    public function addCourseBook(ChildCourseBook $l)
    {
        if ($this->collCourseBooks === null) {
            $this->initCourseBooks();
            $this->collCourseBooksPartial = true;
        }

        if (!in_array($l, $this->collCourseBooks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCourseBook($l);
        }

        return $this;
    }

    /**
     * @param CourseBook $courseBook The courseBook object to add.
     */
    protected function doAddCourseBook($courseBook)
    {
        $this->collCourseBooks[]= $courseBook;
        $courseBook->setBook($this);
    }

    /**
     * @param  CourseBook $courseBook The courseBook object to remove.
     * @return ChildBook The current object (for fluent API support)
     */
    public function removeCourseBook($courseBook)
    {
        if ($this->getCourseBooks()->contains($courseBook)) {
            $this->collCourseBooks->remove($this->collCourseBooks->search($courseBook));
            if (null === $this->courseBooksScheduledForDeletion) {
                $this->courseBooksScheduledForDeletion = clone $this->collCourseBooks;
                $this->courseBooksScheduledForDeletion->clear();
            }
            $this->courseBooksScheduledForDeletion[]= clone $courseBook;
            $courseBook->setBook(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Book is new, it will return
     * an empty collection; or if this Book has previously
     * been saved, it will retrieve related CourseBooks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Book.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildCourseBook[] List of ChildCourseBook objects
     */
    public function getCourseBooksJoinCourse($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseBookQuery::create(null, $criteria);
        $query->joinWith('Course', $joinBehavior);

        return $this->getCourseBooks($query, $con);
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
     * If this ChildBook is new, it will return
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
                    ->filterByBook($this)
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
     * @return   ChildBook The current object (for fluent API support)
     */
    public function setUserStudentBooks(Collection $userStudentBooks, ConnectionInterface $con = null)
    {
        $userStudentBooksToDelete = $this->getUserStudentBooks(new Criteria(), $con)->diff($userStudentBooks);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userStudentBooksScheduledForDeletion = clone $userStudentBooksToDelete;

        foreach ($userStudentBooksToDelete as $userStudentBookRemoved) {
            $userStudentBookRemoved->setBook(null);
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
                ->filterByBook($this)
                ->count($con);
        }

        return count($this->collUserStudentBooks);
    }

    /**
     * Method called to associate a ChildUserStudentBook object to this object
     * through the ChildUserStudentBook foreign key attribute.
     *
     * @param    ChildUserStudentBook $l ChildUserStudentBook
     * @return   \Book The current object (for fluent API support)
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
        $userStudentBook->setBook($this);
    }

    /**
     * @param  UserStudentBook $userStudentBook The userStudentBook object to remove.
     * @return ChildBook The current object (for fluent API support)
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
            $userStudentBook->setBook(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Book is new, it will return
     * an empty collection; or if this Book has previously
     * been saved, it will retrieve related UserStudentBooks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Book.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildUserStudentBook[] List of ChildUserStudentBook objects
     */
    public function getUserStudentBooksJoinUserStudent($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserStudentBookQuery::create(null, $criteria);
        $query->joinWith('UserStudent', $joinBehavior);

        return $this->getUserStudentBooks($query, $con);
    }

    /**
     * Clears out the collCourses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCourses()
     */
    public function clearCourses()
    {
        $this->collCourses = null; // important to set this to NULL since that means it is uninitialized
        $this->collCoursesPartial = null;
    }

    /**
     * Initializes the collCourses collection.
     *
     * By default this just sets the collCourses collection to an empty collection (like clearCourses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initCourses()
    {
        $this->collCourses = new ObjectCollection();
        $this->collCourses->setModel('\Course');
    }

    /**
     * Gets a collection of ChildCourse objects related by a many-to-many relationship
     * to the current object by way of the course_book_tbl cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildBook is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildCourse[] List of ChildCourse objects
     */
    public function getCourses($criteria = null, ConnectionInterface $con = null)
    {
        if (null === $this->collCourses || null !== $criteria) {
            if ($this->isNew() && null === $this->collCourses) {
                // return empty collection
                $this->initCourses();
            } else {
                $collCourses = ChildCourseQuery::create(null, $criteria)
                    ->filterByBook($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collCourses;
                }
                $this->collCourses = $collCourses;
            }
        }

        return $this->collCourses;
    }

    /**
     * Sets a collection of Course objects related by a many-to-many relationship
     * to the current object by way of the course_book_tbl cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $courses A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return ChildBook The current object (for fluent API support)
     */
    public function setCourses(Collection $courses, ConnectionInterface $con = null)
    {
        $this->clearCourses();
        $currentCourses = $this->getCourses();

        $this->coursesScheduledForDeletion = $currentCourses->diff($courses);

        foreach ($courses as $course) {
            if (!$currentCourses->contains($course)) {
                $this->doAddCourse($course);
            }
        }

        $this->collCourses = $courses;

        return $this;
    }

    /**
     * Gets the number of ChildCourse objects related by a many-to-many relationship
     * to the current object by way of the course_book_tbl cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildCourse objects
     */
    public function countCourses($criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        if (null === $this->collCourses || null !== $criteria) {
            if ($this->isNew() && null === $this->collCourses) {
                return 0;
            } else {
                $query = ChildCourseQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByBook($this)
                    ->count($con);
            }
        } else {
            return count($this->collCourses);
        }
    }

    /**
     * Associate a ChildCourse object to this object
     * through the course_book_tbl cross reference table.
     *
     * @param  ChildCourse $course The ChildCourseBook object to relate
     * @return ChildBook The current object (for fluent API support)
     */
    public function addCourse(ChildCourse $course)
    {
        if ($this->collCourses === null) {
            $this->initCourses();
        }

        if (!$this->collCourses->contains($course)) { // only add it if the **same** object is not already associated
            $this->doAddCourse($course);
            $this->collCourses[] = $course;
        }

        return $this;
    }

    /**
     * @param    Course $course The course object to add.
     */
    protected function doAddCourse($course)
    {
        $courseBook = new ChildCourseBook();
        $courseBook->setCourse($course);
        $this->addCourseBook($courseBook);
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$course->getBooks()->contains($this)) {
            $foreignCollection   = $course->getBooks();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a ChildCourse object to this object
     * through the course_book_tbl cross reference table.
     *
     * @param ChildCourse $course The ChildCourseBook object to relate
     * @return ChildBook The current object (for fluent API support)
     */
    public function removeCourse(ChildCourse $course)
    {
        if ($this->getCourses()->contains($course)) {
            $this->collCourses->remove($this->collCourses->search($course));

            if (null === $this->coursesScheduledForDeletion) {
                $this->coursesScheduledForDeletion = clone $this->collCourses;
                $this->coursesScheduledForDeletion->clear();
            }

            $this->coursesScheduledForDeletion[] = $course;
        }

        return $this;
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
        $this->collUserStudentsPartial = null;
    }

    /**
     * Initializes the collUserStudents collection.
     *
     * By default this just sets the collUserStudents collection to an empty collection (like clearUserStudents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initUserStudents()
    {
        $this->collUserStudents = new ObjectCollection();
        $this->collUserStudents->setModel('\UserStudent');
    }

    /**
     * Gets a collection of ChildUserStudent objects related by a many-to-many relationship
     * to the current object by way of the userstudent_book_tbl cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildBook is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildUserStudent[] List of ChildUserStudent objects
     */
    public function getUserStudents($criteria = null, ConnectionInterface $con = null)
    {
        if (null === $this->collUserStudents || null !== $criteria) {
            if ($this->isNew() && null === $this->collUserStudents) {
                // return empty collection
                $this->initUserStudents();
            } else {
                $collUserStudents = ChildUserStudentQuery::create(null, $criteria)
                    ->filterByBook($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collUserStudents;
                }
                $this->collUserStudents = $collUserStudents;
            }
        }

        return $this->collUserStudents;
    }

    /**
     * Sets a collection of UserStudent objects related by a many-to-many relationship
     * to the current object by way of the userstudent_book_tbl cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $userStudents A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return ChildBook The current object (for fluent API support)
     */
    public function setUserStudents(Collection $userStudents, ConnectionInterface $con = null)
    {
        $this->clearUserStudents();
        $currentUserStudents = $this->getUserStudents();

        $this->userStudentsScheduledForDeletion = $currentUserStudents->diff($userStudents);

        foreach ($userStudents as $userStudent) {
            if (!$currentUserStudents->contains($userStudent)) {
                $this->doAddUserStudent($userStudent);
            }
        }

        $this->collUserStudents = $userStudents;

        return $this;
    }

    /**
     * Gets the number of ChildUserStudent objects related by a many-to-many relationship
     * to the current object by way of the userstudent_book_tbl cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildUserStudent objects
     */
    public function countUserStudents($criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        if (null === $this->collUserStudents || null !== $criteria) {
            if ($this->isNew() && null === $this->collUserStudents) {
                return 0;
            } else {
                $query = ChildUserStudentQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByBook($this)
                    ->count($con);
            }
        } else {
            return count($this->collUserStudents);
        }
    }

    /**
     * Associate a ChildUserStudent object to this object
     * through the userstudent_book_tbl cross reference table.
     *
     * @param  ChildUserStudent $userStudent The ChildUserStudentBook object to relate
     * @return ChildBook The current object (for fluent API support)
     */
    public function addUserStudent(ChildUserStudent $userStudent)
    {
        if ($this->collUserStudents === null) {
            $this->initUserStudents();
        }

        if (!$this->collUserStudents->contains($userStudent)) { // only add it if the **same** object is not already associated
            $this->doAddUserStudent($userStudent);
            $this->collUserStudents[] = $userStudent;
        }

        return $this;
    }

    /**
     * @param    UserStudent $userStudent The userStudent object to add.
     */
    protected function doAddUserStudent($userStudent)
    {
        $userStudentBook = new ChildUserStudentBook();
        $userStudentBook->setUserStudent($userStudent);
        $this->addUserStudentBook($userStudentBook);
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$userStudent->getBooks()->contains($this)) {
            $foreignCollection   = $userStudent->getBooks();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a ChildUserStudent object to this object
     * through the userstudent_book_tbl cross reference table.
     *
     * @param ChildUserStudent $userStudent The ChildUserStudentBook object to relate
     * @return ChildBook The current object (for fluent API support)
     */
    public function removeUserStudent(ChildUserStudent $userStudent)
    {
        if ($this->getUserStudents()->contains($userStudent)) {
            $this->collUserStudents->remove($this->collUserStudents->search($userStudent));

            if (null === $this->userStudentsScheduledForDeletion) {
                $this->userStudentsScheduledForDeletion = clone $this->collUserStudents;
                $this->userStudentsScheduledForDeletion->clear();
            }

            $this->userStudentsScheduledForDeletion[] = $userStudent;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->book_id = null;
        $this->publisher_id = null;
        $this->code = null;
        $this->title = null;
        $this->author = null;
        $this->pages = null;
        $this->isbn = null;
        $this->available = null;
        $this->cover = null;
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
            if ($this->collCourseBooks) {
                foreach ($this->collCourseBooks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserStudentBooks) {
                foreach ($this->collUserStudentBooks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCourses) {
                foreach ($this->collCourses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserStudents) {
                foreach ($this->collUserStudents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collCourseBooks instanceof Collection) {
            $this->collCourseBooks->clearIterator();
        }
        $this->collCourseBooks = null;
        if ($this->collUserStudentBooks instanceof Collection) {
            $this->collUserStudentBooks->clearIterator();
        }
        $this->collUserStudentBooks = null;
        if ($this->collCourses instanceof Collection) {
            $this->collCourses->clearIterator();
        }
        $this->collCourses = null;
        if ($this->collUserStudents instanceof Collection) {
            $this->collUserStudents->clearIterator();
        }
        $this->collUserStudents = null;
        $this->aPublisher = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(BookTableMap::DEFAULT_STRING_FORMAT);
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
