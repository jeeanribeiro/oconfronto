<?php

if ($player->alerts > 998 && $player->alerts != 'forever' && $player->alerts < time()){
$unban = $db->execute("update `players` set `alerts`=0 where `id`=?", [$player->id]);
include(__DIR__ . "/templates/private_header.php");
echo "Seu banimento no f�rum acabou!<br/><a href=\"select_forum.php\">Visitar o f�rum</a>.";
include(__DIR__ . "/templates/private_footer.php");
exit;
}

if ($player->alerts > 99 && $player->alerts < time() || $player->alerts == 'forever' || $player->alerts > time()) {
    if ($player->alerts > 99 && $player->alerts < time()) {
        include(__DIR__ . "/templates/private_header.php");
        echo "Seu alerta chegou a " . $player->alerts . "% e voc� n�o poder� visitar o f�rum at� que seu alerta baixe. Seu alerta cai 1% ao dia.<br/><a href=\"home.php\">Voltar</a>.";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }
    if ($player->alerts == 'forever') {
        include(__DIR__ . "/templates/private_header.php");
        echo "Voc� foi banido do f�rum permanentemente.<br/><a href=\"home.php\">Voltar</a>.";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }
    if ($player->alerts > time()) {
        $tempo0 = $player->alerts - time();
        $tempo = ceil($tempo0/60/60/24);
        include(__DIR__ . "/templates/private_header.php");
        echo "Voc� foi banido do f�rum. Seu banimento ir� acabar em " . $tempo . " dia(s).<br/><a href=\"home.php\">Voltar</a>.";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }
}
?>