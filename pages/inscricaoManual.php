<?php 
    require_once('../conexao.php');
    //require_once('../recurso/enviaMail.php');


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
<script language="javascript">
<!--
    
// MASCARA PARA O CAMPO CPF
    
 function fMasc(objeto,mascara) {
				obj=objeto
				masc=mascara
				setTimeout("fMascEx()",1)
			}
 function fMascEx() {
				obj.value=masc(obj.value)
			}
		

function mCPF(cpf){
				cpf=cpf.replace(/\D/g,"")
				cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
				cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
				cpf=cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
				return cpf
			}
    
    
    
function buscarEvento() {
	document.form1.hdAcao.value = "buscar";
	document.form1.submit();
}

function inscreverManual() {
	if ((document.form1.selBusca) && (validaCPF())) {
		if (document.form1.selBusca.value != "") {
         if(confirm("Confirma a inscrição do Participante no Evento seleciona ?")){
			document.form1.hdAcao.value = "inscreverManual";
			document.form1.codEvento.value = document.form1.selBusca.value;
			document.form1.submit();
		}} else {
			alert("Você deve selecionar um evento.");
		}
	} else {
		alert("Você deve buscar e selecionar um evento e preencher o CPF corretamente.");	
	}
}

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

//-->
</script>


    <!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR">
    <head>
        
<script language="JavaScript" src="../js/validador.js"></script>
        
        <link rel="stylesheet" href="../css/alterar_evento.css">
        <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">  
    </head>
    
    <body>
    
        <div id="title"><center><spam>INSCRIÇÃO MANUAL EM EVENTOS PASSADOS</spam></center></div>
        
     <div class="container">    
        <form id="form1" name="form1" style="padding: 2%;" action="<?php echo $editFormAction; ?>" method="post">
           
                <fieldset>
                    <legend>Informe os dados abaixo:</legend>
     
        <br>            
                    
  <div class="row">
      <div class="form-group">
          <div class="col-md-6 col-xs-10">
              <label for="nome-evento">Nome do evento:</label>
              <input type="text" class="form-control" type="text" id="txtBuscaNome" name="txtBuscaNome" required placeholder="Nome do evento">       
          </div>
     </div>
</div> 
                    
    <br>
                    
                    
<div class="row">
      <div class="form-group">
          <div class="col-md-4 col-xs-10">
              <label for="nome-evento">CPF:</label>
              <input maxlength="14" onkeydown="javascript: fMasc( this, mCPF );" type="text" class="form-control" type="text" id="txtMascaraCpf" name="txtMascaraCpf" required placeholder="CPF do participante" value="<?php if (isset($_POST['txtMascaraCpf'])) echo($_POST['txtMascaraCpf']); ?>">       
          </div>
      
          
      <div class="col-md-2 col-xs-4">
              <input style="margin-top: 25px;" class="form-control btn btn-primary" type="button" id="buscar" name="buscar" onClick="buscarEvento();" value="Buscar">        
          </div>
     </div>
</div>
                    <br><br>
                    
                    
 <?php
    
 // busca evento
if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "buscar")) {
	
	$query_rsBusca = "SELECT DISTINCT e.codigo, e.nome, po.codEvento 
					  FROM evento e, porta po 
					  WHERE ((e.nome LIKE '%" . STRTOUPPER($_POST["txtBuscaNome"]) . "%') AND
							 (e.codigo=po.codEvento))";
					  
	$rsBusca = mysqli_query($conn, $query_rsBusca) or die(mysqli_error($conn));
	$row_rsBusca = mysqli_fetch_assoc($rsBusca);
	$totalRows_rsBusca = mysqli_num_rows($rsBusca);
}

//inscreve manualmente
if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "inscreverManual")) {
	
	$query_rsBusca = "SELECT p.codigo AS codParticipante
					  FROM participante p 
					  WHERE (p.cpf = " . str_replace("-","",(str_replace(".","",$_POST["txtMascaraCpf"]))) . ")";

	$rsBusca = mysqli_query($conn, $query_rsBusca) or die(mysqli_error($conn));
	$row_rsBuscaParticipante = mysqli_fetch_assoc($rsBusca);
	$totalRows_rsBuscaParticipante = mysqli_num_rows($rsBusca);

	if ($totalRows_rsBuscaParticipante > 0) {

        //VERIFICAR SE JÁ EXISTE INSCRIÇÃO PARA AQUELE CPF NO EVENTO
        $query_rsBuscaInscricao = 'SELECT i.codEvento, i.codParticipante 
        FROM inscricoes i 
        WHERE ((i.codEvento = "'.$_POST["codEvento"].'") AND (i.codParticipante = "'.$row_rsBuscaParticipante["codParticipante"].'"))';

        $rsBuscaInscricao = mysqli_query($conn, $query_rsBuscaInscricao) or die(mysqli_error($conn));
        $row_rsBuscaInscricao = mysqli_fetch_assoc($rsBuscaInscricao);
        $totalRows_rsBuscaInscricao = mysqli_num_rows($rsBuscaInscricao);

        if($totalRows_rsBuscaInscricao == 0){
            $query_rsInscreve = "INSERT INTO inscricoes (codEvento, codParticipante) VALUES (" 
						. $_POST["codEvento"] . "," 
						.  $row_rsBuscaParticipante["codParticipante"] . ")";
		$result = mysqli_query($conn, $query_rsInscreve);
		
		
		if ($result) {

            $insertGoTo = "confirma.php?codEvento=" . $_POST['codEvento'] ."&codParticipante=" . $row_rsBuscaParticipante['codParticipante'];
			header(sprintf("Location: %s", $insertGoTo));
			
		} 

	} else {
			echo("<div class='row' style='margin-left:2px;'><div class='alert alert-danger col-md-7'>Participante já inscrito neste evento, Para reimprimir o comprovante de inscrição" .
				 "<a href='confirma.php?codEvento=" . $_POST["codEvento"] ."&codParticipante=" . $row_rsBuscaParticipante["codParticipante"] . "'> clique aqui</a>" .
				 "</div></div>");
		}
    } else {
		echo("<script> alert('Participante não cadastrado.')</script>");

    }
}
 ?>
                    
  <?php
                    
    if(isset($_POST['txtMascaraCpf'])){
        $cpf = $_POST['txtMascaraCpf'];          
   
        
    mysqli_select_db($conn, "emerjco_eventos");
        
	$queryEvento = 'SELECT nome, codigo, email FROM participante WHERE (cpf = "' . str_replace("-","",(str_replace(".","",$cpf))) . '")';
        
        
	$result = mysqli_query($conn, $queryEvento) or die(mysqli_error($conn));
        
	while ($rowResultado = mysqli_fetch_row($result)) {
        
    if ($rowResultado == 0){
    	echo("<script>alert('Não há inscrição no evento.');</script>"); 
	}
	else if ($rowResultado != 0){ 	
	 
        //Tabela com resultado Evento e do Participante  
        
    echo("<br><br><p style='font-size:20px;'><strong>Dados do Participante:</strong></p><br>");   
        
	echo(" 
            <div class='row'>
             <div class='form-group'>
              <div class='col-md-6 col-xs-4'>
            <table border=1 class='table table-striped' style='border:1px solid black'>");
        
    echo("
      
            <tr style='background-color:#337AB7; color:white;'>
            <th>Nome Participante</th>
            <th class='col-md-1'>Codigo Participante</th>
            <th>Email Participante</th>   
          </tr>
          
            
          <tr>
            <td>" . $rowResultado[0]     . "</td>
            <td>" . $rowResultado[1]     . "</td>
            <td>" . $rowResultado[2]     . "</td>
            
          </tr>
          
          </table>
           </div>  
         </div>  
       </div>
      
          
          <br>"); 
    
    }
        
    }
                    
                    
      }               
                    
		if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "buscar"))
		if ($totalRows_rsBusca > 0) {
			echo ("<div class='row'>
                    <div class='form-group'>
                        <div class='col-md-9'>
                        <strong><p style='font-size:20px;'>Lista de Eventos:</p></strong>
                        <tr><td colspan='3'><select style='height:200px; border:1px solid #000000' class= 'form-group col-md-11 col-xs-12' name='selBusca' size='5'>");
			do {  
		        echo ("<option style='border-bottom:1px solid #d3d3d3; padding:20px 10px 10px 10px;' value='" . $row_rsBusca["codigo"] . "'>" . $row_rsBusca["nome"] . "</option>");
			} while ($row_rsBusca = mysqli_fetch_assoc($rsBusca));
    	    $rows = mysqli_num_rows($rsBusca);
        	if($rows > 0) {
	  	      mysqli_data_seek($rsBusca, 0);
		      $row_rsBusca = mysqli_fetch_assoc($rsBusca);
	          echo("</select></td></tr></div></div></div>");
			  mysqli_free_result($rsBusca);
			} 
			else {
			echo("N&atilde;o houve registro encontrado!");
			}
   } ?>
             
        <br><br>                
                    
        <div class="row">
            <div class="form-group">                 
                <div class="col-md-2 col-xs-4">
              
              <input name="inscrever" type="button" class="textoNormal form-control btn btn-primary" id="inscrever" onClick="inscreverManual();" value="Inscrever"/>
              
              <input name="hdAcao" type="hidden" id="hdAcao" value="<?php if (isset($_POST["hdAcao"])) echo ($_POST["hdAcao"]); ?>"/>
              <input name="codEvento" type="hidden" id="codEvento" />
                      
                </div>              
          </div>
      </div>
  
                                     
         
 </fieldset>
        </form>
      </div>

    <div id="footer" style="margin-bottom:0px;">
<br />
    <div align="center">
	  <div align="center"><span class="style22"><strong>ESCOLA DA MAGISTRATURA DO ESTADO   DO RIO DE JANEIRO - EMERJ<br />
	    Rua Dom Manuel, n&ordm; 25 - Centro - Telefone:   3133-1880<br />
      </strong></span></div>
  </div>
    </div> 
    
    </body>
</html>