<?

require '../login/conn.php';

$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
if ($email == true) {
    $sql1 = $pdo->query("SELECT * FROM usuarios WHERE email = '" . $email . "'");
    $count1 = $sql1->rowCount();

    if ($count1 == 0) {
        $isAvailable = true;
    } else {
        $isAvailable = false;
    }
} else {
    $isAvailable = false;
}

echo json_encode(array(
    'valid' => $isAvailable,
));
