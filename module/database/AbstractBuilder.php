<?
namespace Cart;
interface AbstractBuilder
{
    public function select(string $columns = ''): QueryBuilder;
    public function where(string $field, string $value, string $operator = '='): QueryBuilder;
    public function last(string $columns = ''): QueryBuilder;
    public function get();
}
