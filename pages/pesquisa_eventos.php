<?php

    $totalRows_rsBusca = "";

    date_default_timezone_set('America/Sao_Paulo');

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

        header("Content-Type: text/html; charset=utf8", true);

    $editFormAction = $_SERVER["PHP_SELF"];

    if (isset($_SERVER["QUERY_STRING"])) {
        
      $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);
    }
?>

    <!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../css/pesquisa_evento.css">
        <link href="../css/smartphone.css" rel="stylesheet" media="screen and (min-width:150px) and (max-width:896px)">
        <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="../js/jquery.inputmask.bundle.js" ></script>
        
        
        <style>
        

        </style>
        
    <script language='JavaScript'>
        function SomenteNumero(e){
            var tecla=(window.event)?event.keyCode:e.which;   
                if((tecla > 47 && tecla < 58)) return true;
                    else{
            if (tecla==8 || tecla==0) return true;
                   else  return false;
        }
    }
    
    </script>
        
    </head>
    
    <body>
    
        <div id="title"><center><spam>PESQUISA DE EVENTOS</spam></center></div>
        
  <form id="cadastrar_eventos" action="<?php echo $editFormAction; ?>" method="post">
      <div class="container">
          <fieldset>
                <legend>Informe os dados abaixo:</legend>
  
    <div class="row">
          <div class="form-group">
              <div class="col-md-4 col-xs-6">
                  <label for="codevento">Código:</label>
                  <input class="form-control" type="text" id="codigoEvento" name="codigoEvento" placeholder="Código do evento" onkeypress='return SomenteNumero(event)'>
              </div>



              <div class="col-md-4 col-xs-6">
                  <label for="dateevento">Data do Evento:</label>
                  <input class="form-control textoNormal" type="text" id="data" name="data" placeholder="Data do Evento" maxlength="8" onkeypress='return SomenteNumero(event)'>
              </div>
          </div>
      </div>  

        <br>

    <div class="row">
          <div class="form-group ">
              <div class="col-md-3 col-xs-6">
                  <label for="start">Período De:</label>
                  <input class="form-control" type="text" id="dataInicial" name="dataInicial" maxlength="8" onkeypress='return SomenteNumero(event)'>
              </div>



          <div class="col-md-3 col-xs-6">
              <label for="end">Até:</label>
              <input class="form-control col-lg-6" type="text" id="dataFinal" name="dataFinal" maxlength="8" onkeypress='return SomenteNumero(event)'>
          </div>
      </div>
    </div>
                
        <br>            
                    
  <div class="row">
      <div class="form-group">
          <div class="col-md-6 col-xs-8">
              <label for="nome-evento">Nome do evento:</label>
              <input type="text" class="form-control" type="text" id="tituloEvento" name="tituloEvento">       
          </div>
      
          
      <div class="col-md-2 col-xs-4">
              <input style="margin-top: 25px;" class="form-control btn btn-primary" type="reset" id="limpar" name="reset" value="Limpar Pesquisas">        
          </div>
      </div>
    </div>
      
                
      <br><br><br>  
    
               
    <div class="row">
      <div class="form-group">
          <div class="col-md-12">
              
<?php  
              
    /* PESQUISA POR CÓDIGO */
    if (empty($_POST["codigoEvento"])) {
            echo " ";
    }
    else{

        $query = strtolower(trim(stripslashes("select distinct p.data, e.codigo, e.nome, e.local, e.tipoEvento1, e.tipoEvento2, e.tipoEvento3, e.foradasede from porta p, evento e where e.codigo = " . $_POST["codigoEvento"] . " AND p.codevento= " . $_POST["codigoEvento"])));
        echo("<p style='font-size:15px;'><strong>Código pesquisado: " . $_POST["codigoEvento"] . "</strong></p>");
        echo "<br>";
        if (substr($query, 0, 1) == "s") { 
            $rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $row_rsBusca = mysqli_fetch_row($rsBusca);
            $totalRows_rsBusca = mysqli_num_rows($rsBusca);
            $totalFields_rsBusca = mysqli_num_fields($rsBusca);
        } else {
            switch(substr($query, 0, 1)) {
                case "i":
                    echo("<p>Inserção");
                    break;
                case "u":
                    echo("<p>Atualização");
                    break;
                case "d":
                    echo("<p>Exclusão");
                    break;
            }
            $result = mysqli_query($conn, $query);
            if ($result) {
                echo(" realizada com sucesso!<br><b>" . mysqli_affected_rows() . " linhas afetadas.</b></p>");	
            } else {
                echo(" não realizada - ERRO!</p>");
                } 
        }

    } 

    /* PESQUISA POR DATA */
    if (empty($_POST["data"])) {
            echo " ";
    } else {
        /* Inverte a data no formato do banco YYYYMMDD */
        $dataInvertida = $_POST["data"];
        $retornaAnoInvertida = substr($dataInvertida, -4);
        $retornaMesInvertida = substr($dataInvertida, 2, -4);
        $retornaDiaInvertida = substr($dataInvertida, 0, 2);
        $retornaDataInvertida = $retornaAnoInvertida . $retornaMesInvertida . $retornaDiaInvertida;

        $query = strtolower(trim(stripslashes("select distinct p.data, e.codigo, e.nome, e.local, e.tipoEvento1, e.tipoEvento2, e.tipoEvento3, e.foradasede from porta p, evento e where data = " . $retornaDataInvertida . " and p.codevento=e.codigo order by p.data desc")));

        echo "<br>";

        /* Coloca a data no formato do padrão DDMMAAAA */
        $dataFormatada = $_POST["data"];
        $retornaAnoFormatada = substr($dataFormatada, -4);
        $retornaMesFormatada = substr($dataFormatada, 2, -4);
        $retornaDiaFormatada = substr($dataFormatada, 0, 2);

        /* Exibe a data no formato do padrão DD/MM/AAAA */
        echo "<p style='font-size:15px;'><strong>" . "Data pesquisada: " . $retornaDiaFormatada . "/" . $retornaMesFormatada . "/" . $retornaAnoFormatada . "</strong></p>";
        echo "<br>";

        if (substr($query, 0, 1) == "s") { 
            $rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $row_rsBusca = mysqli_fetch_row($rsBusca);
            $totalRows_rsBusca = mysqli_num_rows($rsBusca);
            $totalFields_rsBusca = mysqli_num_fields($rsBusca);
        } else {
            switch(substr($query, 0, 1)) {
                case "i":
                    echo("<p>Inserção");
                    break;
                case "u":
                    echo("<p>Atualização");
                    break;
                case "d":
                    echo("<p>Exclusão");
                    break;
            }
            $result = mysqli_query($conn, $query);
            if ($result) {
                echo(" realizada com sucesso!<br><b>" . mysqli_affected_rows() . " linhas afetadas.</b></p>");	
            } else {
                echo(" não realizada - ERRO!</p>");
            }
        }
    }

    /* PESQUISA POR PERÍODO */
    if (empty($_POST["dataInicial"]) && empty($_POST["dataFinal"])) {
            echo " ";
    } else {
        /* Inverte a data no formato do banco YYYYMMDD */
        $dataInicialInvertida = $_POST["dataInicial"];
        $retornaAnoInicialInvertida = substr($dataInicialInvertida, -4);
        $retornaMesInicialInvertida = substr($dataInicialInvertida, 2, -4);
        $retornaDiaInicialInvertida = substr($dataInicialInvertida, 0, 2);
        $retornaDataInicialInvertida = $retornaAnoInicialInvertida . $retornaMesInicialInvertida . $retornaDiaInicialInvertida;

        $dataFinalInvertida = $_POST["dataFinal"];
        $retornaAnoFinalInvertida = substr($dataFinalInvertida, -4);
        $retornaMesFinalInvertida = substr($dataFinalInvertida, 2, -4);
        $retornaDiaFinalInvertida = substr($dataFinalInvertida, 0, 2);
        $retornaDataFinalInvertida = $retornaAnoFinalInvertida . $retornaMesFinalInvertida . $retornaDiaFinalInvertida;


        $query = strtolower(trim(stripslashes("select distinct p.data, e.codigo, e.nome, e.local, e.tipoEvento1, e.tipoEvento2, e.tipoEvento3, e.foradasede from porta p, evento e where data between " . $retornaDataInicialInvertida . " AND " . $retornaDataFinalInvertida . " and p.codevento=e.codigo order by p.data desc")));

        echo "<br>";

        /* Coloca a data no formato do padrão DDMMAAAA */
        $dataInicialFormatada = $_POST["dataInicial"];
        $retornaAnoInicialFormatada = substr($dataInicialFormatada, -4);
        $retornaMesInicialFormatada = substr($dataInicialFormatada, 2, -4);
        $retornaDiaInicialFormatada = substr($dataInicialFormatada, 0, 2);

        $dataFinalFormatada = $_POST["dataFinal"];
        $retornaAnoFinalFormatada = substr($dataFinalFormatada, -4);
        $retornaMesFinalFormatada = substr($dataFinalFormatada, 2, -4);
        $retornaDiaFinalFormatada = substr($dataFinalFormatada, 0, 2);

        /* Exibe a data no formato do padrão DD/MM/AAAA */
        echo "<p style='font-size:16px;'><strong>" . "Período pesquisado: " . $retornaDiaInicialFormatada . "/" . $retornaMesInicialFormatada . "/" . $retornaAnoInicialFormatada . " a " . $retornaDiaFinalFormatada . "/" . $retornaMesFinalFormatada . "/" . $retornaAnoFinalFormatada . "</strong></p>";
        echo "<br>";

        if (substr($query, 0, 1) == "s") { 
            $rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $row_rsBusca = mysqli_fetch_row($rsBusca);
            $totalRows_rsBusca = mysqli_num_rows($rsBusca);
            $totalFields_rsBusca = mysqli_num_fields($rsBusca);
        } else {
            switch(substr($query, 0, 1)) {
                case "i":
                    echo("<p>Inserção");
                    break;
                case "u":
                    echo("<p>Atualização");
                    break;
                case "d":
                    echo("<p>Exclusão");
                    break;
            }
            $result = mysqli_query($conn, $query);
            if ($result) {
                echo(" realizada com sucesso!<br><b>" . mysqli_affected_rows() . " linhas afetadas.</b></p>");	
            } else {
                echo(" não realizada - ERRO!</p>");
            }
        }
    }

    /* PESQUISA POR TÍTULO */		
    if (empty($_POST["tituloEvento"])) {
            echo " ";
    } else {


        $query = strtolower(trim(stripslashes("select distinct p.data, e.codigo, e.nome, e.local, e.tipoEvento1, e.tipoEvento2, e.tipoEvento3, e.foradasede from porta p, evento e where nome like " . "'%" . $_POST["tituloEvento"] . "%'" . " and p.codevento=e.codigo order by p.data desc")));
        echo("<p style='font-size:15px;'><strong>Título pesquisado: " . $_POST["tituloEvento"] . "</strong></p>");
        echo "<br>";
        if (substr($query, 0, 1) == "s") { 
            $rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $row_rsBusca = mysqli_fetch_row($rsBusca);
            $totalRows_rsBusca = mysqli_num_rows($rsBusca);
            $totalFields_rsBusca = mysqli_num_fields($rsBusca);
        } else {
            switch(substr($query, 0, 1)) {
                case "i":
                    echo("<p>Inserção");
                    break;
                case "u":
                    echo("<p>Atualização");
                    break;
                case "d":
                    echo("<p>Exclusão");
                    break;
            }
            $result = mysqli_query($conn, $query);
            if ($result) {
                echo(" realizada com sucesso!<br><b>" . mysqli_affected_rows() . " linhas afetadas.</b></p>");	
            } else {
                echo(" não realizada - ERRO!</p>");
            }
        }
    }


    /* PESQUISA POR TIPO DO EVENTO */		
    if (empty($_POST["tipoEvento"])) {
            echo " ";
    } else {

        $query = strtolower(trim(stripslashes("select distinct p.data, e.codigo, e.nome, e.local, e.tipoEvento1, e.tipoEvento2, e.tipoEvento3, e.foradasede from porta p, evento e where (tipoEvento1 like " . "'%" . $_POST["tipoEvento"] . "%' OR tipoEvento2 like " . "'%" . $_POST["tipoEvento"] . "%' OR tipoEvento3 like " . "'%" . $_POST["tipoEvento"] . "%')" . " and p.codevento=e.codigo order by p.data desc")));
        echo("<p style='font-size:15px;'><strong>Tipo do evento pesquisado: " . $_POST["tipoEvento"] . "</strong></p>");
        echo "<br>";
        if (substr($query, 0, 1) == "s") { 
            $rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $row_rsBusca = mysqli_fetch_row($rsBusca);
            $totalRows_rsBusca = mysqli_num_rows($rsBusca);
            $totalFields_rsBusca = mysqli_num_fields($rsBusca);
        } else {
            switch(substr($query, 0, 1)) {
                case "i":
                    echo("<p>Inserção");
                    break;
                case "u":
                    echo("<p>Atualização");
                    break;
                case "d":
                    echo("<p>Exclusão");
                    break;
            }
            $result = mysqli_query($conn, $query);
            if ($result) {
                echo(" realizada com sucesso!<br><b>" . mysqli_affected_rows() . " linhas afetadas.</b></p>");	
            } else {
                echo(" não realizada - ERRO!</p>");
            }
        }
    }


    /* PESQUISA POR EVENTO QUE OCORREU FORA DA SEDE */		
    if (empty($_POST["eventoforadasede"])) {
            echo " ";
    } else {

        $query = strtolower(trim(stripslashes("select distinct p.data, e.codigo, e.nome, e.local, e.tipoEvento1, e.tipoEvento2, e.tipoEvento3, e.foradasede from porta p, evento e where e.foradasede = " . $_POST["eventoforadasede"] . " order by p.data desc")));
        echo("<p style='font-size:15px;'><strong>Fora da Sede pesquisado: " . $_POST["eventoforadasede"] . "</strong></p>");
        echo "<br>";
        if (substr($query, 0, 1) == "s") { 
            $rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $row_rsBusca = mysqli_fetch_row($rsBusca);
            $totalRows_rsBusca = mysqli_num_rows($rsBusca);
            $totalFields_rsBusca = mysqli_num_fields($rsBusca);
        } else {
            switch(substr($query, 0, 1)) {
                case "i":
                    echo("<p>Inserção");
                    break;
                case "u":
                    echo("<p>Atualização");
                    break;
                case "d":
                    echo("<p>Exclusão");
                    break;
            }
            $result = mysqli_query($conn, $query);
            if ($result) {
                echo(" realizada com sucesso!<br><b>" . mysqli_affected_rows() . " linhas afetadas.</b></p>");	
            } else {
                echo(" não realizada - ERRO!</p>");
            }
        }
    }

    /* Gera a tabela de acordo com o input selecionado para a consulta */
    if ($totalRows_rsBusca == 0 && (isset($_POST["codigoEvento"]) || isset($_POST["data"]) || isset($_POST["dataInicial"]) || isset($_POST["dataFinal"]) || isset($_POST["tituloEvento"]) || isset($_POST["tipoEvento"]))){
        
        echo("<div align='center' style='font-size:15px;'>Não há resultado(s) de evento(s) para a pesquisa realizada.</div>\n");
        
    } else if (empty($_POST["codigoEvento"]) && empty($_POST["data"]) && empty($_POST["dataInicial"]) && empty($_POST["dataFinal"]) && empty($_POST["tituloEvento"]) && empty($_POST["tipoEvento"])){
            
        echo("<div align='center'></div>\n");
    }
    /**/
	
    if (isset($totalRows_rsBusca) && ($totalRows_rsBusca>0)) {
        echo("<table width='850px' border='1' align='center' class='table table-striped'>\n");
        // cabeçalho da tabela
        echo("\t<tr class='bg-primary'>\n");
        //for ($n=0; $n<$totalFields_rsBusca; $n++) {
        for ($n=0; $n<1; $n++) {
            //echo("\t\t<td><b>" . mysql_field_name($rsBusca, $n) . "</b></td>\n");
            echo("\t\t<td  class='bg-primary' align='center'>&nbsp;<b>" . "Data" . "</b>&nbsp;</td>\n");
            echo("\t\t<td  class='bg-primary' align='center'>&nbsp;<b>" . "Código" . "</b>&nbsp;</td>\n");
            echo("\t\t<td  class='bg-primary'>&nbsp;<b>" . "Título" . "</b></td>\n");
            echo("\t\t<td  class='bg-primary'>&nbsp;<b>" . "Local" . "</b></td>\n");
            echo("\t\t<td  class='bg-primary'>&nbsp;<b>" . "1º Tipo do Evento" . "</b>&nbsp;</td  class='bg-primary'>\n");
            echo("\t\t<td  class='bg-primary'>&nbsp;<b>" . "2º Tipo do Evento" . "</b>&nbsp;</td>\n");
            echo("\t\t<td  class='bg-primary'>&nbsp;<b>" . "3º Tipo do Evento" . "</b>&nbsp;</td  class='bg-primary'>\n");
            echo("\t\t<td  class='bg-primary'>&nbsp;<b>" . "Fora da Sede" . "</b>&nbsp;</td>\n");
        }
        echo("\t</tr>\n");

        for ($i=0; $i<$totalRows_rsBusca; $i++) {
            echo("\t<tr>\n");
            for ($n=0; $n<1; $n++) {
                //echo("\t\t<td align='center' style:'padding:15px'>" . $row_rsBusca[$n] . "&nbsp;</td>\n");

                $padraoFormatoData = date('d/m/Y', strtotime($row_rsBusca[0]));

                echo("\t\t<td align='center' style:'padding:15px'>" . $padraoFormatoData . "</td>\n");
                echo("\t\t<td align='center' style:'padding:15px'>" . $row_rsBusca[1] . "</td>\n");
                echo("\t\t<td style:'padding:15px'>" . $row_rsBusca[2] . "</td>\n");
                echo("\t\t<td style:'padding:15px'>" . $row_rsBusca[3] . "</td>\n");
                echo("\t\t<td style:'padding:15px'>" . $row_rsBusca[4] . "</td>\n");
                echo("\t\t<td style:'padding:15px'>" . $row_rsBusca[5] . "</td>\n");
                echo("\t\t<td style:'padding:15px'>" . $row_rsBusca[6] . "</td>\n");
                echo("\t\t<td align='center' style:'padding:15px'>");

                if ($row_rsBusca[7] == 0){
                    echo "Não";			
                } else if ($row_rsBusca[7] == 1){
                    echo "Sim";
                }			
                echo("</td>\n");
            }
            $row_rsBusca = mysqli_fetch_row($rsBusca);
            echo("\t</tr>\n");
        }

        // rodapé da tabela	
        echo("\t<tr>\n");
        echo("\t<td colspan='8'>&nbsp;\n");
        echo("\t</td>\n");	
        echo("\t</tr>\n");
        echo("\t<tr>\n");
        echo("\t\t<td colspan=" . $totalFields_rsBusca . "><b><span style='font-size:16px;'>Total de Eventos: " . $totalRows_rsBusca . "</span></b></td>\n");
        echo("\t</tr>\n");	

        echo("</table>");
        }
    ?>          
          </div> 
       </div>
    </div>
                    
          <br><br>
          <br><br> 
                    
     <div id="desktop">           
        <div class="row">
            <div class="form-group">
                <div class="col-md-12 col-xs-6 pull center">
                   <button style="margin-left:20px;" class="btn btn-primary col-md-2 col-xs-12">Pesquisar</button>
                </div>        
            </div>
        </div>
     </div>
                    
          <br>
                    
    <div id="mobile">           
        <div class="row">
           <div class="form-group">
             <div class="col-md-12">
                <button style="margin-left:20px;" class="btn btn-primary col-md-2 col-xs-12">Pesquisar por Código</button>
               </div>        
            </div>
        </div>
     </div>
       
                    
</div>            
                 
 </fieldset>
        </form>
    
        <script>
    
      $("input[id*='data']").inputmask({
            mask: ['99/99/9999'],
            keepStatic: true
            }); 
        
        </script>       
    </body>
</html>