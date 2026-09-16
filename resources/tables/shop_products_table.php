<?

return array(
    'id'            => "bigint(12) NOT NULL AUTO_INCREMENT",
    'api_id'        => "bigint(12) NOT NULL",   
    'title'         => "varchar(255) NOT NULL", 
    'category'      => "varchar(255) NOT NULL",
    'dose'          => "decimal(11,3) NOT NULL DEFAULT 0.000 ", 
    'units'         => "varchar(255) NOT NULL DEFAULT 'mg' ",   
    'amount'        => "int(10) NOT NULL DEFAULT 0",
    'bonus'         => 'int(10) NOT NULL DEFAULT 0',
    'price'         => "decimal(11,3) NOT NULL DEFAULT 0.000 ",
    'prod_type'     => "varchar(255) NOT NULL DEFAULT 'pills' ",
    'top_sale'      => "tinyint(2) NOT NULL DEFAULT 0",
    'image_path'    => "varchar(500) NOT NULL DEFAULT 'wp-content/uploads' ",             
    'created_at'    => "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
    "PRIMARY KEY (id)"
);
