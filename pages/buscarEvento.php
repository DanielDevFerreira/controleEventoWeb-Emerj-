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

    $acao = NULL;

    $editFormAction = $_SERVER["PHP_SELF"];
    if (isset($_SERVER["QUERY_STRING"])) {
      $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);
    }

    // busca evento
    if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "buscar")) {

        mysqli_select_db($conn, "emerjco_eventos");
        $query_rsBusca = "SELECT e.nome, po.codEvento FROM evento e, porta po WHERE e.nome LIKE '%" . STRTOUPPER($_POST["txtBuscaNome"]) . "%' AND e.codigo=po.codEvento GROUP BY po.codEvento";
        $rsBusca = mysqli_query($conn, $query_rsBusca) or die(mysqli_error($conn));
        $row_rsBusca = mysqli_fetch_assoc($rsBusca);
        $totalRows_rsBusca = mysqli_num_rows($rsBusca);
    }

    //alterar evento
    if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "alterar")) {
            $_SESSION["codEvento"] = $_POST["codEvento"];
            $insertGoTo = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"])
                                    . "/alterar_evento.php";
            header(sprintf("Location: %s", $insertGoTo));
    }

    //excluir evento
    if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "excluir")) {


        $deleteSQL = sprintf("DELETE FROM porta, evento USING porta, evento WHERE porta.codEvento = " . $_POST["codEvento"] . " AND evento.codigo = " . $_POST["codEvento"]);
        mysqli_select_db($conn, "emerjco_evento");
        $Result1 = mysqli_query($conn, $deleteSQL) or die(mysqli_error($conn));
        $acao = "excluido";
    }

    // estatistica do evento
    if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "estatistica")) {
            $_SESSION["codEvento"] = $_POST["codEvento"];
            $insertGoTo = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"])
                                    . "/estatisticaEvento.php";
            header(sprintf("Location: %s", $insertGoTo));
    }
    ?>
    <script language="javascript">
    <!--
    function buscarEvento() {
        document.form1.hdAcao.value = "buscar";
        document.form1.submit();
    }

    function alterarEvento() {
        if (document.form1.selBusca) {
            if (document.form1.selBusca.value != "") {
                document.form1.hdAcao.value = "alterar";
                document.form1.codEvento.value = document.form1.selBusca.value;
                document.form1.submit();
            } else {
                alert("Você deve selecionar um evento.");
            }
        } else {
            alert("Você deve buscar e selecionar um evento.");	
        }
    }

    function excluirEvento() {
         if (document.form1.selBusca) {
            if (document.form1.selBusca.value != "") {
                if(confirm('Deseja realmente exluir esse evento ?')){
                    document.form1.hdAcao.value = "excluir";
                    document.form1.codEvento.value = document.form1.selBusca.value;
                    document.form1.submit();
                }
            } else {
                alert("Você deve selecionar um evento.");
            }
        } else {
            alert("Você deve buscar e selecionar um evento.");	
        }
    }    


    function estatisticaEvento() {
        if (document.form1.selBusca) {
            if (document.form1.selBusca.value != "") {
                document.form1.hdAcao.value = "estatistica";
                document.form1.codEvento.value = document.form1.selBusca.value;
                document.form1.submit();
            } else {
                alert("Você deve selecionar um evento.");
            }
        } else {
            alert("Você deve buscar e selecionar um evento.");	
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

            <div id="title"><center><spam>ALTERAR EVENTOS</spam></center></div>


                        <?php if ($acao == NULL) { ?>

            <form id="form1" name="form1" style="padding: 2%;" action="<?php echo $editFormAction; ?>" method="post">
                <div class="container">
                    <fieldset>
                        <legend>Informe os dados abaixo:</legend>

            <br>   
            

      <div class="row">
          <div class="form-group">
              <div class="col-md-6 col-xs-10">
                  <label for="nome-evento">Nome do evento:</label>
                  <input type="text" class="form-control" type="text" id="txtBuscaNome" name="txtBuscaNome" required placeholder="nome do evento">       
              </div>


          <div class="col-md-2 col-xs-4">
                  <input style="margin-top: 25px;" class="form-control btn btn-primary" type="button" id="buscar" name="buscar" onClick="buscarEvento();" value="Buscar" required>        
              </div>
         </div>
    </div>
            
                    
    
<?php
		if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "buscar"))
		if ($totalRows_rsBusca > 0) {
			echo ("
            
        <div class='row'>
          <div class='form-group'>
              <div class='col-md-9'>    
                  <br><br><select class= 'form-group col-md-11 col-xs-12' name='selBusca' size='5'>");
			do {  
		        echo ("<option value='" . $row_rsBusca["codEvento"] . "'>" . $row_rsBusca["nome"] . "</option>");
			}
            while ($row_rsBusca = mysqli_fetch_assoc($rsBusca));
    	    $rows = mysqli_num_rows($rsBusca);
        	   if($rows > 0) {
	  	        mysqli_data_seek($rsBusca, 0);
		          $row_rsBusca = mysqli_fetch_assoc($rsBusca);
	               echo("</select></div></div></div>
       ");
                   
			  mysqli_free_result($rsBusca);
    } else {
			echo("N&atilde;o houve registro encontrado!");
	
            }
		}
    ?>  
                         
        <br><br>
                    
                    
        <div class="row">
          <div class="form-group">                 
              <div class="col-md-1 col-xs-4">
                  <input class="form-control btn btn-primary" type="button" id="alterar" name="alterar" onClick="alterarEvento();" value="Alterar">
             </div>
          
          <div class="col-md-2">
              
              
                      
          </div>
          
          <div class="col-md-1 col-xs-4">
              <input class="form-control btn btn-primary" type="button" id="excluir" name="excluir" onClick="excluirEvento();" value="Excluir">          
          </div> 
          
          <div class="col-md-2">
              
           
                      
          </div>
          
          <div class="col-md-2 col-xs-4">
              <input class="form-control btn btn-primary" type="button" id="incricao" name="inscricao" onClick="estatisticaEvento();" value="Ver Inscrição">          
          </div>
      </div>
  </div> 
                 
    <div class="row">
       <div class="form-group">                 
          <div class="col-md-1">                
             <input name="hdAcao" type="hidden" id="hdAcao" value="<?php if (isset($_POST["hdAcao"])) echo ($_POST["hdAcao"]); ?>"/>
             <input name="codEvento" type="hidden" id="codEvento" />
        </div>
      </div>
  </div>
                    
         
 </fieldset>
        </form>
        
<?php }
        if ($acao == "excluido") {
	       echo("<p><script> alert('Evento exluído com sucesso!'); </script></p><hr>");
	       echo("<script> window.location.href='buscarEvento.php' </script>");
        }
        else if ($acao == "estatistica") {
	       echo("<script> window.location.href='es.php' </script>");
        }
?>  
          <br><br><br>
    <div id="footer" style="margin-bottom:0px;">
<br />
    <div align="center">
	  <div align="center"><span class="style22"><strong>ESCOLA DA MAGISTRATURA DO ESTADO   DO RIO DE JANEIRO - EMERJ<br />
	    Rua Dom Manuel, n&ordm; 25 - Centro - Telefone:   3133-2682<br />
      </strong></span></div>
  </div>
</div>    
        
    </body>
</html>