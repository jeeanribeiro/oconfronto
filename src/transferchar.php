<?php

	include("lib.php");
	define("PAGENAME", "Login");
	$acc = check_acc($secret_key, $db);

if ($_GET['cancel'])
{
$cancel0 = $db->execute("select * from `pending` where `pending_id`=4 and `pending_other`=?", array($acc->id));
	if ($cancel0->recordcount() > 0){
	$dileti = $db->execute("delete from `pending` where `pending_id`=4 and `pending_other`=?", array($acc->id));
	include("templates/acc_header.php");
	echo "<br/><br/><br/><center>Voc� cancelou a solicita��o de transfer�ncia de personagem. <a href=\"characters.php\">Voltar</a>.</center><br/>";
	include("templates/acc_footer.php");
	exit;
	}else{
	include("templates/acc_header.php");
	echo "<br/><br/><br/><center>Nenhuma solicita��o de transfer�ncia encontrada. <a href=\"characters.php\">Voltar</a>.</center><br/>";
	include("templates/acc_footer.php");
	exit;
	}
}

		$error = 0;

$querynumplayers = $db->execute("select `id` from `players` where `acc_id`=?", array($acc->id));
if ($querynumplayers->recordcount() > 19)
{
include("templates/acc_header.php");
echo "<br/><br/><br/><br/><center>Voc� j� atingiu o n�mero m�ximo de personagens por conta, vinte.<br/>Voc� n�o pode mais adicionar personagens nesta conta. <a href=\"characters.php\">Voltar</a>.</center><br/>";
include("templates/acc_footer.php");
exit;
}

if (!$_GET['id'])
{
	include("templates/acc_header.php");
	echo "<center>Digite o nome do personagem que voc� deseja transferir para sua conta.</center><br/><br/>";
	echo "<form method=\"get\" action=\"transferchar.php\"><center><b>Personagem: <input type=\"text\" name=\"id\" size=\"25\"/> <input type=\"submit\" name=\"submit\" value=\"Enviar\"></b></center></form>";
	include("templates/acc_footer.php");
	exit;
}else{
	$query0 = $db->execute("select * from `players` where `username`=?", array($_GET['id']));
       	$query1 = $db->execute("select * from `pending` where `pending_id`=4 and `pending_status`=?", array($_GET['id']));
       	$query2 = $db->execute("select * from `pending` where `pending_id`=4 and `pending_other`=?", array($char['acc_id']));
       	$query3 = $db->execute("select * from `pending` where `pending_id`=4 and `player_id`=?", array($acc->id));

	if ($query0->recordcount() != 1){
	include("templates/acc_header.php");
	echo "<br/><br/><br/><center>Personagem n�o encontrado. <a href=\"transferchar.php\">Voltar</a>.</center><br/>";
	include("templates/acc_footer.php");
	exit;
	}else{
	$char = $query0->fetchrow();
	}

	if ($char['acc_id'] == $acc->id){
	include("templates/acc_header.php");
	echo "<br/><br/><br/><center>Este personagem j� pertence a sua conta. <a href=\"characters.php\">Voltar</a>.</center><br/>";
	include("templates/acc_footer.php");
	exit;
	}

	if ($query1->recordcount() > 0){
	include("templates/acc_header.php");
	echo "<br/><br/><br/><center>J� existe uma solicita��o de transfer�ncia pendente com este personagem. <a href=\"characters.php\">Voltar</a>.</center><br/>";
	include("templates/acc_footer.php");
	exit;
	}

	if ($query2->recordcount() > 0){
	include("templates/acc_header.php");
	echo "<br/><br/><br/><center>J� existe uma solicita��o de transfer�ncia pendente com a conta deste personagem. <a href=\"characters.php\">Voltar</a>.</center><br/>";
	include("templates/acc_footer.php");
	exit;
	}

	if ($query3->recordcount() > 0){
	include("templates/acc_header.php");
	echo "<br/><br/><br/><center>J� existe uma solicita��o de transfer�ncia pendente com sua conta. <a href=\"characters.php\">Voltar</a>.</center><br/>";
	include("templates/acc_footer.php");
	exit;
	}

	if ($_POST['submit']){
		$cconta = $db->GetOne("select `conta` from `accounts` where `id`=?", array($char['acc_id']));
		$ccontappassss = $db->GetOne("select `password` from `accounts` where `id`=?", array($char['acc_id']));

		if ((!$_POST['conta']) or (!$_POST['senhadaconta'])){
		$errmsg .= "Preencha todos os campos<br/>";
		$error = 1;
		}else if ((!$_POST['transferpass']) and ($char['transpass'] != f)){
		$errmsg .= "Preencha todos os campos<br/>";
		$error = 1;
		}else if ($_POST['conta'] != $cconta){
		$errmsg .= "Algum dado preenchido n�o confere.<br/>";
		$error = 1;
		}else if (sha1($_POST['senhadaconta']) != $ccontappassss){
		$errmsg .= "Algum dado preenchido n�o confere.<br/>";
		$error = 1;
		}else if (($_POST['transferpass'] != $char['transpass']) and ($char['transpass'] != f)){
		$errmsg .= "Algum dado preenchido n�o confere.<br/>";
		$error = 1;
		}

		if ($error == 0){

		$insert['player_id'] = $acc->id;
		$insert['pending_id'] = 4;   	  
		$insert['pending_status'] = $char['username'];
		$insert['pending_time'] = (time() + 1296000);
		$insert['pending_other'] = $char['acc_id'];
		$query = $db->autoexecute('pending', $insert, 'INSERT');

		include("templates/acc_header.php");
		echo "<br/><br/><br/><center>Voc� solicitou a tranfer�ncia de " . $char['username'] . " para sua conta.<br/>Por motivos de seguran�a, voc� ter� que aguardar 14 dias para ver " . $char['username'] . " em sua conta. <a href=\"characters.php\">Voltar</a>.</center><br/>";
		include("templates/acc_footer.php");
		exit;
		}

	}

	include("templates/acc_header.php");
	echo "<br/><br/><br/>";
?>

<fieldset>
<legend><b>Digite as seguintes informa��es de <?=$char['username'];?></b></legend>
<form method="POST" action="transferchar.php?id=<?=$_GET['id'];?>">
<table>
<tr><td width="40%"><b>Conta</b>:</td><td><input type="password" name="conta" value="<?=$_POST['conta'];?>" size="20"/></td></tr>
<tr><td width="40%"><b>Senha da conta</b>:</td><td><input type="password" name="senhadaconta" value="<?=$_POST['senhadaconta'];?>" size="20"/></td></tr>
<?php
if ($char['transpass'] != f){
echo "<tr><td width=\"40%\"><b>Senha de Tranfer�ncia</b>:</td><td><input type=\"password\" name=\"transferpass\" value=\"" . $_POST['transferpass'] . "\" size=\"20\"/></td></tr>";
}
?>
<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Transferir <?=$char['username'];?> para minha conta"></td></tr>
</table>
</form>
<p /><font color=red><?=$errmsg?></font><p />
</fieldset>
<br/>
<a href="characters.php">Voltar</a>.

<?php
include("templates/acc_footer.php");
exit;
}
?>