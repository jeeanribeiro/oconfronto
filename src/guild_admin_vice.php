<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include(__DIR__ . "/lib.php");
define("PAGENAME", "Administra��o do Cl�");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$error = 0;

//Populates $guild variable
$guildquery = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);

if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");

//Guild Leader Admin check
if ($player->username != $guild['leader']) {
    echo "<p />Voc� n�o pode acessar esta p�gina.<p />";
    echo "<a href=\"home.php\">Principal</a><p />";
} else {

if ($_GET['remove']) {
if ($guild['vice'] == NULL || $guild['vice'] == ''){
$msg .= "Seu cl� n�o possui um vice lider.";
}else{
$msg .= "Voc� removeu os privil�gios de vice lider de " . $guild['vice'] . ".<br/><a href=\"guild_admin.php\">Voltar</a>.";
$query = $db->execute("update `guilds` set `vice`='' where `id`=?", [$guild['id']]);
}
}


if (isset($_POST['username']) && ($_POST['submit'])) {

	$username = $_POST['username'];
	$query = $db->execute("select `id`, `username`, `guild` from `players` where `username`=? and `serv`=?", [$username, $guild['serv']]);

    if ($query->recordcount() == 0) {
        $errmsg .= "Este usu�rio n�o existe!<p />";
        $error = 1;
    } elseif ($username == $guild['leader']) {
        $errmsg .= "Este usu�rio � o lider do cl�!<p />";
        $error = 1;
    } elseif ($username == $guild['vice']) {
        $errmsg .= "Este usu�rio � o vice-lider do cl�!<p />";
        $error = 1;
    } else {
   		$member = $query->fetchrow();
	   		if ($member['guild'] != $guild['id']) {
    			$errmsg .= "O usu�rio $username n�o faz parte do cl�: " . $member['guild'] ."!<p />";
    			$error = 1;
    		} else {
			if ($guild['vice'] == NULL || $guild['vice'] == ''){
    			$msg .= "Voc� nomeou $username como vice-lider do cl�.<br/><a href=\"guild_admin.php\">Voltar</a>.";
			}else{
    			$msg .= "Voc� nomeou $username como vice-lider do cl�. O antigo vice-lider, " . $guild['vice'] . "  agora � um membro comum.<br/><a href=\"guild_admin.php\">Voltar</a>.";
			}

    			$query = $db->execute("update `guilds` set `vice`=? where `id`=?", [$username, $guild['id']]);
    			$logmsg = "Voc� foi nomeado vice-lider do cl�: ". $guild['name'] .".";
				addlog($member['id'], $logmsg, $db);
    		}
    	}
	}


if ($guild['vice'] == NULL || $guild['vice'] == ''){
$viceatual1 = "Ningu�m.";
}else{
$viceatual1 = $guild['vice'];
$viceatual2 = "<a href=\"guild_admin_vice.php?remove=" . $guild['vice'] . "\">Remover Vice-Lideran�a de " . $guild['vice'] . "</a>.";
}
?>

<fieldset>
<legend><b><?=$guild['name']?> :: Nomear Vice-Lider</b></legend>
<b>Vice-Lider atual:</b> <?=$viceatual1?> <?=$viceatual2?><br/><br/>
<form method="POST" action="guild_admin_vice.php">
<b>Usu�rio:</b> <?php $query = $db->execute("select `id`, `username` from `players` where `guild`=?", [$guild['id']]);
echo "<select name=\"username\"><option value=''>Selecione</option>";
while($result = $query->fetchrow()){
echo "<option value=\"$result[username]\">$result[username]</option>";
}
echo "</select>"; ?> <input type="submit" name="submit" value="Nomear Vice-Lider"><p />
</form>
<b>ATEN��O:</b> Um vice-lider tem todas as fun��es do administrador do cl�, porem n�o pode desfazer o mesmo e nem nomear novos vice lideres.<p /><?=$msg?><font color=red><?=$errmsg?></font>
</fieldset>

<?php
}
include(__DIR__ . "/templates/private_footer.php");
?>