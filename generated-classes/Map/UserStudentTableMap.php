<?php

namespace Map;

use \UserStudent;
use \UserStudentQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'userstudent_tbl' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class UserStudentTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.UserStudentTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'ioankabi_eam';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'userstudent_tbl';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\UserStudent';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'UserStudent';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 5;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the USER_ID field
     */
    const USER_ID = 'userstudent_tbl.USER_ID';

    /**
     * the column name for the GROUP_ID field
     */
    const GROUP_ID = 'userstudent_tbl.GROUP_ID';

    /**
     * the column name for the DEPT_ID field
     */
    const DEPT_ID = 'userstudent_tbl.DEPT_ID';

    /**
     * the column name for the FIRSTNAME field
     */
    const FIRSTNAME = 'userstudent_tbl.FIRSTNAME';

    /**
     * the column name for the LASTNAME field
     */
    const LASTNAME = 'userstudent_tbl.LASTNAME';

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
        self::TYPE_PHPNAME       => array('UserId', 'GroupId', 'DeptId', 'Firstname', 'Lastname', ),
        self::TYPE_STUDLYPHPNAME => array('userId', 'groupId', 'deptId', 'firstname', 'lastname', ),
        self::TYPE_COLNAME       => array(UserStudentTableMap::USER_ID, UserStudentTableMap::GROUP_ID, UserStudentTableMap::DEPT_ID, UserStudentTableMap::FIRSTNAME, UserStudentTableMap::LASTNAME, ),
        self::TYPE_RAW_COLNAME   => array('USER_ID', 'GROUP_ID', 'DEPT_ID', 'FIRSTNAME', 'LASTNAME', ),
        self::TYPE_FIELDNAME     => array('user_id', 'group_id', 'dept_id', 'firstname', 'lastname', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('UserId' => 0, 'GroupId' => 1, 'DeptId' => 2, 'Firstname' => 3, 'Lastname' => 4, ),
        self::TYPE_STUDLYPHPNAME => array('userId' => 0, 'groupId' => 1, 'deptId' => 2, 'firstname' => 3, 'lastname' => 4, ),
        self::TYPE_COLNAME       => array(UserStudentTableMap::USER_ID => 0, UserStudentTableMap::GROUP_ID => 1, UserStudentTableMap::DEPT_ID => 2, UserStudentTableMap::FIRSTNAME => 3, UserStudentTableMap::LASTNAME => 4, ),
        self::TYPE_RAW_COLNAME   => array('USER_ID' => 0, 'GROUP_ID' => 1, 'DEPT_ID' => 2, 'FIRSTNAME' => 3, 'LASTNAME' => 4, ),
        self::TYPE_FIELDNAME     => array('user_id' => 0, 'group_id' => 1, 'dept_id' => 2, 'firstname' => 3, 'lastname' => 4, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
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
        $this->setName('userstudent_tbl');
        $this->setPhpName('UserStudent');
        $this->setClassName('\\UserStudent');
        $this->setPackage('');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('USER_ID', 'UserId', 'INTEGER' , 'user_tbl', 'USER_ID', true, null, null);
        $this->addForeignKey('GROUP_ID', 'GroupId', 'INTEGER', 'group_tbl', 'GROUP_ID', true, null, null);
        $this->addForeignKey('DEPT_ID', 'DeptId', 'INTEGER', 'dept_tbl', 'DEPT_ID', true, null, null);
        $this->addColumn('FIRSTNAME', 'Firstname', 'VARCHAR', true, 20, null);
        $this->addColumn('LASTNAME', 'Lastname', 'VARCHAR', true, 40, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', '\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'user_id', ), null, null);
        $this->addRelation('Group', '\\Group', RelationMap::MANY_TO_ONE, array('group_id' => 'group_id', ), null, null);
        $this->addRelation('Department', '\\Department', RelationMap::MANY_TO_ONE, array('dept_id' => 'dept_id', ), null, null);
        $this->addRelation('UserStudentBook', '\\UserStudentBook', RelationMap::ONE_TO_MANY, array('user_id' => 'user_id', ), null, null, 'UserStudentBooks');
        $this->addRelation('Book', '\\Book', RelationMap::MANY_TO_MANY, array(), null, null, 'Books');
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
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
                            : self::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? UserStudentTableMap::CLASS_DEFAULT : UserStudentTableMap::OM_CLASS;
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
     * @return array (UserStudent object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = UserStudentTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = UserStudentTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + UserStudentTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UserStudentTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            UserStudentTableMap::addInstanceToPool($obj, $key);
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
            $key = UserStudentTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = UserStudentTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UserStudentTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(UserStudentTableMap::USER_ID);
            $criteria->addSelectColumn(UserStudentTableMap::GROUP_ID);
            $criteria->addSelectColumn(UserStudentTableMap::DEPT_ID);
            $criteria->addSelectColumn(UserStudentTableMap::FIRSTNAME);
            $criteria->addSelectColumn(UserStudentTableMap::LASTNAME);
        } else {
            $criteria->addSelectColumn($alias . '.USER_ID');
            $criteria->addSelectColumn($alias . '.GROUP_ID');
            $criteria->addSelectColumn($alias . '.DEPT_ID');
            $criteria->addSelectColumn($alias . '.FIRSTNAME');
            $criteria->addSelectColumn($alias . '.LASTNAME');
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
        return Propel::getServiceContainer()->getDatabaseMap(UserStudentTableMap::DATABASE_NAME)->getTable(UserStudentTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(UserStudentTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(UserStudentTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new UserStudentTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a UserStudent or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or UserStudent object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserStudentTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \UserStudent) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UserStudentTableMap::DATABASE_NAME);
            $criteria->add(UserStudentTableMap::USER_ID, (array) $values, Criteria::IN);
        }

        $query = UserStudentQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { UserStudentTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { UserStudentTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the userstudent_tbl table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return UserStudentQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a UserStudent or Criteria object.
     *
     * @param mixed               $criteria Criteria or UserStudent object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserStudentTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from UserStudent object
        }


        // Set the correct dbName
        $query = UserStudentQuery::create()->mergeWith($criteria);

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

} // UserStudentTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
UserStudentTableMap::buildTableMap();
