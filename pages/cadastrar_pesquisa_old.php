    <?php 
    require_once('../conexao.php');

    session_start(); 

    header("Content-Type: text/html; charset=utf-8", true);

    $acao = NULL;
	
	$editFormAction = $_SERVER["PHP_SELF"];
    if (isset($_SERVER["QUERY_STRING"])) {
      $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);
    }

//if (isset($_POST["codEvento"]))

//$_SESSION["codEvento"] = $_POST["codEvento"]; estava dando erro de undefined index

if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "inserir")) {	
	
mysqli_select_db($conn, "emerjco_eventos");
	
$pergunta=$_POST["pergunta"];
$codEvento=$_POST["codEvento"];

        $insertSQL = sprintf("INSERT INTO pergunta (pergunta, codEvento) VALUES ('%s', %s)",
        			$pergunta,
					$codEvento);

$resultado = mysqli_query($conn, $insertSQL);

			if($resultado == 1){
            mysqli_commit($conn);
            $acao = "inserido";
        	}
        else 
        	{
            mysqli_rollback($conn);
            $acao = "naoinserido";
        	}
}

    ?>

<script language="javascript">
    function validaCampos()
    {
            var d=document.form1;

            var erros='';

            if (d.pergunta.value == ""){
                erros += "A pergunta deve ser preenchida!\n";		
            }
			
			if (d.codEvento.value == ""){
                erros += "O código do evento deve ser preenchido!\n";		
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

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

    <html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR">
        
    <script type="text/JavaScript">

    function MM_callJS(jsStr) { //v2.0
      return eval(jsStr)
    }

    </script>

    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        

    <title>.: Escola da Magistratura do Estado do Rio de Janeiro :.</title>
        
     <link rel="stylesheet" href="../css/cadastrar_eventos.css">
    <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../js/jquery.inputmask.bundle.js"></script>
        
    </head>

    <body>
        
    <div id="title">
        <center>
            <spam>CADASTRAR ENQUETE</spam>
        </center>
    </div>
      
    <div class="container">
        
            <br><br>
        
    <?php if ($acao == NULL) { ?>
        
    
<form action="<?php echo $editFormAction; ?>" method="post" name="form1">
    <legend>Informe o dado abaixo:</legend>
                
    <div class="row">
      <div class="form-group">
        <div class="col-md-4 col-xs-8"> 
            <label for="pergunta">Pergunta:</label>
            <textarea class="form-control" name="pergunta" id="pergunta" cols="45" rows="5"></textarea>
        </div>    
      </div>    
    </div>    
                
        <br><br>        
            
    <div class="row">
      <div class="form-group">
        <div class="col-md-2 col-xs-4">
            <label for="codEvento">Código do Evento:</label>
            <input class="form-control" type="text" id="codEvento" name="codEvento" placeholder="Código do evento"maxlength="4" value="<?php mysqli_select_db($conn, "emerjco_eventos"); 
                              
          $query_rsBusca = "SELECT MAX(codigo) FROM evento";

          $rsBusca = mysqli_query($conn, $query_rsBusca) or die(mysqli_error($conn));

          while ($vet=mysqli_fetch_row($rsBusca)) { 

            $vet[0]++;

            echo $vet[0];
        }

      ?>">
            
      
      <br /><br  />   
                
		  <input class='btn btn-primary' name="insert" type="button" class="textoNormal" onClick="MM_callJS('validaCampos();')" value="Enviar" />
       
          <input class="btn btn-primary" name="reset" type="reset" id="Limpar" value="Limpar" />
          
          <input type="hidden" name="MM_insert" value="form1">
          
          <input name="hdAcao" type="hidden" id="hdAcao" value="<?php if (isset($_SESSION["codEvento"])) echo('alterar'); else echo('inserir') ?>"/>
              
        </div>
      </div>
    </div>
    
      <br/> 
    
  </form>
    
    </div> 
        
        
    <div class="container">  
        <strong>
        
    <?php }
    if ($acao == "inserido") {        
          	echo("<br>");
            echo("<br>");
            echo("<br>");
            echo("<p>Pergunta cadastrada com sucesso.</p>");
            echo("<p>Por favor, tente novamente ou entre em contato com o DETEC.</p>");
            echo("<br>");
            echo("<br> <hr>");
            echo("<p><a href='../pages/cadastrar_pesquisa.php'>Voltar</em></a><p>");
           
    }

    if ($acao == "naoinserido") {
            echo("<br>");
            echo("<br>");
            echo("<br>");
            echo("<p>Não é possível cadastrar a pergunta.</p>");
            echo("<p>Por favor, tente novamente ou entre em contato com o DETEC.</p>");
            echo("<br>");
            echo("<br> <hr>");
            echo("<p><a href='../pages/cadastrar_pesquisa.php'>Voltar</em></a><p>");
    }
    ?>
            
         </strong>
    </div>
    </body>
    </html>
