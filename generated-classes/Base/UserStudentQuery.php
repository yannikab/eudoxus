<?php

namespace Base;

use \UserStudent as ChildUserStudent;
use \UserStudentQuery as ChildUserStudentQuery;
use \Exception;
use \PDO;
use Map\UserStudentTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'userstudent_tbl' table.
 *
 *
 *
 * @method     ChildUserStudentQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildUserStudentQuery orderByGroupId($order = Criteria::ASC) Order by the group_id column
 * @method     ChildUserStudentQuery orderByDeptId($order = Criteria::ASC) Order by the dept_id column
 * @method     ChildUserStudentQuery orderByFirstname($order = Criteria::ASC) Order by the firstname column
 * @method     ChildUserStudentQuery orderByLastname($order = Criteria::ASC) Order by the lastname column
 *
 * @method     ChildUserStudentQuery groupByUserId() Group by the user_id column
 * @method     ChildUserStudentQuery groupByGroupId() Group by the group_id column
 * @method     ChildUserStudentQuery groupByDeptId() Group by the dept_id column
 * @method     ChildUserStudentQuery groupByFirstname() Group by the firstname column
 * @method     ChildUserStudentQuery groupByLastname() Group by the lastname column
 *
 * @method     ChildUserStudentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserStudentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserStudentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserStudentQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildUserStudentQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildUserStudentQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildUserStudentQuery leftJoinGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the Group relation
 * @method     ChildUserStudentQuery rightJoinGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Group relation
 * @method     ChildUserStudentQuery innerJoinGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the Group relation
 *
 * @method     ChildUserStudentQuery leftJoinDepartment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Department relation
 * @method     ChildUserStudentQuery rightJoinDepartment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Department relation
 * @method     ChildUserStudentQuery innerJoinDepartment($relationAlias = null) Adds a INNER JOIN clause to the query using the Department relation
 *
 * @method     ChildUserStudentQuery leftJoinUserStudentBook($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserStudentBook relation
 * @method     ChildUserStudentQuery rightJoinUserStudentBook($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserStudentBook relation
 * @method     ChildUserStudentQuery innerJoinUserStudentBook($relationAlias = null) Adds a INNER JOIN clause to the query using the UserStudentBook relation
 *
 * @method     ChildUserStudent findOne(ConnectionInterface $con = null) Return the first ChildUserStudent matching the query
 * @method     ChildUserStudent findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUserStudent matching the query, or a new ChildUserStudent object populated from the query conditions when no match is found
 *
 * @method     ChildUserStudent findOneByUserId(int $user_id) Return the first ChildUserStudent filtered by the user_id column
 * @method     ChildUserStudent findOneByGroupId(int $group_id) Return the first ChildUserStudent filtered by the group_id column
 * @method     ChildUserStudent findOneByDeptId(int $dept_id) Return the first ChildUserStudent filtered by the dept_id column
 * @method     ChildUserStudent findOneByFirstname(string $firstname) Return the first ChildUserStudent filtered by the firstname column
 * @method     ChildUserStudent findOneByLastname(string $lastname) Return the first ChildUserStudent filtered by the lastname column
 *
 * @method     array findByUserId(int $user_id) Return ChildUserStudent objects filtered by the user_id column
 * @method     array findByGroupId(int $group_id) Return ChildUserStudent objects filtered by the group_id column
 * @method     array findByDeptId(int $dept_id) Return ChildUserStudent objects filtered by the dept_id column
 * @method     array findByFirstname(string $firstname) Return ChildUserStudent objects filtered by the firstname column
 * @method     array findByLastname(string $lastname) Return ChildUserStudent objects filtered by the lastname column
 *
 */
abstract class UserStudentQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\UserStudentQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'ioankabi_eam', $modelName = '\\UserStudent', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserStudentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserStudentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \UserStudentQuery) {
            return $criteria;
        }
        $query = new \UserStudentQuery();
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
     * @return ChildUserStudent|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserStudentTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserStudentTableMap::DATABASE_NAME);
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
     * @return   ChildUserStudent A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT USER_ID, GROUP_ID, DEPT_ID, FIRSTNAME, LASTNAME FROM userstudent_tbl WHERE USER_ID = :p0';
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
            $obj = new ChildUserStudent();
            $obj->hydrate($row);
            UserStudentTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildUserStudent|array|mixed the result, formatted by the current formatter
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
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserStudentTableMap::USER_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserStudentTableMap::USER_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(UserStudentTableMap::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(UserStudentTableMap::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserStudentTableMap::USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the group_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGroupId(1234); // WHERE group_id = 1234
     * $query->filterByGroupId(array(12, 34)); // WHERE group_id IN (12, 34)
     * $query->filterByGroupId(array('min' => 12)); // WHERE group_id > 12
     * </code>
     *
     * @see       filterByGroup()
     *
     * @param     mixed $groupId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function filterByGroupId($groupId = null, $comparison = null)
    {
        if (is_array($groupId)) {
            $useMinMax = false;
            if (isset($groupId['min'])) {
                $this->addUsingAlias(UserStudentTableMap::GROUP_ID, $groupId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupId['max'])) {
                $this->addUsingAlias(UserStudentTableMap::GROUP_ID, $groupId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserStudentTableMap::GROUP_ID, $groupId, $comparison);
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
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function filterByDeptId($deptId = null, $comparison = null)
    {
        if (is_array($deptId)) {
            $useMinMax = false;
            if (isset($deptId['min'])) {
                $this->addUsingAlias(UserStudentTableMap::DEPT_ID, $deptId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deptId['max'])) {
                $this->addUsingAlias(UserStudentTableMap::DEPT_ID, $deptId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserStudentTableMap::DEPT_ID, $deptId, $comparison);
    }

    /**
     * Filter the query on the firstname column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstname('fooValue');   // WHERE firstname = 'fooValue'
     * $query->filterByFirstname('%fooValue%'); // WHERE firstname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function filterByFirstname($firstname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstname)) {
                $firstname = str_replace('*', '%', $firstname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserStudentTableMap::FIRSTNAME, $firstname, $comparison);
    }

    /**
     * Filter the query on the lastname column
     *
     * Example usage:
     * <code>
     * $query->filterByLastname('fooValue');   // WHERE lastname = 'fooValue'
     * $query->filterByLastname('%fooValue%'); // WHERE lastname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function filterByLastname($lastname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastname)) {
                $lastname = str_replace('*', '%', $lastname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserStudentTableMap::LASTNAME, $lastname, $comparison);
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(UserStudentTableMap::USER_ID, $user->getUserId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserStudentTableMap::USER_ID, $user->toKeyValue('PrimaryKey', 'UserId'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\UserQuery');
    }

    /**
     * Filter the query by a related \Group object
     *
     * @param \Group|ObjectCollection $group The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function filterByGroup($group, $comparison = null)
    {
        if ($group instanceof \Group) {
            return $this
                ->addUsingAlias(UserStudentTableMap::GROUP_ID, $group->getGroupId(), $comparison);
        } elseif ($group instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserStudentTableMap::GROUP_ID, $group->toKeyValue('PrimaryKey', 'GroupId'), $comparison);
        } else {
            throw new PropelException('filterByGroup() only accepts arguments of type \Group or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Group relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function joinGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Group');

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
            $this->addJoinObject($join, 'Group');
        }

        return $this;
    }

    /**
     * Use the Group relation Group object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GroupQuery A secondary query class using the current class as primary query
     */
    public function useGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Group', '\GroupQuery');
    }

    /**
     * Filter the query by a related \Department object
     *
     * @param \Department|ObjectCollection $department The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function filterByDepartment($department, $comparison = null)
    {
        if ($department instanceof \Department) {
            return $this
                ->addUsingAlias(UserStudentTableMap::DEPT_ID, $department->getDeptId(), $comparison);
        } elseif ($department instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserStudentTableMap::DEPT_ID, $department->toKeyValue('PrimaryKey', 'DeptId'), $comparison);
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
     * @return ChildUserStudentQuery The current query, for fluid interface
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
     * Filter the query by a related \UserStudentBook object
     *
     * @param \UserStudentBook|ObjectCollection $userStudentBook  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function filterByUserStudentBook($userStudentBook, $comparison = null)
    {
        if ($userStudentBook instanceof \UserStudentBook) {
            return $this
                ->addUsingAlias(UserStudentTableMap::USER_ID, $userStudentBook->getUserId(), $comparison);
        } elseif ($userStudentBook instanceof ObjectCollection) {
            return $this
                ->useUserStudentBookQuery()
                ->filterByPrimaryKeys($userStudentBook->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserStudentBook() only accepts arguments of type \UserStudentBook or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserStudentBook relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function joinUserStudentBook($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserStudentBook');

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
            $this->addJoinObject($join, 'UserStudentBook');
        }

        return $this;
    }

    /**
     * Use the UserStudentBook relation UserStudentBook object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserStudentBookQuery A secondary query class using the current class as primary query
     */
    public function useUserStudentBookQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserStudentBook($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserStudentBook', '\UserStudentBookQuery');
    }

    /**
     * Filter the query by a related Book object
     * using the userstudent_book_tbl table as cross reference
     *
     * @param Book $book the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function filterByBook($book, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserStudentBookQuery()
            ->filterByBook($book, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUserStudent $userStudent Object to remove from the list of results
     *
     * @return ChildUserStudentQuery The current query, for fluid interface
     */
    public function prune($userStudent = null)
    {
        if ($userStudent) {
            $this->addUsingAlias(UserStudentTableMap::USER_ID, $userStudent->getUserId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the userstudent_tbl table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserStudentTableMap::DATABASE_NAME);
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
            UserStudentTableMap::clearInstancePool();
            UserStudentTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildUserStudent or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildUserStudent object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserStudentTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserStudentTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        UserStudentTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserStudentTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // UserStudentQuery
