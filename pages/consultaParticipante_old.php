<?
require_once('../conexao.php');

$editFormAction = $_SERVER["PHP_SELF"];
if (isset($_SERVER["QUERY_STRING"])) {
  $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);
}

if (isset($_POST["txtNome"]) && (!(is_null($_POST["txtNome"])))) {
	mysqli_select_db($conn, "emerjco_eventos");
	$query = "SELECT * FROM participante WHERE nome LIKE '%" . strtoupper(trim(stripslashes($_POST["txtNome"]))) . "%'";
	$rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));
	$row_rsBusca = mysqli_fetch_row($rsBusca);
	$totalRows_rsBusca = mysqli_num_rows($rsBusca);
	$totalFields_rsBusca = mysqli_num_fields($rsBusca);
}

if (isset($_POST["hdCodParticipante"]) && ($_POST["hdCodParticipante"] != 0)) {
	$query = "SELECT p.nome AS NomeParticipante, e.nome AS NomeEvento FROM participante p, evento e, inscricoes i WHERE i.codParticipante=" . $_POST["hdCodParticipante"] . " AND p.codigo = i.codParticipante AND e.codigo = i.codEvento";
	$rsBusca = mysqli_query($conn, $query) or die(mysql_error($conn));
	$row_rsBusca = mysqli_fetch_row($rsBusca);
	$totalLinhas_rsBusca = mysqli_num_rows($rsBusca);
	$totalRows_rsBusca = 0;
}

?>
<script language="javascript">
	function listaInscricoes() {
		if (document.form1.rdCodParticipante.length) {
		    for (i=0;i<document.form1.rdCodParticipante.length;i++){ 
			    if (document.form1.rdCodParticipante[i].checked) 
					document.form1.hdCodParticipante.value = document.form1.rdCodParticipante[i].value;
			}
		} else {
			document.form1.hdCodParticipante.value = document.form1.rdCodParticipante.value;
		}
		document.form1.submit();
	}
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR"><!-- InstanceBegin template="/Templates/pagina.dwt" codeOutsideHTMLIsLocked="false" -->

<!--
Design by Fernanda Santos
-->

<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<!-- InstanceBeginEditable name="doctitle" -->
<title>.: CONTROLE DE EVENTOS :.</title>
<!-- InstanceEndEditable -->

<link href="../css/pagina.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
.style22 {
	font-size: 12px;
	color: #FFFFFF;
}
body {
	background-image: url();
}
a:link {
	color: #990000;
}
.style25 {color: #000000}
-->
</style>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>
<body>
<div id="header">
</div>

<div id="content">
  <!-- end #sidebar -->
<div id="main">
		<!-- InstanceBeginEditable name="teste" -->
        <div id="welcome" class="post">
			
			<div id="title">
        <center>
            <spam>CONSULTAR DADOS DO PARTICIPANTE</spam>
        </center>
    </div>
			
          <div class="story">
            <div align="center">
             <strong></strong>
            
            <form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">
              <p>&nbsp;</p>
  <div align="center">
    <p>
      <label>Nome:</label>&nbsp;&nbsp;
    <input name="txtNome" type="text" id="txtNome" value="" size="50" />
    &nbsp;&nbsp;
    <input name="Enviar" type="submit" id="Enviar" value="Enviar" />
    </p>
  </div>
  </label>
  <input name="hdCodParticipante" type="hidden" id="hdCodParticipante" value="0" />
<br />  
<? 
if (isset($totalRows_rsBusca) && ($totalRows_rsBusca>0)) {
	echo("<table border=1>\n");

	// cabeçalho da tabela
	echo("\t<tr>\n");
	for ($n=0; $n<$totalFields_rsBusca; $n++) {
		echo("\t\t<td><b>" . mysqli_fetch_field_direct($rsBusca, $n)->name . "</b></td>\n"); // CORRIGIR mysql_field_name PQ Ñ ESTÁ APARECENDO O CABEÇALHO DA TABELA 
	}
	echo("\t</tr>\n");

	for ($i=0; $i<$totalRows_rsBusca; $i++) {
		echo("\t<tr>\n");
		for ($n=0; $n<$totalFields_rsBusca; $n++) {
			if ($n == 0) {
				echo("\t\t<td><input name='rdCodParticipante' type='radio' value='" . $row_rsBusca[$n] . "' />" . $row_rsBusca[$n] . "</td>\n");
			} else {
				echo("\t\t<td>" . $row_rsBusca[$n] . "&nbsp;</td>\n");
			}
		}
		$row_rsBusca = mysqli_fetch_row($rsBusca);
		echo("\t</tr>\n");
	}

	// rodapé da tabela
	echo("\t<tr>\n");
	echo("\t\t<td colspan=3><b>Total: " . $totalRows_rsBusca . "</b></td>\n");
	echo("\t\t<td colspan=" . ($totalFields_rsBusca - 3) . "><input name='btListaInscricoes' type='button' id='btListaInscricoes' onClick='listaInscricoes();' value='Listar Inscricoes' /></td>\n");
	echo("\t</tr>\n");
	echo("</table>");
}

//Exibe eventos inscritos
if (isset($totalLinhas_rsBusca) && ($totalLinhas_rsBusca>0)) {
	for ($i=0; $i<$totalLinhas_rsBusca; $i++) {
		if ($i == 0) {
			echo ("<p><b>Participante: </b>" . $row_rsBusca[0] . "</p>");
			echo ("<p><b>Evento(s) inscrito(s): </b><br>");
			echo ($row_rsBusca[1] . "<br>");
		} else {
			echo ($row_rsBusca[1] . "<br>");
		}
		$row_rsBusca = mysqli_fetch_row($rsBusca);
	}
	echo ("</p>");
}
?>
</form>           
            </div>
          </div>
            
        </div>
	  <!-- InstanceEndEditable -->

		<p></p>
   </div>
<!-- end #main -->
</div>
<!-- end #sidebar2 -->
<!-- end #content -->
<div id="footer" style="margin-bottom:0px;">
<br />
    <div align="center">
	  <div align="center"><span class="style22"><strong>ESCOLA DA MAGISTRATURA DO ESTADO   DO RIO DE JANEIRO - EMERJ<br />
	    Rua Dom Manuel, n&ordm; 25 - Centro - Telefone:   3133-2682<br />
      </strong></span></div>
  </div>
</div>
</body>
<!-- InstanceEnd --></html>