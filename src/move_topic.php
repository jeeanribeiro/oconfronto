<?php

include(__DIR__ . "/lib.php");
define("PAGENAME", "F�rum");
$player = check_user($secret_key, $db);

include(__DIR__ . "/templates/private_header.php");

if (!$_GET['topic']) {
    echo "Um erro desconhecido ocorreu! <a href=\"main_forum.php\">Voltar</a>.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}
if ($player->gm_rank > 2) {
    $procuramensagem = $db->execute("select `topic` from `forum_question` where `id`=?", [$_GET['topic']]);
} else {
    echo "Voc� n�o tem permis�es para mover este t�pico! <a href=\"view_topic.php?id=" . $_GET['topic'] . "\">Voltar</a>.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

if ($procuramensagem->recordcount() == 0) {
    echo "Um erro desconhecido ocorreu! <a href=\"main_forum.php\">Voltar</a>.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}
$nome = $procuramensagem->fetchrow();

if(isset($_POST['submit'])) {

    if (!$_POST['detail']) {
        echo "Voc� precisa preencher todos os campos! <a href=\"move_topic.php?topic=" . $_GET['topic'] . "\">Voltar</a>.";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }
    if ($_POST['detail'] == 'none') {
        echo "Voc� precisa preencher todos os campos! <a href=\"move_topic.php?topic=" . $_GET['topic'] . "\">Voltar</a>.";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }


    if ($_POST['detail'] == 'gangues') {
        $categoria = "Cl�s";
    } elseif ($_POST['detail'] == 'trade') {
        $categoria = "Compro/Vendo";
    } elseif ($_POST['detail'] == 'noticias') {
        $categoria = "Not�cias";
    } elseif ($_POST['detail'] == 'sugestoes') {
        $categoria = "Sugest�es";
    } elseif ($_POST['detail'] == 'duvidas') {
        $categoria = "D�vidas";
    } elseif ($_POST['detail'] == 'fan') {
        $categoria = "Fanwork";
    } elseif ($_POST['detail'] == 'off') {
        $categoria = "Off-Topic";
    } else {
        $categoria = $_POST['detail'];
    }


    $logalert2 = "O t�pico " . $nome['topic'] . " foi movido para a sess�o " . $categoria . " pelo moderador <b>" . $player->username . "</b>";
    forumlog($logalert2, $db);


    $real = $db->execute("update `forum_question` set `category`=? where `id`=?", [$_POST['detail'], $_GET['topic']]);
    echo "Postagem editada com sucesso! <a href=\"view_topic.php?id=" . $_GET['topic'] . "\">Voltar</a>.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

?>

<table width="500" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<form method="POST" action="move_topic.php?topic=<?=$_GET['topic']?>">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
<tr>
<td colspan="3" bgcolor="#E6E6E6"><strong>Mover T�pico</strong> </td>
</tr>
<tr>
<td>Para onde deseja mover o t�pico: <b><?=$nome['topic']?></b> ?<br/>
<select name="detail">
<option value="none" selected="selected">Selecione</option>
<option value="noticias">Not�cias</option>
<option value="equipe">Equipe</option>
<option value="sugestoes">Sugest�es</option>
<option value="gangues">Cl�s</option>
<option value="trade">Compro/Vendo</option>
<option value="duvidas">Duvidas</option>
<option value="fan">Fanwork</option>
<option value="outros">Outros</option>
<option value="off">Off-Topic</option></td>
</tr>
<tr>
<td><input type="submit" name="submit" value="Mover" /></td>
</tr>
</table>
</td>
</form>
</tr>
</table>
<?php
include(__DIR__ . "/templates/private_footer.php");
?>
