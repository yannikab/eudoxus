<?php

namespace Base;

use \Course as ChildCourse;
use \CourseQuery as ChildCourseQuery;
use \Exception;
use \PDO;
use Map\CourseTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'course_tbl' table.
 *
 *
 *
 * @method     ChildCourseQuery orderByCourseId($order = Criteria::ASC) Order by the course_id column
 * @method     ChildCourseQuery orderByDeptId($order = Criteria::ASC) Order by the dept_id column
 * @method     ChildCourseQuery orderByPeriod($order = Criteria::ASC) Order by the period column
 * @method     ChildCourseQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildCourseQuery orderByStaff($order = Criteria::ASC) Order by the staff column
 * @method     ChildCourseQuery orderByEcts($order = Criteria::ASC) Order by the ects column
 *
 * @method     ChildCourseQuery groupByCourseId() Group by the course_id column
 * @method     ChildCourseQuery groupByDeptId() Group by the dept_id column
 * @method     ChildCourseQuery groupByPeriod() Group by the period column
 * @method     ChildCourseQuery groupByName() Group by the name column
 * @method     ChildCourseQuery groupByStaff() Group by the staff column
 * @method     ChildCourseQuery groupByEcts() Group by the ects column
 *
 * @method     ChildCourseQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCourseQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCourseQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCourseQuery leftJoinSemester($relationAlias = null) Adds a LEFT JOIN clause to the query using the Semester relation
 * @method     ChildCourseQuery rightJoinSemester($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Semester relation
 * @method     ChildCourseQuery innerJoinSemester($relationAlias = null) Adds a INNER JOIN clause to the query using the Semester relation
 *
 * @method     ChildCourseQuery leftJoinCourseBook($relationAlias = null) Adds a LEFT JOIN clause to the query using the CourseBook relation
 * @method     ChildCourseQuery rightJoinCourseBook($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CourseBook relation
 * @method     ChildCourseQuery innerJoinCourseBook($relationAlias = null) Adds a INNER JOIN clause to the query using the CourseBook relation
 *
 * @method     ChildCourse findOne(ConnectionInterface $con = null) Return the first ChildCourse matching the query
 * @method     ChildCourse findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCourse matching the query, or a new ChildCourse object populated from the query conditions when no match is found
 *
 * @method     ChildCourse findOneByCourseId(int $course_id) Return the first ChildCourse filtered by the course_id column
 * @method     ChildCourse findOneByDeptId(int $dept_id) Return the first ChildCourse filtered by the dept_id column
 * @method     ChildCourse findOneByPeriod(int $period) Return the first ChildCourse filtered by the period column
 * @method     ChildCourse findOneByName(string $name) Return the first ChildCourse filtered by the name column
 * @method     ChildCourse findOneByStaff(string $staff) Return the first ChildCourse filtered by the staff column
 * @method     ChildCourse findOneByEcts(int $ects) Return the first ChildCourse filtered by the ects column
 *
 * @method     array findByCourseId(int $course_id) Return ChildCourse objects filtered by the course_id column
 * @method     array findByDeptId(int $dept_id) Return ChildCourse objects filtered by the dept_id column
 * @method     array findByPeriod(int $period) Return ChildCourse objects filtered by the period column
 * @method     array findByName(string $name) Return ChildCourse objects filtered by the name column
 * @method     array findByStaff(string $staff) Return ChildCourse objects filtered by the staff column
 * @method     array findByEcts(int $ects) Return ChildCourse objects filtered by the ects column
 *
 */
abstract class CourseQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\CourseQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'ioankabi_eam', $modelName = '\\Course', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCourseQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCourseQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \CourseQuery) {
            return $criteria;
        }
        $query = new \CourseQuery();
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
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildCourse|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CourseTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CourseTableMap::DATABASE_NAME);
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
     * @return   ChildCourse A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT COURSE_ID, DEPT_ID, PERIOD, NAME, STAFF, ECTS FROM course_tbl WHERE COURSE_ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildCourse();
            $obj->hydrate($row);
            CourseTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildCourse|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(12, 56, 832), $con);
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
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CourseTableMap::COURSE_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CourseTableMap::COURSE_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the course_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCourseId(1234); // WHERE course_id = 1234
     * $query->filterByCourseId(array(12, 34)); // WHERE course_id IN (12, 34)
     * $query->filterByCourseId(array('min' => 12)); // WHERE course_id > 12
     * </code>
     *
     * @param     mixed $courseId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function filterByCourseId($courseId = null, $comparison = null)
    {
        if (is_array($courseId)) {
            $useMinMax = false;
            if (isset($courseId['min'])) {
                $this->addUsingAlias(CourseTableMap::COURSE_ID, $courseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($courseId['max'])) {
                $this->addUsingAlias(CourseTableMap::COURSE_ID, $courseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COURSE_ID, $courseId, $comparison);
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
     * @see       filterBySemester()
     *
     * @param     mixed $deptId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function filterByDeptId($deptId = null, $comparison = null)
    {
        if (is_array($deptId)) {
            $useMinMax = false;
            if (isset($deptId['min'])) {
                $this->addUsingAlias(CourseTableMap::DEPT_ID, $deptId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deptId['max'])) {
                $this->addUsingAlias(CourseTableMap::DEPT_ID, $deptId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::DEPT_ID, $deptId, $comparison);
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
     * @see       filterBySemester()
     *
     * @param     mixed $period The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function filterByPeriod($period = null, $comparison = null)
    {
        if (is_array($period)) {
            $useMinMax = false;
            if (isset($period['min'])) {
                $this->addUsingAlias(CourseTableMap::PERIOD, $period['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($period['max'])) {
                $this->addUsingAlias(CourseTableMap::PERIOD, $period['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::PERIOD, $period, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CourseTableMap::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the staff column
     *
     * Example usage:
     * <code>
     * $query->filterByStaff('fooValue');   // WHERE staff = 'fooValue'
     * $query->filterByStaff('%fooValue%'); // WHERE staff LIKE '%fooValue%'
     * </code>
     *
     * @param     string $staff The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function filterByStaff($staff = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($staff)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $staff)) {
                $staff = str_replace('*', '%', $staff);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CourseTableMap::STAFF, $staff, $comparison);
    }

    /**
     * Filter the query on the ects column
     *
     * Example usage:
     * <code>
     * $query->filterByEcts(1234); // WHERE ects = 1234
     * $query->filterByEcts(array(12, 34)); // WHERE ects IN (12, 34)
     * $query->filterByEcts(array('min' => 12)); // WHERE ects > 12
     * </code>
     *
     * @param     mixed $ects The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function filterByEcts($ects = null, $comparison = null)
    {
        if (is_array($ects)) {
            $useMinMax = false;
            if (isset($ects['min'])) {
                $this->addUsingAlias(CourseTableMap::ECTS, $ects['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ects['max'])) {
                $this->addUsingAlias(CourseTableMap::ECTS, $ects['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::ECTS, $ects, $comparison);
    }

    /**
     * Filter the query by a related \Semester object
     *
     * @param \Semester $semester The related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function filterBySemester($semester, $comparison = null)
    {
        if ($semester instanceof \Semester) {
            return $this
                ->addUsingAlias(CourseTableMap::DEPT_ID, $semester->getDeptId(), $comparison)
                ->addUsingAlias(CourseTableMap::PERIOD, $semester->getPeriod(), $comparison);
        } else {
            throw new PropelException('filterBySemester() only accepts arguments of type \Semester');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Semester relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function joinSemester($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Semester');

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
            $this->addJoinObject($join, 'Semester');
        }

        return $this;
    }

    /**
     * Use the Semester relation Semester object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \SemesterQuery A secondary query class using the current class as primary query
     */
    public function useSemesterQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSemester($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Semester', '\SemesterQuery');
    }

    /**
     * Filter the query by a related \CourseBook object
     *
     * @param \CourseBook|ObjectCollection $courseBook  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function filterByCourseBook($courseBook, $comparison = null)
    {
        if ($courseBook instanceof \CourseBook) {
            return $this
                ->addUsingAlias(CourseTableMap::COURSE_ID, $courseBook->getCourseId(), $comparison);
        } elseif ($courseBook instanceof ObjectCollection) {
            return $this
                ->useCourseBookQuery()
                ->filterByPrimaryKeys($courseBook->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCourseBook() only accepts arguments of type \CourseBook or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CourseBook relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function joinCourseBook($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CourseBook');

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
            $this->addJoinObject($join, 'CourseBook');
        }

        return $this;
    }

    /**
     * Use the CourseBook relation CourseBook object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CourseBookQuery A secondary query class using the current class as primary query
     */
    public function useCourseBookQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCourseBook($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CourseBook', '\CourseBookQuery');
    }

    /**
     * Filter the query by a related Book object
     * using the course_book_tbl table as cross reference
     *
     * @param Book $book the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function filterByBook($book, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCourseBookQuery()
            ->filterByBook($book, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCourse $course Object to remove from the list of results
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function prune($course = null)
    {
        if ($course) {
            $this->addUsingAlias(CourseTableMap::COURSE_ID, $course->getCourseId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the course_tbl table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CourseTableMap::DATABASE_NAME);
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
            CourseTableMap::clearInstancePool();
            CourseTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildCourse or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildCourse object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CourseTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CourseTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        CourseTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CourseTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // CourseQuery
