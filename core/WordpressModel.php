<?

namespace Cart;

class WordpressModel extends CoreModel
{


    protected $db;
    protected $table;
    protected $columns;
    protected $schema;

    public function __construct(AdapterModel $model)
    {
        require_once wp_shop_path() . '/wp-admin/includes/upgrade.php';
        global $wpdb;
        $this->db      = $wpdb;
        $model         = $model->getProps();
        $this->table   = $this->db->prefix . $model->table;
        $this->columns = $model->columns;
        $this->schema  = $model->schema;
    }


    /**
     * create new table
     */
    public function createTable(): bool
    {
        $table           = $this->table;
        $charset_collate = $this->db->get_charset_collate();
        $structure       = $this->formate();
        $sql             = "CREATE TABLE IF NOT EXISTS {$table} ($structure) $charset_collate;";
        dbDelta($sql);

        return $this->tableExist();
    }

    /**
     * delete table
     */
    public function dropTable()
    {
        $table = $this->table;
        $this->db->query("DROP TABLE IF EXISTS {$table}");
        return $this->tableExist($table);
    }

    /**
     * remove all records from table 
     */
    public function clearTable()
    {
        $table = $this->table;
        $this->db->query("TRUNCATE TABLE {$table}");
        return true;
    }

    /**
     * fill table 
     */
    public function seedTable(array $data): int
    {

        foreach ($data as $row) {

            $id = $this->create($row);
        }
        return $id;
    }

    /**
     * 
     */
    public function tableExist(string $table = ''): bool
    {
        $table = empty($table) ? $this->table : $this->db->prefix . $table;
        $check = $this->db->prepare('SHOW TABLES LIKE %s', $this->db->esc_like($table));
        return $this->db->get_var($check) == $table;
    }

    /**
     * insert data 
     * @return $id
     */
    public function create(array $data): int
    {
        $table = $this->table;

        $this->db->insert($table, $data);

        return $this->db->insert_id;
    }


    /**
     * delete record 
     */
    public function delete(array $where): int
    {
        $table  = $this->table;
        $result = $this->db->delete($table, $where);
        return $result;
    }


    /**
     * retrieve records from database
     */
    public function select(string $column = null, array $where): array
    {
        $key    = $where['key'];
        $value  = $where['value'];
        if (empty($column)) $column = '*';
        $query  = "SELECT $column FROM {$this->table} WHERE {$key}={$value}";
        $result = $this->query($query, false);
        return empty($result) ? [] : $result;
    }


    /**
     * update record 
     */
    public function update(array $data, array $where): int
    {
        $rows_count = $this->db->update("{$this->table}", $data, $where);
        return $rows_count;
    }

    /**
     * 
     */
    public function query($query, bool $obj = false): array
    {
        $_type = fn () => $obj !== false ? 'OBJECT' : 'ARRAY_A';
        $result = $this->db->get_results($query, $_type());
        return empty($result) ? [] : $result;
    }


    /**
     * make a formatted string
     */
    private function formate()
    {
        $table  = str_replace($this->db->prefix, '', $this->table);
        $schema = $this->schema;
        $structure = [];
        $structure[$table] = '';
        foreach ($schema as $field => $properties) {
            $str = !is_int($field) ? $field . ' ' . $properties . ' , ' . "\n" : $properties . ' ,';
            $structure[$table] = $structure[$table] . $str;
            $structure[$table] = rtrim($structure[$table], ",");
        }
        return $structure[$table];
    }
}
