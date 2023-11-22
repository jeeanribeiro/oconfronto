<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Editar perfil");
$player = check_user($secret_key, $db);

$error = 0;

include(__DIR__ . "/templates/private_header.php");

if ($_POST['submit']) {
    if (!$_POST['avatar']) {
        $errmsg .= "Por favor preencha todos os campos!";
        $error = 1;
    } elseif ($_POST['avatar'] && !@GetImageSize($_POST['avatar'])) {
        $errmsg .= "O endere�o desta imagem n�o � v�lido!";
        $error = 1;
    }

    if ($error == 0) {

	$avat = $_POST['avatar'] ?: "anonimo.gif";

        $query = $db->execute("update `players` set `avatar`=? where `id`=?", [$avat, $player->id]);
        $msg .= "Voc� alterou seu avatar com sucesso!";
    }
}

?>

<fieldset>
<legend><b>Editar perfil</b></legend>
<table width="100%">
<form method="POST" action="avatar.php">
<tr><td width="30%"><b>Coment�rios</b>:</td><td><a href="edit_comment.php">Clique aqui para editar seus coment�rios.</a></td></tr>
<tr><td width="30%"><b>Avatar</b>:</td><td><input type="text" name="avatar" value="<?=$player->avatar?>" size="45"/></td></tr>
<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Salvar Dados"></td></tr>
</table>
</form>
<p /><font color=green><?=$msg?></font><p />
<p /><font color=red><?=$errmsg?></font><p />
</fieldset>

<?php include(__DIR__ . "/templates/private_footer.php");
?>