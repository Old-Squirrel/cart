<?

return array(
    'id'         => "bigint(20) NOT NULL AUTO_INCREMENT",
    'total'      => 'decimal(11,3) NOT NULL',
    'subtotal'   => 'decimal(11,3) NOT NULL',
    'insurance'  => 'decimal(11,3) NOT NULL DEFAULT 0.000',
    'currency'   => 'tinytext NOT NULL',
    'products'   => 'varchar(2500) NOT NULL',
    'billing'    => 'varchar(2500) NOT NULL',
    'payment'    => 'varchar(2500) NOT NULL',
    'shipping'   => 'varchar(2500) NOT NULL',
    'browser'    => 'varchar(1000) NOT NULL',
    'http'       => 'varchar(1000) NOT NULL',
    'status'     => "tinytext NOT NULL",
    'api_status' => "tinytext NOT NULL DEFAULT 'sent'",
    'created_at' => "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
    "PRIMARY KEY (id)"
);
