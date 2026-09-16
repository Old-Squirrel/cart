<?

namespace Cart;

class QueryBuilder  implements AbstractBuilder
{

    private $table;
    private $columns;
    private $model;

    protected $query;

    public function __construct($model)
    {

        $this->model    = $model;
        $this->table    = $model->getProps()->table;
        $this->columns  = $model->getProps()->columns;
    }

    /**
     * Build a SELECT query
     */
    public function select(string $columns = ''): QueryBuilder
    {
        $columns = !empty($columns) ? $columns : implode(',', $this->columns);
        $sql = "SELECT {$columns} FROM {$this->table}";
        $this->query->sql = $sql;
        $this->query->type = 'select';
        return $this;
    }


    /**
     * Add a WHERE condition
     */
    public function where(string $field, string $value, string $operator = '='): QueryBuilder
    {

        switch (true) {
            case in_array($this->query->type, ['select', 'update', 'delete']):
                $this->query->where[] = "$field $operator '$value'";
                return $this;
            case !in_array($this->query->type, ['select', 'update', 'delete']):
                throw new DatabaseQueryException('use SELECT, DELETE or UPDATE before WHERE');
                break;
            default:
                throw new DatabaseQueryException('unknown_field');
                break;
        }
        return $this;
    }

    /**
     * @param string $order_by if null order by `id`
     * @param string $columns comma separated list of columns
     */
    public function last(string $select = ''): QueryBuilder
    {

        $columns = !empty($select) ? $select : implode(',', $this->columns);
        $last = "ORDER BY `id` DESC LIMIT 0,1";
        $sql = "SELECT {$columns} FROM {$this->table} {$last}";
        $this->query->sql = $sql;
        $this->query->last = true;
        return $this;
    }


    /**
     * perform query and return result
     */
    public function get(bool $obj = false): ?array
    {
        $query = $this->query;
        $sql = $query->sql;
        switch (true) {
            case !empty($this->query->last) && $this->query->last === true:
                $sql .= ';';
                break;
            case !empty($query->where):
                $sql .= " WHERE " . implode(' AND ', $query->where) . ';';
                break;
        }
        $result =  $this->model->query($sql, $obj);
        $this->reset();
        return $result;
    }

    /**
     * 
     */
    public function reset(): QueryBuilder
    {
        $this->query    = new \stdClass;
        return $this;
    }
}
