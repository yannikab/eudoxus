<?php

namespace Base;

use \Semester as ChildSemester;
use \SemesterQuery as ChildSemesterQuery;
use \Exception;
use \PDO;
use Map\SemesterTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'semester_tbl' table.
 *
 *
 *
 * @method     ChildSemesterQuery orderByDeptId($order = Criteria::ASC) Order by the dept_id column
 * @method     ChildSemesterQuery orderByPeriod($order = Criteria::ASC) Order by the period column
 *
 * @method     ChildSemesterQuery groupByDeptId() Group by the dept_id column
 * @method     ChildSemesterQuery groupByPeriod() Group by the period column
 *
 * @method     ChildSemesterQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSemesterQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSemesterQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSemesterQuery leftJoinDepartment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Department relation
 * @method     ChildSemesterQuery rightJoinDepartment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Department relation
 * @method     ChildSemesterQuery innerJoinDepartment($relationAlias = null) Adds a INNER JOIN clause to the query using the Department relation
 *
 * @method     ChildSemesterQuery leftJoinCourse($relationAlias = null) Adds a LEFT JOIN clause to the query using the Course relation
 * @method     ChildSemesterQuery rightJoinCourse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Course relation
 * @method     ChildSemesterQuery innerJoinCourse($relationAlias = null) Adds a INNER JOIN clause to the query using the Course relation
 *
 * @method     ChildSemester findOne(ConnectionInterface $con = null) Return the first ChildSemester matching the query
 * @method     ChildSemester findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSemester matching the query, or a new ChildSemester object populated from the query conditions when no match is found
 *
 * @method     ChildSemester findOneByDeptId(int $dept_id) Return the first ChildSemester filtered by the dept_id column
 * @method     ChildSemester findOneByPeriod(int $period) Return the first ChildSemester filtered by the period column
 *
 * @method     array findByDeptId(int $dept_id) Return ChildSemester objects filtered by the dept_id column
 * @method     array findByPeriod(int $period) Return ChildSemester objects filtered by the period column
 *
 */
abstract class SemesterQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\SemesterQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'ioankabi_eam', $modelName = '\\Semester', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSemesterQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSemesterQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \SemesterQuery) {
            return $criteria;
        }
        $query = new \SemesterQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$dept_id, $period] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildSemester|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SemesterTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SemesterTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildSemester A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT DEPT_ID, PERIOD FROM semester_tbl WHERE DEPT_ID = :p0 AND PERIOD = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildSemester();
            $obj->hydrate($row);
            SemesterTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildSemester|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildSemesterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(SemesterTableMap::DEPT_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(SemesterTableMap::PERIOD, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildSemesterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(SemesterTableMap::DEPT_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(SemesterTableMap::PERIOD, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the dept_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDeptId(1234); // WHERE dept_id = 1234
     * $query->filterByDeptId(array(12, 34)); // WHERE dept_id IN (12, 34)
     * $query->filterByDeptId(array('min' => 12)); // WHERE dept_id > 12
     * </code>
     *
     * @see       filterByDepartment()
     *
     * @param     mixed $deptId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSemesterQuery The current query, for fluid interface
     */
    public function filterByDeptId($deptId = null, $comparison = null)
    {
        if (is_array($deptId)) {
            $useMinMax = false;
            if (isset($deptId['min'])) {
                $this->addUsingAlias(SemesterTableMap::DEPT_ID, $deptId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deptId['max'])) {
                $this->addUsingAlias(SemesterTableMap::DEPT_ID, $deptId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SemesterTableMap::DEPT_ID, $deptId, $comparison);
    }

    /**
     * Filter the query on the period column
     *
     * Example usage:
     * <code>
     * $query->filterByPeriod(1234); // WHERE period = 1234
     * $query->filterByPeriod(array(12, 34)); // WHERE period IN (12, 34)
     * $query->filterByPeriod(array('min' => 12)); // WHERE period > 12
     * </code>
     *
     * @param     mixed $period The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSemesterQuery The current query, for fluid interface
     */
    public function filterByPeriod($period = null, $comparison = null)
    {
        if (is_array($period)) {
            $useMinMax = false;
            if (isset($period['min'])) {
                $this->addUsingAlias(SemesterTableMap::PERIOD, $period['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($period['max'])) {
                $this->addUsingAlias(SemesterTableMap::PERIOD, $period['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SemesterTableMap::PERIOD, $period, $comparison);
    }

    /**
     * Filter the query by a related \Department object
     *
     * @param \Department|ObjectCollection $department The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSemesterQuery The current query, for fluid interface
     */
    public function filterByDepartment($department, $comparison = null)
    {
        if ($department instanceof \Department) {
            return $this
                ->addUsingAlias(SemesterTableMap::DEPT_ID, $department->getDeptId(), $comparison);
        } elseif ($department instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SemesterTableMap::DEPT_ID, $department->toKeyValue('PrimaryKey', 'DeptId'), $comparison);
        } else {
            throw new PropelException('filterByDepartment() only accepts arguments of type \Department or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Department relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildSemesterQuery The current query, for fluid interface
     */
    public function joinDepartment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Department');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Department');
        }

        return $this;
    }

    /**
     * Use the Department relation Department object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DepartmentQuery A secondary query class using the current class as primary query
     */
    public function useDepartmentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDepartment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Department', '\DepartmentQuery');
    }

    /**
     * Filter the query by a related \Course object
     *
     * @param \Course|ObjectCollection $course  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSemesterQuery The current query, for fluid interface
     */
    public function filterByCourse($course, $comparison = null)
    {
        if ($course instanceof \Course) {
            return $this
                ->addUsingAlias(SemesterTableMap::DEPT_ID, $course->getDeptId(), $comparison)
                ->addUsingAlias(SemesterTableMap::PERIOD, $course->getPeriod(), $comparison);
        } else {
            throw new PropelException('filterByCourse() only accepts arguments of type \Course');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Course relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildSemesterQuery The current query, for fluid interface
     */
    public function joinCourse($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Course');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Course');
        }

        return $this;
    }

    /**
     * Use the Course relation Course object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CourseQuery A secondary query class using the current class as primary query
     */
    public function useCourseQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCourse($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Course', '\CourseQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSemester $semester Object to remove from the list of results
     *
     * @return ChildSemesterQuery The current query, for fluid interface
     */
    public function prune($semester = null)
    {
        if ($semester) {
            $this->addCond('pruneCond0', $this->getAliasedColName(SemesterTableMap::DEPT_ID), $semester->getDeptId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(SemesterTableMap::PERIOD), $semester->getPeriod(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the semester_tbl table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SemesterTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SemesterTableMap::clearInstancePool();
            SemesterTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildSemester or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildSemester object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SemesterTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SemesterTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        SemesterTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SemesterTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // SemesterQuery
