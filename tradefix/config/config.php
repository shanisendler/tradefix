<?php
function detectEnvironment()
{
    // cheking if it is development or production

    $host = $_SERVER['HTTP_HOST'];
    $devDomains = ['dev.tradefix.co.il'];

    if (strpos($host, 'localhost') !== false || in_array($host, $devDomains)) {
        return 'development';
    } else {
        return 'production';
    }
}

return [
    'development' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'tradefixbrglp_legalLoss',       
    ],
    'production' => [
        'host' => '',
        'username' => '',
        'password' => '',
        'database' => 'tradefixbrglp_legalLoss',        
    ],
];


?>