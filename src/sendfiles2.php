<?php
include(__DIR__ . "/lib.php");
define("PAGENAME", "Evento");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkwork.php");
include(__DIR__ . "/templates/private_header.php");
?>
<b>ATEN��O:</b><br/>
Voc�, jogador de oconfronto, j� deve ter percebido que estamos precisando de imagens para armaduras, elmos, botas, etc. Por isso, estamos realizando um concur�o.
<br/><br/>

<fieldset>
<legend><b>Como funciona</b></legend>
<b>1�</b> - <i>Visite o ferreiro e procure um item que est� sem imagem.</i><br />
<b>2�</b> - <i>Desenhe o seu item em tamanho 32x32, e salve-o em qualidade Bitmap (BMP), sem fundo transparente.</i><br />
<b>3�</b> - <i>Iremos analizar seu iten, ele ser� aprovado ou n�o, mas nem sempre vamos responder falando sobre nossa decis�o.</i><br />
<b>4�</b> - <i>Se seu item for aprovado voc� receber� o item no jogo e/ou uma recompensa.</i><br />
</fieldset>
<br/><br/>

<fieldset>
<legend><b>LEMBRE-SE</b></legend>
<b>1�</b> - <i><b>O item vai ter que ser desenhado, apenas por voc�, caso contr�rio voc� poder� ser banido.</b></i><br />
<b>2�</b> - <i>Seu item deve seguir o padr�o do jogo, caso contr�rio n�o ser� aceito. Exemplos:</i><br />
<center><img src="http://www.oconfronto.kinghost.net/images/itens/doubleaxe.gif"> <img src="http://www.oconfronto.kinghost.net/images/itens/goldensword.gif"> <img src="http://www.oconfronto.kinghost.net/images/itens/crusarmor.gif"> <img src="http://www.oconfronto.kinghost.net/images/itens/sainthelmet.gif"></center>
</fieldset>
<br />
<center><font color="red">Atingimos o numero m�ximo de espadas, e apartir de agora n�o iremos mais aprova-las.</font></center>
<br />
<fieldset>
<legend><b>Enviar Imagen</b></legend>
<form action="sendfiles2.php" method="post" enctype="multipart/form-data">
<input type="file" name="foto" size="30"><input type="submit" name="upload" value="Enviar">
</form>
</fieldset>
<font size=\"1\">Ao enviar permito que minha imagem seja usada no jogo.</font><br/><br/>
<?php

if ($_POST['upload']) {
    $erro = $config = [];

    // Prepara a vari�vel do arquivo
    $arquivo = $_FILES["foto"] ?? false;

    // Tamanho m�ximo do arquivo (em bytes)
    $config["tamanho"] = 1_000_000;
    // Largura m�xima (pixels)
    $config["largura"] = 500;
    // Altura m�xima (pixels)
    $config["altura"] = 500;

    // Formul�rio postado... executa as a��es
    if($arquivo) {
        // Verifica se o mime-type do arquivo � de imagem
        if(!preg_match('#^image\/(pjpeg|jpeg|gif|bmp)$#mi', (string) $arquivo["type"])) {
            $erro[] = "<span style=\"color: white; border: solid 1px ; background: red;\">Arquivo em formato inv�lido!</span><br/>- A imagem deve ser bmp.";
        } else {
            // Verifica tamanho do arquivo
            if($arquivo["size"] > $config["tamanho"]) {
                $erro[] = "<span style=\"color: white; border: solid 1px ; background: red;\">Arquivo em tamanho muito grande!</span><br>- A imagem deve ser de no m�ximo " . $config["tamanho"] . " bytes.";
            }

            // Para verificar as dimens�es da imagem
            $tamanhos = getimagesize($arquivo["tmp_name"]);

            // Verifica largura
            if($tamanhos[0] > $config["largura"]) {
                $erro[] = "Largura da imagem n�o deve ultrapassar " . $config["largura"] . " pixels";
            }

            // Verifica altura
            if($tamanhos[1] > $config["altura"]) {
                $erro[] = "Altura da imagem n�o deve ultrapassar " . $config["altura"] . " pixels";
            }
        }

        // Imprime as mensagens de erro
        if($erro !== []) {
            foreach($erro as $err) {
                echo " - " . $err . "<BR>";
            }
        }

        // Verifica��o de dados OK, nenhum erro ocorrido, executa ent�o o upload...
        else {
            // Pega extens�o do arquivo
            preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", (string) $arquivo["name"], $ext);


            // Gera um nome �nico para a imagem
            $imagem_nome = md5(uniqid(time())) . "." . $ext[1];

            // Caminho de onde a imagem ficar�
            $imagem_dir = "concur/" . $imagem_nome;

            // Faz o upload da imagem
            move_uploaded_file($arquivo["tmp_name"], $imagem_dir);

            $endereco = "http://www.oconfronto.kinghost.net/concur/".$imagem_nome."";
            $insert['player'] = $player->username;
            $insert['img'] = $endereco;
            $query = $db->autoexecute('concur', $insert, 'INSERT');

            echo "<span style=\"color: white; border: solid 1px; background: green;\">Sua imagem foi enviada com sucesso!</span><br/>";
            echo "<font size=\"1\">Iremos avalia-la, e se for aceita ser� adicionada ao jogo e voc� reconpensado.</font>";
        }
    }
}


    include(__DIR__ . "/templates/private_footer.php");
?>