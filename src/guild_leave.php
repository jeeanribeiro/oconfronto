<?php
/*************************************/
/*           ezRPG Script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/
  
include(__DIR__ . "/lib.php");
define("PAGENAME", "Abandonar Cl�");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

//Populates $guild variable
$query = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);
if ($query->recordcount() == 0) {
      header("Location: home.php");
}else{ 
$guild = $query->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");
  
if ($_GET['act'] == "go") {
	$leader = $db->GetOne("select `leader` from `guilds` where `id`=?", [$player->guild]);
	$vice = $db->GetOne("select `vice` from `guilds` where `id`=?", [$player->guild]);
      
    if ($player->username != $leader && $player->username != $vice) {
    	$query = $db->execute("update `guilds` set `members`=? where `id`=?", [$guild['members'] - 1, $player->guild]);
        $query = $db->execute("update `players` set `guild`=? where `username`=?", [NULL, $player->username]);
        echo "Voc� abandonou seu cl� com sucesso.<br />";
        echo "<a href=\"home.php\">Principal</a>.";
    } else {
      	echo "Voc� n�o pode abandonar este cl�. Se voc� for o lider dele, dever� desfaze-lo primeiro. Se for o vice-lider, abandone seu cargo primeiro.<br />";
        echo "<a href=\"home.php\">Principal</a>.";
    }
} else {
      echo "Voc� tem certeza que quer abandonar seu cl�?<br />";
      echo "<a href=\"guild_leave.php?act=go\">Abandonar</a> | <a href=\"home.php\">Voltar</a>.";
}

include(__DIR__ . "/templates/private_footer.php");
?>