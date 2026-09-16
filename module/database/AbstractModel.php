<?
namespace Cart;

abstract class AbstractModel
{
    protected $db;
    protected $table;
    protected $columns;
    protected $schema;
    

    public function __construct(AdapterModel $model)
    {
    }

    public function getProps()
    {
        return (object)['table' => $this->table, 'schema' => $this->schema, 'columns' => $this->columns];
    }

    abstract function createTable(): bool;

    abstract function tableExist(string $table = ''): bool;

    abstract function dropTable();

    abstract function clearTable();

    abstract function seedTable(array $data): int;

    abstract function create(array $data): int;

    abstract function delete(array $where): int;

    abstract function select(string $columns = '', array $where): array;

    abstract function update(array $data, array $where): int;

    abstract function query($query, bool $obj = false): array;

}
