﻿<?
require_once('../conexao.php');

$editFormAction = $_SERVER["PHP_SELF"];
if (isset($_SERVER["QUERY_STRING"])) {
  $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);
}

if (isset($_POST["codEvento"]) && (!(is_null($_POST["codEvento"])))) {
	mysqli_select_db($conn, "emerjco_eventos");
	$query = "SELECT pergunta as 'Pergunta', op0 as 'Cartaz', op1 as 'Site da EMERJ', op2 as 'Email', op3 as 'Ofício', op4 as 'Outros', op5 as 'Universidade', op6 as 'Instalações do TJERJ', op7 as 'Instalações da EMERJ', 
	op8 as 'Facebook/Twitter', op9 as 'Indicação de Terceiros', op10 as 'Instagram' FROM pergunta WHERE codEvento = " . $_POST["codEvento"] . "";
	$rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));
	$row_rsBusca = mysqli_fetch_row($rsBusca);
	$totalRows_rsBusca = mysqli_num_rows($rsBusca);
	$totalFields_rsBusca = mysqli_num_fields($rsBusca);

	//mysqli_select_db($conn, "emerjco_eventos");
	$query_rsBusca1 = "SELECT c.local AS Local, p.codEvento FROM cartaz c, pergunta p WHERE c.codEvento = " . $_POST["codEvento"] . " and  c.codEvento = p.codEvento ";
	$rsBusca1 = mysqli_query($conn, $query_rsBusca1) or die(mysqli_error($conn));
	$row_rsBusca1 = mysqli_fetch_row($rsBusca1);
	$totalRows_rsBusca1 = mysqli_num_rows($rsBusca1);
	$totalFields_rsBusca1 = mysqli_num_fields($rsBusca1);

	$query_rsBusca2 = "SELECT o.outros AS Outros FROM outros o WHERE o.codEvento= " . $_POST["codEvento"] . "";
	$rsBusca2 = mysqli_query($conn, $query_rsBusca2) or die(mysqli_error($conn));
	$row_rsBusca2 = mysqli_fetch_row($rsBusca2);
	$totalRows_rsBusca2 = mysqli_num_rows($rsBusca2);
	$totalFields_rsBusca2 = mysqli_num_fields($rsBusca2);

	$query_rsBusca3 = "SELECT u.universidade AS Universidade FROM universidade u WHERE u.codEvento= " . $_POST["codEvento"] . "";
	$rsBusca3 = mysqli_query($conn, $query_rsBusca3) or die(mysqli_error($conn));
	$row_rsBusca3 = mysqli_fetch_row($rsBusca3);
	$totalRows_rsBusca3 = mysqli_num_rows($rsBusca3);
	$totalFields_rsBusca3 = mysqli_num_fields($rsBusca3);
}
?>


 <!DOCTYPE html>

<html>

    <head>

        <link rel="stylesheet" href="../css/cadastrar_eventos.css">

        <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <script src="../js/funcoes_cadastrar.js" type="text/javascript"></script> 

        <script src="../js/validador.js" type="text/javascript"></script>

        <script src="../js/jquery.inputmask.bundle.js" ></script>

        <meta http-equiv="X-UA-Compatible" content="chrome=1">   

        <meta http-equiv="Content-Type" content="text/html; charset=utf8">

    </head>

<body> 

 <div id="title"><center><spam>CONSULTA RESULTADO DA ENQUETE</spam></center></div>

<form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">
              <p>&nbsp;</p>
  <div align="center">
    <p>
      <label>Código do Evento:</label>&nbsp;&nbsp;
    <input name="codEvento" type="text" id="codEvento" value="" size="10" />
    &nbsp;&nbsp;
    <input name="Enviar" type="submit" id="Enviar" value="Enviar" />
    </p>
  </div>
  </label>
  <input name="hdCodParticipante" type="hidden" id="hdCodParticipante" value="0" />
<br />  
<? 

if (isset($totalRows_rsBusca) && ($totalRows_rsBusca>0)) {
	
	echo("<table class='table table-striped' border=1 width=710>\n");

	// cabeçalho da tabela
	echo("\t<tr style='background-color:#337AB7; color:white;'>\n");
	for ($n=0; $n<$totalFields_rsBusca; $n++) {
		echo("\t\t<td align='center'><b>" . mysqli_fetch_field_direct($rsBusca, $n)->name . "</b></td>\n"); 
		
	}
	echo("\t</tr>\n");

	for ($i=0; $i<$totalRows_rsBusca; $i++) {
		echo("\t<tr>\n");
		for ($n=0; $n<$totalFields_rsBusca; $n++) {
			if ($n == 0) {
				echo("\t\t<td align='center'>" . $row_rsBusca[$n] . "</td>\n");
			} else {
				echo("\t\t<td align='center'>" . $row_rsBusca[$n] . "&nbsp;</td>\n");
			}
		}
		$row_rsBusca = mysqli_fetch_row($rsBusca);
		echo("\t</tr>\n");
	}
	
	// linha em branco
	echo("\t<tr>\n");
	echo("\t\t<td colspan=12><br></td>\n");
	echo("\t</tr>\n");
	
	// respostas dos comentários
	echo("\t<tr>\n");
	echo("\t\t<td align='center' colspan=12><b>Listar as respostas do Cartaz / Outros / Universidade</b></td>\n");
	echo("\t</tr>\n");
	
	// linha em branco
	echo("\t<tr>\n");
	echo("\t\t<td colspan=12><br></td>\n");
	echo("\t</tr>\n");
	
	echo("\t<tr>\n");
	echo("\t\t<td colspan=12><b>Cartaz</b></td>\n");
	echo("\t</tr>\n");
		
	$i1 = 1;
	while ($vet1=mysqli_fetch_row($rsBusca1)) {
		echo("\t<tr>\n");
		echo("\t\t<td align='center' width='3%'>" . $i1 . "&nbsp;</td>\n");
		echo("\t\t<td align='left' colspan='11'>" . $vet1[0] . "&nbsp;</td>\n");
		echo("\t</tr>\n");
		$i1++;
	}
	
	// linha em branco
	echo("\t<tr>\n");
	echo("\t\t<td colspan=12><br></td>\n");
	echo("\t</tr>\n");
	
	echo("\t<tr>\n");
	echo("\t\t<td colspan=12><b>Outros</b></td>\n");
	echo("\t</tr>\n");
		
	$i2 = 1;
	while ($vet2=mysqli_fetch_row($rsBusca2)) {
		echo("\t<tr>\n");
		echo("\t\t<td align='center' width='3%'>" . $i2 . "&nbsp;</td>\n");
		echo("\t\t<td align='left' colspan='11'>" . $vet2[0]  . "&nbsp;</td>\n");
		echo("\t</tr>\n");
		$i2++;
	}
	
	// linha em branco
	echo("\t<tr>\n");
	echo("\t\t<td colspan=12><br></td>\n");
	echo("\t</tr>\n");
	
	echo("\t<tr>\n");
	echo("\t\t<td colspan=12><b>Universidade</b></td>\n");
	echo("\t</tr>\n");
		
	$i3 = 1;
	while ($vet3=mysqli_fetch_row($rsBusca3)) {
		echo("\t<tr>\n");
		echo("\t\t<td align='center' width='3%'>" . $i3 . "&nbsp;</td>\n");
		echo("\t\t<td align='left' colspan='11'>" . $vet3[0]  . "&nbsp;</td>\n");
		echo("\t</tr>\n");
		$i3++;
	}
	
	echo("</table>");
}

?>
</form>           
            </div>
          </div>   
        </div>

</body>
</html>