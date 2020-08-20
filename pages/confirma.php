<?php 
require_once('../conexao.php');
include('barcode.php');

session_start();
$dataTemp = NULL;

header("Content-Type: text/html; charset=utf-8", true);

if (isset($_GET["codEvento"]))
	$_SESSION["codEvento"] = $_GET["codEvento"];
if (isset($_GET["codParticipante"]))
	$_SESSION["codParticipante"] = $_GET["codParticipante"];

if ((isset($_SESSION["codEvento"])) && (isset($_SESSION["codParticipante"]))) {
	$codEvento = $_SESSION["codEvento"];
	$codParticipante = $_SESSION["codParticipante"];
		
	$codInscricao = sprintf("%06d%08d", $codEvento, $codParticipante);

	$selectSQL = sprintf("SELECT    participante.nome as nomeParticipante, " .
									"participante.dddCelular, " . 
									"participante.telCelular, " . 
									"participante.dddResidencial, " . 
									"participante.telResidencial, " .
									"participante.cpf, " . 
									"evento.nome as nomeEvento, " .
									"evento.local as localEvento, " .
									"evento.endereco as enderecoEvento " .
						"FROM        evento, participante, inscricoes " .
						"WHERE       ((inscricoes.codEvento = %s) AND " .
						            "(inscricoes.codParticipante = %s) AND " .
						            "(inscricoes.codParticipante = participante.codigo) AND " .
						            "(inscricoes.codEvento = evento.codigo))",
					$codEvento,
					$codParticipante);

	mysqli_select_db($conn, "emerjco_eventos");
	$Result1 = mysqli_query($conn, $selectSQL) or die(mysqli_error($conn));
	$row_rsInscricao = mysqli_fetch_assoc($Result1);
	$totalRows_rsInscricao = mysqli_num_rows($Result1);

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

	$selectSQL = sprintf("SELECT  	data, " .
							        "horaInicio, " .
							        "horaFim " .
						 "FROM    porta " .
						 "WHERE   codEvento = %s", $codEvento);

	mysqli_select_db($conn, "emerjco_eventos");
	$Result2 = mysqli_query($conn, $selectSQL) or die(mysqli_error($conn));
	$row_rsPortas = mysqli_fetch_assoc($Result2);
	$totalRows_rsPortas = mysqli_num_rows($Result2);
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<link href="css/default.css" rel="stylesheet" type="text/css">


<style type="text/css">
<!--
.style2 {font-size: 9px}
.style4 {color: #000099}
.style33 {
	color: #990000;
	font-weight: bold;
}
.style34 {color: #000000; font-size: 14px;}
.style37 {color: #990000}
.style38 {font-size: small}
.style39 {font-size: small; color: #000000; }
.style40 {color: #000000; font-weight: bold; font-size: 13px; text-align: center; margin: 0 auto;}
.style43 {color: #990000; font-weight: bold; font-size: 16px}
.style41 {color: #000000; font-weight: bold; font-size: 15px}
.style42 {color: #990000; font-weight: bold; font-size: 14px}
a.linknegrito:link, a.linknegrito:visited, a.linknegrito:active {
	color: #000000;
	font-weight: bold;
	text-decoration: underline;
}
a.linknegrito:hover {
	color: #990000;
	font-weight: bold;
	text-decoration: underline;
    
}


</style>
    
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
    
<body>
<div  align="center">
  <table width="550"  border="1" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><div align="center"><span class="style34">- <strong>COMPROVANTE DE INSCRIÇÃO</strong> -<br />
        Escola da Magistratura do Estado do Rio de Janeiro<br />
      Rua Dom Manuel nº;&nbsp25 - Centro - Telefone: 3133-3369</span></div></td>
      </tr>
    <tr>
      <td>
        <table  width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
              <!-- BARCODE LATERAL DO COMPROVANTE -->
            <td width="50" rowspan="4" style="width:60px;"><div style="margin-left:10px" class="textoT&iacute;tulo"><?php if ((isset($totalRows_rsInscricao)) && ($totalRows_rsInscricao == 1)) vbarcode($codInscricao); ?></div></td>
              
              <!-- NOME DO EVENTO NO COMPROVANTE -->
            <td width="456"><div style="margin-top:2px; margin-left:-40px;" align="center" class="textoT&iacute;tulo style40"><?php if ((isset($totalRows_rsInscricao)) && ($totalRows_rsInscricao == 1)) echo($row_rsInscricao["nomeEvento"]);?><br /></div></td>
          </tr>
          <tr>
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <?php
			 				if ((isset($totalRows_rsPortas)) && ($totalRows_rsPortas > 0)) {
							echo("<tr>" .
        		    			 "<td class='textoT&iacute;tulo style40'>Data</td>" .
			    		         "<td class='textoT&iacute;tulo style40'>Horário de Início</td>" .
            					 "<td class='textoT&iacute;tulo style40'>Horário de Término</td>" .
						         "</tr>");
							do {  
								$data = explode('-', $row_rsPortas["data"]);
     							echo("<tr><td class='textoNormal style40'>" . $data[2] . "/" . $data[1] . "/" . $data[0] . "</td>" . 
									"<td class='textoNormal style40'>" . substr($row_rsPortas["horaInicio"],0,5) . "</td>" .
									"<td class='textoNormal style40'>" . substr($row_rsPortas["horaFim"],0,5) . "</td></tr>");
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
							}
						  ?>
            </table>					</td>
          </tr>
          <tr>
            <td>
                <!-- LOCAL DO EVENTO NO COMPROVANTE -->
              <p style="margin-top:-1px;"><span class="style40">Local:</span>
                <span style="font-size: 12px;" class="textoNormal style40">
                <?php if ((isset($totalRows_rsInscricao)) && ($totalRows_rsInscricao == 1)) echo($row_rsInscricao["localEvento"]);?>
                </span></p>
                
                <!-- ENDEREÇO DO EVENTO NO COMPROVANTE -->
              <p style="margin-top:-12px;"><span  class="style33"><span class="style34">Endereço:</span>
                <?php if ((isset($totalRows_rsInscricao)) && ($totalRows_rsInscricao == 1)) echo($row_rsInscricao["enderecoEvento"]);?>
                </span><br/>
                <span style="font-size:15px;">Caso haja lotação máxima do auditório, a transmissão de sinal do evento ocorrerá, simultaneamente, no espaco indicado pela equipe da EMERJ.</span> <br />
              </p>
              
              
              <div align="center"></div>
  <hr align="left" />
                <!-- NOME DO PARTICIPANTE NO COMPROVANTE -->
              <p style="margin-top:-5px;"><span class="style40">Nome:</span>
                <span class="textoNormal style40">
                <?php if ((isset($totalRows_rsInscricao)) && ($totalRows_rsInscricao == 1)) echo($row_rsInscricao["nomeParticipante"]);?>
              </span>		                </p>
                
                <!-- CPF DO PARTICIPANTE NO COMPROVANTE -->
              <p style="margin-top:-15px;"><span class="style40">CPF:</span><span class="textoNormal style40">
                <?php if ((isset($totalRows_rsInscricao)) && ($totalRows_rsInscricao == 1)) echo(substr($row_rsInscricao["cpf"], 0,3) . "." . substr($row_rsInscricao["cpf"], 3,3) . "." . substr($row_rsInscricao["cpf"], 6,3) . "-". substr($row_rsInscricao["cpf"], 9,2));?>
                <br />
              </span>	                </p></td>
          </tr>
          <tr>
            <td>
              <p align="left" style="margin-top:-17px;"><span class="style40">Número da Inscrição: </span>
                <span class="style43"><?php if ((isset($totalRows_rsInscricao)) && ($totalRows_rsInscricao == 1)) echo($codInscricao); else echo("Inscrição Inválida"); ?>
                </span>
              <hr align="left" />
              <br />
                
                <!-- BARCODE CENTRAL DO COMPROVANTE -->
              <div align="center" style="margin-left: -50px; margin-top:-20px;"><?php if ((isset($totalRows_rsInscricao)) && ($totalRows_rsInscricao == 1)) fbarcode($codInscricao); ?></div>
              </p>
            <br /></td>
          </tr>
      </table>		</td>
      </tr>
  </table>
  
  <br/>
  <br/>
</div>
<div align="center" class="style33"><span class="style41"><strong>REGRAS E INSCRIÇÕES ON-LINE</strong></span><br />
</div>
<div align="center"><br />
  
  <table width="691" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td>      <div align="justify">
        <form id="form1" name="form1" method="post" action="">
          <div align="justify">
           <p class="textoNormal style4 style38">
            <span class="style34"><strong>1.</strong> &Eacute; <strong>obrigat&oacute;ria</strong> a apresenta&ccedil;&atilde;o do comprovante de inscri&ccedil;&atilde;o  na portaria de acesso &agrave; EMERJ ou no local do evento.</span><br />
            <span class="style34"><strong>2.</strong> &Eacute; <strong>obrigat&oacute;rio</strong> o registro de presen&ccedil;a, <strong>mediante LEITURA &Oacute;TICA DA INSCRI&Ccedil;&Atilde;O</strong>, <strong>na entrada e na sa&iacute;da de cada per&iacute;odo do evento</strong>, n&atilde;o podendo o participante alegar desconhecimento. Caso o  participante registre a sua presen&ccedil;a uma &uacute;nica vez, somente na entrada ou na  sa&iacute;da do evento, n&atilde;o far&aacute; jus ao c&ocirc;mputo de participa&ccedil;&atilde;o, <u>ainda que tenha permanecido presente durante todo o evento</u>. No caso de evento com <strong>programa&ccedil;&atilde;o em per&iacute;odos distintos (manh&atilde; e tarde  ou noite)</strong> ou em mais de um dia, dever&aacute; ser  registrada a presen&ccedil;a por per&iacute;odo, ou seja, <strong>entrada e sa&iacute;da no turno  da manh&atilde;</strong>; e id&ecirc;ntico procedimento nos demais  turnos e/ou dias.</span><br />
            <span class="style34"><strong>3.</strong> O registro de presen&ccedil;a estar&aacute; dispon&iacute;vel <span class="style33"><strong>somente 30 minutos antes do  hor&aacute;rio de in&iacute;cio e at&eacute; 30 minutos ap&oacute;s o t&eacute;rmino do evento</strong></span>, que pode ocorrer em hor&aacute;rio diferente do divulgado na programa&ccedil;&atilde;o.</span><br />
            <span class="style34"><strong>4.</strong> A <strong>EMERJ</strong> n&atilde;o se responsabilizar&aacute; por registro de presen&ccedil;a em  hor&aacute;rio diverso do estabelecido.</span><br />
            <span class="style34"><strong>5.</strong> No caso de falha operacional na leitura &oacute;tica da inscri&ccedil;&atilde;o, o  participante dever&aacute; assinar <strong>Lista de Presen&ccedil;a Nominal</strong>, previamente impressa, de  acordo com as regras j&aacute; mencionadas.</span><br />
            <span class="style34"><strong>6.</strong> O <strong class="style33">cancelamento da inscri&ccedil;&atilde;o</strong> poder&aacute; ser realizado no site da EMERJ at&eacute; <span class="style33">23h59min do dia que antecede o dia do evento</span>.</span><br />
            <span class="style34"><strong>7.</strong> <strong class="style33">A inscri&ccedil;&atilde;o garante vaga at&eacute; o hor&aacute;rio de in&iacute;cio do evento</strong>. Ap&oacute;s esse hor&aacute;rio, o local ser&aacute; aberto ao p&uacute;blico interessado sem  inscri&ccedil;&atilde;o pr&eacute;via.</span><br />
            <span class="style34"><strong>8.</strong> S&oacute; far&atilde;o jus ao <strong class="style33">certificado e horas complementares</strong> os participantes que assistirem a <span class="style33">pelo menos 75%</span><span class="style34"> (setenta e cinco por cento) da carga hor&aacute;ria total do evento.</span><br />
            <span class="style34"><strong>9.</strong> O <strong class="style33">certificado on-line de participa&ccedil;&atilde;o</strong> poder&aacute; ser solicitado pelo link <strong><u><a href="http://emerj.com.br/evento/certificado/certificado.php" target="_blank" class="linknegrito" title="Certificado On-Line">emerj.com.br/evento/certificado/certificado.php</a></u></strong> e somente estar&atilde;o dispon&iacute;veis os eventos em que o participante tiver obtido presen&ccedil;a m&iacute;nima registrada igual ou superior a 75% da carga hor&aacute;ria total prevista, a partir dos hor&aacute;rios divulgados na programa&ccedil;&atilde;o, salvo quando o evento terminar antes do hor&aacute;rio previsto. Caso o evento n&atilde;o esteja listado, o participante dever&aacute; consultar a Secretaria Acad&ecirc;mica da EMERJ se possui direito, no prazo m&aacute;ximo de 1 (um) ano ap&oacute;s a data do evento, munido do comprovante de inscri&ccedil;&atilde;o, que tamb&eacute;m &eacute; necess&aacute;rio para comprova&ccedil;&atilde;o das horas concedidas junto à OAB e a ESAJ.</span><br />
            <span class="style34"><strong>10.</strong> N&atilde;o haver&aacute; devolu&ccedil;&atilde;o da taxa de solicita&ccedil;&atilde;o de certificado de participa&ccedil;&atilde;o. Caso ocorra pagamento indevido, o valor poder&aacute; permanecer como cr&eacute;dito para futuras solicita&ccedil;&otilde;es.</span><br />
            <span class="style34"><strong>11.</strong><strong class="style33"> A&nbsp;EMERJ filmar&aacute; todo o evento</strong>. Ficam os participantes cientes de que as grava&ccedil;&otilde;es e fotografias poder&atilde;o ser utilizadas para divulga&ccedil;&atilde;o e fins institucionais, inclusive, nos cursos a dist&acirc;ncia, bem como a disponibiliza&ccedil;&atilde;o do material na p&aacute;gina da <strong>EMERJ</strong> na internet.</span><br />
            <span class="style34"><strong>12.</strong> As regras para <strong class="style33">frequ&ecirc;ncia e cr&eacute;dito</strong> de horas est&atilde;o dispon&iacute;veis no site da EMERJ, pelo link <strong>Regras e Inscri&ccedil;&otilde;es On-Line</strong>: <span><strong><u><a href="http://www.emerj.tjrj.jus.br/paginas/eventos/regras_inscricoesonline.html" target="_blank" class="linknegrito" title="Regras e Inscri&ccedil;&otilde;es On-Line">www.emerj.tjrj.jus.br/paginas/eventos/regras_inscricoesonline.html</a></u></strong>.</span><br />
            <span class="style34"><strong>13.</strong> A carga hor&aacute;ria relativa &agrave; participa&ccedil;&atilde;o dos funcion&aacute;rios do Tribunal de Justi&ccedil;a  do Estado do Rio de Janeiro (Resolu&ccedil;&atilde;o do Conselho da Magistratura 07/2016 - TJ/RJ) ser&aacute; conferida pela ESAJ, mediante requerimento individual naquela Escola.</span>
            </span>
            </p>
            </div>
          </form>
        </div></td>
    </tr>
  </table>
  
  <br />
</div>
<div align="center">
  <table width="691" height="296" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><div align="center" class="TDtable1 style34 style38"><strong class="style41">SOLICITAÇÃO DE CERTIFICADO DE PARTICIPAÇÃO EM EVENTO - EMERJ</strong><br />
              <br />
              </div></td>
          </tr>
        </table>
        </td>
      </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="3" class="TDtable2 style34">Participante: &nbsp;<?php echo($row_rsInscricao["nomeParticipante"]) ?></td>
          </tr>
          <tr>
            <td colspan="3" class="TDtable2 style34">Evento: &nbsp;<?php echo($row_rsInscricao["nomeEvento"]) ?></td>
          </tr>
          <tr>
            <td colspan="3" class="TDtable2 style34">Data do Evento: 
              <?php
						if ((isset($dataTemp)) && ($dataTemp !=  "" )) {
							echo($dataTemp);
						} ?>					</td>
          </tr>
          <tr>
            <td class="TDtable2">
              <table width="100%" height="20" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="45%" class="style34">Telefone: <?php echo($telTemp); ?></td>
                  <td width="55%" style="height:25px;"><span class="style34">Data e Visto do DIFIN:</span></td>
                  </tr>
            </table>				  </td>
          </tr>
          <tr>
            <td colspan="3">
              <table width="100%" height="20" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="35%" class="TDtable2 style34" style="height:25px;">Recebi em ____/____/______ </td>
                  <td width="65%" class="TDtable2 style34" style="height:25px;">Assinatura:</td>
                  </tr>
            </table>					</td>
          </tr>
          <tr>
            <td>
              <table width="100%">
                <tr>
                  <td class="textoT&iacute;tulo style2"><div align="center"></div></td>
                  <td width="685" colspan="3"><div align="center" class="style39">
                    <div align="right"><br />
                      Atualizado em: 12/02/2020</div>
                    </div>
                    </div></td>
                </tr>
            </table>					</td>
            </tr>
          <tr>
            <td style="border-bottom:dotted #000000 3px; height:5px;"><img src="imagens/blank.gif" width="1" height="1" /></td>
            </tr>
          <tr>
            <td style="height:5px;"><img src="imagens/blank.gif" width="1" height="1" /></td>
            </tr>
          <tr>
            <td>
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="2"><div align="center" class="TDtable1 style34 style38"><br />
                    <span class="style41">SOLICITA&Ccedil;&Atilde;O DE CERTIFICADO DE PARTICIPA&Ccedil;&Atilde;O EM EVENTO - EMERJ</span></div></td>
                </tr>
                <tr>
                  <td colspan="2" class="TDtable2 style34 style38"><div align="right"><strong class="style33">*Via do Participante - IMPRESCIND&Iacute;VEL na solicita&ccedil;&atilde;o de certificado</strong>
                    <h3>&nbsp;</h3>
                    </div>
                    <div align="center" class="style33"><strong><br />
                      Bradesco - ag&ecirc;ncia 6246 - Conta Corrente n&ordm; 3005-8 - Favorecido: Fundo EMERJ R$ 27,00 </strong><br />
                      <br />
                  </div></td>
                </tr>
                <tr>
                  <td colspan="2" class="TDtable2 style34 style38">Nome: <?php echo($row_rsInscricao["nomeParticipante"]) ?></td>
                </tr>
                <tr>
                  <td colspan="2" class="TDtable2 style34 style38">Evento: &nbsp;<?php echo($row_rsInscricao["nomeEvento"]) ?></td>
                </tr>
                <tr>
                  <td colspan="2" class="TDtable2 style34 style38">Data do Evento: &nbsp;
                    <?php
						if ((isset($dataTemp)) && ($dataTemp !=  "" )) {
							echo($dataTemp);
						} ?></td>
                </tr>
                <tr>
                  <td width="20%" class="TDtable2 style34 style38" style="height:25px;">Data:</td>
                  <td width="70%" colspan="2" align="left" class="TDtable2 style34 style38" style="height:25px;">Visto do SEADE:</td>
                </tr>
            </table>					</td>
            </tr>
          <tr>
            <td>
              <table width="100%">
                <tr>
                  <td width="685" class="textoT&iacute;tulo style2"><div align="left"></div></td>
                  <td width="685" class="textoT&iacute;tulo style2"><div align="center"></div></td>
                  <td width="685" colspan="3" class="textoT&iacute;tulo style2"><div align="right"></div></td>
                  </tr>
                <tr>
                  <td class="textoT&iacute;tulo style2">&nbsp;</td>
                  <td class="textoT&iacute;tulo style2">&nbsp;</td>
                  <td colspan="3" class="textoT&iacute;tulo style2">&nbsp;</td>
                </tr>
                <tr>
                  <td class="textoT&iacute;tulo style2">&nbsp;</td>
                  <td class="textoT&iacute;tulo style2">&nbsp;</td>
                  <td colspan="3" class="textoT&iacute;tulo style2">&nbsp;</td>
                </tr>
                <tr>
                  <td class="textoT&iacute;tulo style2">&nbsp;</td>
                  <td class="textoT&iacute;tulo style2">&nbsp;</td>
                  <td colspan="3" class="textoT&iacute;tulo style2">&nbsp;</td>
                </tr>
                <tr>
                  <td class="textoT&iacute;tulo style2">&nbsp;</td>
                  <td class="textoT&iacute;tulo style2">&nbsp;</td>
                  <td colspan="3" class="textoT&iacute;tulo style2">&nbsp;</td>
                </tr>
                </table>					</td>
            </tr>
        </table>
      </td>
      </tr>
  </table>
</div>
<div align="center">
  <table width="30%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><div align="center"><a href="javascript:print()"><img src="../images/impressora.png" width="36" height="35" border="0" /></a></div></td>
    </tr>
  </table>
</div>
</body>
</html>