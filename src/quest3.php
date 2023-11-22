<?php

include(__DIR__ . "/lib.php");
define("PAGENAME", "Miss�es");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");

$calculo = ($player->level * $player->level);
$cost = ceil($calculo);



if ($player->level < 25) {
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Treinador</b></legend>\n";
    echo "<i>Seu nivel � muito baixo!</i><br/>\n";
    echo '<a href="home.php">Voltar</a>.';
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

if ($player->level > 35) {
    $query = $db->execute("delete from `quests` where `player_id`=? and `quest_id`=5", [$player->id]);
    $query = $db->execute("delete from `quests` where `player_id`=? and `quest_id`=6", [$player->id]);
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Treinador</b></legend>\n";
    echo "<i>Seu nivel � muito alto!</i><br/>\n";
    echo '<a href="home.php">Voltar</a>.';
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}


switch($_GET['act']) {

    case "who":
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Treinador</b></legend>\n";
        echo "<i>Eu treino guerreiros, ganho a vida assim.</i><br><br>\n";
        echo "<a href=\"quest3.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        break;

    case "help":
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Treinador</b></legend>\n";
        echo "<i>Bom, esse � meu trabalho, treinar guerreiros. Gostaria de come�ar seu treinamento por " . $cost . " de ouro?<br>Se eu te treinar, voc� poder� adiquirir at� tr�s n�veis!</i><br><br>\n";
        echo "<a href=\"quest3.php?act=acept\">Aceito</a> | <a href=\"quest3.php?act=decline\">Recuso</a> | <a href=\"home.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        break;

    case "decline":
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Treinador</b></legend>\n";
        echo "<i>Tudo bem, a escolha � sua.</i><br><br>\n";
        echo "<a href=\"home.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        break;

    case "begin":
        $verificationertz = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=5 and `quest_status`=1", [$player->id]);
        if ($verificationertz->recordcount() == 0) {
            include(__DIR__ . "/templates/private_header.php");
            echo "<fieldset><legend><b>Aviso</b></legend>\n";
            echo "<i>Um erro desconhecido ocorreu, contate o administrador.</i><br><br>\n";
            echo "<a href=\"home.php\">P�gina Principal</a>.";
            echo "</fieldset>";
            include(__DIR__ . "/templates/private_footer.php");
            exit;
        }
        $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=5", [$player->kills + 12, $player->id]);
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Treinador</b></legend>\n";
        echo "<i>Grandes guerreiros precisam aprender a matar desde cedo, ent�o minha miss�o � voc� ser� simples. <b>Mate 12 usu�rios</b> e voc� consiguir� os 3 n�veis.</i><br><br>\n";
        echo "<a href=\"quest3.php\">Continuar</a> | <a href=\"home.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        break;

    case "acept":
        $verifikcheck = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=5", [$player->id]);
        if ($verifikcheck->recordcount() != 0) {
            include(__DIR__ . "/templates/private_header.php");
            echo "<fieldset><legend><b>Treinador</b></legend>\n";
            echo "Voc� j� me pagou!</i><br/><br/>\n";
            echo "<a href=\"home.php\">Voltar</a>.";
            echo "</fieldset>";
            include(__DIR__ . "/templates/private_footer.php");
            exit;
        }
        if ($player->gold - $cost < 0) {
            include(__DIR__ . "/templates/private_header.php");
            echo "<fieldset><legend><b>Treinador</b></legend>\n";
            echo "<i>Voc� n�o possui esta quantia de ouro!</i><br/><br/>\n";
            echo "<a href=\"home.php\">Voltar</a>.";
            echo "</fieldset>";
            include(__DIR__ . "/templates/private_footer.php");
            exit;
        }
        $query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - $cost, $player->id]);
        $insert['player_id'] = $player->id;
        $insert['quest_id'] = 5;
        $insert['quest_status'] = 1;
        $query = $db->autoexecute('quests', $insert, 'INSERT');
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Treinador</b></legend>\n";
        echo "<i>Obrigado. vamos logo come�ar com o treinamento.</i><br><br>\n";
        echo "<a href=\"quest3.php\">Come�ar Treinamento</a> | <a href=\"home.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;

}
?>
<?php
$verificacao1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 5]);
$quest1 = $verificacao1->fetchrow();

$verificac2 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 6]);
$quest2 = $verificac2->fetchrow();

if ($verificacao1->recordcount() == 0 && $verificac2->recordcount() == 0) {
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Treinador</b></legend>\n";
    echo "<i>Ol� meu jovem. Porque me precura?</i><br/><br>\n";
    echo "<a href=\"quest3.php?act=who\">Quem � voc�?</a> | <a href=\"quest3.php?act=help\">Preciso treinar</a> | <a href=\"home.php\">Voltar</a>.";
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}


if ($quest1['quest_status'] == 1) {
    $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=5", [$player->kills + 12, $player->id]);
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Treinador</b></legend>\n";
    echo "<i>Grandes guerreiros precisam aprender a matar desde cedo, ent�o minha miss�o � voc� ser� simples. <b>Mate 12 usu�rios</b> e voc� consiguir� os 3 n�veis.</i><br><br>\n";
    echo "<a href=\"quest3.php\">Continuar</a> | <a href=\"home.php\">Voltar</a>.";
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
}

if ($quest1['quest_status'] > 1) {

    $remaining = ($quest1['quest_status'] - $player->kills);

    if ($remaining < 1) {
        $insert['player_id'] = $player->id;
        $insert['quest_id'] = 6;
        $insert['quest_status'] = 1;
        $query = $db->autoexecute('quests', $insert, 'INSERT');
        $query = $db->execute("delete from `quests` where `player_id`=? and `quest_id`=5", [$player->id]);
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Treinador</b></legend>\n";
        echo "<i>Voc� j� matou todos os usu�rios nesces�rios.</i><br><br>";
        echo "<a href=\"quest3.php\">Continuar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Treinador</b></legend>\n";
    echo "<i>Voc� precisa matar <b>" . $remaining . " usu�rio(s)</b> para terminar seu treinamento.</i><br><br>";
    echo "<a href=\"home.php\">Voltar</a>.";
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;

}

if ($quest2['quest_status'] == 1) {
    $newlvl = ($player->level + 3);
    $query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=6", [90, $player->id]);
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Treinador</b></legend>\n";
    echo "<i>Bom, espero que voc� tenha aprendido a matar.<br><b>(Voc� passou para o n�vel " . $newlvl . ")</b></i><br><br>";
    echo "<a href=\"home.php\">Voltar</a>.";
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    $plevel = ($player->level + 3);
    $dividecinco = ($plevel / 5);
    $dividecinco = floor($dividecinco);

    $ganha = 100 + ($dividecinco * 15) + $player->extramana;

    $db->execute("update `players` set `mana`=?, `maxmana`=? where `id`=?", [$ganha, $ganha, $player->id]);

    $expofnewlvl = floor(20 * (($player->level + 3) * ($player->level + 3) * ($player->level + 3)) / ($player->level + 3));
    $query = $db->execute("update `players` set `magic_points`=?, `stat_points`=?, `level`=?, `maxexp`=?, `maxhp`=?, `exp`=0, `hp`=? where `id`=?", [$player->magic_points + 3, $player->stat_points + 9, $player->level + 3, $expofnewlvl, $player->maxhp + 90, $player->maxhp + 90, $player->id]);
    exit;
}

if ($quest2['quest_status'] == 90) {
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Erro</b></legend>\n";
    echo "<i>Voc� j� fez esta miss�o.</i><br><br>";
    echo "<a href=\"home.php\">Voltar</a>.";
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}



?>