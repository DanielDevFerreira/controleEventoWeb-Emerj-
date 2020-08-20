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

	$editFormAction = $_SERVER["PHP_SELF"];
	if (isset($_SERVER["QUERY_STRING"])) {
	  $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);
	}

	if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "relatorio")) {
		$_SESSION["codEvento"] = $_POST["codEvento"];
		$insertGoTo = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"])
									. "/relatorio.php";
		header(sprintf("Location: %s", $insertGoTo));		
	}
?>

<script language="javascript">
	function relatorio(){
		if (document.form1.selBusca) {
			if (document.form1.selBusca.value != "") {
				document.form1.hdAcao.value = "relatorio";
				document.form1.codEvento.value = document.form1.selBusca.value;
				document.form1.submit();
			} else {
				alert("Você deve selecionar um evento.");
			}
		} else {
			alert("Você deve buscar e selecionar um evento.");	
		}
	}
</script>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

    <html xmlns="http://www.w3.org/1999/xhtml">

        <head>
            
            <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
            <script language="JavaScript" src="../../js/validador.js"></script>
            <meta http-equiv="Content-Type" content="text/html; charset=utf8"/>
            
        <style>
            
            #title{
                color: white;
                background: #0c344c;
                font-family: 'Bree Serif', serif;
                padding: 0.75%;
                font-size: 120%;
                text-shadow: 5px 5px 5px #333;
            }
            
        </style>
            
        </head>

    <body>
        
        <div id="title"><center><spam>Informações do Evento</spam></center></div>
        
        <?php // Assim que entra
            if (isset($_SESSION["codEvento"])) {

            mysqli_select_db($conn, "emerjco_eventos");
            $consulta = "SELECT 'Inscritos no evento',
                        COUNT(codParticipante)
                        FROM inscricoes
                        WHERE codEvento = " . $_SESSION["codEvento"] . "
                        UNION 
                        SELECT 'Vagas restantes',
                                vagas
                        FROM evento
                        WHERE codigo = " . $_SESSION["codEvento"] . "
                        UNION 
                        SELECT 'Participantes na base de dados',
                                COUNT(codigo)
                        FROM participante
                        UNION 
                        SELECT 'Participantes ativos',
                                COUNT(codigo)
                        FROM participante
                        WHERE ativo=1";
            $rsConsulta = mysqli_query($conn, $consulta) or die(mysqli_error($conn));
            $row_rsConsulta = mysqli_fetch_array($rsConsulta);
            $totalRows_rsConsulta = mysqli_num_rows($rsConsulta);
                
            

        

        if ($totalRows_rsConsulta > 0) { ?>
        
        <br><br>
        
         <div class="container">
            <form action="<?php echo $editFormAction; ?>" method="post" name="form1">
                
                
                <table class='table table-striped'>
                    <caption style='background-color:#337AB7; color:white; padding: 1%;'>Dados do Evento</caption>
                    <?php do {
                            echo ("<tr style='margin-top: 20px;'><td class='textoT&iacute;tulo' style='width:300px;' >" . $row_rsConsulta[0] . "</td>");
                            echo ("<td class='textoT&iacute;tulo' style='width:50px; margin-top: 10px; ' >" . $row_rsConsulta[1] . "</td></tr>");
                        } while ($row_rsConsulta = mysqli_fetch_row($rsConsulta));
                    ?>		
                    <tr>
                        <td colspan="2"><div align="center"><a href="relatorio.php">Lista de Inscritos</a></div></td>
                    </tr>
                    <tr>
                        <td colspan='2' align='center'>
                        <a href='buscarEvento.php'><button type="button" class="btn btn-primary col-md-2">voltar</button></a></td>
                    </tr>
                </table>
                <input name="hdAcao" type="hidden" id="hdAcao" value="<?php if (isset($_POST["hdAcao"])) echo ($_POST["hdAcao"]); ?>"/>
            </form>
             </div>

            <?php
                                        
                                   
                                        
            } else {
                echo("<p>Não foi encontrado nenhuma informação sobre o evento selecionado.</p><hr>");
                echo("<a href='admin.php'>voltar</a>"); 	}
        } else {
            echo("<p>Nenhum evento foi selecionado!</p><hr>");
            echo("<a href='admin.php'>voltar</a>");
        } ?>
        </body>
        </html>