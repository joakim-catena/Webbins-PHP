<?php namespace Webbins\Database;

use Exception;

class GroupBy {
    /**
     * Column.
     * @var  string
     */
    private $column = '';

    /**
     * Construct.
     * @param  string  $column
     * @param  const   $order
     */
    public function __construct($column) {
        $this->setColumn($column);
    }

    /**
     * Set column.
     * @param   string  $column
     * @return  void
     */
    private function setColumn($column) {
        assert(is_string($column), 'Column must be a string.');
        $this->column = $column;
    }

    /**
     * Get column.
     * @return  string
     */
    public function getColumn() {
        return $this->column;
    }
}
