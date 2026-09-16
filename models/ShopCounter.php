<?
namespace Cart;
class ShopCounter extends AdapterModel
{
    protected $attributes = array('visitors');
    protected $columns    = array('visitors');


    public function __construct()
    {
       parent::__construct($this);
    }

    /**
     * 
     */

    public function last($json = null)
    {
        $table = $this->db->getProps()->table;
        $query = "SELECT * FROM {$table} WHERE id > 0 ORDER BY id DESC LIMIT 1";

        $result = $this->query($query);

        return $json ? $result[0]['visitors'] : json_decode($result[0]['visitors'], true);
    }

    /**
     * 
     */
    protected function visitors(array $data)
    {
        $result = [];
        foreach(_cart()->config()->get('counters') as $attr){
            if(!empty($data[$attr])){
                $result[$attr] = $data[$attr];
            }
        }
   
        return json_encode($result);
    }



}
