<?php

namespace Base;

use \Department as ChildDepartment;
use \DepartmentQuery as ChildDepartmentQuery;
use \Exception;
use \PDO;
use Map\DepartmentTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'dept_tbl' table.
 *
 *
 *
 * @method     ChildDepartmentQuery orderByDeptId($order = Criteria::ASC) Order by the dept_id column
 * @method     ChildDepartmentQuery orderByInstId($order = Criteria::ASC) Order by the inst_id column
 * @method     ChildDepartmentQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method     ChildDepartmentQuery groupByDeptId() Group by the dept_id column
 * @method     ChildDepartmentQuery groupByInstId() Group by the inst_id column
 * @method     ChildDepartmentQuery groupByName() Group by the name column
 *
 * @method     ChildDepartmentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDepartmentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDepartmentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDepartmentQuery leftJoinInstitution($relationAlias = null) Adds a LEFT JOIN clause to the query using the Institution relation
 * @method     ChildDepartmentQuery rightJoinInstitution($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Institution relation
 * @method     ChildDepartmentQuery innerJoinInstitution($relationAlias = null) Adds a INNER JOIN clause to the query using the Institution relation
 *
 * @method     ChildDepartmentQuery leftJoinUserStudent($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserStudent relation
 * @method     ChildDepartmentQuery rightJoinUserStudent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserStudent relation
 * @method     ChildDepartmentQuery innerJoinUserStudent($relationAlias = null) Adds a INNER JOIN clause to the query using the UserStudent relation
 *
 * @method     ChildDepartmentQuery leftJoinUserSecretariat($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserSecretariat relation
 * @method     ChildDepartmentQuery rightJoinUserSecretariat($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserSecretariat relation
 * @method     ChildDepartmentQuery innerJoinUserSecretariat($relationAlias = null) Adds a INNER JOIN clause to the query using the UserSecretariat relation
 *
 * @method     ChildDepartmentQuery leftJoinSemester($relationAlias = null) Adds a LEFT JOIN clause to the query using the Semester relation
 * @method     ChildDepartmentQuery rightJoinSemester($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Semester relation
 * @method     ChildDepartmentQuery innerJoinSemester($relationAlias = null) Adds a INNER JOIN clause to the query using the Semester relation
 *
 * @method     ChildDepartment findOne(ConnectionInterface $con = null) Return the first ChildDepartment matching the query
 * @method     ChildDepartment findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDepartment matching the query, or a new ChildDepartment object populated from the query conditions when no match is found
 *
 * @method     ChildDepartment findOneByDeptId(int $dept_id) Return the first ChildDepartment filtered by the dept_id column
 * @method     ChildDepartment findOneByInstId(int $inst_id) Return the first ChildDepartment filtered by the inst_id column
 * @method     ChildDepartment findOneByName(string $name) Return the first ChildDepartment filtered by the name column
 *
 * @method     array findByDeptId(int $dept_id) Return ChildDepartment objects filtered by the dept_id column
 * @method     array findByInstId(int $inst_id) Return ChildDepartment objects filtered by the inst_id column
 * @method     array findByName(string $name) Return ChildDepartment objects filtered by the name column
 *
 */
abstract class DepartmentQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\DepartmentQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'ioankabi_eam', $modelName = '\\Department', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDepartmentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDepartmentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \DepartmentQuery) {
            return $criteria;
        }
        $query = new \DepartmentQuery();
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
     * @return ChildDepartment|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DepartmentTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DepartmentTableMap::DATABASE_NAME);
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
     * @return   ChildDepartment A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT DEPT_ID, INST_ID, NAME FROM dept_tbl WHERE DEPT_ID = :p0';
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
            $obj = new ChildDepartment();
            $obj->hydrate($row);
            DepartmentTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildDepartment|array|mixed the result, formatted by the current formatter
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
     * @return ChildDepartmentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DepartmentTableMap::DEPT_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildDepartmentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DepartmentTableMap::DEPT_ID, $keys, Criteria::IN);
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
     * @param     mixed $deptId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDepartmentQuery The current query, for fluid interface
     */
    public function filterByDeptId($deptId = null, $comparison = null)
    {
        if (is_array($deptId)) {
            $useMinMax = false;
            if (isset($deptId['min'])) {
                $this->addUsingAlias(DepartmentTableMap::DEPT_ID, $deptId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deptId['max'])) {
                $this->addUsingAlias(DepartmentTableMap::DEPT_ID, $deptId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DepartmentTableMap::DEPT_ID, $deptId, $comparison);
    }

    /**
     * Filter the query on the inst_id column
     *
     * Example usage:
     * <code>
     * $query->filterByInstId(1234); // WHERE inst_id = 1234
     * $query->filterByInstId(array(12, 34)); // WHERE inst_id IN (12, 34)
     * $query->filterByInstId(array('min' => 12)); // WHERE inst_id > 12
     * </code>
     *
     * @see       filterByInstitution()
     *
     * @param     mixed $instId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDepartmentQuery The current query, for fluid interface
     */
    public function filterByInstId($instId = null, $comparison = null)
    {
        if (is_array($instId)) {
            $useMinMax = false;
            if (isset($instId['min'])) {
                $this->addUsingAlias(DepartmentTableMap::INST_ID, $instId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($instId['max'])) {
                $this->addUsingAlias(DepartmentTableMap::INST_ID, $instId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DepartmentTableMap::INST_ID, $instId, $comparison);
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
     * @return ChildDepartmentQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DepartmentTableMap::NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related \Institution object
     *
     * @param \Institution|ObjectCollection $institution The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDepartmentQuery The current query, for fluid interface
     */
    public function filterByInstitution($institution, $comparison = null)
    {
        if ($institution instanceof \Institution) {
            return $this
                ->addUsingAlias(DepartmentTableMap::INST_ID, $institution->getInstId(), $comparison);
        } elseif ($institution instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DepartmentTableMap::INST_ID, $institution->toKeyValue('PrimaryKey', 'InstId'), $comparison);
        } else {
            throw new PropelException('filterByInstitution() only accepts arguments of type \Institution or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Institution relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildDepartmentQuery The current query, for fluid interface
     */
    public function joinInstitution($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Institution');

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
            $this->addJoinObject($join, 'Institution');
        }

        return $this;
    }

    /**
     * Use the Institution relation Institution object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \InstitutionQuery A secondary query class using the current class as primary query
     */
    public function useInstitutionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInstitution($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Institution', '\InstitutionQuery');
    }

    /**
     * Filter the query by a related \UserStudent object
     *
     * @param \UserStudent|ObjectCollection $userStudent  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDepartmentQuery The current query, for fluid interface
     */
    public function filterByUserStudent($userStudent, $comparison = null)
    {
        if ($userStudent instanceof \UserStudent) {
            return $this
                ->addUsingAlias(DepartmentTableMap::DEPT_ID, $userStudent->getDeptId(), $comparison);
        } elseif ($userStudent instanceof ObjectCollection) {
            return $this
                ->useUserStudentQuery()
                ->filterByPrimaryKeys($userStudent->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserStudent() only accepts arguments of type \UserStudent or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserStudent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildDepartmentQuery The current query, for fluid interface
     */
    public function joinUserStudent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserStudent');

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
            $this->addJoinObject($join, 'UserStudent');
        }

        return $this;
    }

    /**
     * Use the UserStudent relation UserStudent object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserStudentQuery A secondary query class using the current class as primary query
     */
    public function useUserStudentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserStudent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserStudent', '\UserStudentQuery');
    }

    /**
     * Filter the query by a related \UserSecretariat object
     *
     * @param \UserSecretariat|ObjectCollection $userSecretariat  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDepartmentQuery The current query, for fluid interface
     */
    public function filterByUserSecretariat($userSecretariat, $comparison = null)
    {
        if ($userSecretariat instanceof \UserSecretariat) {
            return $this
                ->addUsingAlias(DepartmentTableMap::DEPT_ID, $userSecretariat->getDeptId(), $comparison);
        } elseif ($userSecretariat instanceof ObjectCollection) {
            return $this
                ->useUserSecretariatQuery()
                ->filterByPrimaryKeys($userSecretariat->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserSecretariat() only accepts arguments of type \UserSecretariat or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserSecretariat relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildDepartmentQuery The current query, for fluid interface
     */
    public function joinUserSecretariat($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserSecretariat');

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
            $this->addJoinObject($join, 'UserSecretariat');
        }

        return $this;
    }

    /**
     * Use the UserSecretariat relation UserSecretariat object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserSecretariatQuery A secondary query class using the current class as primary query
     */
    public function useUserSecretariatQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserSecretariat($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserSecretariat', '\UserSecretariatQuery');
    }

    /**
     * Filter the query by a related \Semester object
     *
     * @param \Semester|ObjectCollection $semester  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDepartmentQuery The current query, for fluid interface
     */
    public function filterBySemester($semester, $comparison = null)
    {
        if ($semester instanceof \Semester) {
            return $this
                ->addUsingAlias(DepartmentTableMap::DEPT_ID, $semester->getDeptId(), $comparison);
        } elseif ($semester instanceof ObjectCollection) {
            return $this
                ->useSemesterQuery()
                ->filterByPrimaryKeys($semester->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySemester() only accepts arguments of type \Semester or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Semester relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildDepartmentQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildDepartment $department Object to remove from the list of results
     *
     * @return ChildDepartmentQuery The current query, for fluid interface
     */
    public function prune($department = null)
    {
        if ($department) {
            $this->addUsingAlias(DepartmentTableMap::DEPT_ID, $department->getDeptId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the dept_tbl table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DepartmentTableMap::DATABASE_NAME);
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
            DepartmentTableMap::clearInstancePool();
            DepartmentTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildDepartment or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildDepartment object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(DepartmentTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DepartmentTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        DepartmentTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DepartmentTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // DepartmentQuery
