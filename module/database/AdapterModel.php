<?

namespace Cart;

abstract class AdapterModel extends AbstractModel implements CoreAdapter
{
    use AdapterTrait;

    /**
     * core model instance
     */
    protected  $db;

    /**
     * table title
     */
    protected $table;

    /**
     * list of columns to fill or select
     */
    protected $columns;

    /**
     * database table structure
     */
    protected $schema;

    /**
     * list of json columns
     */
    protected $attributes = [];

    private $alias        = 'model';

    protected $prefix     = 'Shop';

    protected $source     = 'tables';

    protected $operators  = ['=', '>', '<', '>=', '=<', '!=', 'LIKE'];

    public function __construct()
    {
        $this->setProps();
    }

    /**
     * 
     */
    public function createTable(): bool
    {
        return $this->db->createTable();
    }

    /**
     * 
     */
    public function dropTable()
    {

        $this->db->dropTable();
        return $this;
    }

    /**
     * 
     */
    public function clearTable()
    {

        $this->db->clearTable();
        return $this;
    }


    /**
     * 
     */
    public function seedTable(array $data): int
    {
        return array_reduce($data, fn ($row_data) => $this->create($row_data));
    }

    /**
     * 
     */
    public function tableExist(string $table = ''): bool
    {
        return $this->db->tableExist($table);
    }


    /**
     * create database record
     */
    public function create(array $data): int
    {
        if (!empty($this->attributes)) {
            foreach ($this->attributes as $column) {

                if (!empty($data[$column])) {
                    $attributes = ['column' => $column, 'attrs' => $data[$column]];
                    $data[$column] = $this->setColumnAttributes($attributes);
                }
            }
        }
        $data['created_at'] = date('Y-m-d H:i:s');

        $new_id = $this->db->create($data);
        return $new_id;
    }



    /**
     * delete table data
     * @return int - number of deleted rows
     */
    public function delete(array $where): int
    {
        return $this->db->delete($where);
    }


    /**
     * 
     */
    public function select(string $columns = '',  array $where): array
    {
        $columns = empty($columns) ? implode(',', $this->columns) : $columns;
        $q = $this->db->select($columns, $where);

        return $q;
    }


    /**
     * 
     */
    public function update(array $data, array $where): int
    {
        return $this->db->update($data, $where);
    }


    /**
     * 
     */
    public function query($query, bool $obj = false): array
    {
        $q = $this->db->query($query, $obj);
        return $q;
    }

    /**
     * set columns attrs, json imitation
     */
    protected function setColumnAttributes(array $data): string
    {

        if (method_exists($this, $data['column'])) {
            $method = $data['column'];
            return $this->$method($data['attrs']); 
        }
    }

    /**
     * get full list of columns
     */
    protected function getColumnsList(): array
    {
        $schema = $this->db->getProps()->schema;
        return array_filter(array_keys($schema), fn ($key) => !is_numeric($key));
    }



    /**
     * define model properties 
     */
    private function setProps(): void
    {
        $table = $this->defineTable();
        $this->table  = $table;
        $this->schema = _cart()->loader()->get('tables', $table);
        $this->db     = Core::getInstance($this);
    }

    /**
     * define model table
     */
    protected function defineTable()
    {
        $namespace = __NAMESPACE__ .'\\';
        $suffix = 's_' . rtrim($this->source, 's');
        $child = str_replace($namespace, '', get_class($this));
        $child = str_replace($this->prefix, '', $child);
        $table = strtolower($this->prefix . '_' . $child . $suffix);
        if (_helper()->str_contains($table, 'cys')) $table = str_replace('cys', 'cies', $table); // currencys ==> currencies
        return $table;
    }
}
