<?
require __DIR__ . '/../inc/config.php';
require __DIR__ . '/../login/conn.php';
include __DIR__ . '/../inc/url.php';
include __DIR__ . '/../inc/functions.php';
include __DIR__ . '/../inc/company.php';
require __DIR__ . '/../vendor/autoload.php';

//declare(strict_types=1);

use NFePHP\NFe\Make;
use NFePHP\NFe\Complements;
use NFePHP\DA\NFe\Danfe;

$id = anti_injection($_GET['id']);

$consulta = $pdo->query("SELECT * FROM nfe WHERE id_nfe = " . $id);
$linha = $consulta->fetch(PDO::FETCH_ASSOC);

$xmlAssinado = file_get_contents(__DIR__ . '/finalizadas/' . $linha['chave_acesso'] . '.xml');

header('Content-disposition: attachment; filename=' . $linha['chave_acesso'] . '.xml');
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Description: File Transfer");

header("Expires: Fri, 01 Jan 2010 05:00:00 GMT");
if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE") == false) {
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");
}

echo $xmlAssinado;
