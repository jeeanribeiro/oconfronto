<?php
include("lib.php");

define("PAGENAME", "Lista de Amigos");
$player = check_user($secret_key, $db);
$maxfriends = 20; //Max friends allowed
//start counting friends
$num_rows_query = mysql_query("SELECT * FROM `friends` WHERE `uid` = $player->id");
$num_rows = mysql_num_rows($num_rows_query);
//end counting friends

$zeroamigos = 0;
$totalgkills = 0;

?>
<?php
if($_GET['add']){
	if($_GET['add'] == $player->username){
	include("templates/private_header.php");
		echo "Voc� n�o pode adicionar voc� mesmo!<br><a href=\"friendlist.php\">Voltar � lista de amigos</a> | <a href=\"members.php\">Voltar � lista de membros</a>";
	include("templates/private_footer.php");
		exit;
	}elseif($num_rows + 1 > $maxfriends){
	include("templates/private_header.php");
		echo "Voc� atingiu o numero m�ximo de amigos!<br><a href=\"friendlist.php\">Voltar � lista de amigos</a> | <a href=\"members.php\">Voltar � lista de membros</a>";
	include("templates/private_footer.php");
	exit;
	}

	$quereya = $db->execute("select * from `friends` where `fname`=? and `uid`=?", array($_GET['add'], $player->id));
	if ($quereya->recordcount() > 0){
	include("templates/private_header.php");
	echo "Voc� j� tem este usu�rio na sua lista de amigos!<br><a href=\"friendlist.php\">Voltar � lista de amigos</a> | <a href=\"members.php\">Voltar � lista de membros</a>";
	include("templates/private_footer.php");
	exit;
	}

	$quereya = $db->execute("select `username` from `players` where `username`=?", array($_GET['add']));
	if ($quereya->recordcount() == 0){
	include("templates/private_header.php");
	echo "Este usu�rio n�o existe!<br><a href=\"friendlist.php\">Voltar � lista de amigos</a> | <a href=\"members.php\">Voltar � lista de membros</a>";
	include("templates/private_footer.php");
	exit;
	}

	$amigoserver = $db->GetOne("select `serv` from `players` where `username`=?", array($_GET['add']));
	if ($player->serv != $amigoserver){
	include("templates/private_header.php");
	echo "Este usu�rio pertence a outro servidor!<br><a href=\"friendlist.php\">Voltar � lista de amigos</a> | <a href=\"members.php\">Voltar � lista de membros</a>";
	include("templates/private_footer.php");
	exit;
	}
		include("templates/private_header.php");
		$add = $db->GetOne("select `username` from `players` where `username`=?", array($_GET['add']));

		$asql="INSERT INTO `friends` (`uid` ,`fname`)VALUES ('$player->id', '$add')";
		$aresult=mysql_query($asql);
			if($aresult){
			echo "Amigo adicionado!<br><a href=\"friendlist.php\">Voltar � lista de amigos</a> | <a href=\"members.php\">Voltar � lista de membros</a>";
			include("templates/private_footer.php");
			exit;
			}else{
			echo "Um erro desconhecido ocorreu!<br><a href=\"friendlist.php\">Voltar � lista de amigos</a> | <a href=\"members.php\">Voltar � lista de membros</a>";
			include("templates/private_footer.php");
			exit;
			}
}
elseif ($_GET['delete']){
$dsql = $db->execute("select * from `friends` where `uid`=? and `fname`=?", array($player->id, $_GET['delete']));
	if ($dsql->recordcount() > 0){
	$deletaoamigo = $db->execute("delete from `friends` where `uid`=? and `fname`=?", array($player->id, $_GET['delete']));
	include("templates/private_header.php");
	echo "Amigo removido!<br><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}else{
	include("templates/private_header.php");
	echo "Um erro desconhecido ocorreu.<br><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}
}
elseif ($_GET['deleteinvite']){
$dsql2 = $db->execute("select * from `group_invite` where `group_id`=? and `invited_id`=?", array($player->id, $_GET['deleteinvite']));
	if ($dsql2->recordcount() > 0){
	$deletaoconviti = $db->execute("DELETE FROM `group_invite` WHERE `group_id`=? AND `invited_id`=?", array($player->id, $_GET['deleteinvite']));
	include("templates/private_header.php");
	echo "Convite para grupo de ca�a removido.<br><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}else{
	include("templates/private_header.php");
	echo "Um erro desconhecido ocorreu.<br><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}
}
elseif ($_GET['deleteconvite']){
$dsql4 = $db->execute("select * from `group_invite` where `group_id`=? and `invited_id`=?", array($_GET['deleteconvite'], $player->id));
	if ($dsql4->recordcount() > 0){
	$deletaoconviti = $db->execute("DELETE FROM `group_invite` WHERE `group_id`=? AND `invited_id`=?", array($_GET['deleteconvite'], $player->id));
	include("templates/private_header.php");
	echo "O convite foi recusado.<br><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}else{
	include("templates/private_header.php");
	echo "Um erro desconhecido ocorreu.<br><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}
}
elseif ($_GET['deletedogrupo']){
$dsql3 = $db->execute("select * from `groups` where `id`=? and `player_id`=?", array($player->id, $_GET['deletedogrupo']));
	if ($dsql3->recordcount() > 0){

			if ($player->id == $_GET['deletedogrupo']){
			include("templates/private_header.php");
			echo "Voc� n�o pode se expulsar do seu pr�pio grupo.<br><a href=\"friendlist.php\">Voltar</a>.";
			include("templates/private_footer.php");
			exit;
			}

		$logmsg = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> te expulsou do grupo de ca�a.";
		addlog($_GET['deletedogrupo'], $logmsg, $db);

	$deletegrpomember = $db->execute("DELETE FROM `groups` WHERE `id`=? AND `player_id`=?", array($player->id, $_GET['deletedogrupo']));
	include("templates/private_header.php");
	echo "Usu�rio removido do seu grupo de ca�a.<br><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}else{
	include("templates/private_header.php");
	echo "Um erro desconhecido ocorreu.<br><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}
}
elseif ($_GET['addgroup']){
	$verificaantesdegrupo1 = $db->execute("select `id`, `username`, `level` from `players` where `username`=?", array($_GET['addgroup']));
	if ($verificaantesdegrupo1->recordcount() == 0)
	{
	include("templates/private_header.php");
	echo "Amigo n�o encontrado!<br /><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}else{
	$groupfriend = $verificaantesdegrupo1->fetchrow();
	}

 	if ($player->level < 30){
	include("templates/private_header.php");
	echo "Seu n�vel � inferior � 30. Apenas usu�rios de n�vel 30 ou mais podem criar grupos de ca�a.<br /><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}

 	if ($groupfriend['level'] < 30){
	include("templates/private_header.php");
	echo "O usu�rio que voc� deseja convidar possui n�vel inferior � 30.<br /><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}

 	if ($groupfriend['level'] > ($player->level + 30)){
	include("templates/private_header.php");
	echo "A diferen�a de n�vel entre voc� e seu amigo � maior que 30 n�veis.<br /><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}

 	if ($groupfriend['level'] < ($player->level - 30)){
	include("templates/private_header.php");
	echo "A diferen�a de n�vel entre voc� e seu amigo � maior que 30 n�veis.<br /><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}

	$checkseeamigo = $db->execute("select * from `friends` WHERE `uid`=? and `fname`=?", array($player->id, $groupfriend['username']));
	if ($checkseeamigo->recordcount() == 0)
	{
	include("templates/private_header.php");
	echo "O usu�rio " . $groupfriend['username'] . " n�o � seu amigo.<br /><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}

	$checkseteminvitegrupo = $db->execute("select * from `group_invite` WHERE `invited_id`=? and `group_id`=?", array($groupfriend['id'], $player->id));
	if ($checkseteminvitegrupo->recordcount() > 0)
	{
	include("templates/private_header.php");
	echo "Um convite j� foi enviado ao seu amigo.<br /><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}

	$checksetemgrupo = $db->execute("select * from `groups` WHERE `player_id`=?", array($groupfriend['id']));
	if ($checksetemgrupo->recordcount() > 0)
	{
	include("templates/private_header.php");
	echo "Seu amigo j� est� em um grupo de ca�a.<br /><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}

	$checksevctemgrupo = $db->execute("select * from `groups` WHERE `player_id`=? and `id`!=?", array($player->id, $player->id));
	if ($checksevctemgrupo->recordcount() > 0)
	{
	include("templates/private_header.php");
	echo "Voc� j� est� em um grupo de ca�a. Para criar um novo grupo primeiro saia de seu grupo atual.<br /><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}

	$mandaconvite = $db->execute("select * from `groups` WHERE `id`=?", array($player->id));
	if ($mandaconvite->recordcount() == 0)
	{
		$insert['id'] = $player->id;
		$insert['player_id'] = $player->id;
		$criaogrupo = $db->autoexecute('groups', $insert, 'INSERT');
	}

		$insert['group_id'] = $player->id;
		$insert['invited_id'] = $groupfriend['id'];
		$mandaoconvittix = $db->autoexecute('group_invite', $insert, 'INSERT');

			$logmsg = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> est� te convidando para fazer parte um grupo de ca�a. <a href=\"group_accept.php?id=" . $player->id . "\">Clique aqui</a> para aceitar.";
			addlog($groupfriend['id'], $logmsg, $db);

	include("templates/private_header.php");
	echo "" . $groupfriend['username'] . " foi convidado para fazer parte do seu grupo de ca�a.<br /><a href=\"friendlist.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}

include("templates/private_header.php");
?>
<fieldset>
<legend><b>Amigos</b></legend>
<?php
$query = $db->execute("select `fname` from `friends` WHERE `uid`=? order by `fname` asc", array($player->id));
if ($query->recordcount() == 0)
{
	echo "<br/><center><b><font size=\"1\">Voc� n�o tem amigos.</font></b></center><br/>";
	$zeroamigos = 5;
}
else
{

	echo "<table width=\"100%\" border=\"0\">";
	echo "<tr>";
	echo "<th width=\"15%\"><b>Imagem</b></td>";
	echo "<th width=\"25%\"><b>Usu�rio</b></td>";
	echo "<th width=\"20%\"><b>Nivel</b></td>";
	echo "<th width=\"20%\"><b>Voca��o</b></td>";
	echo "<th width=\"15%\"><b>Op��es</b></td>";
	echo "</tr>";

	while($friend = $query->fetchrow())
	{

		$queryromulo = $db->execute("select `id`, `username`, `gm_rank`, `level`, `avatar`, `voc`, `promoted` from `players` where `username`=?", array($friend['fname']));
		$member = $queryromulo->fetchrow();
		echo "<tr>\n";

	echo "<td height=\"64px\"><div style=\"position: relative;\">";
	echo "<img src=\"" . $member['avatar'] . "\" width=\"64px\" height=\"64px\" style=\"position: absolute; top: 1; left: 1;\" alt=\"" . $member['username'] . "\" border=\"0\">";

	$checkranknosite = $db->execute("select `time` from `online` where `player_id`=?", array($member['id']));
	if ($checkranknosite->recordcount() > 0) {
	echo "<img src=\"images/online1.gif\" width=\"64px\" height=\"64px\" style=\"position: absolute; top: 1; left: 1;\" alt=\"" . $member['username'] . "\" border=\"0\">";
	}

	echo "</div></td>";

	echo "<td><a href=\"profile.php?id=" . $member['username'] . "\">";
	echo ($member['username'] == $player->username)?"<b>":"";
	echo $member['username'];
	echo ($member['username'] == $player->username)?"</b>":"";
	echo "</a></td>\n";
	echo "<td>" . $member['level'] . "</td>\n";
	echo "<td>";
if ($member['voc'] == 'archer' and $member['promoted'] == 'f'){
echo "Ca�ador";
} else if ($member['voc'] == 'knight' and $member['promoted'] == 'f'){
echo "Espadachim";
} else if ($member['voc'] == 'mage' and $member['promoted'] == 'f'){
echo "Bruxo";
} else if (($member['voc'] == 'archer') and ($member['promoted'] == 't' or $member['promoted'] == 's' or $member['promoted'] == 'r')){
echo "Arqueiro";
} else if (($member['voc'] == 'knight') and ($member['promoted'] == 't' or $member['promoted'] == 's' or $member['promoted'] == 'r')){
echo "Guerreiro";
} else if (($member['voc'] == 'mage') and ($member['promoted'] == 't' or $member['promoted'] == 's' or $member['promoted'] == 'r')){
echo "Mago";
} else if ($member['voc'] == 'archer' and $member['promoted'] == 'p'){
echo "Arqueiro Royal";
} else if ($member['voc'] == 'knight' and $member['promoted'] == 'p'){
echo "Cavaleiro";
} else if ($member['voc'] == 'mage' and $member['promoted'] == 'p'){
echo "Arquimago";
}
	echo "</td>\n";
	echo "<td><font size=\"1\"><a href=\"mail.php?act=compose&to=" . $member['username'] . "\">Mensagem</a><br/><a href=\"battle.php?act=attack&username=" . $member['username'] . "\">Lutar</a><br/> - <a href=\"friendlist.php?delete=". $member['username'] ."\">Amigo</a></font></td>\n";
	echo "</tr>\n";
	}
	echo "</table>";
}
?>
</fieldset>
<?php
if ($zeroamigos != 5){
echo "<font size=\"1\"><b>Voc� tem ".$num_rows." amigo(s)</b></font>";
}

	echo "<br/><br/>\n";
	echo "<fieldset>\n";
	echo "<legend><b>Grupo de Ca�a</b></legend>\n";
	$procuraseugrupo = $db->execute("select * from `groups` WHERE `player_id`=?", array($player->id));
		if ($procuraseugrupo->recordcount() == 0)
		{
		echo "<br/><center><b><font size=\"1\">Voc� n�o possui um grupo de ca�a.</font></b></center><br/>";
 			if ($player->level < 30){
			echo "<center><b><font size=\"1\">Apenas usu�rios de n�vel 30 ou mais podem criar grupos de ca�a.</font></b></center><br/>";
			}
		}else{
		$procuragrupoinfo = $procuraseugrupo->fetchrow();
		$iddddoseugrupo = $procuragrupoinfo['id'];

	echo "<table width=\"100%\" border=\"0\">";
	echo "<tr>";
	echo "<th width=\"25%\"><b>Usu�rio</b></td>";
	echo "<th width=\"10%\"><b>N�vel</b></td>";
	echo "<th width=\"10%\"><b>EXP</b></td>";
	echo "<th width=\"35%\"><b>Informa��o</b></td>";
	echo "<th width=\"20%\"><b>Op��es</b></td>";
	echo "</tr>";

	$listamembersgrupo = $db->execute("select groups.player_id, groups.exp, groups.kills, players.id, players.username, players.level from `groups`, `players` WHERE groups.id=? and players.id=groups.player_id", array($iddddoseugrupo));
		while($grupoaceito = $listamembersgrupo->fetchrow())
		{
		echo "<tr>";
		echo "<td><a href=\"profile.php?id=" . $grupoaceito['username'] . "\">" . $grupoaceito['username'] . "</a></td>";
		echo "<td>" . $grupoaceito['level'] . "</td>";

		$porcentoexperiencia = floor(100 / $listamembersgrupo->recordcount());
		echo "<td>" . $porcentoexperiencia . "%</td>";

		echo "<td><font size=\"1\">Gerou " . $grupoaceito['exp'] . " de experi�ncia.</font></td>";
		$totalgkills = $totalgkills + $grupoaceito['kills'];

		if ($player->id == $grupoaceito['id']){
		echo "<td><font size=\"1\"><a href=\"group_leave.php?id=". $iddddoseugrupo ."\">Sair do Grupo</a></font></td>";
		}elseif ($player->id == $iddddoseugrupo){
		echo "<td><font size=\"1\"><a href=\"friendlist.php?deletedogrupo=". $grupoaceito['id'] ."\">Expulsar do Grupo</a></font></td>";
		}else{
		echo "<td><font size=\"1\"><a href=\"mail.php?act=compose&to=". $grupoaceito['username'] ."\">Mensagem</a></font></td>";
		}

		echo "</tr>";
		}

				$procuraconvidados = $db->execute("select * from `group_invite` WHERE `group_id`=?", array($iddddoseugrupo));
				if ($procuraconvidados->recordcount() > 0)
				{
					while($convidado = $procuraconvidados->fetchrow())
					{
					$exibeconvidadosinfo = $db->execute("select `id`, `username`, `level`, `avatar`, `voc`, `promoted` from `players` where `id`=?", array($convidado['invited_id']));
					$invited = $exibeconvidadosinfo->fetchrow();
					echo "<tr>";
					echo "<td><a href=\"profile.php?id=" . $invited['username'] . "\">" . $invited['username'] . "</a></td>";
					echo "<td>" . $invited['level'] . "</td>";
					echo "<td>#</td>";
					echo "<td><font size=\"1\">Aguardando aceitar convite.</font></td>";

					if ($iddddoseugrupo == $player->id){
					echo "<td><font size=\"1\"><a href=\"friendlist.php?deleteinvite=". $invited['id'] ."\">Remover Convite</a></font></td>";
					}else{
					echo "<td><font size=\"1\"><a href=\"mail.php?act=compose&to=". $invited['username'] ."\">Mensagem</a></font></td>";
					}

					echo "</tr>";
					}
				}

	echo "</table>";
	}
	
	if ($procuraseugrupo->recordcount() > 0)
	{
	echo "<center><font size=\"1\">Seu grupo j� matou " . $totalgkills . " monstros.</font></center>";
	}

	echo "</fieldset>\n";
	if ($procuraseugrupo->recordcount() > 0)
	{
	echo "<center><font size=\"1\">" . $listamembersgrupo->recordcount() . " usu�rio(s) no grupo. M�ximo de 4 usu�rios.</font></center>";
	}


	$convitex1 = $db->execute("select * from `group_invite` WHERE `invited_id`=?", array($player->id));
		if ($convitex1->recordcount() > 0)
		{
		echo "<br/><br/>";
		echo "<fieldset>";
		echo "<legend><b>Convites para grupos de Ca�a</b></legend>\n";

	echo "<table width=\"100%\" border=\"0\">";
	echo "<tr>";
	echo "<th width=\"30%\"><b>Lider</b></td>";
	echo "<th width=\"10%\"><b>N�vel</b></td>";
	echo "<th width=\"20%\"><b>Membros</b></td>";
	echo "<th width=\"20%\"><b>Informa��o</b></td>";
	echo "<th width=\"20%\"><b>Op��es</b></td>";
	echo "</tr>";

			while($convitex2 = $convitex1->fetchrow())
			{
				$lidernamy = $db->GetOne("select `username` from `players` where `id`=?", array($convitex2['group_id']));
				$liderlevy = $db->GetOne("select `level` from `players` where `id`=?", array($convitex2['group_id']));
				$lidergorupmembis = $db->execute("select * from `groups` WHERE `id`=?", array($convitex2['group_id']));

				echo "<tr>";
				echo "<td><a href=\"profile.php?id=" . $lidernamy . "\">" . $lidernamy . "</a></td>";
				echo "<td>" . $liderlevy . "</td>";
				echo "<td>" . $lidergorupmembis->recordcount() . "</td>";
				if ($lidergorupmembis->recordcount() > 3){
				echo "<td><font size=\"1\">Sem Vagas</font></td>";
				}else{
				echo "<td><font size=\"1\">Dispon�vel</font></td>";
				}
				echo "<td><font size=\"1\"><a href=\"group_accept.php?id=". $convitex2['group_id'] ."\">Aceitar</a> / <a href=\"friendlist.php?deleteconvite=". $convitex2['group_id'] ."\">Recusar</a></font></td>";
				echo "</tr>";
			}
	echo "</table>";

		}
		echo "</fieldset>";

	echo "<br/><br/>\n";
	echo "<fieldset>\n";
	echo "<legend><b>Op��es</b></legend>\n";
	echo "<form method=\"get\" action=\"friendlist.php\">\n";
	echo "<table width=\"100%\">\n";
	echo "<tr><td width=\"30%\"><b><font size=\"1\">Adicionar Amigo:</font></b></td>\n<td width=\"40%\"><input type=\"text\" name=\"add\" /></td>";
	echo "<td width=\"30%\"><input type=\"submit\" value=\"Adicionar\" /></td></tr>";

if (($zeroamigos != 5) and ($player->level > 29)){
	if (($procuraseugrupo->recordcount() == 0) or (($procuraseugrupo->recordcount() != 0) and ($iddddoseugrupo == $player->id))){

	echo "<tr><td width=\"30%\"><b><font size=\"1\">Adicionar Amigo no Grupo de Ca�a:</font></b></td>\n<td width=\"40%\">";

	$queryfriends = $db->execute("select `fname` from `friends` WHERE `uid`=?", array($player->id));
	echo "<select name=\"addgroup\"><option value=''>Selecione</option>";
	while($result = $queryfriends->fetchrow()){
	echo "<option value=\"" . $result['fname'] . "\">" . $result['fname'] . "</option>";
	}
	echo "</select></td>";
	echo "<td width=\"30%\"><input type=\"submit\" value=\"Adicionar\" /></td></tr>";
	}
}
	echo "</table>\n";
	echo "</form>\n</fieldset>\n";

	include("templates/private_footer.php");
?>