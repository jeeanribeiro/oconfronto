<?php
	include(__DIR__ . "/lib.php");
	define("PAGENAME", "Cr�ditos");
	$player = check_user($secret_key, $db);
	include(__DIR__ . "/templates/private_header.php");
?>
<b>As pessoas envolvidas na cria��o do site s�o:</b>
<br/><br/>
<b>Engine:</b> <i>ezRPG.</i><br />
<b>C�digos PHP:</b> <i>Zeggy, Jrotta, Adiel Araujo, Tshj, Treta, Die4me, Khashul, Cdoyle e outros membros do f�rum do ezRPG Project.</i><br />
<b>Imagens:</b> <i>Wonderrow, Jrotta, Smurtz, CipSoft GmbH, Spyware.</i><br />
<b>Icones:</b> <i>famfamfam.com</i><br />
<b>Itens:</b> <i>Lionblood, Galiant, Jrotta, Marcotonio, JuanDrake(Gawain), Rockonra, Adrianox, Pabloloko, Yurizito.</i><br />
<b>Agrade�a pelos efeitos em javascript �:</b> <i>Treta e Jrotta.</i><br />
<?php
	include(__DIR__ . "/templates/private_footer.php");
?>