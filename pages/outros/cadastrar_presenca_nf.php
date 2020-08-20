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

//if (!function_exists ("GetSQLValueString")){

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {

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

$editFormAction = $_SERVER["PHP_SELF"]; //action na própria página
if (isset($_SERVER["QUERY_STRING"])) {
  $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);
}

//if (isset($_SESSION["codEvento"])) {
	if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "inserir")) {

    mysqli_select_db($conn, "emerjco_eventos");
	
	$codEvento = substr($_POST["codInscricao"], 2,-8); 
	$codParticipante = substr($_POST["codInscricao"], 7, 14); 

	$query_rsBusca = "SELECT p.nome as nomeParticipante, e.nome as nomeEvento, e.local as localEvento FROM evento e, participante p, inscricoes i, porta po WHERE (po.data=CURRENT_DATE) AND (i.codEvento = $codEvento) AND (i.codParticipante = $codParticipante) AND (i.codParticipante = p.codigo) AND (i.codEvento = e.codigo)"; //SELECT PARA PESQUISAR INSCRIÇÃO

	$rsBusca = mysqli_query($conn, $query_rsBusca) or die(mysqli_error($conn));
	$row_rsBusca = mysqli_fetch_assoc($rsBusca);
	$totalRows_rsBusca = mysqli_num_rows($rsBusca);
//}

//inclui registro de presença	

	$insertSQL = sprintf("INSERT INTO registroponto2 (codEvento, codParticipante, data, hora) VALUES ('".$codEvento."', '".$codParticipante."', current_timestamp(), current_timestamp());",
							GetSQLValueString($_POST["codInscricao"], "text"));
							
	mysqli_select_db($database_seven, $seven);	
	mysqli_query("SET AUTOCOMMIT=0", $seven);	
	mysqli_query("BEGIN", $seven);
	$Result1 = mysqli_query($insertSQL, $seven) or die(mysqli_error());
	
	if ($row_rsBusca > 0) {
		mysqli_query("COMMIT", $seven);
		$acao = "inserido";
	}
	else 
	{
		mysqli_query("ROLLBACK", $seven);
		$acao = "naoinserido";
	}
	mysqli_query("SET AUTOCOMMIT=1", $seven);
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


   <script language="javascript">

        // Projeto futuro passa para Jquery. Funcionando a parte de pegar o valor, so falta acrescentar ao html

      /* $(function teste(){            

            $("#btn1").click(function(){

                $("#data,#inicio,#fim,#carga").each(function() {

              $("#portas").append($(this).val()).css('font-size','20px');

            });
            });
            });

            $("#portas").val("teste");*/

            /*$("#inicio,#fim,#vaga").change(function() {

            $('#cadastrar_evento').find(":text:visible").each(function(i) {

                console.log($(this).val());

                    });

                });*/                       
    </script>

<body>

 <div id="title"><center>
   <spam>REGISTRAR PRESENÇA NO EVENTO</spam></center></div>

   <? if ($acao == NULL) { ?>
<form id="form1" name="form1" action="<?php echo $editFormAction; ?>" method="post">

<div class="container">

    <br>

    <fieldset>

  <div class="row">

      <div class="form-group">

          <div class="col-md-4 col-xs-10" align="center">

              <label for="codInscricao">Nº da Inscrição:</label>

              <input class="form-control col-lg-6" type="text" id="codInscricao" name="codInscricao" placeholder="Nº de Vagas" >

          </div>

      </div>

  </div>
  
  <div class="col-md-2 col-xs-4" align="center">

   <input class="form-control col-lg-6 btn btn-primary" type="button" id="enviar" name="insert" value="Cadastrar" onClick="MM_callJS('validaCampos();')">

   </div>
  
  	<p>

    <input type="hidden" name="MM_insert" value="form1">
    <input name="hdAcao" type="hidden" id="hdAcao" value="<?php if (isset($_SESSION["codInscricao"])) echo('alterar'); else echo('inserir') ?>"/>

	</p>

	 </fieldset>

        </form>

<strong>
<? }

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

        echo  "<script> window.alert ('Não é possível registrar sua presença. Você está inscrito no evento? Por favor, tente novamente ou entre em contato com o DETEC.'); window.location.href='cadastrar_presenca.php'
		</script>";
        }
?>
</strong>

</body>

</html>