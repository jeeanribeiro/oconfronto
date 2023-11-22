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
$query = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);

if ($query->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $query->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");
?>
<script type="text/javascript" src="bbeditor/ed.js"></script>
<?php
//Guild Leader Admin check
if ($player->username != $guild['leader'] && $player->username != $guild['vice']) {
    echo "Voc� n�o tem acesso a essa p�gina.<br/>";
    echo "<a href=\"home.php\">Principal</a>.";
include(__DIR__ . "/templates/private_footer.php");
exit;
}
//If price set then update query
if (isset($_POST['price']) && ($_POST['submit'])) {
    if (($_POST['price']) < 0) {
        $msg1 .= "<font color=\"red\">O pre�o para entrar no cl� deve ser 0 ou mais.</font><p />";
        $error = 1;
    } elseif ($_POST['price'] == $guild['price']) {
        $error = 1;
    } elseif ($_POST['price'] > 999999) {
        $msg1 .= "<font color=\"red\">O pre�o maximo � de 999999!</font><p />";
        $error = 1;
    } elseif (!is_numeric($_POST['price'])) {
        $msg1 .= "<font color=\"red\">Este valor n�o � valido.</font><p />";
        $error = 1;
    } else {
        $query = $db->execute("update `guilds` set `price`=? where `id`=?", [$_POST['price'], $guild['id']]);
        $msg1 .= "Voc� trocou o pre�o para entrar no seu cl�.<p />";
    }
}
//Imagem by jrotta
if (isset($_POST['img']) && ($_POST['submit'])) {
    if (strlen((string) $_POST['img']) < 12) {
        $msg2 .= "<font color=\"red\">O endere�o da imagem deve ser maior que 12 caracteres!</font><p />";
        $error = 1;
    }
    elseif (!@GetImageSize($_POST['img'])) {
        $msg2 .= "<font color=\"red\">O endere�o da imagem n�o � valido!</font><p />";
        $error = 1;
    } else {
        $query = $db->execute("update `guilds` set `img`=? where `id`=?", [$_POST['img'], $guild['id']]);
        $msg2 .= "Voc� trocou a imagem do seu cl�.<p />";
    }
}
//If motd set then update query
if (isset($_POST['motd']) && ($_POST['submit'])) {
    if (strlen((string) $_POST['motd']) < 3) {
        $msg3 .= "<font color=\"red\">A mensagem do seu cl� deve conter de 5 � 50 caracteres!</font><p />";
        $error = 1;
    } elseif ($_POST['motd'] == $guild['motd']) {
        $error = 1;
    } elseif (strlen((string) $_POST['motd']) > 50) {
        $msg3 .= "<font color=\"red\">A mensagem do seu cl� deve conter de 5 � 50 caracteres!</font><p />";
        $error = 1;
    } else {
        $query = $db->execute("update `guilds` set `motd`=? where `id`=?", [$_POST['motd'], $guild['id']]);
        $msg3 .= "Voc� trocou a mensagem do seu cl�.<p />";
    }
}
//If blurb set then update query
if (isset($_POST['blurb']) && ($_POST['submit'])) {
    if (strlen((string) $_POST['blurb']) < 50) {
        $msg4 .= "<font color=\"red\">A descri��o deve ser maior que 50 caracteres!</font><p />";
        $error = 1;
    } elseif ($_POST['blurb'] == $guild['blurb']) {
        $error = 1;
    } elseif (strlen((string) $_POST['blurb']) > 5000) {
        $msg4 .= "<font color=\"red\">A descri��o deve ser menor que 5000 caracteres!</font><p />";
        $error = 1;
    } else {
        $tirahtmldades=strip_tags((string) $_POST['blurb']);
        $texto=nl2br($tirahtmldades);

$listaExtensao = ['JPG' => 1, 'jpg' => 2, 'PNG' => 3, 'png' => 4, 'BMP' => 5, 'bmp' => 6, 'GIF' => 7, 'gif' => 8];
$aux = " " . $texto . "";


while(true){
	$inicioImg = 0;

	$inicioImg = strpos($aux ,'[img]');
	
	if($inicioImg < 1) {
		break;
	}
	
	$fimImg = strpos($aux ,'[/img]');
	$tamanho = strlen($aux);
	$parteAnterior = substr($aux , 0, $inicioImg);
	$partePosterior = substr($aux , $fimImg+6, $tamanho-1);
	$parteLink = substr($aux ,  $inicioImg, $fimImg-$inicioImg+6);
	$extensao = substr($parteLink, strlen($parteLink)-9,3);

	if(!array_key_exists($extensao, $listaExtensao)){
		$parteLink  = '[IMG REMOVIDA]';
	}
	$textoFinal = $textoFinal.$parteAnterior.$parteLink;
	$aux = $partePosterior;


}
	$mostraimg = $textoFinal . "" . $aux;
	$mostraimg = substr($mostraimg, 1);


        $query = $db->execute("update `guilds` set `blurb`=? where `id`=?", [$mostraimg, $guild['id']]);
        $msg4 .= "Voc� trocou a descri��o do seu cl�.<p />";
    }
}
?>
<center><font size=4><b>Administra��o do Cl� :: <?=$guild['name'];?></b></font></center>
<p /><p />
<fieldset>
<legend><b>Pagamento do Cl�</b></legend>
<?php
		$valortempo = $guild['pagopor'] - time();
		if ($valortempo < 60) {
      $valortempo2 = $valortempo;
      $auxiliar2 = "segundo(s)";
  } elseif ($valortempo < 3600) {
      $valortempo2 = floor($valortempo / 60);
      $auxiliar2 = "minuto(s)";
  } elseif ($valortempo < 86400) {
      $valortempo2 = floor($valortempo / 3600);
      $auxiliar2 = "hora(s)";
  } elseif ($valortempo > 86400) {
      $valortempo2 = floor($valortempo / 86400);
      $auxiliar2 = "dia(s)";
  }
?>
<center><b>Cl� pago por:</b> <?=$valortempo2;?> <?=$auxiliar2;?>. <a href="guild_admin_pay.php">Pagar mais</a>.<br>Este cl� ser� deletado se o tempo acabar e voc� n�o pagar mais.</center>
</fieldset><p /><p />
<center>
<input type="button" VALUE="Enviar Mensagem" ONCLICK="window.location.href='guild_admin_msg.php'">
<input type="button" VALUE="Convidar usu�rio" ONCLICK="window.location.href='guild_admin_invite.php'">
<input type="button" VALUE="Expulsar membro" ONCLICK="window.location.href='guild_admin_kick.php'">
<input type="button" VALUE="Tesouro" ONCLICK="window.location.href='guild_admin_treasury.php'"><br/>
<input type="button" VALUE="Nomear/Remover Vice-Lider" ONCLICK="window.location.href='guild_admin_vice.php'">
<input type="button" VALUE="Passar lideran�a" ONCLICK="window.location.href='guild_admin_leadership.php'">
<input type="button" VALUE="Desfazer o cl�" ONCLICK="window.location.href='guild_admin_disband.php'"><br/>
<input type="button" VALUE="Cl�s Aliados" ONCLICK="window.location.href='guild_admin_aliado.php'">
<input type="button" VALUE="Cl�s Inimigos" ONCLICK="window.location.href='guild_admin_enemy.php'">
<input type="button" VALUE="Adquirir melhorias para o Cl�" ONCLICK="window.location.href='guild_admin_upgrade.php'"></center>
<p /><p />
<fieldset>
<legend><b>Editar perfil</b></legend>
<p />
<table width="100%">
<form method="POST" action="guild_admin.php">
<tr><td width="25%"><b>Pre�o para entrar</b>:</td><td><input type="text" name="price" value="<?php
if (!$_POST['price']){
echo $guild['price'];
}else{
echo $_POST['price'];
}
?>" size="10"/><br/><?=$msg1;?></td></tr>
<tr><td width="25%"><b>Imagem</b>:</td><td><input type="text" name="img" value="<?php
if (!$_POST['img']){
echo $guild['img'];
}else{
echo $_POST['img'];
}
?>" size="40"/><br/><?=$msg2;?></td></tr>
<tr><td width="25%"><b>Mensagem</b>:</td><td><input type="text" name="motd" size="40" value="<?php
if (!$_POST['motd']){
echo $guild['motd'];
}else{
echo $_POST['motd'];
}
?>"/><br/><?=$msg3;?></td></tr>
<tr><td width="25%"><b>Descri��o</b>:</td><td>
<?php
$textoreferencia = $_POST['blurb'] ?: $guild['blurb'];
?><script>edToolbar('blurb'); </script><textarea rows="12" name="blurb" id="blurb" class="ed"><?php
$quebras = ['<br />', '<br>', '<br/>'];
echo str_replace($quebras, "", (string) $textoreferencia);
?></textarea><font size="1"><br/>M�ximo de 5000 caracteres.</font> <?=$msg4;?></td></tr>
<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Atualizar"></td></tr>
</table>
</form>
</fieldset>
<?php
include(__DIR__ . "/templates/private_footer.php");
?>
