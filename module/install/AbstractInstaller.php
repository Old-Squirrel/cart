<?
namespace Cart;

abstract class AbstractInstaller 
{
    use StatusInspectorTrait;
    
    public function __construct()
    {

    }

    abstract function up();
    
    abstract function down();
    
    abstract function confirm();

    protected function resource() {
        return _cart()->resource();
    }
}
