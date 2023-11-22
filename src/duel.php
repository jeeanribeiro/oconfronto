<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Duelos");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");
$customprice = 0;

if($_GET['add']){

} elseif($_GET['deny']) {
	$denyduel = $db->execute("select * from `duels` where `id`=?", [$_GET['deny']]);
	if ($denyduel->recordcount() == 0){
        	include(__DIR__ . "/templates/private_header.php");
        	echo "<fieldset><legend><b>Duelo</b></legend>";
        	echo "Convite de duelo n�o encontrado.";
        	echo "</fieldset>";
		echo"<br/><a href=\"duel.php\">Voltar</a>.</br>";
        	include(__DIR__ . "/templates/private_footer.php");
		exit;
	}
 $denyrow = $denyduel->fetchrow();
 if ($denyrow['owner'] != $player->id && $denyrow['rival'] != $player->id) {
     include(__DIR__ . "/templates/private_header.php");
     echo "<fieldset><legend><b>Duelo</b></legend>";
     echo "Convite de duelo n�o encontrado.";
     echo "</fieldset>";
     echo"<br/><a href=\"duel.php\">Voltar</a>.</br>";
     include(__DIR__ . "/templates/private_footer.php");
     exit;
 }
		if ($denyrow['owner'] == $player->id) {
      $db->execute("delete from `duels` where `id`=? and `owner`=?", [$_GET['deny'], $player->id]);
      $denyacao = "cancelar";
  } elseif ($denyrow['rival'] == $player->id) {
      $db->execute("update `duels` set `active`='d' where `id`=? and `rival`=?", [$_GET['deny'], $player->id]);
      $denyacao = "recusar";
  }

        	include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Duelo</b></legend>";
        	echo "Voc� acaba de " . $denyacao . " o duelo.";
        	echo "</fieldset>";
		echo"<br/><a href=\"duel.php\">Voltar</a>.</br>";
        	include(__DIR__ . "/templates/private_footer.php");
		exit;

} elseif($_POST['submit']){
$futurorival = $db->execute("select `id`, `username`, `bank`, `serv` from `players` where `username`=?", [$_POST['rival']]);
$rival = $futurorival->fetchrow();

	if (!$_POST['rival'] || $futurorival->recordcount() == 0){
        include(__DIR__ . "/templates/private_header.php");
        	echo "<fieldset><legend><b>Duelo</b></legend>";
        	echo "Preencha um nome de usu�rio v�lido.";
        	echo "</fieldset>";
		echo"<br/><a href=\"duel.php\">Voltar</a>.</br>";
        	include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($_POST['prize']){
		if (!is_numeric($_POST['prize'])){
        		include(__DIR__ . "/templates/private_header.php");
        		echo "<fieldset><legend><b>Duelo</b></legend>";
        		echo "O valor da aposta n�o � v�lido.";
        		echo "</fieldset>";
			echo"<br/><a href=\"duel.php\">Voltar</a>.</br>";
        		include(__DIR__ . "/templates/private_footer.php");
        		exit;
		}
		if ($_POST['prize'] < 0){
        		include(__DIR__ . "/templates/private_header.php");
        		echo "<fieldset><legend><b>Duelo</b></legend>";
        		echo "O valor da aposta n�o � v�lido.";
        		echo "</fieldset>";
			echo"<br/><a href=\"duel.php\">Voltar</a>.</br>";
        		include(__DIR__ . "/templates/private_footer.php");
        		exit;
		}
		if ($_POST['prize'] > $player->bank){
        		include(__DIR__ . "/templates/private_header.php");
        		echo "<fieldset><legend><b>Duelo</b></legend>";
        		echo "Voc� n�o possui " . $_POST['prize'] . " no banco.";
        		echo "</fieldset>";
			echo"<br/><a href=\"duel.php\">Voltar</a>.</br>";
        		include(__DIR__ . "/templates/private_footer.php");
        		exit;
		}
		if ($_POST['prize'] > $rival['bank']){
        		include(__DIR__ . "/templates/private_header.php");
        		echo "<fieldset><legend><b>Duelo</b></legend>";
        		echo "Seu rival n�o possui " . $_POST['prize'] . " no banco.";
        		echo "</fieldset>";
			echo"<br/><a href=\"duel.php\">Voltar</a>.</br>";
        		include(__DIR__ . "/templates/private_footer.php");
        		exit;
		}
		$customprice = 1;
	}

	if ($player->serv != $rival['serv']){
        	include(__DIR__ . "/templates/private_header.php");
        	echo "<fieldset><legend><b>Duelo</b></legend>";
        	echo "Esse usu�rio pertence a outro servidor.";
        	echo "</fieldset>";
		echo"<br/><a href=\"duel.php\">Voltar</a>.</br>";
        	include(__DIR__ . "/templates/private_footer.php");
        	exit;
	}

	if ($player->username == $rival['username']){
        	include(__DIR__ . "/templates/private_header.php");
        	echo "<fieldset><legend><b>Duelo</b></legend>";
        	echo "Voc� n�o pode duelar contra voc� mesmo.";
        	echo "</fieldset>";
		echo"<br/><a href=\"duel.php\">Voltar</a>.</br>";
        	include(__DIR__ . "/templates/private_footer.php");
        	exit;
	}

	$addjahexists = $db->execute("select `id` from `duels` where ((`owner`=? and `rival`=?) or (`owner`=? and `rival`=?))", [$player->id, $rival['id'], $rival['id'], $player->id]);
	if ($addjahexists->recordcount() > 0){
        	include(__DIR__ . "/templates/private_header.php");
        	echo "<fieldset><legend><b>Duelo</b></legend>";
        	echo "J� existe um convite de duelo entre voc� e " . $rival['username'] . ".";
        	echo "</fieldset>";
		echo"<br/><a href=\"duel.php\">Voltar</a>.</br>";
        	include(__DIR__ . "/templates/private_footer.php");
        	exit;
	}

		$insert['owner'] = $player->id;
		$insert['rival'] = $rival['id'];
		if ($customprice == 1){
		$insert['prize'] = $_POST['prize'];
		}
		$insert['time'] = time();
		$insert['active'] = 'w';
		$query = $db->autoexecute('duels', $insert, 'INSERT');

	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Duelo</b></legend>";
	echo "Voc� desafiou " . $rival['username'] . " para um duelo.<br/>";
	echo "Seu rival apenas poder� aceitar o duelo quando ambos estiverem online.<br/>";
	echo "Voc� n�o poder� retirar do banco o ouro que apostou at� que ven�a o duelo ou cancele-o.";
	echo "</fieldset>";
	echo"<br/><a href=\"duel.php\">Voltar</a>.</br>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}


include(__DIR__ . "/templates/private_header.php");

	echo "<fieldset>\n";
	echo "<legend><b>Duelos</b></legend>\n";
	$procuraseusduelos = $db->execute("select * from `duels` where `owner`=?", [$player->id]);
		if ($procuraseusduelos->recordcount() == 0)
		{
		echo "<br/><center><b><font size=\"1\">Voc� n�o desafiou ningu�m no momento.</font></b></center><br/>";
		}else{

		echo "<table width=\"100%\" border=\"0\">";
		echo "<tr>";
		echo "<th width=\"25%\"><b>Usu�rio</b></td>";
		echo "<th width=\"10%\"><b>N�vel</b></td>";
		echo "<th width=\"30%\"><b>Info</b></td>";
		echo "<th width=\"15%\"><b>Status</b></td>";
		echo "<th width=\"20%\"><b>Op��es</b></td>";
		echo "</tr>";

			while($rivalid = $procuraseusduelos->fetchrow())
			{
			$getrivalinfo = $db->execute("select `username`, `level` from `players` where `id`=?", [$rivalid['rival']]);
			$rivalinfo = $getrivalinfo->fetchrow();

			echo "<tr>";
			echo "<td><a href=\"profile.php?id=" . $rivalinfo['username'] . "\">" . $rivalinfo['username'] . "</a></td>";
			echo "<td>" . $rivalinfo['level'] . "</td>";

			if ($rivalid['active'] == 'd'){
			echo "<td><font size=\"1\">Convite recusado.</font></td>";
			}else{
			echo "<td><font size=\"1\">Aguardando aceitar convite.</font></td>";
			}

				$checkrivalonline = $db->execute("select * from `online` where `player_id`=?", [$rivalid['rival']]);
				if ($checkrivalonline->recordcount() > 0) {
				echo "<td><font size=\"1\">Online</font></td>";
				}else{
				echo "<td><font size=\"1\">Offline</font></td>";
				}

			echo "<td><font size=\"1\"><a href=\"mail.php?act=compose&to=". $rivalinfo['username'] ."\">Mensagem</a><br/><a href=\"duel.php?deny=". $rivalid['id'] ."\">Cancelar Duelo</a></font></td>";
			echo "</tr>";
			}

		echo "</table>";
		}
	echo "</fieldset>";

	echo "<br/><br/>";
	echo "<fieldset>";
	echo "<legend><b>Desafiar Usu�rio</b></legend>";
	echo "<form method=\"POST\" action=\"duel.php\">";
	echo "<table width=\"100%\">";
	echo "<tr>";
	echo "<td width=\"20%\"><b><font size=\"1\">Usu�rio:</font></b></td>";
	echo "<td width=\"80%\"><input type=\"text\" name=\"rival\" /></td>";
	echo "</tr><tr>";
	echo "<td width=\"20%\"><b><font size=\"1\">Aposta:</font></b></td>";
	echo "<td width=\"40%\"><input type=\"text\" name=\"prize\" value=\"0\"/></td>";
	echo "<td width=\"40%\"><input type=\"submit\" name=\"submit\" value=\"Desafiar\" /></td></tr>";
	echo "</table>";
	echo "</form></fieldset>";

include(__DIR__ . "/templates/private_footer.php");
?>