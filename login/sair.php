<?

spl_autoload_register(function ($class) {
    require_once(dirname(__FILE__) . "/{$class}.class.php");
});

//session_start();

$objLogin = new login();
$objLogin->logout('../');
