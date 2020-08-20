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
	header("Location: ../../index.php");
}

$nivelLogado = $_SESSION['usuarioNiveisAcessoId'];

header("Content-Type: text/html; charset=utf-8", true);

$acao = NULL;

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "UPPER('" . $theValue . "')" : "NULL";
      break;    
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
	}
  return $theValue;
}

$editFormAction = $_SERVER["PHP_SELF"];
if (isset($_SERVER["QUERY_STRING"])) {
  $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);
}


if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "inserir")) {	

	mysqli_select_db($conn, "emerjco_eventos");
	
$codEvento = substr($_POST["codInscricao"], 2,-8); 
$codParticipante = substr($_POST["codInscricao"], 7, 14); 
 
	$query_rsBusca = "SELECT i.codEvento AS CodigoEvento, e.nome, po.data, p.cpf AS cpfParticipante, i.codParticipante AS CodigoParticipante,  p.nome AS NomeParticipante,  p.matriculatj AS MatTJ, po.horaInicio, po.cargaHoraria 	FROM participante p, evento e, inscricoes i, porta po 	WHERE p.codigo = $codParticipante AND i.codEvento = $codEvento and i.codEvento = po.codEvento and i.codEvento = e.codigo and i.codParticipante = p.codigo";		//SELECT p.nome as nomeParticipante, e.nome as nomeEvento, e.local as localEvento FROM evento e, participante p, inscricoes i, porta po WHERE (po.data=CURRENT_DATE) AND (i.codEvento = $codEvento) AND (i.codParticipante = $codParticipante) AND (i.codParticipante = p.codigo) AND (i.codEvento = e.codigo)"; 		//SELECT PARA PESQUISAR INSCRIÇÃO
	$rsBusca = mysqli_query($conn, $query_rsBusca) or die(mysqli_error($conn));
	$row_rsBusca = mysqli_fetch_assoc($rsBusca);
	$totalRows_rsBusca = mysqli_num_rows($rsBusca);

    //CODINSCICAO = 12 DÍGITOS
$codEvento = substr($_POST["codInscricao"], 2,-6); 
$codParticipante = substr($_POST["codInscricao"], 6, 12); 

//CODINSCICAO = 14 DÍGITOS
//$codEvento = substr($_POST["codInscricao"], 2,-8); 
//$codParticipante = substr($_POST["codInscricao"], 7, 14);   

		//echo $_SESSION["codInscricao"];
		
	$insertSQL = sprintf("INSERT INTO registroponto (codEvento, codParticipante, data, hora) VALUES ('".$codEvento."', '".$codParticipante."', current_timestamp(), current_timestamp());",
 					    GetSQLValueString($_POST["codInscricao"], "text"));
/*	$insertSQL = sprintf("INSERT INTO frequencia2 (codEvento, codParticipante, dataPorta, horaPorta, cargaHorariaOAB, tipoCargaHoraria, dataAtribuida, permanencia, percentual)                        VALUES ('$resutLista[0]','$resutLista[1]','$resutLista[2]','$resutLista[3]','$resutLista[4]','$resutLista[5]','$resutLista[6]','$resutLista[7]', '1')";									  				GetSQLValueString($_POST["codInscricao"], "text"));
*/
	mysqli_select_db($conn, "emerjco_eventos");
    // se não confuncionar, troca para TRUE
    mysqli_autocommit($conn, TRUE);
    
	//mysql_query("BEGIN", $seven);
	$Result1 = mysqli_query($conn, $insertSQL) or die(mysqli_error($conn));
	
	if ($row_rsBusca > 0) {
		mysqli_commit($conn);
		$acao = "inserido";
	}
	else 
	{
		mysqli_rollback($conn);
		$acao = "naoinserido";
	}
	mysqli_autocommit($conn, TRUE);
}

?>

<script language="javascript">
function validaCampos()
{
		var d=document.form1;
		
		
		var erros='';
		
		if (d.codInscricao.value == ""){
			erros += "O nº de inscrição deve ser preenchido!\n";		
		}					
	
		if (erros.length > 0)
		{			
			alert('Atenção é necessário informar\n\n'+erros);		
		}
		else
		{			
			enviaFormulario();
		}			
}

  function enviaFormulario() 
  {
	document.form1.submit();
  }
 
</script>

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

<script language="JavaScript" src="../js/validador.js"></script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR">
<script type="text/JavaScript">

function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}

</script>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
<meta http-equiv="X-UA-Compatible" content="chrome=1">   

<title>.: Registro de Presença :.</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<style type="text/css">
body {
	font-family: "Titillium Web", Arial, Helvetica, sans-serif;
	font-weight: normal;
	font-style: normal;
}
@font-face {
    font-family:"Titillium Web", Arial, Helvetica, sans-serif;
    src:url(fonte/TitilliumWeb-Regular.ttf);
	font-weight:normal;
	font-style:normal;
	font-size:12px;
	color: #000000;
}
.style1 {color: #990000}
.style2 {color: #990000; font-weight: bold;
}
</style>
</head>

<body>

   <div align="center">
   <!-- inicio -->   
   <div align="center">
   
   <?php if ($acao == NULL) { ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1">
  <h2>Registro de Presen&ccedil;a em Evento</h2>
  <table width="791" border="0">
    <tr>
    <td width="217" height="32" bgcolor="#F4F4F4"><span>Nº da Inscrição<span class="style1"></span></span></td>
      <td width="606" bgcolor="#F4F4F4"><input name="codInscricao" type="int" class="textoNormal" value="<?php if ((isset($totalRows_rsBusca)) && ($totalRows_rsBusca > 0)) echo($row_rsBusca["codInscricao"]) ?>" size="16" maxlength="14" onKeyPress="return somenteNumeros(event)" /></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
  
  <br /> 
  
  <div align="center">
      <input name="insert" type="button" class="textoNormal" onClick="MM_callJS('validaCampos();')" value="Enviar" />
      &nbsp;
      <input name="reset" type="reset" id="Limpar" value="Limpar" />
    </div>
    
    <input type="hidden" name="MM_insert" value="form1">
      <input name="hdAcao" type="hidden" id="hdAcao" value="<?php if (isset($_SESSION["codInscricao"])) echo('alterar'); else echo('inserir') ?>"/>
</form>
<strong>
<?php }
if ($acao == "inserido") {
	$date = date('H:i');
		echo("<br>");
		echo("<br>");
		echo("<br>");
		echo("<p>Nome do Evento: "); echo($row_rsBusca["nomeEvento"]); 		
		echo("<p>Local do Evento: "); echo($row_rsBusca["localEvento"]);		
		echo("<p>Nome do Participante: "); echo($row_rsBusca["nomeParticipante"]); 
		echo("<p>Data do Registro: "); echo strftime( '%d/%m/%Y ', strtotime('today') ); echo("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp"); echo("Hora do Registro: "); echo $date; 
		echo("<br>");
		echo("<br>");
		echo("<p><f >Presença Registrada com sucesso!!</p>");
		echo("<br>");
		echo("<br>");
		echo("<hr>");		
		echo("<p><a href='http://emerj.com.br/seven/registroPresenca.php'>Voltar</em></a><p>");
}

if ($acao == "naoinserido") {
		echo("<br>");
		echo("<br>");
		echo("<br>");
		echo("<p>Não é possível registrar sua presença. Você está inscrito no evento?</p>");
		echo("<p>Por favor, tente novamente ou entre em contato com o DETEC.</p>");
		echo("<br>");
		echo("<br> <hr>");
		echo("<p><a href='http://emerj.com.br/seven/registroPresenca.php'>Voltar</em></a><p>");
}
?>
</strong>
   </div>
</div>

</body>
</html>