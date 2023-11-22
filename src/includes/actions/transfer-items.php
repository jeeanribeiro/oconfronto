<?php

if ($_POST['transferitems']) {
    $verifikeuser = $db->execute("select `id` from `quests` where `quest_id`=4 and `quest_status`=90 and `player_id`=?", [$player->id]);
    if ($verifikeuser->recordcount() == 0) {
        include(__DIR__ . "/templates/private_header.php");
        echo"<fieldset><legend><b>Enviar Itens</b></legend>";
        echo"Voc� precisa chegar ao nivel 40 e completar uma miss�o para utilizar esta fun��o.";
        if ($player->level > 39) {
            echo"<br><center><a href=\"quest2.php\"><b>Clique aqui para fazer a miss�o.</b></a></center>";
        }
        echo"</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    if (!$_POST['username']) {
        $error = 1;
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Voc� precisa preencher todos os campos!<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    if (!$_POST['itselected']) {
        $error = 1;
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Voc� precisa preencher todos os campos!<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    if (!$_POST['passcode']) {
        $error = 1;
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Voc� precisa preencher todos os campos!<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    if (strtolower((string) $_POST['passcode']) !== strtolower((string) $player->transpass)) {
        $error = 1;
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Sua senha de transfer�ncia est� incorreta.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    if ($_POST['username'] == $player->username) {
        $error = 1;
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Voc� n�o pode enviar um iten para voc� mesmo!<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }


    $quhjdjn = $db->execute("select items.item_bonus, items.status, items.mark, blueprint_items.id, blueprint_items.name, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.id=? and items.player_id=?", [$_POST['itselected'], $player->id]);
    $item5 = $quhjdjn->fetchrow();


    $checkuser = $db->execute("select `id`, `username`, `serv` from `players` where `username`=?", [$_POST['username']]);
    $destination = $checkuser->fetchrow();


    if ($quhjdjn->recordcount() == 0) {
        $error = 1;
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Voc� n�o possui este item.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    if ($item5['status'] == 'equipped') {
        $error = 1;
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Voc� n�o pode enviar um item que est� sendo usado.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    if ($item5['mark'] == 't') {
        $error = 1;
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Voc� n�o pode enviar um item que est� � venda no mercado.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    if ($item5['type'] == 'stone') {
        $error = 1;
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Voc� n�o pode enviar pedras.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    if ($checkuser->recordcount() == 0) {
        $error = 1;
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "O usu�rio " . $_POST['username'] . " n�o existe.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    if ($player->serv != $destination['serv']) {
        $error = 1;
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Este usu�rio pertence a outro servidor.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }


    if ($error == 0) {
        $sendit = $db->execute("update `items` set `player_id`=? where `id`=?", [$destination['id'], $_POST['itselected']]);

        $insert['player_id'] = $player->id;
        $insert['name1'] = $player->username;
        $insert['name2'] = $destination['username'];
        $insert['action'] = "enviou";
        $insert['value'] = "um/uma <b>" . $item5['name'] . " +" . $item5['item_bonus'] . "</b>";
        $insert['itemid'] = $_POST['itselected'];
        $insert['blue_id'] = $item5['id'];
        $insert['time'] = time();
        $query = $db->autoexecute('log_item', $insert, 'INSERT');

        $insert['player_id'] = $destination['id'];
        $insert['name1'] = $destination['username'];
        $insert['name2'] = $player->username;
        $insert['action'] = "recebeu";
        $insert['value'] = "um/uma <b>" . $item5['name'] . " +" . $item5['item_bonus'] . "</b>";
        $insert['itemid'] = $_POST['itselected'];
        $insert['blue_id'] = $item5['id'];
        $insert['time'] = time();
        $query = $db->autoexecute('log_item', $insert, 'INSERT');

        $logmsg = "O usu�rio <b>" . $player->username . "</b> lhe enviou um(a) <b>" . $item5['name'] . " +" . $item5['item_bonus'] . "</b>.";
        addlog($destination['id'], $logmsg, $db);
        include(__DIR__ . "/templates/private_header.php");
        echo "<fieldset><legend><b>Sucesso</b></legend>\n";
        echo "Voc� enviou um(a) <b>" . $item5['name'] . " +" . $item5['item_bonus'] . "</b> com sucesso para o usu�rio: <b>" . $_POST['username'] . "</b>.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
        echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }
}
