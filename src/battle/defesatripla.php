<?php

$mana = 25;
$magiaatual = $db->GetOne("select `magia` from `bixos` where `player_id`=?", [$player->id]);

if ($player->mana < $mana) {
    $_SESSION['ataques'] .= "Voc� tentou lan�ar um feiti�o mas est� sem mana sufuciente.<br/>";
    $otroatak = 5;
} elseif ($magiaatual != 0) {
    $_SESSION['ataques'] .= "Voc� n�o pode ativar um feiti�o passivo enquanto outro est� ativo.<br/>";
    $otroatak = 5;
} else {
    $db->execute("update `bixos` set `magia`=? where `player_id`=?", [6, $player->id]);
    $db->execute("update `bixos` set `turnos`=? where `player_id`=?", [4, $player->id]);
    $db->execute("update `players` set `mana`=`mana`-? where `id`=?", [$mana, $player->id]);
    $_SESSION['ataques'] .= "<font color=\"blue\">Voc� lan�ou o feiti�o defesa dupla.</font><br/>";
}
