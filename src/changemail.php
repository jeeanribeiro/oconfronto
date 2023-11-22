<?php
	include(__DIR__ . "/lib.php");
	define("PAGENAME", "Alterar Email");
	$acc = check_acc($secret_key, $db);

	include(__DIR__ . "/templates/acc_header.php");

if ($_GET['act'] == \CANCEL){
$query = $db->execute("delete from `pending` where `pending_id`=1 and `player_id`=?", [$acc->id]);
echo "<br/><br/><br/><center>A solicita��o para mudan�a de email foi removida. <a href=\"characters.php\">Voltar</a>.</center><br/>";
include(__DIR__ . "/templates/acc_footer.php");
exit;
}

	echo "<br/><center><font size=\"1\"><b>Email Atual:</b> " . $acc->email . ".</font></center><br/>";


if ($_POST['submit']) {
    if (!$_POST['senhadaconta']) {
        $errmsg .= "Voc� precisa preencher todos os campos.";
        $error = 1;
    } elseif (!$_POST['emaill']) {
        $errmsg .= "Voc� precisa preencher todos os campos.";
        $error = 1;
    } elseif (!$_POST['emaill2']) {
        $errmsg .= "Voc� precisa preencher todos os campos.";
        $error = 1;
    } elseif (sha1((string) $_POST['senhadaconta']) != $acc->password) {
        $errmsg .= "Seu senha antiga est� incorreta.";
        $error = 1;
    } elseif ($_POST['emaill'] != $_POST['emaill2']) {
        $errmsg .= "Voc� n�o digitou os dois emails corretamente!";
        $error = 1;
    } elseif (strlen((string) $_POST['emaill']) < 3) {
        $errmsg .= "O seu endere�o de email deve conter mais de 5 caracteres.";
        $error = 1;
    } elseif (strlen((string) $_POST['emaill']) > 200) {
        $errmsg .= "O seu endere�o de email deve conter menos de 200 caracteres.";
        $error = 1;
    } elseif (!preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", (string) $_POST['emaill'])) {
        $errmsg .= "O formato do seu email � inv�lido!";
        $error = 1;
    } else {
        $query = $db->execute("select `id` from `accounts` where `email`=?", [$_POST['emaill']]);
        $query2 = $db->execute("select * from `pending` where `pending_id`=1 and `player_id`=?", [$acc->id]);
        $query3 = $db->execute("select * from `pending` where `pending_id`=1 and `pending_status`=?", [$_POST['emaill']]);
        if ($query->recordcount() > 0) {
            $errmsg .= "Este email j� est� em uso.";
            $error = 1;
        } elseif ($query2->recordcount() > 0) {
            $errmsg .= "Voc� j� enviou uma solicita��o de mudan�a de email.";
            $error = 1;
        } elseif ($query3->recordcount() > 0) {
            $errmsg .= "Este email j� est� em uso.";
            $error = 1;
        }
    }
    if ($error == 0) {
        	$insert['player_id'] = $acc->id;
		$insert['pending_id'] = 1;   	  
		$insert['pending_status'] = $_POST['emaill'];
		$insert['pending_time'] = (time() + 1_296_000);
		$query = $db->autoexecute('pending', $insert, 'INSERT');
        $msg .= "Voc� solicitou a mudan�a de seu email para: " . $_POST['emaill'] . ".<br/>Por motivos de seguran�a, seu email s� ser� alterado depois de 14 dias.";
    }
}

?>

<fieldset>
<legend><b>Alterar email</b></legend>
<form method="POST" action="changemail.php">
<table>
<tr><td width="40%"><b>Senha da conta</b>:</td><td><input type="password" name="senhadaconta" value="<?=$_POST['senhadaconta'];?>" size="20"/></td></tr>
<tr><td width="40%"><b>Novo email</b>:</td><td><input type="text" name="emaill" value="<?=$_POST['emaill'];?>" size="25"/></td></tr>
<tr><td width="40%"><b>Repita o email</b>:</td><td><input type="text" name="emaill2" value="<?=$_POST['emaill2'];?>" size="25"/></td></tr>
<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Alterar Email"></td></tr>
</table>
</form>
<p /><font color=green><?=$msg?></font><p />
<p /><font color=red><?=$errmsg?></font><p />
</fieldset>
<br/>
<a href="characters.php">Voltar</a>.

<?php include(__DIR__ . "/templates/acc_footer.php");
?>