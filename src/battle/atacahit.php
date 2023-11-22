<?php

$misschance = random_int(0, 100);
if ($misschance <= $player->miss) {
    $_SESSION['ataques'] .= "Voc� tentou atacar " . $enemy->prepo . " " . $enemy->username . " mas errou!<br />";
} else {
    $totalpak = random_int($player->mindmg, $player->maxdmg);

    $magiaatual = $db->execute("select `magia`, `turnos` from `bixos` where `player_id`=?", [$player->id]);
    $magiaatual2 = $magiaatual->fetchrow();

    if ($magiaatual2['magia'] == 1) {
        $porcento = $totalpak / 100;
        $porcento = ceil($porcento * 15);
        $totalpak += $porcento;
    } elseif ($magiaatual2['magia'] == 2) {
        $porcento = $totalpak / 100;
        $porcento = ceil($porcento * 45);
        $totalpak += $porcento;
    } elseif ($magiaatual2['magia'] == 12) {
        $porcento = $totalpak / 100;
        $porcento = ceil($porcento * 35);
        $totalpak += $porcento;
    }

    if (($bixo->hp - $totalpak) < 1) {
        $db->execute("update `bixos` set `hp`=0 where `player_id`=?", [$player->id]);
        $matou = 5;
    } else {
        $db->execute("update `bixos` set `hp`=`hp`-? where `player_id`=?", [$totalpak, $player->id]);
    }
    $_SESSION['ataques'] .= "<font color=\"green\">Voc� atacou " . $enemy->prepo . " " . $enemy->username . " e tirou " . $totalpak . " de vida.</font><br/>";
}
