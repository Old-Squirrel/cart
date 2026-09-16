<?

namespace Cart;

abstract class ShopModel
{

    /**
     * @return $model instance
     */
    public static function table(string $model_alias): AdapterModel
    {
        $list  = self::children();
        $model = $list[$model_alias];
        
        $model = new $model();
        return $model;
    }

    /**
     * @return array ['alias' => 'ClassName'] 
     */
    public static function children(): array
    {
        $tables_list = Config::getAttribute('tables');
        $class_list = [];
        foreach ($tables_list as $table) {
            $child      = _helper()->class_name('shop', '', $table);
            $class_list = array_merge($class_list, $child);
        }
        return $class_list;
    }


    /**
     * @return QueryBuilder $builder instance
     */
    public static function builder(string $model_alias): QueryBuilder
    {
        $adapter = self::table($model_alias);
        $model   = Core::getInstance($adapter);
        $builder = new QueryBuilder($model);
        return $builder->reset();
    }

    

}
