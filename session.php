<?
session_start();
echo '<pre>';

print_r($_SESSION);

echo '--------------------------------';

print_r($_COOKIE);

echo '</pre>';

//unset($_SESSION['id']);
//unset($_SESSION['carrinho']);
