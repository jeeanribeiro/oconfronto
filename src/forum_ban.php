<?php

include(__DIR__ . "/lib.php");
define("PAGENAME", "F�rum");
$player = check_user($secret_key, $db);

include(__DIR__ . "/templates/private_header.php");
if (!$_GET['player']) {
    echo "Nenhum usu�rio foi selecionado! <a href=\"select_forum.php\">Voltar</a>.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

if ($player->gm_rank < 3) {
    echo "Voc� n�o pode acessar esta p�gina! <a href=\"select_forum.php\">Voltar</a>.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}
$user = $db->execute("select `username`, `gm_rank` from `players` where `id`=?", [$_GET['player']]);
if ($user->recordcount() == 0) {
	echo "Este usu�rio n�o existe! <a href=\"select_forum.php\">Voltar</a>.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}
$user2 = $user->fetchrow();
if(isset($_POST['ban']))
{

if (!$_POST['motivo'])
{
	echo "Voc� precisa digitar o motivo do banimento! <a href=\"forum_ban.php?player=" . $_GET['player'] . "\">Voltar</a>.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (!$_POST['days'])
{
	echo "Voc� precisa digitar o tempo do banimento! <a href=\"forum_ban.php?player=" . $_GET['player'] . "\">Voltar</a>.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (!$_POST['days'])
{
	echo "O numero de dias digitado n�o � v�lido! <a href=\"forum_ban.php?player=" . $_GET['player'] . "\">Voltar</a>.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}
if ($player->gm_rank <= $user2['gm_rank']){
	echo "Voc� n�o pode banir Moderadores/Administradores! <a href=\"select_forum.php\">Voltar</a>.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}


if ($_POST['days'] > 998){
$ban = $db->execute("update `players` set `alerts`=? where `id`=?", [\FOREVER, $_GET['player']]);
	$logmsg = "Voc� foi banido do f�rum permanentemente.<br/><b>Motivo:</b> " . strip_tags((string) $_POST['motivo']) . "";
}else{
$tempo = time();
$dias = ceil($_POST['days'] * 86400);
$totaldias = ceil($tempo + $dias);
$ban = $db->execute("update `players` set `alerts`=? where `id`=?", [$totaldias, $_GET['player']]);
	$logmsg = "Voc� foi banido do f�rum por " . strip_tags((string) $_POST['days']) . " dias.<br/><b>Motivo:</b> " . strip_tags((string) $_POST['motivo']) . "";
}
	addlog($_GET['player'], $logmsg, $db);

	$logalert2 = "" . $user2['username'] . " foi banido do f�rum por " . strip_tags((string) $_POST['days']) . " dia(s) pelo moderador <b>" . $player->username . "</b><br/><b>Motivo:</b> " . strip_tags((string) $_POST['motivo']) . "";
	forumlog($logalert2, $db);

	echo "" . $user2['username'] . " foi banido do f�rum! <a href=\"select_forum.php\">Voltar</a>.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}
echo "<form method=\"POST\" action=\"forum_ban.php?player=" . $_GET['player'] . "\">";
echo "<b>Deseja banir " . $user2['username'] . " do f�rum por quanto tempo?</b><br/>";
echo "<b>Banir por:</b> <input type=\"text\" name=\"days\" size=\"5\"/> dias. <font size=1>(digite 999 para banir permanentemente).</font><br/>";
echo "<b>Motivo:</b> <input type=\"text\" name=\"motivo\" size=\"30\"/> ";
echo " <input type=\"submit\" name=\"ban\" value=\"Banir!\"></form>";
include(__DIR__ . "/templates/private_footer.php");
exit;
?>
