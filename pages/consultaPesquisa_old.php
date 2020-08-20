<?php
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
    <input name="codEvento" type="text" id="codEvento" value="" size="15" />
    &nbsp;&nbsp;
    <input class="btn btn-primary" name="Enviar" type="submit" id="Enviar" value="Enviar" />
    </p>
  </div>
  </label>
  <input name="hdCodParticipante" type="hidden" id="hdCodParticipante" value="0" />
<br />  
<?php 

if (isset($totalRows_rsBusca) && ($totalRows_rsBusca>0)) {
	
	echo("<table class='table table-striped' border=1 width=710>\n");

	// cabeçalho da tabela
	echo("\t<tr style='background-color:#337AB7; color:white;'>\n");
	for ($n=0; $n<$totalFields_rsBusca; $n++) {
		echo("\t\t<td align='center'><b>" . mysqli_fetch_field_direct($rsBusca, $n)->name . "</b></td>\n"); // CORRIGIR mysql_field_name PQ Ñ ESTÁ APARECENDO O CABEÇALHO DA TABELA 
		
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
	
	echo("\t<tr>\n");
	echo("\t\t<td colspan=1>Cartaz</td>\n");
	echo("\t\t<td colspan=11></td>\n");
	echo("\t</tr>\n");
	
	echo("\t<tr>\n");
	echo("\t\t<td colspan=1>Outros</td>\n");
	echo("\t\t<td colspan=11></td>\n");
	echo("\t</tr>\n");
	
	echo("\t<tr>\n");
	echo("\t\t<td colspan=1>Universidade</td>\n");
	echo("\t\t<td colspan=11></td>\n");
	echo("\t</tr>\n");
	
	echo("</table>");
}

?>
</form>           
            </div>
          </div>   
        </div>

</body>
</html>