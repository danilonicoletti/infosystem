<?

$host = $_SERVER['HTTP_HOST'];
if ($host == "www.infoaquila.com.br") {
    $url = 'https://www.infoaquila.com.br/infosystem/';
} elseif ($host == "infoaquila.com.br") {
    $url = 'https://infoaquila.com.br/infosystem/';
} elseif ($host == "localhost") {
    $url = 'https://localhost/infosystem/';
}
