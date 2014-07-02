<?php namespace Webbins\Database;

use Exception;
use PDO;
use PDOException;

require('Join.php');
require('Where.php');
require('OrderBy.php');

class DB {
    const ARRAYS = PDO::FETCH_ASSOC;
    const OBJECTS = PDO::FETCH_OBJ;

    /**
     * Stores the instance of the class.
     * @var  Database
     */
    private static $self;

    /**
     * If connect is false, then a database isn't connected.
     */
    private static $connect = false;

    /**
     * Stores the PDO connection.
     * @var  PDO object
     */
    private static $connection;

    /**
     * Stores all tables.
     * @var  array
     */
    private $selects = array();

    /**
     * Stores all tables.
     * @var  array
     */
    private static $tables = array();

    /**
     * Stores all joins.
     * @var  array
     */
    private $joins = array();

    /**
     * Stores all ons.
     * @var  array
     */
    private $ons = array();

    /**
     * Stores all wheres.
     * @var  array
     */
    private $wheres = array();

    /**
     * Stores all order bys.
     * @var  array
     */
    private $orderBys = array();

    /**
     * Stores the limit value.
     * @var  int
     */
    private $limits = 0;

    /**
     * Stores the offset value.
     * @var  int
     */
    private $offsets = '';

    private static $preparedStatement;

    private $preparedStatements = array();

    private static $lastQuery = '';

    /**
     * Construct. Stores an instance of itself so static
     * methods can use it.
     *
     * Also fetches configs and creates a new PDO connection.
     * @param  string  $driver
     * @param  string  $host
     * @param  string  $database
     * @param  string  $user
     * @param  string  $password
     */
    public function __construct($driver, $host, $database, $user, $password, $connect=true) {
        self::$self = $this;

        self::$connect = $connect;

        if ($connect) {
            $dsn = $driver.':dbname='.$database.';host='.$host;

            self::$connection = new PDO($dsn, $user, $password);
        }
    }

    /**
     * Add one or multiple tables (separated by comma (,))
     * do the tables array.
     * @param   string  $tables
     * @throws  Exception
     * @return  Database
     */
    public static function table($tables) {
        if (!self::$connect) {
            throw new Exception('The database connection is turned off. Switch it on in the config file.');
        }
        $tables = explode(',', $tables);

        foreach ($tables as $table) {
            self::$tables[] = trim($table);
        }

        return self::$self;
    }

    /**
     * Return all tables as a string, separated by comma (,)´.
     * @return  string
     */
    private function getTables() {
        $string = '';

        foreach (self::$tables as $table) {
            $string .= $table.', ';
        }

        return trim($string, ', ');
    }

    /**
     * Join.
     * @param   string  $table
     * @return  DB
     */
    public function join($table) {
        $this->joins[] = new Join($table, Join::JOIN);
        return self::$self;
    }

    /**
     * Inner join.
     * @param   string  $table
     * @return  DB
     */
    public function innerJoin($table) {
        $this->joins[] = new Join($table, Join::INNERJOIN);
        return self::$self;
    }

    /**
     * Outer join.
     * @param   string  $table
     * @return  DB
     */
    public function outerJoin($table) {
        $this->joins[] = new Join($table, Join::OUTERJOIN);
        return self::$self;
    }

    /**
     * Left join.
     * @param   string  $table
     * @return  DB
     */
    public function leftJoin($table) {
        $this->joins[] = new Join($table, Join::LEFTJOIN);
        return self::$self;
    }

    /**
     * Right join.
     * @param   string  $table
     * @return  DB
     */
    public function rightJoin($table) {
        $this->joins[] = new Join($table, Join::RIGHTJOIN);
        return self::$self;
    }

    /**
     * On. Attaches to the latest join.
     * If the second column isn't passed, the method will evalute
     * the first column as the whole "on query" and break it down
     * to two columns with "=" as a separator.
     * @param   string  $column1
     * @param   string  $column2
     * @return  DB
     */
    public function on($column1, $column2='') {
        if (empty($column2)) {
            preg_match('/^(.+?)=(.+?)$/', $column1, $matches);
            $column1 = $matches[1];
            $column2 = $matches[2];
        }
        $this->joins[Count($this->joins)-1]->setOn($column1, $column2);
        return self::$self;
    }

    /**
     * Return all joins and ons as a string.
     * @return  string
     */
    private function getJoins() {
        $string = '';

        foreach ($this->joins as $join) {
            $string .= $join->getType().' '.$join->getTable().' ';
            if ($join->getOn()) {
                $string .= $join->getOn().' ';
            }
        }

        return trim($string);
    }

    /**
     * Set select.
     * @param   string  $column
     * @return  DB
     */
    public function select($column) {
        $this->selects[] = $column;
        return self::$self;
    }

    /**
     * Get selects as a string. If no select has been
     * set, then wildcard "*" will be returned as default.
     * @return  string
     */
    private function getSelects() {
        if (empty($this->selects)) {
            return '*';
        }

        $string = '';

        foreach ($this->selects as $select) {
            $string .= $select.', ';
        }

        return trim($string, ', ');
    }

    /**
     * Where. If no compare operator is set, the method will evalute
     * the string in $column as a single query, then break it down to
     * column, compare operator and value by itself.
     * where('ID=1')
     * where('ID', '=', 1)
     * @param   string  $column
     * @param   string  $compareOperator
     * @param   string  $value
     * @return  DB
     */
    public function where($column, $compareOperator='', $value='') {
        if (empty($compareOperator)) {
            preg_match('/^(.+?)(=|>|<|>=|<=|<>|!=|!<|!>|like)(.+)$/i', $column, $matches);
            $column = $matches[1];
            $compareOperator = $matches[2];
            $value = $matches[3];
        }
        $this->wheres[] = new Where($column, $compareOperator, $value);
        return self::$self;
    }

    /**
     * And where. Calls "where()" and then adds an
     * AND operator.
     * @param   string  $column
     * @param   string  $compareOperator
     * @param   string  $value
     * @return  DB
     */
    public function andWhere($column, $compareOperator='', $value='') {
        $this->where($column, $compareOperator, $value);
        $this->wheres[Count($this->wheres)-1]->setOperator(Where::AND_OPERATOR);
        return self::$self;
    }

    /**
     * Or where. Calls "where()" and then adds an
     * OR operator.
     * @param   string  $column
     * @param   string  $compareOperator
     * @param   string  $value
     * @return  DB
     */
    public function orWhere($column, $compareOperator='', $value='') {
        $this->where($column, $compareOperator, $value);
        $this->wheres[Count($this->wheres)-1]->setOperator(Where::OR_OPERATOR);
        return self::$self;
    }

    /**
     * Returns all wheres as a string.
     * @return  string
     */
    private function getWheres() {
        $string = '';

        foreach ($this->wheres as $where) {
            if ($where->getOperator()) {
                $string .= $where->getOperator().' ';
            }
            $string .= $where->getColumn();
            $string .= ' '.$where->getCompareOperator().' ';
            $string .= self::$connection->quote($where->getValue()).' ';
        }

        $string = ltrim($string, '&&');

        if ($string) {
            return trim('Where '.$string);
        }

        return '';
    }

    /**
     *  Order by.
     *  @param   string  $column
     *  @param   string  $order
     *  @return  DB
     */
    public function orderBy($column, $order) {
        $this->orderBys[] = new OrderBy($column, $order);
        return self::$self;
    }

    /**
     *  Get all order bys and return them as a string.
     *  @return  string
     */
    private function getOrderBys() {
        $string = '';

        foreach ($this->orderBys as $orderBy) {
            $string .= $orderBy->getColumn().' '.$orderBy->getOrder().', ';
        }

        if ($string) {
            return trim('Order by '.$string, ', ');
        }

        return '';
    }

    /**
     *  Limit.
     *  @param   string|int  $limit
     *  @return  DB
     */
    public function limit($limit) {
        $this->limits = $limit;
        return self::$self;
    }

    /**
     *  Get limit as a string.
     *  @return  string
     */
    private function getLimits() {
        if ($this->limits) {
            return 'Limit '.$this->limits;
        }

        return '';
    }

    /**
     *  Offset.
     *  @param   string|int  $offset
     *  @return  DB
     */
    public function offset($offset) {
        $this->offsets = $offset;
        return self::$self;
    }

    /**
     *  Get offset as a string.
     *  @return  string
     */
    private function getOffsets() {
        if ($this->offsets) {
            return 'Offset '.$this->offsets;
        }

        return '';
    }

    public static function raw($query) {

    }

    /**
     * Get param type checks a values type and
     * returns a PDO type.
     * @param   mixed  $value
     * @return  int|bool|null|string
     */
    public function getParamType($value) {
        switch (true) {
            case is_int($value):
                return PDO::PARAM_INT;
                break;
            case is_bool($value):
                return PDO::PARAM_BOOL;
                break;
            case is_null($value):
                return PDO::PARAM_NULL;
                break;
            default:
                return PDO::PARAM_STR;
        }
    }

    /**
     * Insert. There are two ways when using insert. One of the way
     * is to pass an array containing both key and value. Or you can
     * pass two arrays, one containing keys and the other one
     * containing values.
     *
     * $insert = array('Key' => 'Value');
     *
     * $insertKeys = array('Key');
     * $insertValues = array('Value');
     *
     * @param   array  $keys
     * @param   array  $values
     * @return  bool
     */
    public function insert(Array $keys, $values=array()) {
        // runs the code below if the user has passed a second array of values.
        // Meaning the user wishes to pass keys and values separately.
        // This piece of code converts the users data to the original way
        // of putting in data.
        if (!empty($values)) {
            assert(is_array($keys) && is_array($values), 'Both keys and values must be arrays.');

            $newKey = array();
            for ($i=0; $i<count($keys); $i++) {
                $newKey[$keys[$i]] = $values[$i];
            }
            $keys = $newKey;
        }

        $c = '';
        $v = '';

        foreach ($keys as $key => $value) {
            $c .= $key.', ';
            $v .= '?, ';
        }

        // remove last comma and space
        $c = trim($c, ', ');
        $v = trim($v, ', ');

        $query = 'Insert Into '.$this->getTables().' ('.$c.') Values ('.$v.');';

        self::$preparedStatement = self::$connection->prepare($query);

        $i = 0;
        foreach ($keys as $key => $value) {
            $i++;
            self::$preparedStatement->bindValue($i, $value, $this->getParamType($value));
        }

        self::clean();
        return self::$preparedStatement->execute();
    }

    /**
     * Update method.
     * @param   array  $keys
     * @param   array  $values
     * @return  bool
     */
    public function update(Array $keys, $values=array()) {
        // runs the code below if the user has passed a second array of values.
        // Meaning the user wishes to pass keys and values separately.
        // This piece of code converts the users data to the original way
        // of putting in data.
        if (!empty($values)) {
            assert(is_array($keys) && is_array($values), 'Both keys and values must be arrays.');

            $newKey = array();
            for ($i=0; $i<count($keys); $i++) {
                $newKey[$keys[$i]] = $values[$i];
            }
            $keys = $newKey;
        }

        $c = '';

        foreach ($keys as $key => $value) {
            $c .= $key.'=?, ';
        }

        // remove last comma and space
        $c = trim($c, ', ');

        $query = 'Update '.$this->getTables().' Set '.$c.' '.$this->getWheres().';';

        self::$preparedStatement = self::$connection->prepare($query);

        $i = 0;
        foreach ($keys as $key => $value) {
            $i++;
            self::$preparedStatement->bindValue($i, $value, $this->getParamType($value));
        }

        self::clean();
        return self::$preparedStatement->execute();
    }

    /**
     * Delete method. Before a delete can take action the user must have called
     * where() before. This is to reduce the risk of removing all posts by
     * mistake. You can force to remove all posts inside a table by passing
     * "true".
     * @param   bool  $force
     * @return  bool
     */
    public function delete($force=false) {
        if ($force) {
            $query = 'Delete From '.$this->getTables().';';
        } else {
            $query = 'Delete From '.$this->getTables().' '.$this->getWheres().';';
        }

        self::$preparedStatement = self::$connection->prepare($query);

        self::clean();
        return self::$preparedStatement->execute();
    }

    /**
     * Returns the last inserted id.
     * @return  int
     */
    public static function lastInsertedID() {
        return self::$connection->lastInsertId();
    }

    /**
     *  Get an array with objects from a query.
     *  @param   const  $mode=self::OBJECTS  DB::OBJECTS, DB::ARRAYS
     *  @return  array
     */
    public function get($mode=self::OBJECTS) {
        $this->prepare();
        self::execute();
        return self::$preparedStatement->fetchAll($mode);
    }

    /**
     *  Get first result.
     *  @param   const  $mode=self::OBJECTS  DB::OBJECTS, DB::ARRAYS
     *  @return  array
     */
    public function first($mode=self::OBJECTS) {
        $this->prepare();
        self::$preparedStatement->execute();
        self::clean();
        return self::$preparedStatement->fetch($mode);
    }

    /**
     *  Prepares a query.
     *  @return  void
     */
    public function prepare() {
        // Select {selects} From {tables} {joins} {wheres} {orderbys} {limit}
        $args = array(
            $this->getSelects(),
            $this->getTables(),
            $this->getJoins(),
            $this->getWheres(),
            $this->getOrderBys(),
            $this->getLimits(),
            $this->getOffsets()
        );

        $query = 'Select %s From %s %s %s %s %s %s';

        $query = preg_replace('/\s+/', ' ', trim(vsprintf($query, $args)));

        self::$preparedStatement = self::$connection->prepare($query);
    }

    /**
     *  Executes a prepared statement.
     *  @throws  Exception
     *  @return  DB
     */
    public static function execute() {
        self::$preparedStatement->execute();

        if (self::$preparedStatement->errorInfo()[1]) {
            throw new Exception(self::$preparedStatement->errorInfo()[2]);
        }

        self::clean();

        return self::$self;
    }

    /**
     * Returns the last query stored in preparedStatement.
     * @return  string
     */
    public static function debugQuery() {
        return self::$preparedStatement->queryString;
    }

    /**
     * Resets all values to default.
     * @return  void
     */
    private static function clean() {
        $self = self::$self;

        $self::$tables  = array();
        $self->selects  = array();
        $self->joins    = array();
        $self->ons      = array();
        $self->wheres   = array();
        $self->orderBys = array();
        $self->limits   = 0;
        $self->offsets  = '';
    }
}
