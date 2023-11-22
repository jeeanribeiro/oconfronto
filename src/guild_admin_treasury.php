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
$username = ($_POST['username']);
$amount = floor($_POST['amount']);

//Populates $guild variable
$query = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);

if ($query->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $query->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");

//Guild Leader Admin check
if ($player->username != $guild['leader'] && $player->username != $guild['vice']) {
    echo "<p />Voc� n�o pode acessar esta p�gina.<p />";
    echo "<a href=\"home.php\">Principal</a><p />";
} else {

if (isset($_POST['username']) && ($_POST['amount']) && ($_POST['submit'])) {
	
	$query = $db->execute("select * from `players` where `username`=?", [$username]);
	
    if ($query->recordcount() == 0) {
        $errmsg .= "Este usu�rio n�o existe!<p />";
        $error = 1;
    } elseif ($amount < 1) {
        $errmsg .= "Voc� n�o pode enviar esta quantia de dinheiro!<p />";
        $error = 1;
    } elseif (!is_numeric($amount)) {
        $errmsg .= "Voc� n�o pode enviar esta quantia de dinheiro!<p />";
        $error = 1;
    } elseif ($amount > $guild['gold']) {
        $errmsg .= "Seu cl� n�o possui esta quantia de dinheiro!<p />";
        $error = 1;
    } else {
        $member = $query->fetchrow();
        	if ($member['guild'] != $guild['id']) {
    			$errmsg .= "O usu�rio $username n�o � membro do cl� ". $guild['name'] ."!<p />";
    			$error = 1;
        	} else {
            	$query = $db->execute("update `guilds` set `gold`=? where `id`=?", [$guild['gold'] - $amount, $player->guild]);
            	$query1 = $db->execute("update `players` set `gold`=? where `username`=?", [$member['gold'] + $amount, $member['username']]);
            	$logmsg = "Voc� recebeu <b>$amount</b> de ouro do cl�: <b>". $guild['name'] ."</b>.";
				addlog($member['id'], $logmsg, $db);

		$insert['player_id'] = $member['id'];
		$insert['name1'] = $player->username;
		$insert['name2'] = $guild['name'];
		$insert['action'] = "ganhou";
		$insert['value'] = $amount;
		$insert['aditional'] = "gangue";
		$insert['time'] = time();
		$query = $db->autoexecute('log_gold', $insert, 'INSERT');

            	$msg .= "Voc� tranferiu <b>$amount</b> de ouro para: <b>$username</b>.<p />";
        	}
    	}
	}

?>

<fieldset>
<legend><b><?=$guild['name']?> :: Tranferir Ouro</b></legend>
<form method="POST" action="guild_admin_treasury.php">
<table>
<tr>
<td><b>Usu�rio:</b></td><td><input type="text" name="username" size="20"/></td></tr>
<td><b>Quantia:</b></td><td><input name="amount" size="20" type="text"> <input type="submit" name="submit" value="Enviar"></td></tr>
</table>
</form>
</fieldset>
<p /><?=$msg?><p />
<p /><font color=red><?=$errmsg?></font><p />
<fieldset>
<legend><b><?=$guild['name']?> :: Saldo</b></legend>
Existe <b><?=$guild['gold']?> de ouro</b> no tesouro do cl�.
</fieldset>

<?php
}
include(__DIR__ . "/templates/private_footer.php");
?>