<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Administra��o do Cl�");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$error = 0;
$errorb = 0;

//Populates $guild variable
$guildquery = $db->execute("select `id`, `name`, `leader`, `vice`, `members`, `serv` from `guilds` where `id`=?", [$player->guild]);

if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");

//Guild Leader Admin check
if ($player->username != $guild['leader'] && $player->username != $guild['vice']) {
    echo "Voc� n�o pode acessar esta p�gina.";
    echo "<br/><a href=\"home.php\">Voltar</a>.";
} else {

if ($_GET['unaliance'] && $_GET['aled_na']){

$alynamme = $_GET['aled_na'];

	$acheckcla = $db->execute("select `name` from `guilds` where `id`=?", [$alynamme]);
	$bcheckjaaly = $db->execute("select `id` from `guild_paliance` where (`guild_na`=? and `aled_na`=?) or (`guild_na`=? and `aled_na`=?)", [$guild['id'], $alynamme, $alynamme, $guild['id']]);
	$ccheckjaaly = $db->execute("select `id` from `guild_aliance` where `guild_na`=? and `aled_na`=?", [$guild['id'], $alynamme]);
	
	if ($acheckcla->recordcount() != 1) {
    		$errmsg .= "Este cl� n�o existe!";
   		$errorb = 1;
	}elseif ($bcheckjaaly->recordcount() < 1 && $ccheckjaaly->recordcount() < 1) {
    		$errmsg .= "Este cl� n�o � um cl� aliado!";
   		$errorb = 1;
	} elseif ($errorb === 0) {
     $deletaaliancagname = $db->GetOne("select `name` from `guilds` where `id`=?", [$_GET['aled_na']]);
     $log1 = $db->execute("select `id` from `players` where `guild`=?", [$alynamme]);
     while($p1 = $log1->fetchrow())
  			{
      			$logmsg1 = "O cl� <a href=\"guild_profile.php?id=". $guild['id'] ."\">". $guild['name'] ."</a> desfez as alian�a que tinha com seu cl�.";
  			addlog($p1['id'], $logmsg1, $db);
  			}
     $msgallyname = $db->GetOne("select `name` from `guilds` where `id`=?", [$alynamme]);
     $log2 = $db->execute("select `id` from `players` where `guild`=?", [$guild['id']]);
     while($p2 = $log2->fetchrow())
  			{
      			$logmsg2 = "Seu cl� desfez as alian�as que tinha com o cl� <a href=\"guild_profile.php?id=". $alynamme ."\">". $msgallyname ."</a>.";
  			addlog($p2['id'], $logmsg2, $db);
  			}
     $query = $db->execute("delete from `guild_aliance` where `guild_na`=? and `aled_na`=?", [$guild['id'], $alynamme]);
     $query = $db->execute("delete from `guild_aliance` where `guild_na`=? and `aled_na`=?", [$alynamme, $guild['id']]);
     $query = $db->execute("delete from `guild_paliance` where `guild_na`=? and `aled_na`=?", [$guild['id'], $alynamme]);
     $query = $db->execute("delete from `guild_paliance` where `guild_na`=? and `aled_na`=?", [$alynamme, $guild['id']]);
     $msg .= "As liga��es com o cl� " . $deletaaliancagname . " foram removidas com sucesso.";
 }

}elseif (isset($_POST['gname']) && ($_POST['submit'])) {
    
	$checkcla = $db->execute("select `id`, `leader`, `vice`, `name`, `serv` from `guilds` where `id`=?", [$_POST['gname']]);
	$checkjaaly0 = $db->execute("select `id` from `guild_paliance` where (`guild_na`=? and `aled_na`=?) or (`guild_na`=? and `aled_na`=?)", [$guild['id'], $_POST['gname'], $_POST['gname'], $guild['id']]);
	$checkjaaly1 = $db->execute("select `id` from `guild_aliance` where `guild_na`=? and `aled_na`=?", [$guild['id'], $_POST['gname']]);
	$checkjaaly2 = $db->execute("select `id` from `guild_enemy` where `guild_na`=? and `enemy_na`=?", [$guild['id'], $_POST['gname']]);
	$aliancaguildname = $db->GetOne("select `name` from `guilds` where `id`=?", [$_POST['gname']]);

    if ($checkcla->recordcount() != 1) {
        $errmsg .= "Este cl� n�o existe!";
        $error = 1;
    } elseif ($checkjaaly0->recordcount() > 0) {
        $errmsg .= "Uma solicita��o de alian�a entre o seu cl� e o cl� " . $aliancaguildname . " j� est� pendente.";
        $error = 1;
    } elseif ($checkjaaly1->recordcount() > 0) {
        $errmsg .= "Este cl� j� � um aliado!";
        $error = 1;
    } elseif ($checkjaaly2->recordcount() > 0) {
        $errmsg .= "Este cl� � um cl� inimigo!";
        $error = 1;
    } elseif ($error === 0) {
        $enyguild = $checkcla->fetchrow();
        if ($guild['serv'] != $enyguild['serv']){
     			echo "Este cl� pertence a outro servidor.";
       			echo "<br/><a href=\"guild_admin_aliado.php\">Voltar</a>.";
     			include(__DIR__ . "/templates/private_footer.php");
     			exit;
     			}
        $to1 = $db->GetOne("select `id` from `players` where `username`=?", [$enyguild['leader']]);
        $to2 = $db->GetOne("select `id` from `players` where `username`=?", [$enyguild['vice']]);
        $insert['guild_na'] = $guild['id'];
        $insert['aled_na'] = $enyguild['id'];
        $insert['time'] = time();
        $acpt = $db->autoexecute('guild_paliance', $insert, 'INSERT');
        $acptid = $db->Insert_ID();
        $logmsg = "O cl� <a href=\"guild_profile.php?id=". $guild['name'] ."\">". $guild['name'] ."</a> est� solicitando uma alian�a com seu cl�. <a href=\"guild_admin_accept.php?id=" . $acptid . "\">Clique aqui</a> para aceitar.";
        addlog($to1, $logmsg, $db);
        $logmsg2 = "O cl� <a href=\"guild_profile.php?id=". $guild['name'] ."\">". $guild['name'] ."</a> est� solicitando uma alian�a com seu cl�. <a href=\"guild_admin_accept.php?id=" . $acptid . "\">Clique aqui</a> para aceitar.";
        addlog($to2, $logmsg2, $db);
        $msg .= "Voc� solicitou uma alian�a com o cl� " . $enyguild['name'] . ". Se ela for aceita, voc� ser� informado.";
    } else{
     		$errmsg .= "Um erro desconhecido ocorreu.";
     		$error = 1;
  		}
}
?>

<fieldset>
<legend><b><?=$guild['name']?> :: Cl�s Aliados</b></legend>
<p />
<form method="POST" action="guild_admin_aliado.php">
<b>Solicitar alian�a com o cl�:</b> <?php $query = $db->execute("select `id`, `name` from `guilds` where `name`!=? and `serv`=?", [$guild['name'], $guild['serv']]);
echo "<select name=\"gname\"><option value=''>Selecione</option>";
while($result = $query->fetchrow()){
echo "<option value=\"$result[id]\">$result[name]</option>";
}
echo "</select>"; ?> <input type="submit" name="submit" value="Solicitar Alian�a">
</form>
</fieldset>
<center><p /><font color=green><?=$msg?></font><p /></center>
<center><p /><font color=red><?=$errmsg?></font><p /></center>
<br/>
<fieldset>
<legend><b>Gerenciar Alian�as</b></legend>
<?php
$query0000 = $db->execute("select `aled_na` from `guild_aliance` where `guild_na`=? order by `aled_na` asc", [$guild['id']]);
$query0001 = $db->execute("select `aled_na` from `guild_paliance` where `guild_na`=? order by `aled_na` asc", [$guild['id']]);
$query0002 = $db->execute("select `id`, `guild_na` from `guild_paliance` where `aled_na`=? order by `aled_na` asc", [$guild['id']]);

if ($query0000->recordcount() < 1 && $query0001->recordcount() < 1 && $query0002->recordcount() < 1) {
echo "<p /><center><b>Seu cl� n�o possui alian�as.</b></center><p />";
}else{
echo "<p />";
while($ali = $query0000->fetchrow()){
$postgname = $db->GetOne("select `name` from `guilds` where `id`=?", [$ali[\ALED_NA]]);
echo "<b>Cl�: <a href=\"guild_profile.php?id=" . $ali[\ALED_NA] . "\">" . $postgname . "</b></a> - <a href=\"guild_admin_aliado.php?unaliance=true&aled_na=" . $ali[\ALED_NA] . "\">Desfazer Alian�a</a>";
}
while($ali = $query0001->fetchrow()){
$postgname2 = $db->GetOne("select `name` from `guilds` where `id`=?", [$ali[\ALED_NA]]);
echo "<b>Cl�: <a href=\"guild_profile.php?id=" . $ali[\ALED_NA] . "\">" . $postgname2 . "</b></a> - <a href=\"guild_admin_aliado.php?unaliance=true&aled_na=" . $ali[\ALED_NA] . "\">Remover solicita��o de alian�a</a>";
}
while($ali = $query0002->fetchrow()){
$postgname3 = $db->GetOne("select `name` from `guilds` where `id`=?", [$ali[\GUILD_NA]]);
echo "<b>Cl�: <a href=\"guild_profile.php?id=" . $ali[\GUILD_NA] . "\">" . $postgname3 . "</b></a> - <a href=\"guild_admin_accept.php?id=" . $ali[\ID] . "\">Aceitar Alian�a</a>";
}
echo "<p />";
}
?>
</fieldset>


<?php
}
include(__DIR__ . "/templates/private_footer.php");
?>