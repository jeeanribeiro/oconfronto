<?php

$mana = 15;
if ($player->mana < $mana) {
    $_SESSION['ataques'] .= "Voc� tentou lan�ar um feiti�o mas est� sem mana sufuciente.<br/>";
    $otroatak = 5;
} else {
    $curar = random_int(100, 200);
    if (($player->hp + $curar) > $player->maxhp) {
        $db->execute("update `players` set `hp`=`maxhp` where `id`=?", [$player->id]);
        $_SESSION['ataques'] .= "<font color=blue>Voc� fez um feiti�o e recuperou toda sua vida.</font><br/>";
    } else {
        $db->execute("update `players` set `hp`=`hp`+? where `id`=?", [$curar, $player->id]);
        $_SESSION['ataques'] .= "<font color=blue>Voc� fez um feiti�o e recuperou " . $curar . " de vida.</font><br/>";
    }
    $db->execute("update `players` set `mana`=`mana`-? where `id`=?", [$mana, $player->id]);
}
