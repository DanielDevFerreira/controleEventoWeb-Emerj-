<?php

require_once('../conexao.php');

    session_start();

    if (!isset($_SESSION['usuarioNome']) or !isset($_SESSION['usuarioId']) or !isset ($_SESSION['usuarioNiveisAcessoId']) or !isset($_SESSION['usuarioEmail'])){
	unset(

		$_SESSION['usuarioId'],
		$_SESSION['usuarioNome'],
		$_SESSION['usuarioNiveisAcessoId'],
		$_SESSION['usuarioEmail']
	);

	//redirecionar o usuario para a página de login

	header("Location: ../index.php");

}

$nivelLogado = $_SESSION['usuarioNiveisAcessoId'];

header("Content-Type: text/html; charset=utf-8", true);

$acao = NULL;

$editFormAction = $_SERVER["PHP_SELF"];
if (isset($_SERVER["QUERY_STRING"])) {
  $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);
}

?>

<!DOCTYPE html>

<html>

    <head>

        <link rel="stylesheet" href="../css/evento_periodo.css">

        <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

		<!--CHAMANDO CSS QUE OCULTA ÍCONE IMPRESSORA-->
		<link rel="stylesheet" type="text/css" href="../css/print.css" media="print"/>

        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <script src="../js/funcoes_cadastrar.js" type="text/javascript"></script>  --> 

        <script src="../js/validador.js" type="text/javascript"></script>

        <script src="../js/jquery.inputmask.bundle.js" ></script>

        <meta http-equiv="X-UA-Compatible" content="chrome=1">   

        <meta http-equiv="Content-Type" content="text/html; charset=utf8">

<script language='JavaScript'>
function somenteNumeros(e) {
        var charCode = e.charCode ? e.charCode : e.keyCode;
        // charCode 8 = backspace   
        // charCode 9 = tab
        if (charCode != 8 && charCode != 9) {
            // charCode 48 equivale a 0   
            // charCode 57 equivale a 9
            if (charCode < 48 || charCode > 57) {
                return false;
            }
        }
    }
    

</script>

<style>
        
        @media print {
            form{
                display: none;
            }
        }
        
    </style>

</head>

<body>

<div id="title"><center><spam>CONSULTA FREQUÊNCIA DO PARTICIPANTE</spam></center></div>
    <br><br>
 
<?php date_default_timezone_set('America/Sao_Paulo'); ?>
  
 <div class="container">
 
     <form id="formConsultaPonto" name="formConsultaPonto" method="post" action="<?php echo $editFormAction; ?>"> 
       
        <fieldset>

        <legend>Informe o dado abaixo:</legend>
            
    <br> 

  <div class="row">
      <div class="form-group">
          <div class="col-md-5 col-xs-10 ">
              <label for="numeroInscricao">Número de Inscrição:</label>
              <input class="form-control" name="numeroInscricao" id="numeroInscricao" type="text" size="15" maxlength="14" required/>
        </div>
	</div>
  </div>
        
    <br><br>    
   
    <div class="row">
      <div class="form-group">
        <div class="col-md-2 col-xs-4">
              <input class="form-control col-lg-6 btn btn-primary" type="submit" id="ConsultadePonto" name="ConsultadePonto" onchange="vazio" value="Consulta de Ponto">
        </div>
        
        <div class="col-md-2 col-xs-4">
              <input class="form-control col-lg-6 btn btn-primary" type="reset" id="limpar" name="Limpar Campos" value="Limpar">
        </div>
      </div>
  </div>
        
       </fieldset>
     </form>
	
    </div>
    


<br/>
    
   <div class="container">
       

<?php
       
       
          
	if (empty($_POST["numeroInscricao"]))  {
		echo " ";
	}
	else{
		
		$numeroInscricao = $_POST["numeroInscricao"];
		
		$tamanhoNumeroInscricao = strlen($numeroInscricao);
		
		$codigoEvento = substr($numeroInscricao, 2, 4);
		
		if ($tamanhoNumeroInscricao == 12) {
			/* se o código com 12 dígitos */
			$codigoParticipante = substr($numeroInscricao, -6, 7);
            
		} else if ($tamanhoNumeroInscricao == 14) {
			/* se o código com 14 dígitos */
			$codigoParticipante = substr($numeroInscricao, -7, 8);
            
		} else if ($tamanhoNumeroInscricao == 0){
            echo "<script>alert('É necessário informar um código de inscrição!')</script>";
		}else if ($tamanhoNumeroInscricao < 12){
            echo "<script>alert('Número de inscrição inválido!')</script>";
            return false;
            
        }

	mysqli_select_db($conn, "emerjco_eventos");
        
	$queryEvento = strtolower(trim(stripslashes('select distinct e.codigo, e.nome, pa.nome, pa.codigo, p.data, pa.cpf from evento e, porta p, participante pa, inscricoes i where e.codigo = "' . $codigoEvento . '" AND p.codEvento ="'. $codigoEvento . '" AND pa.codigo ="' . $codigoParticipante .'"')));
        
        
	$result = mysqli_query($conn, $queryEvento) or die(mysqli_error($conn));
        
	while ($rowResultado = mysqli_fetch_row($result)) {
        
    if ($rowResultado == 0){
    	echo("<script>alert('Não há inscrição no evento.');</script>"); 
	}
	else if ($rowResultado != 0){ 	
	
    
     $padraoFormatadoData = date('d/m/Y', strtotime($rowResultado[4]));
        
        //Tabela com resultado Evento e do Participante  
        
    echo("<br><br><center><p style='font-size:20px;'><strong>Dados do Evento e Participante</strong></p></center><br>"); 

	echo("<p style='font-size:20px;'>Nome do Participante: $rowResultado[2] </p><br>");
	echo("<p style='font-size:20px;'>CPF: $rowResultado[5] </p><br>");	
        
	echo("<table border=1 class='table table-striped' style='border:1px solid black'>");
        
    echo("<tr style='background-color:#337AB7; color:white;'>
            <th align='center'>Código do evento</th>
            <th align='center'>Evento</th>
            <th align='center'>Data do evento</th>            
            <th align='center'>Código do participante</th>   
          </tr>
            
          <tr>
            <td align='center'>" . $rowResultado[0]     . "</td>
            <td align='center'>" . $rowResultado[1]     . "</td>
            <td align='center'>" . $padraoFormatadoData . "</td>
            <td align='center'>" . $rowResultado[3]     . "</td>
          </tr>
          
          </table>
          
          <br>"); 
        
        
        
        $dataDoEvento = $rowResultado[4];
        $timestamp = strtotime($dataDoEvento . "-365 days");
        //echo "1 ano atrás: " . date('d/m/Y', $timestamp); 

        // Calcula a data atual daqui 1 ano atrás
        $dataAtual = date("Y/m/d");
        $timestamp1Ano = strtotime($dataAtual . "-365 days");
        $umAnoAntes = date('Y-m-d', $timestamp1Ano);	

        if ($dataDoEvento <= $umAnoAntes){

            echo "<p align='center' style='font-size:15px; color:#900';'><strong>Evento ocorreu há mais de 1 ano atrás.</strong></p>";
        }

          $dataAtual = date("Y/m/d");
          if ($rowResultado[4] > $dataAtual){
              echo "Evento ainda não ocorreu.";
          }	

        }

        }


        mysqli_select_db($conn, "emerjco_eventos");
        $queryInscricoes = strtolower(trim(stripslashes("select codEvento, codParticipante from inscricoes where codParticipante = " . $codigoParticipante . " and codEvento = " . $codigoEvento)));
        $resultInscricoes = mysqli_query($conn, $queryInscricoes) or die(mysqli_error($conn));	
        $totalRegistrosInscricoes = mysqli_num_rows($resultInscricoes);

        if ($totalRegistrosInscricoes == 0){
            echo("<script> alert('Não há inscrição no evento.');</script>"); 
        }
        else if ($totalRegistrosInscricoes != 0){ 	
		
		
		/*Não mexer*/
		
		mysqli_select_db($conn, "emerjco_eventos");
		$queryRegistroPonto = strtolower(trim(stripslashes("select data, hora from registroponto where codParticipante = " . $codigoParticipante . " and codEvento = " . $codigoEvento . " ORDER BY data, hora ASC")));
		$resultRegistroPonto = mysqli_query($conn, $queryRegistroPonto) or die(mysqli_error($conn));
		$row_RegistroPonto = mysqli_fetch_row($resultRegistroPonto);
		$totalRegistrosRegistroPonto = mysqli_num_rows($resultRegistroPonto);
		
		if ($totalRegistrosRegistroPonto == 0){
		
		echo ("<p style='font-size:16px; color:#900';'>Não há registros para o número de inscrição.</p>");
				
		} else if ($totalRegistrosRegistroPonto != 0){
       ?>
       
        <div align=center>
       
       <?php
            
		echo "<p style='font-size:20px;'><strong>Ponto</strong></p>";
		echo "<br>";
		echo("<table class='table table-striped col-md-6' cellpadding='10px' border=1 style='border:#999 1px solid; border-collapse:collapse; padding:15px; font-size:15px;'>\n");
		
		echo("\t<tr style='background-color:#337AB7; color:white;'>\n");
	for ($n=0; $n<1; $n++) {
		echo("\t\t<td align='center'>&nbsp;<b>" . "Data" . "</b>&nbsp;</td>\n");
		echo("\t\t<td align='center'>&nbsp;<b>" . "Hora" . "</b>&nbsp;</td>\n");
	}
	echo("\t</tr>\n");

	for ($i=0; $i<$totalRegistrosRegistroPonto; $i++) {
		echo("\t<tr>\n");
		for ($n=0; $n<1; $n++) {	
			
			$padraoFormatoData = date('d/m/Y', strtotime($row_RegistroPonto[0]));
			
			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;");
			
				echo $padraoFormatoData;
			
			echo("&nbsp;</td>\n");
			
			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_RegistroPonto[1] . "&nbsp;</td>\n");
		}
		$row_RegistroPonto = mysqli_fetch_row($resultRegistroPonto);
		echo("\t</tr>\n");
	}
	echo("</table>");
	}
	echo "<br><br>";
		
		/**/
		
		mysqli_select_db($conn, "emerjco_eventos");
		$queryFrequencia = strtolower(trim(stripslashes("select distinct f.codEvento, f.codParticipante, f.dataPorta, f.horaPorta, f.cargaHorariaOAB, f.permanencia, f.percentual, p.horaFim, f.tipoCargaHoraria from frequencia2 f, porta p where f.codParticipante = " . $codigoParticipante . " and f.codEvento = " . $codigoEvento . " and (f.codEvento = p.codEvento) and (f.horaPorta = p.horaInicio) ORDER BY f.dataPorta, f.horaPorta ASC")));
		$resultFrequencia = mysqli_query($conn, $queryFrequencia) or die(mysqli_error($conn));
		$row_Frequencia = mysqli_fetch_row($resultFrequencia);
		$totalRegistrosFrequencia = mysqli_num_rows($resultFrequencia);
		
		$dataAtual = date("Y/m/d");
		
	if ($totalRegistrosFrequencia == 0){
		
		echo ("<p style='font-size:16px; color:#900';'>Não há apuração para o número de inscrição.</p>");
						
	} else if ($totalRegistrosFrequencia != 0){
		
		echo "<p style='font-size:20px;'><strong>Apuração</strong></p>";
		echo "<br>";
		echo("<table class='table table-striped cellpadding='10px' border=1 style='border:#999 1px solid; border-collapse:collapse; padding:15px; font-size:15px;'>\n");
		
		echo("\t<tr style='background-color:#337AB7; color:white;'>\n");
	for ($n=0; $n<1; $n++) {
		echo("\t\t<td align='center' valign='middle'>&nbsp;<b>" . "Data" . "</b>&nbsp;</td>\n");
		echo("\t\t<td align='center'>&nbsp;<b>" . "Hora Inicial" . "</b>&nbsp;</td>\n");
		echo("\t\t<td align='center'>&nbsp;<b>" . "Hora Final" . "</b>&nbsp;</td>\n");
		echo("\t\t<td align='center'>&nbsp;<b>" . "Permanência" . "</b>&nbsp;</td>\n");
		echo("\t\t<td align='center'>&nbsp;<b>" . "Percentual" . "</b>&nbsp;</td>\n");
		echo("\t\t<td align='center'>&nbsp;<b>" . "Carga Horária OAB" . "</b>&nbsp;</td>\n");
		echo("\t\t<td align='center'>&nbsp;<b>" . "Tipo de Presença" . "</b>&nbsp;</td>\n");
	}
	echo("\t</tr>\n");

	for ($i=0; $i<$totalRegistrosFrequencia; $i++) {
		echo("\t<tr>\n");
		for ($n=0; $n<1; $n++) {	
			
			$padraoFormatoData = date('d/m/Y', strtotime($row_Frequencia[2]));
			
			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $padraoFormatoData . "&nbsp;</td>\n");
			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[3] . "&nbsp;</td>\n");
			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[7] . "&nbsp;</td>\n");
			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[5] . "&nbsp;</td>\n");
			
			$percentualPortas = $row_Frequencia[6] * 100;
			$percentualPortasFormatado = round($percentualPortas,2);
			
			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $percentualPortasFormatado . " %&nbsp;</td>\n");
			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[4] . "&nbsp;</td>\n");
			echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[8] . "&nbsp;</td>\n");
		}
		$row_Frequencia = mysqli_fetch_row($resultFrequencia);
		echo("\t</tr>\n");
	}
	echo("</table>");
	
	echo "<br><br>";
	echo "<strong>Tipo de presença:</strong> 1 - Normal / 2 - Manual / 3 - Videoconferência / 4 - Requerimento / 5 - Webinar";
	echo "<br><br>";

	}
	}	
}
       
?>
<br />
    </div>
    </div>

<!--BOTÃO IMPRESSORA -->
<div id="btn-print"  align="center"><a href="javascript:print();"><img src="../images/impressora.png" width="36px" height="35px" /></a></div>  

<br />
<br />

</body>
</html>