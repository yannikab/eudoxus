<?php

namespace Base;

use \Group as ChildGroup;
use \GroupQuery as ChildGroupQuery;
use \Exception;
use \PDO;
use Map\GroupTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'group_tbl' table.
 *
 *
 *
 * @method     ChildGroupQuery orderByGroupId($order = Criteria::ASC) Order by the group_id column
 * @method     ChildGroupQuery orderByAlias($order = Criteria::ASC) Order by the alias column
 * @method     ChildGroupQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method     ChildGroupQuery groupByGroupId() Group by the group_id column
 * @method     ChildGroupQuery groupByAlias() Group by the alias column
 * @method     ChildGroupQuery groupByName() Group by the name column
 *
 * @method     ChildGroupQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildGroupQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildGroupQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildGroupQuery leftJoinUserStudent($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserStudent relation
 * @method     ChildGroupQuery rightJoinUserStudent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserStudent relation
 * @method     ChildGroupQuery innerJoinUserStudent($relationAlias = null) Adds a INNER JOIN clause to the query using the UserStudent relation
 *
 * @method     ChildGroupQuery leftJoinUserPublisher($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserPublisher relation
 * @method     ChildGroupQuery rightJoinUserPublisher($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserPublisher relation
 * @method     ChildGroupQuery innerJoinUserPublisher($relationAlias = null) Adds a INNER JOIN clause to the query using the UserPublisher relation
 *
 * @method     ChildGroupQuery leftJoinUserSecretariat($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserSecretariat relation
 * @method     ChildGroupQuery rightJoinUserSecretariat($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserSecretariat relation
 * @method     ChildGroupQuery innerJoinUserSecretariat($relationAlias = null) Adds a INNER JOIN clause to the query using the UserSecretariat relation
 *
 * @method     ChildGroupQuery leftJoinFaq($relationAlias = null) Adds a LEFT JOIN clause to the query using the Faq relation
 * @method     ChildGroupQuery rightJoinFaq($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Faq relation
 * @method     ChildGroupQuery innerJoinFaq($relationAlias = null) Adds a INNER JOIN clause to the query using the Faq relation
 *
 * @method     ChildGroup findOne(ConnectionInterface $con = null) Return the first ChildGroup matching the query
 * @method     ChildGroup findOneOrCreate(ConnectionInterface $con = null) Return the first ChildGroup matching the query, or a new ChildGroup object populated from the query conditions when no match is found
 *
 * @method     ChildGroup findOneByGroupId(int $group_id) Return the first ChildGroup filtered by the group_id column
 * @method     ChildGroup findOneByAlias(string $alias) Return the first ChildGroup filtered by the alias column
 * @method     ChildGroup findOneByName(string $name) Return the first ChildGroup filtered by the name column
 *
 * @method     array findByGroupId(int $group_id) Return ChildGroup objects filtered by the group_id column
 * @method     array findByAlias(string $alias) Return ChildGroup objects filtered by the alias column
 * @method     array findByName(string $name) Return ChildGroup objects filtered by the name column
 *
 */
abstract class GroupQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\GroupQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'ioankabi_eam', $modelName = '\\Group', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildGroupQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildGroupQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \GroupQuery) {
            return $criteria;
        }
        $query = new \GroupQuery();
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
     * @return ChildGroup|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = GroupTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GroupTableMap::DATABASE_NAME);
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
     * @return   ChildGroup A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT GROUP_ID, ALIAS, NAME FROM group_tbl WHERE GROUP_ID = :p0';
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
            $obj = new ChildGroup();
            $obj->hydrate($row);
            GroupTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildGroup|array|mixed the result, formatted by the current formatter
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
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(GroupTableMap::GROUP_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(GroupTableMap::GROUP_ID, $keys, Criteria::IN);
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
     * @param     mixed $groupId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterByGroupId($groupId = null, $comparison = null)
    {
        if (is_array($groupId)) {
            $useMinMax = false;
            if (isset($groupId['min'])) {
                $this->addUsingAlias(GroupTableMap::GROUP_ID, $groupId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupId['max'])) {
                $this->addUsingAlias(GroupTableMap::GROUP_ID, $groupId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupTableMap::GROUP_ID, $groupId, $comparison);
    }

    /**
     * Filter the query on the alias column
     *
     * Example usage:
     * <code>
     * $query->filterByAlias('fooValue');   // WHERE alias = 'fooValue'
     * $query->filterByAlias('%fooValue%'); // WHERE alias LIKE '%fooValue%'
     * </code>
     *
     * @param     string $alias The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterByAlias($alias = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($alias)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $alias)) {
                $alias = str_replace('*', '%', $alias);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(GroupTableMap::ALIAS, $alias, $comparison);
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
     * @return ChildGroupQuery The current query, for fluid interface
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

        return $this->addUsingAlias(GroupTableMap::NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related \UserStudent object
     *
     * @param \UserStudent|ObjectCollection $userStudent  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterByUserStudent($userStudent, $comparison = null)
    {
        if ($userStudent instanceof \UserStudent) {
            return $this
                ->addUsingAlias(GroupTableMap::GROUP_ID, $userStudent->getGroupId(), $comparison);
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
     * @return ChildGroupQuery The current query, for fluid interface
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
     * Filter the query by a related \UserPublisher object
     *
     * @param \UserPublisher|ObjectCollection $userPublisher  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterByUserPublisher($userPublisher, $comparison = null)
    {
        if ($userPublisher instanceof \UserPublisher) {
            return $this
                ->addUsingAlias(GroupTableMap::GROUP_ID, $userPublisher->getGroupId(), $comparison);
        } elseif ($userPublisher instanceof ObjectCollection) {
            return $this
                ->useUserPublisherQuery()
                ->filterByPrimaryKeys($userPublisher->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserPublisher() only accepts arguments of type \UserPublisher or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserPublisher relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function joinUserPublisher($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserPublisher');

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
            $this->addJoinObject($join, 'UserPublisher');
        }

        return $this;
    }

    /**
     * Use the UserPublisher relation UserPublisher object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserPublisherQuery A secondary query class using the current class as primary query
     */
    public function useUserPublisherQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserPublisher($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserPublisher', '\UserPublisherQuery');
    }

    /**
     * Filter the query by a related \UserSecretariat object
     *
     * @param \UserSecretariat|ObjectCollection $userSecretariat  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterByUserSecretariat($userSecretariat, $comparison = null)
    {
        if ($userSecretariat instanceof \UserSecretariat) {
            return $this
                ->addUsingAlias(GroupTableMap::GROUP_ID, $userSecretariat->getGroupId(), $comparison);
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
     * @return ChildGroupQuery The current query, for fluid interface
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
     * Filter the query by a related \Faq object
     *
     * @param \Faq|ObjectCollection $faq  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function filterByFaq($faq, $comparison = null)
    {
        if ($faq instanceof \Faq) {
            return $this
                ->addUsingAlias(GroupTableMap::GROUP_ID, $faq->getGroupId(), $comparison);
        } elseif ($faq instanceof ObjectCollection) {
            return $this
                ->useFaqQuery()
                ->filterByPrimaryKeys($faq->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFaq() only accepts arguments of type \Faq or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Faq relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function joinFaq($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Faq');

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
            $this->addJoinObject($join, 'Faq');
        }

        return $this;
    }

    /**
     * Use the Faq relation Faq object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \FaqQuery A secondary query class using the current class as primary query
     */
    public function useFaqQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFaq($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Faq', '\FaqQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildGroup $group Object to remove from the list of results
     *
     * @return ChildGroupQuery The current query, for fluid interface
     */
    public function prune($group = null)
    {
        if ($group) {
            $this->addUsingAlias(GroupTableMap::GROUP_ID, $group->getGroupId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the group_tbl table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
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
            GroupTableMap::clearInstancePool();
            GroupTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildGroup or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildGroup object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(GroupTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        GroupTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            GroupTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // GroupQuery
