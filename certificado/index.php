<?php

// chamando os arquivos necessários do DOMPdf
require_once 'dompdf/lib/html5lib/Parser.php';
require_once 'dompdf/lib/php-font-lib-master/src/FontLib/Autoloader.php';
require_once 'dompdf/lib/php-svg-lib-master/src/autoload.php';
require_once 'dompdf/src/Autoloader.php';
include('phpqrcode/qrlib.php');

 include_once ('conexao.php');

header("Content-Type: text/html; charset=utf8", true);

   $dataTemp = NULL;

/*if (isset($_POST["codEvento"]))
	$_SESSION["codEvento"] = $_POST["codEvento"];
if (isset($_POST["codParticipante"]))
	$_SESSION["codParticipante"] = $_POST["codParticipante"];*/



$codInscricao = $_POST["codInscricao"];

$tamanhoNumeroInscricao = strlen($codInscricao);

$codEvento = substr($codInscricao, 2, 4);

if ($tamanhoNumeroInscricao == 12) {
    /* se o código com 12 dígitos */
    $codParticipante = substr($codInscricao, -6, 7);

} else if ($tamanhoNumeroInscricao == 14) {
    /* se o código com 14 dígitos */
    $codParticipante = substr($codInscricao, -7, 8);

}

	$selectSQL = sprintf("SELECT    participante.nome as nomeParticipante, " .
									"participante.dddCelular, " . 
									"participante.telCelular, " . 
									"participante.dddResidencial, " . 
									"participante.telResidencial, " .
									"participante.cpf, " . 
									"evento.nome as nomeEvento, " .
									"evento.local as localEvento, " .
									"evento.endereco as enderecoEvento, " .
									"frequencia2.cargaHorariaOAB as cargaHorariaOAB " .
						"FROM        evento, participante, inscricoes, porta, frequencia2 " .
						"WHERE       ((inscricoes.codEvento = %s) AND " .
						            "(inscricoes.codParticipante = %s) AND " .
						            "(inscricoes.codParticipante = participante.codigo) AND " .
						            "(inscricoes.codEvento = evento.codigo) AND " .
						            "(frequencia2.codEvento = evento.codigo) AND " .
						            "(frequencia2.codEvento = inscricoes.codEvento) AND " .
						            "(frequencia2.codEvento = porta.codEvento) AND " .
						            "(evento.codigo = porta.codEvento))",
					$codEvento,
					$codParticipante);
    mysqli_select_db($conn, "emerjco_eventos");
	$Result1 = mysqlI_query($conn, $selectSQL) or die(mysqlI_error($conn));
	$row_rsInscricao = mysqli_fetch_assoc($Result1);
	$totalRows_rsInscricao = mysqli_num_rows($Result1);

	if($totalRows_rsInscricao == 0){
        echo ("<script>alert('código de inscrição não encontrado !'  );location.href='../pages/certificado.php';</script>");
    } else {

	$telTemp = NULL;
	if ((isset($row_rsInscricao["dddCelular"])) && (isset($row_rsInscricao["telCelular"]))) {
		$telTemp = $row_rsInscricao["dddCelular"] . " " . $row_rsInscricao["telCelular"];
	}
	if ((isset($row_rsInscricao["dddResidencial"])) && (isset($row_rsInscricao["telResidencial"]))) {
		if ($telTemp == NULL) {
			$telTemp = $row_rsInscricao["dddResidencial"] . " " . $row_rsInscricao["telResidencial"];
		} else {
			$telTemp = $telTemp . " - " . $row_rsInscricao["dddResidencial"] . " " . $row_rsInscricao["telResidencial"];
		}
	}
	
	/**/

	$selectSQL = sprintf("SELECT DISTINCT (data) " .
						 "FROM    porta " .
						 "WHERE   codEvento = %s", $codEvento);

    mysqli_select_db($conn, "emerjco_eventos");
	$Result2 = mysqli_query($conn, $selectSQL) or die(mysqli_error($conn));
	$row_rsPortas = mysqli_fetch_assoc($Result2);
	$totalRows_rsPortas = mysqli_num_rows($Result2);
	
	/**/

	$selectSQLHoraInicial = sprintf("SELECT DISTINCT data, " .
							        "horaInicio, " .
							        "horaFim " .
						 "FROM    porta " .
						 "WHERE   codEvento = %s ORDER BY horaInicio ASC LIMIT 1", $codEvento);

    mysqli_select_db($conn, "emerjco_eventos");
	$ResultHoraInicial = mysqli_query($conn, $selectSQLHoraInicial) or die(mysqli_error($conn));
	$row_rsPortasInicial = mysqli_fetch_assoc($ResultHoraInicial);
	$totalRows_rsPortasInicial = mysqli_num_rows($ResultHoraInicial);
	
	/**/

	$selectSQLHoraFinal = sprintf("SELECT DISTINCT data, " .
							        "horaInicio, " .
							        "horaFim " .
						 "FROM    porta " .
						 "WHERE   codEvento = %s ORDER BY horaFim DESC LIMIT 1", $codEvento);

    mysqli_select_db($conn, "emerjco_eventos");
	$ResultHoraFinal = mysqli_query($conn, $selectSQLHoraFinal) or die(mysqli_error($conn));
	$row_rsPortasFinal = mysqli_fetch_assoc($ResultHoraFinal);
	$totalRows_rsPortasFinal = mysqli_num_rows($ResultHoraFinal);
	
	/**/

	$selectSQLcodigoCRC = sprintf("SELECT codigocrc " .
						 "FROM    certificadoonline " .
						 "WHERE   situacaoPagamento = 'aprovado' and pagamento = 1 and codigoEvento = %s and codigoParticipante = %s", $codEvento, $codParticipante);

    mysqli_select_db($conn, "emerjco_eventos");
	$ResultcodigoCRC = mysqli_query($conn, $selectSQLcodigoCRC) or die(mysqli_error($conn));
	$row_codigoCRC = mysqli_fetch_assoc($ResultcodigoCRC);
	$totalRows_codigoCRC = mysqli_num_rows($ResultcodigoCRC);
	
	/**/
	
	$selectSQLdataHoraAprovacao = sprintf("SELECT enviar_email " .
						 "FROM    certificadoonline " .
						 "WHERE   situacaoPagamento = 'aprovado' and (pagamento = 1) and (enviar_email <> 'NULL') and codigoEvento = %s and codigoParticipante = %s", $codEvento, $codParticipante);

    mysqli_select_db($conn, "emerjco_eventos");
	$ResultdataHoraAprovacao = mysqli_query($conn, $selectSQLdataHoraAprovacao) or die(mysqli_error($conn));
	$row_dataHoraAprovacao = mysqli_fetch_assoc($ResultdataHoraAprovacao);
	$totalRows_dataHoraAprovacao = mysqli_num_rows($ResultdataHoraAprovacao);
	
	/**/
	
	$selectSQLCargaHoraria = sprintf("SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( cargaHorariaOAB ) ) ) FROM frequencia2 WHERE codEvento = %s and codParticipante = %s", $codEvento, $codParticipante);
    mysqli_select_db($conn, "emerjco_eventos");
	$ResultCargaHoraria = mysqli_query($conn, $selectSQLCargaHoraria) or die(mysqli_error($conn));
	$totalRows_rsPortasAGAIN = mysqli_num_rows($ResultCargaHoraria);
	
	/* FUNCIONANDO ANTERIORMENTE */
	
	/*$selectSQLCargaHoraria = sprintf("SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( cargaHoraria ) ) ) FROM porta WHERE codEvento = %s", $codEvento);
	mysql_select_db($database_evento, $evento);
	$ResultCargaHoraria = mysql_query($selectSQLCargaHoraria, $evento) or die(mysql_error());
	$totalRows_rsPortasAGAIN = mysql_num_rows($ResultCargaHoraria);*/
	
	/* FUNCIONANDO ANTERIORMENTE */
	
	/**/
	
	$selectSQLDatasEvento = sprintf("SELECT DISTINCT (data) " .
								 "FROM    porta " .
								 "WHERE   codEvento = %s", $codEvento);

    mysqli_select_db($conn, "emerjco_eventos");
	$ResultDatasEvento = mysqli_query($conn, $selectSQLDatasEvento) or die(mysqli_error($conn));
	$totalRows_DatasEvento = mysqli_num_rows($ResultDatasEvento);
	
	}

// definindo os namespaces
Dompdf\Autoloader::register();
use Dompdf\Dompdf;

// inicializando o objeto Dompdf
$dompdf = new Dompdf();

// coloque nessa variável o código HTML que você quer que seja inserido no PDF
$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	$html .= '<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR">';
	$html .= '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
	$html .= '<body style="background-repeat:no-repeat; background-image:url(certificadoemerj.jpg); background-position:center;">';
	$html .= '<table border=0 align="center" width=605 style="font-family:Arial, Helvetica, sans-serif; font-size:20px">';
	$html .= '<tr>';
	$html .= '<td height="230"><br /></td>';
	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td align=center>';
	$html .= '<div align="center" style="width:810px;"><span>Conferido a </span> ';
	if ((isset($totalRows_rsInscricao)) && ($totalRows_rsInscricao == 1))	
	$html .= '<strong>';
	$html .= '<strong>' . $row_rsInscricao['nomeParticipante'] . '</strong>';	
	$html .= '</strong>';
	
	$html .= ', por sua participação no evento ';
	if ((isset($totalRows_rsInscricao)) && ($totalRows_rsInscricao == 1))
	$html .= '<strong>';
	$html .= '<strong>' . $row_rsInscricao["nomeEvento"] .  '</strong>,';
	$html .= '</strong>';
	
	$html .= ' realizado em ';

	if ((isset($totalRows_rsPortas)) && ($totalRows_rsPortas > 0)) {
	
		if ($totalRows_rsPortas == 2){
		
			$selectSQLData1 = sprintf("SELECT DISTINCT (data) " .
								 "FROM    porta " .
								 "WHERE   codEvento = %s ORDER BY data ASC LIMIT 1", $codEvento);

            mysqli_select_db($conn, "emerjco_eventos");
			$ResultData1 = mysqli_query($conn, $selectSQLData1) or die(mysqli_error($conn));
			$totalRows_rsPortasData1 = mysqli_num_rows($ResultData1);
			
			while ($row_rsPortasData1 = mysqli_fetch_assoc($ResultData1)){
				$date1 = date_create($row_rsPortasData1['data']);
				$html .= '<strong>' . date_format($date1,'d/m/Y')  . ' e' . '</strong>' . ' ';
			}
			
			$selectSQLData2 = sprintf("SELECT DISTINCT (data) " .
								 "FROM    porta " .
								 "WHERE   codEvento = %s ORDER BY data DESC LIMIT 1", $codEvento);
            mysqli_select_db($conn, "emerjco_eventos");
			$ResultData2 = mysqli_query($conn, $selectSQLData2) or die(mysqli_error($conn));
			$totalRows_rsPortasData2 = mysqli_num_rows($ResultData2);
			
			while ($row_rsPortasData2 = mysqli_fetch_assoc($ResultData2)){
				$date2 = date_create($row_rsPortasData2['data']);
				$html .= '<strong>' .  date_format($date2,'d/m/Y') . '</strong>,' . ' ';
			
			}
		} else if ($totalRows_rsPortas == 1) {	
			  do {  
				  $data = explode('-', $row_rsPortas["data"]);
				  $html .= '<strong>' . ($data[2] . '/' . $data[1] . '/' . $data[0])  . ',' . '</strong>' . ' ';
				  if (strcmp(($data[2] . "/" . $data[1] . "/" . $data[0]), substr($dataTemp, strlen($dataTemp) - 10, 10)) == true) {
					  if ($dataTemp == NULL) {
						  $dataTemp = $dataTemp . $data[2] . "/" . $data[1] . "/" . $data[0];
					  } else {
						  $dataTemp = $dataTemp . " - " . $data[2] . "/" . $data[1] . "/" . $data[0];
					  }
				  }
			  } while ($row_rsPortas = mysqli_fetch_assoc($Result2));
		  		$rows = mysqli_num_rows($Result2);
		  			if($rows > 0) {
			 			mysqli_data_seek($Result2, 0);
			  			$row_rsPortas = mysqli_fetch_assoc($Result2);
		  			}
			} else if ($totalRows_rsPortas > 2) {	
			  
			 $selectSQLMaisDatas = sprintf("SELECT DISTINCT (data) " .
								 "FROM    porta " .
								 "WHERE   codEvento = %s ORDER BY data ASC", $codEvento);

            mysqli_select_db($conn, "emerjco_eventos");
			$ResultMaisDatas = mysqli_query($conn, $selectSQLMaisDatas) or die(mysqli_error($conn));
			$totalRows_MaisDatas = mysqli_num_rows($ResultMaisDatas);
			
			while ($row_MaisDatas = mysqli_fetch_assoc($ResultMaisDatas)){
				$dateMaisDatas = date_create($row_MaisDatas['data']);
				$html .= '<strong>' . date_format($dateMaisDatas,'d/m/Y')  . ' e' . '</strong>' . ' ';
			}
			}
	}
	
	$html .= ' no(a) ';
	if ((isset($totalRows_rsInscricao)) && ($totalRows_rsInscricao == 1))
	$html .= '<strong>';
	$html .= '<strong>' . $row_rsInscricao["localEvento"] . '</strong>';
	$html .= '</strong>.';
	
	$html .= '<br><br><br>';
	
	$dataHoraAprovacao = $row_dataHoraAprovacao["enviar_email"];
	$dataAprovacaoFormatada = explode(" ", $dataHoraAprovacao);
	$dataAprovacao = $dataAprovacaoFormatada[0];
	
	$partesDataAprovacao = explode("-", $dataAprovacao);

	$dia = $partesDataAprovacao[2];
	$mes = $partesDataAprovacao[1];
	$ano = $partesDataAprovacao[0];
	
	if ($mes == 1) {
		$mesAprovacao = 'janeiro';
	} else if ($mes == 2) {
		$mesAprovacao = 'fevereiro';
	} else if ($mes == 3) {
		$mesAprovacao = 'março';
	} else if ($mes == 4) {
		$mesAprovacao = 'abril';
	} else if ($mes == 5) {
		$mesAprovacao = 'maio';
	} else if ($mes == 6) {
		$mesAprovacao = 'junho';
	} else if ($mes == 7) {
		$mesAprovacao = 'julho';
	} else if ($mes == 8) {
		$mesAprovacao = 'agosto';
	} else if ($mes == 9) {
		$mesAprovacao = 'setembro';
	} else if ($mes == 10) {
		$mesAprovacao = 'outubro';
	} else if ($mes == 11) {
		$mesAprovacao = 'novembro';
	} else if ($mes == 12) {
		$mesAprovacao = 'dezembro';
	}
	
	$dataCompleta = 'Rio de Janeiro, ' . $dia . ' de ' . $mesAprovacao . ' de ' . $ano . '.';
	$html .= utf8_encode($dataCompleta);
	
	//setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	//date_default_timezone_set('America/Sao_Paulo');
	//$html .= utf8_encode(strftime('Rio de Janeiro, %d de %B de %Y.', strtotime('today')));
    
    $html .= '<br><br>';
    $html .= '<span style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">Lucia Frota Pestana de Aguiar Silva<br>Secretária-Geral da EMERJ</span>';
	$html .= '</div>';
	$html .= '<div align=left>';

	$row = mysqli_fetch_assoc($ResultCargaHoraria);
	$cargahorariacertificadoSoma = explode(":",$row['SEC_TO_TIME( SUM( TIME_TO_SEC( cargaHorariaOAB ) ) )']);
	
	//$html .= '<br>';
	$html .= '<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">* Foram atribuídas ' . $cargahorariacertificadoSoma[0] . ":" . $cargahorariacertificadoSoma[1] . ' horas de estágio pela OAB.</span>';
	
	$html .= '<br>';
	$salvaimagem = 'qrcode/qrcode_certificado_'. $codInscricao . '_' . $row_codigoCRC["codigocrc"] . '.png';
	QRcode::png("http://emerj.com.br/evento/certificado/certificados/" . "certificado_" . $codInscricao . '_' . $row_codigoCRC["codigocrc"] . ".pdf", $salvaimagem);
	QRcode::png("http://emerj.com.br/evento/certificado/certificados/" . "certificado_" . $codInscricao . '_' . $row_codigoCRC["codigocrc"] . ".pdf", "qrcode/qrcode_emerj.png");
	$html .= '<table width="0" width="80%"><tr>';
	$html .= '<td><img src="qrcode/qrcode_emerj.png" width="80" height="80" style="float:left; margin-right:5px;"></td>';
	$html .= '<td><p style="font-family:Arial, Helvetica, sans-serif; font-size:11px; margin-top:0px;"> Para conferir a veracidade do certificado, acesse o link <a href="emerj.com.br/evento/certificado/validacertificado.php" target="_blank">emerj.com.br/evento/certificado/validacertificado.php</a> e utilize o número de inscrição: ' . $codInscricao . ' e o código CRC: ' . $row_codigoCRC["codigocrc"] . ' para consulta.</p></td></tr></table>';
	
	$html .= '</div>';
	$html .= '</td>';
	$html .= '</tr>';
	$html .= '</table>';
	$html .= '</body>';
	$html .= '</html>';                 


// carregamos o código HTML no nosso arquivo PDF
$dompdf->loadHtml($html);

// (Opcional) Defina o tamanho (A4, A3, A2, etc) e a oritenação do papel, que pode ser 'portrait' (em pé) ou 'landscape' (deitado)
$dompdf->setPaper('A4', 'landscape');

// Renderizar o documento
$dompdf->render();

// pega o código fonte do novo arquivo PDF gerado
$output = $dompdf->output();

// defina aqui o nome do arquivo que você quer que seja salvo
file_put_contents("Certificado.pdf", $output);

// redirecionamos o usuário para o download do arquivo
die("<script>location.href='Certificado.pdf';</script>");

?>