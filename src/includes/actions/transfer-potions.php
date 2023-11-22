<?php

if ($_GET['transpotion'] && !$_POST['mandap']) {
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Enviar Po��es</b></legend>\n";
    echo "<form method=\"post\" action=\"inventory.php?transpotion=true\"><table><tr><td><b>Desejo enviar:</b></td><td><select name=\"potion\"><option value=\"none\" selected=\"selected\">Selecione</option><option value=\"hp\">Health Potions</option><option value=\"bhp\">Big Health Potions</option><option value=\"mana\">Mana Potions</option><option value=\"energy\">Energy Potions</option></select></td></tr>";
    echo "<tr><td><b>Quantia:</b></td><td><input type=\"text\" name=\"quantia\" size=\"4\"/></td></tr>";
    echo "<tr><td><b>Senha de Transfer�ncia:</b></td><td><input type=\"password\" name=\"passcode\" size=\"20\"/></td></tr>";
    echo "<tr><td><b>Para:</b></td><td><input type=\"text\" name=\"to\"/> <input type=\"submit\" name=\"mandap\" value=\"Enviar\" /></td></tr></table>";
    echo "</form></fieldset><a href=\"inventory.php\">Voltar</a>.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}
if ($_GET['transpotion'] && $_POST['mandap']) {
    if (!$_POST['potion']){
  		include(__DIR__ . "/templates/private_header.php");
  		echo "<fieldset><legend><b>Erro</b></legend>\n";
          	echo "Voc� precisa preencher todos os campos!<br />";
          	echo "<a href=\"inventory.php\">Voltar</a>.";
  		echo "</fieldset>";
          	include(__DIR__ . "/templates/private_footer.php");
          	exit;
  	}
    if (!$_POST['quantia']){
  		include(__DIR__ . "/templates/private_header.php");
  		echo "<fieldset><legend><b>Erro</b></legend>\n";
          	echo "Voc� precisa preencher todos os campos!<br />";
          	echo "<a href=\"inventory.php\">Voltar</a>.";
  		echo "</fieldset>";
          	include(__DIR__ . "/templates/private_footer.php");
          	exit;
  	}
    if (!$_POST['passcode']){
  		include(__DIR__ . "/templates/private_header.php");
  		echo "<fieldset><legend><b>Erro</b></legend>\n";
          	echo "Voc� precisa preencher todos os campos!<br />";
          	echo "<a href=\"inventory.php\">Voltar</a>.";
  		echo "</fieldset>";
          	include(__DIR__ . "/templates/private_footer.php");
          	exit;
  	}
    if (!$_POST['to']){
  		include(__DIR__ . "/templates/private_header.php");
  		echo "<fieldset><legend><b>Erro</b></legend>\n";
          	echo "Voc� precisa preencher todos os campos!<br />";
          	echo "<a href=\"inventory.php\">Voltar</a>.";
  		echo "</fieldset>";
          	include(__DIR__ . "/templates/private_footer.php");
          	exit;
  	}
    if ($_POST['passcode'] != $player->transpass){
  		include(__DIR__ . "/templates/private_header.php");
  		echo "<fieldset><legend><b>Erro</b></legend>\n";
          	echo "Sua senha de transfer�ncia est� incorreta.<br />";
          	echo "<a href=\"inventory.php\">Voltar</a>.";
  		echo "</fieldset>";
          	include(__DIR__ . "/templates/private_footer.php");
          	exit;
  	}
    if (!is_numeric($_POST['quantia']) || $_POST['quantia'] < 1){
   		include(__DIR__ . "/templates/private_header.php");
   		echo "<fieldset><legend><b>Erro</b></legend>\n";
           	echo "A quantia de po��es digitada n�o � uma quantia v�lida.<br />";
           	echo "<a href=\"inventory.php\">Voltar</a>.";
   		echo "</fieldset>";
           	include(__DIR__ . "/templates/private_footer.php");
           	exit;
   	}
    if ($_POST['potion'] != \HP && $_POST['potion'] != \BHP && $_POST['potion'] != \MANA && $_POST['potion'] != \ENERGY){
   		include(__DIR__ . "/templates/private_header.php");
   		echo "<fieldset><legend><b>Erro</b></legend>\n";
           	echo "Selecione um tipo de po��o para enviar.<br />";
           	echo "<a href=\"inventory.php\">Voltar</a>.";
   		echo "</fieldset>";
           	include(__DIR__ . "/templates/private_footer.php");
           	exit;
   	}
    $veruser = $db->execute("select `id`, `username`, `serv` from `players` where `username`=?", [$_POST['to']]);
    if ($veruser->recordcount() == 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "O usu�rio " . $_POST['to'] . " n�o existe.<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include(__DIR__ . "/templates/private_footer.php");
        	exit;
	}
    $memberto = $veruser->fetchrow();
    if ($player->serv != $memberto['serv']) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "Este usu�rio pertence a outro servidor.<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include(__DIR__ . "/templates/private_footer.php");
        	exit;
	}
    if ($_POST['potion'] == \HP){
  		$pid = 136;
  		$tipo = "Health Potion";
  		}elseif ($_POST['potion'] == \BHP){
  		$pid = 148;
  		$tipo = "Big Health Potion";
  		}elseif ($_POST['potion'] == \MANA){
  		$pid = 150;
  		$tipo = "Mana Potion";
  		}elseif ($_POST['potion'] == \ENERGY){
  		$pid = 137;
  		$tipo = "Energy Potion";
  		}
    $quantia = floor($_POST['quantia']);
    $numpotio = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=? and `mark`='f'", [$player->id, $pid]);
    if ($numpotio->recordcount() < $quantia){
   		include(__DIR__ . "/templates/private_header.php");
   		echo "<fieldset><legend><b>Erro</b></legend>\n";
           	echo "Voc� n�o possui " . $quantia . " " . $tipo . "s para enviar.<br />";
           	echo "<a href=\"inventory.php\">Voltar</a>.";
   		echo "</fieldset>";
           	include(__DIR__ . "/templates/private_footer.php");
           	exit;
   	}
    $insert['player_id'] = $player->id;
    $insert['name1'] = $player->username;
    $insert['name2'] = $memberto['username'];
    $insert['action'] = "enviou";
    $insert['value'] = "<b>" . $quantia . " " . $tipo . "s</b>";
    $insert['itemid'] = 0;
    $insert['time'] = time();
    $query = $db->autoexecute('log_item', $insert, 'INSERT');
    $insert['player_id'] = $memberto['id'];
    $insert['name1'] = $memberto['username'];
    $insert['name2'] = $player->username;
    $insert['action'] = "recebeu";
    $insert['value'] = "<b>" . $quantia . " " . $tipo . "s</b>";
    $insert['itemid'] = 0;
    $insert['blue_id'] = $pid;
    $insert['time'] = time();
    $query = $db->autoexecute('log_item', $insert, 'INSERT');
    $logmsg = "O usu�rio <b>" . $player->username . "</b> lhe enviou <b>" . $quantia . " " . $tipo . "s</b>.";
    addlog($memberto['id'], $logmsg, $db);
    $mandapocoes = $db->execute("update `items` set `player_id`=? where `player_id`=? and `item_id`=? and `mark`='f' LIMIT ?", [$memberto['id'], $player->id, $pid, $quantia]);
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Sucesso</b></legend>\n";
    echo "Voc� acaba de enviar " . $quantia . " " . $tipo . "s para " . $memberto['username'] . ".<br />";
    echo "<a href=\"inventory.php\">Voltar</a>.";
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}
