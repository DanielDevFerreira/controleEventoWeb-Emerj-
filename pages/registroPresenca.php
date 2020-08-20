    <?php 
    require_once('../conexao.php');

    date_default_timezone_set('America/Bahia');//NA PRODUÇÃO RETIRAR ESTA LINHA, PROBLEMA DE HORÁRIO DE VERÃO

    session_start(); 

    header("Content-Type: text/html; charset=utf-8", true);

    $horaAtual = date('H:i:s');//PEGA A HORA ATUAL

    $acao = NULL;

     if(isset($_POST['eventodia']))
    {
        $_SESSION['evendodia'] = $_POST['eventodia'];
    }else{
         $_SESSION['evendodia'] = '';
     }



    $editFormAction = $_SERVER["PHP_SELF"];
    if (isset($_SERVER["QUERY_STRING"])) {
      $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);
    }


    if(isset($_POST['codInscricao'])){
        $codInscricao = $_POST['codInscricao'];
    }

    //  CONSULTA PARA RETORNA OS EVENTOS DO DIA 
    $query_rsBusca = "SELECT  e.nome as nomeEvento, po.codEvento as CodigoEvento, po.horaInicio as horaInicioEvento, po.tipo as tipoPorta FROM evento e, porta po 
    WHERE (po.data=CURRENT_DATE) AND e.codigo = po.codEvento";
    $rsBusca2 = mysqli_query($conn, $query_rsBusca) or die(mysqli_error($conn));
    $totalRows_buscaEventos = mysqli_num_rows($rsBusca2);

    ?>

    <script src="../js/jquery.js"></script>
    <script language="javascript">

    </script>

    <script language='JavaScript'>  

    </script>

    <script language="JavaScript" src="recursos/validador.js"></script>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

    <html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR">
        
    <script type="text/JavaScript">

    function MM_callJS(jsStr) { //v2.0
      return eval(jsStr)
    }
        

    </script>

    <head>
        
        <style>
        
        /*CSS IFRAME 2 DENTRO DO REGISTRO PRESENÇA*/
                        
        #iframe{
            float: right;
            margin-top: -50px;
            margin-right: 100px;
            height: 800px;
            width: 100%;
            border: none;
            clear: left;
         }
            
       body{
           padding-bottom: 10%;
        }
            
        </style>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf8">
   
     <link rel="stylesheet" href="../css/cadastrar_eventos.css">
    <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../js/jquery.inputmask.bundle.js"></script>
        
    </head>

    <body>
    <div id="ocultar">
        
    <div id="title">
        <center>
            <spam>REGISTRO DE PRESENÇA</spam>
        </center>
    </div>
        <br>
      
        <div align="center">
        <div align="right" style="width:900px;">
            
    <?php  
    
        echo "<strong><span style='font-family:Arial, Helvetica, sans-serif'>" . "Data: " . date("d/m/Y") . " às ". date('H:i:s') . "</span></strong>";
    ?>

        </div>
        </div>
        
    <div class="container">
        
      
            <br><br>
        
    <div class="row">
      <div class="form-group">
        <div class="col-md-5 col-xs-6">
            
            <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
            <div id="legenda">
                <legend>Informe o dado abaixo:</legend>
                
             <label for="eventodia">Selecione o evento do dia:</label>
            </div>
                
            <!-- SELECT QUE RETORNA OS EVENTOS DO DIA, DIRETO DO BANCO DE DADOS -->    
        <select class="form-control" id="eventodia" name="eventodia" onChange="this.form.submit()">
                 
                <option value="">Selecione um evento</option>
                
                <?php
                
                if($totalRows_buscaEventos >0){      
                                   
                    while($row_rsBusca2 = mysqli_fetch_assoc($rsBusca2)){    ?>
                
                
                <option value="<?php echo ($row_rsBusca2['CodigoEvento'] . "." . substr($row_rsBusca2['horaInicioEvento'],0,-3) . " - " . $row_rsBusca2["nomeEvento"]. "." .$row_rsBusca2['tipoPorta']); ?>">

                <?php 
                    echo ("<strong>" . substr($row_rsBusca2['horaInicioEvento'], 0 , -3) . "</strong> - " . $row_rsBusca2['nomeEvento'] . " ");
                } ?>
                </option> 
                <?php } else { ?>
                
                <option value="">SEM EVENTOS NA DATA DE HOJE</option>
                -
                <?php } ?>
        </select>
                
        <p style="font-size:20px; text-align:center; margin-top: 20px;" id="valorEvento"></p>
            
         <br> 
               
    </form>
        
    </div>
          
        <iframe name="iframe" id="iframe" src="registroPresenca2.php"></iframe>

    </div>
    </body>
        
        <script>
           
       </script>
    
    </html>


