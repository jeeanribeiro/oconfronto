<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Miss�es");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");

if ($player->level < 300)
{
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Miss�o</b></legend>\n";
	echo "<i>Seu nivel � muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

	$verificacao1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 13]);
	$verificacao2 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 14]);

	if ($verificacao1->recordcount() > 0){
	$quest1 = $verificacao1->fetchrow();
	}

	if ($verificacao2->recordcount() > 0){
	$quest2 = $verificacao2->fetchrow();
	}

switch($_GET['act'])
{

	case "castle":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>J� ouvi v�rias hist�rias suas atrav�s de meus mensageiros, voc� parece ser um forte e destemido guerreiro, qualidades que eu adimiro. E por isso lhe chamei aqui, para realizar algumas tarefas em meu nome, oque acha?<br/>Voc� seria recompensado generosamente.</i><br><br>\n";
		echo "<a href=\"quest6.php?act=aceptcastle\">Aceitar</a> / <a href=\"quest6.php?act=declinecastle\">Recusar</a>";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
	break;


	case "declinecastle":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Tudo bem, poderia mandar lhe punirem caso recusace, mas tenho certeza de que voc� mudara de id�ia.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
	break;

	case "aceptcastle":
		if ($verificacao1->recordcount() > 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Aviso</b></legend>\n";
		echo "<i>Voc� j� aceitou esta miss�o.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}
  include(__DIR__ . "/templates/private_header.php");
  $insert['player_id'] = $player->id;
  $insert['item_id'] = 159;
  $query = $db->autoexecute('items', $insert, 'INSERT');
  $insert['player_id'] = $player->id;
  $insert['quest_id'] = 13;
  $insert['quest_status'] = 1;
  $query = $db->autoexecute('quests', $insert, 'INSERT');
  echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
  echo "<i>�timo, voc� fez a coisa certa ao aceitar. Vamos come�ar logo, preciso que alguem leve um pacote ao rei Rashar, � um pacote muito valioso, certifique-se que ele chegue em seguran�a.</i><br>";
  echo "<b>(voc� adiquiriu um pacote)</b><br><br>\n";
  echo "<a href=\"quest6.php?act=go\">Ir ao imp�rio de Rashar</a> / <a href=\"home.php\">Voltar</a>.";
  echo "</fieldset>";
  include(__DIR__ . "/templates/private_footer.php");
	break;

	case "go":
		$csadack = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=13 and (`quest_status`>100 or `quest_status`=90)", [$player->id]);
		if ($csadack->recordcount() != 0){
		header("Location: home.php");
		}else{
		include(__DIR__ . "/templates/private_header.php");

		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [time() + 36000, $player->id, 13]);

		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� est� a caminho do imp�rio de Rashar.</i><br>";
		echo "<i>Faltam 10 horas para voc� chegar.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		}
	break;

	case "entregar":
		if ($quest1['quest_status'] != 2){
		header("Location: home.php");
		}else{
		$vesetemobox = $db->execute("select * from `items` where `item_id`=159 and `player_id`=?", [$player->id]);
		if ($vesetemobox->recordcount() == 0){
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Rashar</b></legend>\n";
		echo "<i>Que pacote? Voc� n�o tem nenhum pacote no seu invent�rio.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a> / <a href=\"quest6.php?act=abort\">Abandonar miss�o</a>";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		}else{
		include(__DIR__ . "/templates/private_header.php");
		$upxxdateeaaa = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 13]);
		$deletaboxxe = $db->execute("delete from `items` where `item_id`=159 and `player_id`=? limit 1", [$player->id]);

		$insert['player_id'] = $player->id;
		$insert['quest_id'] = 14;   	  
		$insert['quest_status'] = 1;
		$query = $db->autoexecute('quests', $insert, 'INSERT');

		echo "<fieldset><legend><b>Rashar</b></legend>\n";
		echo "<i>Vejo que Alexander est� procurando novos guerreiros. Este pacote me � in�til, ele apenas est� testando sua confian�a. Vejo que voc� � honesto, pois este pacote possui grande valor comercial. Boa sorte guerreiro.</i><br><br>\n";
		echo "<a href=\"quest6.php?act=backalex\">Voltar � Alexander</a>";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		}
		}
	break;

	case "backalex":
	if ($quest1['quest_status'] == 90 && $quest2['quest_status'] == 1){

		$upxxdateeaz = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [time() + 36000, $player->id, 14]);

		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� est� indo � Alexander.</i><br>";
		echo "<i>Faltam 10 horas para voc� chegar.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");

		}else{
		header("Location: home.php");
		}
	break;


	case "finish":
	if ($quest1['quest_status'] == 90 && $quest2['quest_status'] == 80){

		$setnoventa = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 14]);

		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>�timo, vamos continuar.</i><br><br>\n";
		echo "<a href=\"quest7.php\">Continuar</a> / <a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");

		}else{
		header("Location: home.php");
		}
	break;

	case "nofinish":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>N�o sei oque passa em sua cabe�a, fazer parte da elite imperial � o sonho de todo guerreiro. Se mudar de id�ia, sinta-se livre para voltar aqui.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
	break;

	case "abort":
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 13]);
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [89, $player->id, 14]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� abandonou a miss�o.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
	break;
}
?>
<?php
	if ($verificacao1->recordcount() == 0)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Mensagem</b></legend>\n";
		echo "<i>" . $player->username . ", voc� atingiu altos n�veis de batalha, e o Rei deseja falar com voc� pessoalmente.</i><br/><br>\n";
		echo "<a href=\"quest6.php?act=castle\">Ir ao Castelo</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}


	if ($quest1['quest_status'] == 1)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Agora que eu j� lhe entreguei o pacote, v� ao imp�rio de Rashar.</i><br><br>";
		echo "<a href=\"quest6.php?act=go\">Ir ao imp�rio de Rashar</a> / <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] > 100)
		{
			if ($quest1['quest_status'] < time())
			{
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 13]);
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Miss�o</b></legend>\n";
			echo "<i>Voc� chegou no imp�rio de Rashar.</i><br><br>";
			echo "<a href=\"quest6.php\">Continuar</a>.";
	     	 	echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
			}

		include(__DIR__ . "/templates/private_header.php");
		$time = ($quest1['quest_status'] - time());
		$time_remaining = ceil($time / 60);
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� est� a caminho do imp�rio de Rashar.</i><br>";
		echo "<i>Faltam $time_remaining minuto(s) para voc� chegar.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 2)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Rashar</b></legend>\n";
		echo "<i>Ol� " . $player->username . ", oque lhe traz aqui?</i><br><br>";
		echo "<a href=\"quest6.php?act=entregar\">Entregar pacote</a> / <a href=\"home.php\">Voltar</a>.";
	     	echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 90 && $quest2['quest_status'] == 1)
		{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� j� entregou o pacote � Rashar, agora volte e fale com alexander.</i><br><br>";
		echo "<a href=\"quest6.php?act=backalex\">Voltar � Alexander</a>";
	     	echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest2['quest_status'] > 100)
		{
			if ($quest2['quest_status'] < time())
			{
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [80, $player->id, 14]);
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Miss�o</b></legend>\n";
			echo "<i>Voc� chegou � Alexander.</i><br><br>";
			echo "<a href=\"quest6.php\">Continuar</a>.";
	     	 	echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
			}

		include(__DIR__ . "/templates/private_header.php");
		$time = ($quest2['quest_status'] - time());
		$time_remaining = ceil($time / 60);
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� est� a caminho de Alexander.</i><br>";
		echo "<i>Faltam $time_remaining minuto(s) para voc� chegar.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}


	if ($quest2['quest_status'] == 80)
		{
		include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
			echo "<i>Ol� " . $player->username . ", recebi uma mensagem de Rashar, ele recebeu o pacote.</i><br />";
			echo "<i>Vejo que voc� � um guerreiro honesto, e com o treinamento que estou disposto a oferecer, voc� poder� ingressar na elite imperial. Oque acha? Estar� disposto a fazer alguns sacrificios?</i><br /><br />";
			echo "<a href=\"quest6.php?act=finish\">Sim</a> / <a href=\"quest6.php?act=nofinish\">N�o</a>.";
	     	 	echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		}

	if ($quest2['quest_status'] == 89)
	{
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� abandonou esta miss�o.</i><br><br>";
		echo "<a href=\"home.php\">Voltar</a>.";
	     	echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($quest2['quest_status'] == 90)
	{
		header("Location: quest7.php");
	}

?>