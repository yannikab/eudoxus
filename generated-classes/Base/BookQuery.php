<?php

namespace Base;

use \Book as ChildBook;
use \BookQuery as ChildBookQuery;
use \Exception;
use \PDO;
use Map\BookTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'book_tbl' table.
 *
 *
 *
 * @method     ChildBookQuery orderByBookId($order = Criteria::ASC) Order by the book_id column
 * @method     ChildBookQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method     ChildBookQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     ChildBookQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildBookQuery orderByAuthor($order = Criteria::ASC) Order by the author column
 * @method     ChildBookQuery orderByPages($order = Criteria::ASC) Order by the pages column
 * @method     ChildBookQuery orderByIsbn($order = Criteria::ASC) Order by the isbn column
 * @method     ChildBookQuery orderByAvailable($order = Criteria::ASC) Order by the available column
 * @method     ChildBookQuery orderByCover($order = Criteria::ASC) Order by the cover column
 *
 * @method     ChildBookQuery groupByBookId() Group by the book_id column
 * @method     ChildBookQuery groupByPublisherId() Group by the publisher_id column
 * @method     ChildBookQuery groupByCode() Group by the code column
 * @method     ChildBookQuery groupByTitle() Group by the title column
 * @method     ChildBookQuery groupByAuthor() Group by the author column
 * @method     ChildBookQuery groupByPages() Group by the pages column
 * @method     ChildBookQuery groupByIsbn() Group by the isbn column
 * @method     ChildBookQuery groupByAvailable() Group by the available column
 * @method     ChildBookQuery groupByCover() Group by the cover column
 *
 * @method     ChildBookQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildBookQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildBookQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildBookQuery leftJoinPublisher($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publisher relation
 * @method     ChildBookQuery rightJoinPublisher($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publisher relation
 * @method     ChildBookQuery innerJoinPublisher($relationAlias = null) Adds a INNER JOIN clause to the query using the Publisher relation
 *
 * @method     ChildBookQuery leftJoinCourseBook($relationAlias = null) Adds a LEFT JOIN clause to the query using the CourseBook relation
 * @method     ChildBookQuery rightJoinCourseBook($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CourseBook relation
 * @method     ChildBookQuery innerJoinCourseBook($relationAlias = null) Adds a INNER JOIN clause to the query using the CourseBook relation
 *
 * @method     ChildBookQuery leftJoinUserStudentBook($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserStudentBook relation
 * @method     ChildBookQuery rightJoinUserStudentBook($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserStudentBook relation
 * @method     ChildBookQuery innerJoinUserStudentBook($relationAlias = null) Adds a INNER JOIN clause to the query using the UserStudentBook relation
 *
 * @method     ChildBook findOne(ConnectionInterface $con = null) Return the first ChildBook matching the query
 * @method     ChildBook findOneOrCreate(ConnectionInterface $con = null) Return the first ChildBook matching the query, or a new ChildBook object populated from the query conditions when no match is found
 *
 * @method     ChildBook findOneByBookId(int $book_id) Return the first ChildBook filtered by the book_id column
 * @method     ChildBook findOneByPublisherId(int $publisher_id) Return the first ChildBook filtered by the publisher_id column
 * @method     ChildBook findOneByCode(string $code) Return the first ChildBook filtered by the code column
 * @method     ChildBook findOneByTitle(string $title) Return the first ChildBook filtered by the title column
 * @method     ChildBook findOneByAuthor(string $author) Return the first ChildBook filtered by the author column
 * @method     ChildBook findOneByPages(int $pages) Return the first ChildBook filtered by the pages column
 * @method     ChildBook findOneByIsbn(string $isbn) Return the first ChildBook filtered by the isbn column
 * @method     ChildBook findOneByAvailable(boolean $available) Return the first ChildBook filtered by the available column
 * @method     ChildBook findOneByCover(resource $cover) Return the first ChildBook filtered by the cover column
 *
 * @method     array findByBookId(int $book_id) Return ChildBook objects filtered by the book_id column
 * @method     array findByPublisherId(int $publisher_id) Return ChildBook objects filtered by the publisher_id column
 * @method     array findByCode(string $code) Return ChildBook objects filtered by the code column
 * @method     array findByTitle(string $title) Return ChildBook objects filtered by the title column
 * @method     array findByAuthor(string $author) Return ChildBook objects filtered by the author column
 * @method     array findByPages(int $pages) Return ChildBook objects filtered by the pages column
 * @method     array findByIsbn(string $isbn) Return ChildBook objects filtered by the isbn column
 * @method     array findByAvailable(boolean $available) Return ChildBook objects filtered by the available column
 * @method     array findByCover(resource $cover) Return ChildBook objects filtered by the cover column
 *
 */
abstract class BookQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\BookQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'ioankabi_eam', $modelName = '\\Book', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildBookQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildBookQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \BookQuery) {
            return $criteria;
        }
        $query = new \BookQuery();
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
     * @return ChildBook|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = BookTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(BookTableMap::DATABASE_NAME);
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
     * @return   ChildBook A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT BOOK_ID, PUBLISHER_ID, CODE, TITLE, AUTHOR, PAGES, ISBN, AVAILABLE, COVER FROM book_tbl WHERE BOOK_ID = :p0';
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
            $obj = new ChildBook();
            $obj->hydrate($row);
            BookTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildBook|array|mixed the result, formatted by the current formatter
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
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(BookTableMap::BOOK_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(BookTableMap::BOOK_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the book_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBookId(1234); // WHERE book_id = 1234
     * $query->filterByBookId(array(12, 34)); // WHERE book_id IN (12, 34)
     * $query->filterByBookId(array('min' => 12)); // WHERE book_id > 12
     * </code>
     *
     * @param     mixed $bookId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByBookId($bookId = null, $comparison = null)
    {
        if (is_array($bookId)) {
            $useMinMax = false;
            if (isset($bookId['min'])) {
                $this->addUsingAlias(BookTableMap::BOOK_ID, $bookId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bookId['max'])) {
                $this->addUsingAlias(BookTableMap::BOOK_ID, $bookId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookTableMap::BOOK_ID, $bookId, $comparison);
    }

    /**
     * Filter the query on the publisher_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisherId(1234); // WHERE publisher_id = 1234
     * $query->filterByPublisherId(array(12, 34)); // WHERE publisher_id IN (12, 34)
     * $query->filterByPublisherId(array('min' => 12)); // WHERE publisher_id > 12
     * </code>
     *
     * @see       filterByPublisher()
     *
     * @param     mixed $publisherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, $comparison = null)
    {
        if (is_array($publisherId)) {
            $useMinMax = false;
            if (isset($publisherId['min'])) {
                $this->addUsingAlias(BookTableMap::PUBLISHER_ID, $publisherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherId['max'])) {
                $this->addUsingAlias(BookTableMap::PUBLISHER_ID, $publisherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookTableMap::PUBLISHER_ID, $publisherId, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(BookTableMap::CODE, $code, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(BookTableMap::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the author column
     *
     * Example usage:
     * <code>
     * $query->filterByAuthor('fooValue');   // WHERE author = 'fooValue'
     * $query->filterByAuthor('%fooValue%'); // WHERE author LIKE '%fooValue%'
     * </code>
     *
     * @param     string $author The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByAuthor($author = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($author)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $author)) {
                $author = str_replace('*', '%', $author);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(BookTableMap::AUTHOR, $author, $comparison);
    }

    /**
     * Filter the query on the pages column
     *
     * Example usage:
     * <code>
     * $query->filterByPages(1234); // WHERE pages = 1234
     * $query->filterByPages(array(12, 34)); // WHERE pages IN (12, 34)
     * $query->filterByPages(array('min' => 12)); // WHERE pages > 12
     * </code>
     *
     * @param     mixed $pages The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByPages($pages = null, $comparison = null)
    {
        if (is_array($pages)) {
            $useMinMax = false;
            if (isset($pages['min'])) {
                $this->addUsingAlias(BookTableMap::PAGES, $pages['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pages['max'])) {
                $this->addUsingAlias(BookTableMap::PAGES, $pages['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookTableMap::PAGES, $pages, $comparison);
    }

    /**
     * Filter the query on the isbn column
     *
     * Example usage:
     * <code>
     * $query->filterByIsbn('fooValue');   // WHERE isbn = 'fooValue'
     * $query->filterByIsbn('%fooValue%'); // WHERE isbn LIKE '%fooValue%'
     * </code>
     *
     * @param     string $isbn The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByIsbn($isbn = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($isbn)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $isbn)) {
                $isbn = str_replace('*', '%', $isbn);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(BookTableMap::ISBN, $isbn, $comparison);
    }

    /**
     * Filter the query on the available column
     *
     * Example usage:
     * <code>
     * $query->filterByAvailable(true); // WHERE available = true
     * $query->filterByAvailable('yes'); // WHERE available = true
     * </code>
     *
     * @param     boolean|string $available The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByAvailable($available = null, $comparison = null)
    {
        if (is_string($available)) {
            $available = in_array(strtolower($available), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(BookTableMap::AVAILABLE, $available, $comparison);
    }

    /**
     * Filter the query on the cover column
     *
     * @param     mixed $cover The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByCover($cover = null, $comparison = null)
    {

        return $this->addUsingAlias(BookTableMap::COVER, $cover, $comparison);
    }

    /**
     * Filter the query by a related \Publisher object
     *
     * @param \Publisher|ObjectCollection $publisher The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByPublisher($publisher, $comparison = null)
    {
        if ($publisher instanceof \Publisher) {
            return $this
                ->addUsingAlias(BookTableMap::PUBLISHER_ID, $publisher->getPublisherId(), $comparison);
        } elseif ($publisher instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(BookTableMap::PUBLISHER_ID, $publisher->toKeyValue('PrimaryKey', 'PublisherId'), $comparison);
        } else {
            throw new PropelException('filterByPublisher() only accepts arguments of type \Publisher or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Publisher relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function joinPublisher($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Publisher');

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
            $this->addJoinObject($join, 'Publisher');
        }

        return $this;
    }

    /**
     * Use the Publisher relation Publisher object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PublisherQuery A secondary query class using the current class as primary query
     */
    public function usePublisherQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublisher($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publisher', '\PublisherQuery');
    }

    /**
     * Filter the query by a related \CourseBook object
     *
     * @param \CourseBook|ObjectCollection $courseBook  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByCourseBook($courseBook, $comparison = null)
    {
        if ($courseBook instanceof \CourseBook) {
            return $this
                ->addUsingAlias(BookTableMap::BOOK_ID, $courseBook->getBookId(), $comparison);
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
     * @return ChildBookQuery The current query, for fluid interface
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
     * Filter the query by a related \UserStudentBook object
     *
     * @param \UserStudentBook|ObjectCollection $userStudentBook  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByUserStudentBook($userStudentBook, $comparison = null)
    {
        if ($userStudentBook instanceof \UserStudentBook) {
            return $this
                ->addUsingAlias(BookTableMap::BOOK_ID, $userStudentBook->getBookId(), $comparison);
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
     * @return ChildBookQuery The current query, for fluid interface
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
     * Filter the query by a related Course object
     * using the course_book_tbl table as cross reference
     *
     * @param Course $course the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByCourse($course, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCourseBookQuery()
            ->filterByCourse($course, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related UserStudent object
     * using the userstudent_book_tbl table as cross reference
     *
     * @param UserStudent $userStudent the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function filterByUserStudent($userStudent, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserStudentBookQuery()
            ->filterByUserStudent($userStudent, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildBook $book Object to remove from the list of results
     *
     * @return ChildBookQuery The current query, for fluid interface
     */
    public function prune($book = null)
    {
        if ($book) {
            $this->addUsingAlias(BookTableMap::BOOK_ID, $book->getBookId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the book_tbl table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BookTableMap::DATABASE_NAME);
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
            BookTableMap::clearInstancePool();
            BookTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildBook or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildBook object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(BookTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(BookTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        BookTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            BookTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // BookQuery
