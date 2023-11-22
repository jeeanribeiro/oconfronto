<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Regras");
$player = check_user($secret_key, $db);
include(__DIR__ . "/templates/private_header.php");
?>
<fieldset>
<legend><b>Regras</b></legend>
Estas regras se aplicam a todas as partes de "O Confronto".<br><br>

<b>1.</b> A responsabilidade pela sua conta � exclusivamente sua. Sua conta n�o ser� restaurada ou ter� "rollback", a menos que os danos tenham sido causados pela nossa equipe.
<br><br>
<b>2.</b> Voc� n�o est� autorizado a efetuar login em contas de outros jogadores.
<br><br>
<b>3.</b> Partilhar contas n�o � permitido.
<br><br>
<b>4.</b> Voc� PODE ter mais de uma conta.
<br><br>
<b>5.</b> Voc� n�o est� autorizado a cometer abusos ou roubar contas de outros players.
<br><br>
<b>6.</b> Todas as a��es devem ser feitas por voc�, estando presente no computador. Toda navega��o deve ser realizada por voc�, clicando no mouse de seu computador. Isso tamb�m significa que adulterar a URL com edi��o no seu navegador � proibido. Add-ons em seu navegador que afetam a funcionalidade do jogo s�o proibidos.
<br><br>
<b>7.</b> Bugs devem ser reportados assim que descobertos, e qualquer uso de bug poder� resultar em penalidades.
<br><br>
<b>8.</b> � proibido fazer spam ou flood em qualquer lugar. Tamb�m � proibido anunciar qualquer coisa.
<br><br>
<b>9.</b> � proibido postar links ou outras coisas com conte�do nazista, pornogr�fico, racista ou qualquer material que ofenda grupos de pessoas ou indiv�duos.
<br><br>
<b>10.</b> � proibido enganar ou mentir para a equipe de administra��o ou se tentar passar por um administrador.
<br><br>
<b>11.</b> Qualquer a��o que viole o acordo e a licen�a estabelecidos � proibida.
<br><br>
<b>12.</b> Violando ou tentando violar qualquer destas regras poder� resultar em penalidade decidida pela administra��o.
<br><br>
<b>13.</b> Todas as atividades criminais que violem leis locais ser�o reportadas para as autoridades policiais.
<br><br>
<b>14.</b> Estas regras est�o sujeitas a mudan�as a qualquer tempo sem aviso pr�vio. 

</fieldset>
<?php
    include(__DIR__ . "/templates/private_footer.php");
?>