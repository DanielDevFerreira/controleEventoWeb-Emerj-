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

	header("Content-Type: text/html; charset=utf8", true);



$editFormAction = $_SERVER["PHP_SELF"];

if (isset($_SERVER["QUERY_STRING"])) {

  $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);

}

?>


    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"

    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

    <html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR">

    <head><meta http-equiv="Content-Type" content="text/html; charset=uft8">

    <title>Lista de cancelamentos</title>

    <link href="../../css/pagina.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!--CHAMANDO CSS QUE OCULTA ÍCONE IMPRESSORA-->
    <link rel="stylesheet" type="text/css" href="../css/print.css" media="print"/>

    <link rel="icon" href="favicon.ico"/>

    <link rel="stylesheet" href="../css/cadastrar_eventos.css">

    <meta name="robots" content="noindex">

    <script language='JavaScript'>

    function SomenteNumero(e){

        var tecla=(window.event)?event.keyCode:e.which;   

        if((tecla>47 && tecla<58)) return true;

        else{

            if (tecla==8 || tecla==0) return true;

        else  return false;

        }

}

</script>

<style type="text/css">


    th, td {

        padding: 5px;

    }


    @media print {
        form, #buscar{
            display: none;
        }
    }



</style>

</head>

    <body style="margin-top:0px; padding-top:0px;">

    <div align="center" style="background-color:#03354e; margin-top:0px; padding-top:0px;"></div>



    
    <br><br>
        
    <div class="container">

    <div class="row">
      <div class="form-group">
            <div class="col-md-4 col-xs-9">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                   <legend>Informe os dados abaixo:</legend>
                   <label for="codEvento">Código do Evento:</label>
                   <input class="form-control" type="text" id="codEvento" name="codEvento" placeholder="Código do Evento">
            </div>
          
            <div class="col-md-2 col-xs-5">
                <input style="margin-top: 75px;" class="form-control btn btn-primary" type="submit" id="buscar" name="buscar" value="Buscar">
            </div>
              
                </form>
            </div>
        </div>
    
   <br><br> 

<?php 

    if(isset($_POST['codEvento'])){
        
    $codEvento = $_POST['codEvento'];
        
    $query_cancelamento = 'SELECT c.id, c.codEvento, e.nome as nomeEvento, c.codParticipante, c.navegador, c.versao, c.interface, c.tipoDispositivoMovel, c.dataHora FROM cancelamento c, evento e WHERE c.codEvento= "' . $codEvento . '"AND e.codigo=c.codEvento';

	$result_cancelamento = mysqli_query($conn, $query_cancelamento) or die(mysqli_error($conn));	

	$totalRegistros = mysqli_num_rows($result_cancelamento);
        
        
        
    ?>    
        
    <div align="center">

	<table class="table table-striped"  border="1" align="center" style="border:#999 1px solid; border-collapse:collapse;" id="informacoes">	

    	<tr style="background-color: #337AB7; color: white;">

          <td align="center" width="3%"><span style="font-size:15px"><strong>Id</strong></span></td>
          <td align="center" width="5%"><span style="font-size:15px"><strong>Código do Evento</strong></span></td>
          <td align="center" width="44%"><span style="font-size:15px"><strong>Nome do Evento</strong></span></td>
          <td align="center" width="7%"><span style="font-size:15px"><strong>Código do(a) Participante</strong></span></td>
          <td align="center" width="10%"><span style="font-size:15px"><strong>Navegador</strong></span></td>
          <td align="center" width="10%"><span style="font-size:15px"><strong>Versão</strong></span></td>
          <td align="center" width="10%"><span style="font-size:15px"><strong>Interface</strong></span></td>
          <td align="center" width="10%"><span style="font-size:15px"><strong>Dispositivo Móvel</strong></span></td>
          <td align="center" width="20%"><span style="font-size:15px;"><strong>Data/Hora do cancelamento</strong></span></td>
            
    	</tr>

    

    <?php  $i = 1; 

	while ($row = mysqli_fetch_assoc($result_cancelamento)) {  
		      
        echo "<tr>";
        echo "<td align='center' width='3%'><span style='font-size:15px'>" . $row["id"] . "<span></td>\n";
        echo "<td align='center' width='5%'><span style='font-size:15px'>" . $row["codEvento"] . "<span></td>\n";
        echo "<td align='center' width='44%'><span style='font-size:15px'>" . $row["nomeEvento"] . "<span></td>\n";
        echo "<td align='center' width='7%'><span style='font-size:15px'>" . $row["codParticipante"] . "<span></td>\n";
        echo "<td align='center' width='10%'><span style='font-size:15px'>" . $row["navegador"] . "<span></td>\n";
        echo "<td align='center' width='10%'><span style='font-size:15px'>" . $row["versao"] . "<span></td>\n";
        echo "<td align='center' width='10%'><span style='font-size:15px;'>" . $row["interface"] . "<span></td>\n"; 
        echo "<td align='center' width='10%'><span style='font-size:15px;'>" . $row["tipoDispositivoMovel"] . "<span></td>\n"; 

		$retornaData = strstr($row["dataHora"], ' ', true);

		$retornaHora = strstr($row["dataHora"], ' ');

		$padraoFormatoData = date('d/m/Y', strtotime($retornaData));

		
        echo "<td align='center' width='30%'><span style='font-size:15px;'>" . $padraoFormatoData . " &agrave;s " . $retornaHora . "<span></td>\n";         
        echo "</tr>";
        $i++;

    }

		echo("<tr><td colspan=9>&nbsp;</td></tr>\n");
		echo("<td align='left' colspan=9><strong><span style='font-size:15px;'>Total de cancelamentos: " . $totalRegistros . "</span></strong></td></tr>\n");
        echo "</table>";

    ?>			

    </table>

   </div>
    </div>    
        
        
     <?php   
        
    } else{
        
        echo("<p>Nenhum evento foi selecionado!</p><hr>");
    }

?>
    

    <br/><br/>

    <!--BOTÃO IMPRESSORA -->
    <div id="btn-print"  align="center"><a href="javascript:print();"><img src="../images/impressora.png" width="36px" height="35px" /></a></div>  

    </body>

    </html>