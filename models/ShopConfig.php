<?

namespace Cart;


class ShopConfig extends AdapterModel
{



    protected $columns = array('state');


    public function __construct()
    {

        parent::__construct($this);
    }

    /**
     * get last saved config
     */
    public function last($json = null)
    {
        $table = $this->db->getProps()->table;

        $query = "SELECT * FROM {$table} WHERE id > 0 ORDER BY id DESC LIMIT 1";

        $result = $this->query($query);

        return $json ? $result[0]['state'] : json_decode($result[0]['state'], true);
    }


}
