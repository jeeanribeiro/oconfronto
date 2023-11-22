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
$username = ($_GET['username']);

//Populates $guild variable
$guildquery = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);

if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");

//Guild Leader Admin check
if ($player->username != $guild['leader'] && $player->username != $guild['vice']) {
    echo "Voc� n�o pode acessar esta p�gina. <a href=\"home.php\">Voltar</a>.";
} elseif ($guild['members'] >= ($guild['maxmembers'])) {
    echo "Seu cl� j� est� grande demais! (max. " . $guild['maxmembers'] . " membros).<br/><a href=\"guild_admin.php\">Voltar</a>.";
} else {
//If username is set
if (isset($_GET['username']) && ($_GET['submit'])) {
    //Checks if player exists
	$query = $db->execute("select `id`, `guild`, `serv` from `players` where `username`='$username'");
	$member = $query->fetchrow();
	
    if ($query->recordcount() == 0) {
        $errmsg .= "<center><b>Este usu�rio n�o existe!</b></center>";
        $error = 1;
    } elseif ($member['serv'] != $guild['serv']) {
        $errmsg .= "<center><b>Este usu�rio pertence a outro servidor.</b></center>";
        $error = 1;
    } elseif ($member['guild'] != NULL) {
        $errmsg .= "<center><b>Voc� n�o pode convidar um usu�rio que est� em outro cl�!</b></center>";
        $error = 1;
    } else {	//Insert user invite into guild_invites table
    			$insert['player_id'] = $member['id'];
    			$insert['guild_id'] = $guild['id'];
    			$query = $db->autoexecute('guild_invites', $insert, 'INSERT');
    			
    			if (!$query) {
    				$errmsg .= "<center><b>N�o foi possivel convidar o usu�rio! Provavelmete ele j� est� convidado.</b></center>";
    			}
    			else {
    				$logmsg = "Est�o te convidando para participar do cl�: <b><a href=\"guild_profile.php?id=" . $guild['id'] . "\">" . $guild['name'] . "</a></b>. <b><a href=\"guild_join.php?id=" . $guild['id'] . "\">Participar</a>.<br/>O custo para participar deste cl� � de " . $guild['price'] . " de ouro.</a></b>";
					addlog($member['id'], $logmsg, $db);
    				$msg .= "<center><b>Voc� convidou $username para o cl�.</b></center>";
    			}
    	   }
	}

?>

<fieldset>
<p />
<legend><b><?=$guild['name']?> :: Convidar usu�rios</b></legend>
<p />
<form method="GET" action="guild_admin_invite.php">
<b>Usu�rio:</b> <input type="text" name="username" size="20"/> <input type="submit" name="submit" value="Convidar"><p />
</form>
<p /><?=$msg?><p />
<p /><font color=red><?=$errmsg?></font><p />
</fieldset>
<a href="guild_admin.php">Voltar</a>.
<?php
}
include(__DIR__ . "/templates/private_footer.php");
?>