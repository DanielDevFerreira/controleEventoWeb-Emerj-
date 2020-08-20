<?php
function novaSenha() {
	session_start();
	enviaEmail($_SESSION["email"], "Reativação de Senha", "email/senha.txt", $_SESSION["nome"] . "-" . $_SESSION["codParticipante"] . "-" . $_SESSION["codAtivacao"]);
}

function enviaEmail($para, $assunto, $arquivo, $parametros) {
	$mensagem = NULL;

	$parametro = explode("-", $parametros);

	if (isset($parametro[0]))
		$pattern["NOME"] = $parametro[0];
	if (isset($parametro[1]))
		$pattern["CODPARTICIPANTE"] = $parametro[1];
	if (isset($parametro[2]))
		$pattern["CODATIVACAO"] = $parametro[2];
	if (isset($parametro[3]))
		$pattern["CODEVENTO"] = $parametro[3];
	if (isset($parametro[4]))
		$pattern["NAMEEVENTO"] = $parametro[4];
		foreach ($pattern as $key => $output) {
    	$base[] = $key;
		$bnew[] = $output;
	}
	$fd = @fopen($arquivo, "r");
	while (!feof($fd)) {
		$line = fgets($fd, 2048);
		$mensagem .= str_replace($base, $bnew, $line);
	}
	fclose($fd);

	mail($para, $assunto, $mensagem,
	     "From: <emerjsite@tjrj.jus.br> \r\n"
	     ."Reply-To: emerjsite@tjrj.jus.br\r\n"
		 ."Content-type: text/html\r\n"
	     ."X-Mailer: PHP/" . phpversion());
}
?>