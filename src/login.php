<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Login");
$acc = check_acc($secret_key, $db);

if (!$_GET['id'])
{
	header("Location: characters.php");
	exit;
}
$loginban = $db->GetOne("select `ban` from `players` where `id`=?", [$_GET['id']]);
$youracc = $db->execute("select * from `players` where `id`=? and `acc_id`=?", [$_GET['id'], $acc->id]);
if ($loginban > time()) {
    include(__DIR__ . "/templates/acc_header.php");
    $time = $loginban - time();
    $days_remaining = $time / 86400;
    echo "<br/><br/><br/><center>" . $loginto . " foi banido do jogo. O banimento acabar� em " . round($days_remaining) . " dia(s). <a href=\"characters.php\">Voltar</a>.</center><br/>";
    include(__DIR__ . "/templates/acc_footer.php");
    exit;
}
if ($youracc->recordcount() != 1) {
    include(__DIR__ . "/templates/acc_header.php");
    echo "<br/><br/><br/><center>Este usu�rio n�o pertence a sua conta ou n�o foi encontrado. <a href=\"characters.php\">Voltar</a>.</center><br/>";
    include(__DIR__ . "/templates/acc_footer.php");
    exit;
}
$_SESSION['userid'] = $_GET['id'];
$_SESSION['playerhash'] = sha1($acc->password . $_GET['id'] . $acc->id . $_SERVER['REMOTE_ADDR'] . $secret_key);
header("Location: home.php");
exit;
?>
