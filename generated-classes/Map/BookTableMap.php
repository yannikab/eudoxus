<?php

namespace Map;

use \Book;
use \BookQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'book_tbl' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class BookTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.BookTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'ioankabi_eam';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'book_tbl';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Book';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Book';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the BOOK_ID field
     */
    const BOOK_ID = 'book_tbl.BOOK_ID';

    /**
     * the column name for the PUBLISHER_ID field
     */
    const PUBLISHER_ID = 'book_tbl.PUBLISHER_ID';

    /**
     * the column name for the CODE field
     */
    const CODE = 'book_tbl.CODE';

    /**
     * the column name for the TITLE field
     */
    const TITLE = 'book_tbl.TITLE';

    /**
     * the column name for the AUTHOR field
     */
    const AUTHOR = 'book_tbl.AUTHOR';

    /**
     * the column name for the PAGES field
     */
    const PAGES = 'book_tbl.PAGES';

    /**
     * the column name for the ISBN field
     */
    const ISBN = 'book_tbl.ISBN';

    /**
     * the column name for the AVAILABLE field
     */
    const AVAILABLE = 'book_tbl.AVAILABLE';

    /**
     * the column name for the COVER field
     */
    const COVER = 'book_tbl.COVER';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('BookId', 'PublisherId', 'Code', 'Title', 'Author', 'Pages', 'Isbn', 'Available', 'Cover', ),
        self::TYPE_STUDLYPHPNAME => array('bookId', 'publisherId', 'code', 'title', 'author', 'pages', 'isbn', 'available', 'cover', ),
        self::TYPE_COLNAME       => array(BookTableMap::BOOK_ID, BookTableMap::PUBLISHER_ID, BookTableMap::CODE, BookTableMap::TITLE, BookTableMap::AUTHOR, BookTableMap::PAGES, BookTableMap::ISBN, BookTableMap::AVAILABLE, BookTableMap::COVER, ),
        self::TYPE_RAW_COLNAME   => array('BOOK_ID', 'PUBLISHER_ID', 'CODE', 'TITLE', 'AUTHOR', 'PAGES', 'ISBN', 'AVAILABLE', 'COVER', ),
        self::TYPE_FIELDNAME     => array('book_id', 'publisher_id', 'code', 'title', 'author', 'pages', 'isbn', 'available', 'cover', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('BookId' => 0, 'PublisherId' => 1, 'Code' => 2, 'Title' => 3, 'Author' => 4, 'Pages' => 5, 'Isbn' => 6, 'Available' => 7, 'Cover' => 8, ),
        self::TYPE_STUDLYPHPNAME => array('bookId' => 0, 'publisherId' => 1, 'code' => 2, 'title' => 3, 'author' => 4, 'pages' => 5, 'isbn' => 6, 'available' => 7, 'cover' => 8, ),
        self::TYPE_COLNAME       => array(BookTableMap::BOOK_ID => 0, BookTableMap::PUBLISHER_ID => 1, BookTableMap::CODE => 2, BookTableMap::TITLE => 3, BookTableMap::AUTHOR => 4, BookTableMap::PAGES => 5, BookTableMap::ISBN => 6, BookTableMap::AVAILABLE => 7, BookTableMap::COVER => 8, ),
        self::TYPE_RAW_COLNAME   => array('BOOK_ID' => 0, 'PUBLISHER_ID' => 1, 'CODE' => 2, 'TITLE' => 3, 'AUTHOR' => 4, 'PAGES' => 5, 'ISBN' => 6, 'AVAILABLE' => 7, 'COVER' => 8, ),
        self::TYPE_FIELDNAME     => array('book_id' => 0, 'publisher_id' => 1, 'code' => 2, 'title' => 3, 'author' => 4, 'pages' => 5, 'isbn' => 6, 'available' => 7, 'cover' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('book_tbl');
        $this->setPhpName('Book');
        $this->setClassName('\\Book');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('BOOK_ID', 'BookId', 'INTEGER', true, null, null);
        $this->addForeignKey('PUBLISHER_ID', 'PublisherId', 'INTEGER', 'publisher_tbl', 'PUBLISHER_ID', true, null, null);
        $this->addColumn('CODE', 'Code', 'VARCHAR', true, 20, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', true, 100, null);
        $this->addColumn('AUTHOR', 'Author', 'VARCHAR', true, 120, null);
        $this->addColumn('PAGES', 'Pages', 'INTEGER', true, null, null);
        $this->addColumn('ISBN', 'Isbn', 'CHAR', true, 17, null);
        $this->addColumn('AVAILABLE', 'Available', 'BOOLEAN', true, 1, null);
        $this->addColumn('COVER', 'Cover', 'BLOB', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Publisher', '\\Publisher', RelationMap::MANY_TO_ONE, array('publisher_id' => 'publisher_id', ), null, null);
        $this->addRelation('CourseBook', '\\CourseBook', RelationMap::ONE_TO_MANY, array('book_id' => 'book_id', ), null, null, 'CourseBooks');
        $this->addRelation('UserStudentBook', '\\UserStudentBook', RelationMap::ONE_TO_MANY, array('book_id' => 'book_id', ), null, null, 'UserStudentBooks');
        $this->addRelation('Course', '\\Course', RelationMap::MANY_TO_MANY, array(), null, null, 'Courses');
        $this->addRelation('UserStudent', '\\UserStudent', RelationMap::MANY_TO_MANY, array(), null, null, 'UserStudents');
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('BookId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('BookId', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return (int) $row[
                            $indexType == TableMap::TYPE_NUM
                            ? 0 + $offset
                            : self::translateFieldName('BookId', TableMap::TYPE_PHPNAME, $indexType)
                        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? BookTableMap::CLASS_DEFAULT : BookTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (Book object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = BookTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = BookTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + BookTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = BookTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            BookTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = BookTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = BookTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                BookTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(BookTableMap::BOOK_ID);
            $criteria->addSelectColumn(BookTableMap::PUBLISHER_ID);
            $criteria->addSelectColumn(BookTableMap::CODE);
            $criteria->addSelectColumn(BookTableMap::TITLE);
            $criteria->addSelectColumn(BookTableMap::AUTHOR);
            $criteria->addSelectColumn(BookTableMap::PAGES);
            $criteria->addSelectColumn(BookTableMap::ISBN);
            $criteria->addSelectColumn(BookTableMap::AVAILABLE);
            $criteria->addSelectColumn(BookTableMap::COVER);
        } else {
            $criteria->addSelectColumn($alias . '.BOOK_ID');
            $criteria->addSelectColumn($alias . '.PUBLISHER_ID');
            $criteria->addSelectColumn($alias . '.CODE');
            $criteria->addSelectColumn($alias . '.TITLE');
            $criteria->addSelectColumn($alias . '.AUTHOR');
            $criteria->addSelectColumn($alias . '.PAGES');
            $criteria->addSelectColumn($alias . '.ISBN');
            $criteria->addSelectColumn($alias . '.AVAILABLE');
            $criteria->addSelectColumn($alias . '.COVER');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(BookTableMap::DATABASE_NAME)->getTable(BookTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(BookTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(BookTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new BookTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a Book or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Book object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BookTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Book) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(BookTableMap::DATABASE_NAME);
            $criteria->add(BookTableMap::BOOK_ID, (array) $values, Criteria::IN);
        }

        $query = BookQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { BookTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { BookTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the book_tbl table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return BookQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Book or Criteria object.
     *
     * @param mixed               $criteria Criteria or Book object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BookTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Book object
        }

        if ($criteria->containsKey(BookTableMap::BOOK_ID) && $criteria->keyContainsValue(BookTableMap::BOOK_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.BookTableMap::BOOK_ID.')');
        }


        // Set the correct dbName
        $query = BookQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // BookTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BookTableMap::buildTableMap();
