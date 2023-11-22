<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include(__DIR__ . "/lib.php");
define("PAGENAME", "Entrar no Cl�");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

//Check for user ID
if (!$_GET['id']) {
	header("Location: guild_listing.php");
} else {
	//Populates $guild variable
	$query = $db->execute("select * from  guilds  where  id =?", [$_GET['id']]);
	if ($query->recordcount() == 0) {
		header("Location: guild_listing.php");
	} else {
		$guild = $query->fetchrow();
	}
	
	include(__DIR__ . "/templates/private_header.php");
	//Checks if player is in a guild or cannot afford the guild price
	if ($player->guild != NULL) {
		echo "Voc� j� est� em um cl�!<br/>";
		echo "<a href=\"home.php\">Principal</a>";
	} elseif ($player->gold < $guild['price']) {
		echo "Voc� n�o tem dinheiro para entar no cl�. Custa " . $guild['price'] . " de ouro.<br/>";
		echo "<a href=\"home.php\">Principal</a><p />";
	} else {
		$mayjoin = true;
		if ($db->execute("show tables like 'guild_invites'")->recordcount() > 0) { // if guild invites mod is installed ...
			$checkquery = $db->execute("select count(*) inv_count from guild_invites where player_id =? and guild_id =?", [$player->id, $guild['id']]);	
			$check = $checkquery->fetchrow();
			if ($check['inv_count'] > 0) {
				$db->execute("delete from guild_invites where guild_id=? and player_id=?", [$guild['id'], $player->id]);	
			} else {
				echo "Voc� n�o foi convidado por este cl�.<br/>";
				echo "<a href=\"home.php\">Principal</a>.";
				$mayjoin = false;
			}
		}
		if ($mayjoin == true) {
			$db->execute("update players set  gold=?, guild=? where id=?", [$player->gold - $guild['price'], $guild['id'], $player->id]);
			$db->execute("update guilds set members=?, gold=? where id=?", [$guild['members'] + 1, $guild['gold'] + $guild['price'], $guild['id']]);
			echo "Obrigado por participar do cl�: <b>" . $guild['name'] . "</b>!<br/>";
			echo "<a href=\"home.php\">Principal</a>.";
		}
	}
	include(__DIR__ . "/templates/private_footer.php");
}

?>