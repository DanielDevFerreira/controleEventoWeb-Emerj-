<?php 
    require_once('../conexao.php');
   
    //require_once('../recurso/enviaMail.php');
    session_start();


    if (isset($_SESSION['evendodia']) AND $_SESSION['evendodia'] != ''){
        $titulo = explode(".", $_SESSION['evendodia']);
        $tipoPorta = $titulo[2];
    }

    date_default_timezone_set('America/Bahia');//NA PRODUÇÃO RETIRAR ESTA LINHA, PROBLEMA DE HORÁRIO DE VERÃO

    //session_start(); 

    //header("Content-Type: text/html; charset=utf-8", true);

    $horaAtual = date('H:i:s');//PEGA A HORA ATUAL

    $acao = NULL;

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

 if(isset($_POST['codInscricao'])) {
       $codInscricao = $_POST['codInscricao'];
    }


if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "inscreverManual")) {
    
        mysqli_select_db($conn, "emerjco_eventos");

        $codEvento = substr($_POST["codInscricao"], 2,-8); 
        $codParticipante = substr($_POST["codInscricao"], 7, 14);


        if (strlen($codInscricao ) < 12){
            echo ("<script>alert('Nº de inscrição inválido, verifique se contém 14 digitos!');
             window.location.href='registroPresenca2.php';  
         </script>");


        }
    
        else if($codEvento != $titulo[0]){
          echo ("<script>alert('O código de evento não pertence ao evento selecionado!');
              window.location.href='registroPresenca2.php';  
         </script>");
        }
    
        else {

            //SELECT PARA PESQUISAR REGISTRO DE EXISTENTE PARA AQUELE PARTICIPANTE NAQUELE EVENTO
            $query_registro = "SELECT idRegistro, codParticipante, codEvento, hora FROM registroponto 
            WHERE codParticipante = $codParticipante AND codEvento = $codEvento ORDER BY idRegistro DESC LIMIT 1";
            $buscaRegistro = mysqli_query($conn, $query_registro) or die(mysqli_error($conn));
            $row_buscaRegistro = mysqli_fetch_array($buscaRegistro);
            $totalRows_buscaRegistro = mysqli_num_rows($buscaRegistro);


            //SELECT PARA PESQUISAR INSCRIÇÃO
            $query_rsBusca = "SELECT p.nome as nomeParticipante, e.nome as nomeEvento, e.local as localEvento, po.data FROM evento e, participante p, inscricoes i, porta po 
            WHERE (po.data=CURRENT_DATE) AND (i.codEvento = $codEvento) AND (i.codEvento = po.codEvento) AND (i.codParticipante = $codParticipante) AND (i.codParticipante = p.codigo) AND (i.codEvento = e.codigo)";
            $rsBusca = mysqli_query($conn, $query_rsBusca) or die(mysqli_error($conn));
            $row_rsBusca = mysqli_fetch_array($rsBusca);
            $totalRows_rsBusca = mysqli_num_rows($rsBusca);
        }
        /*CODINSCICAO = 12 DÍGITOS
        $codEvento = substr($_POST["codInscricao"], 2,-6); 
        $codParticipante = substr($_POST["codInscricao"], 6, 12); */

        //CODINSCICAO = 14 DÍGITOS
        //$codEvento = substr($_POST["codInscricao"], 2,-8); 
        //$codParticipante = substr($_POST["codInscricao"], 7, 14);   

            //echo $_SESSION["codInscricao"];        

          if ($totalRows_rsBusca > 0) {

          if($totalRows_buscaRegistro > 0) {
              //VARIÁVEIS PARA CONVERSÃO E COMPARATIVO DE HORAS
              $hora = strtotime($row_buscaRegistro[3]);//pega a última hora do banco e converte em seg
              $horaBanco = date("H:i:s", $hora);//converte em formato h:m:s
              $horaMais = strtotime("+1 minutes", $hora);//acrescenta 1 minutos ao registro do banco
              $exibeHoraMais = date("H:i:s", $horaMais);//converte em formato h:m:s
              $comparaHoraAtual = strtotime($horaAtual);//pega a hora atual converte em seg

              if ($comparaHoraAtual < $horaMais) {
                  mysqli_rollback($conn);
                  $acao = "timeout";
              }
              else
              {
                  $insertSQL = sprintf("INSERT INTO registroponto (codEvento, codParticipante, data, hora, tipoPorta, tipoPresenca) VALUES ('".$codEvento."', '".$codParticipante."', current_timestamp(), current_timestamp(), '".$tipoPorta."', 1);",
                      GetSQLValueString($_POST["codInscricao"], "text"));
                  mysqli_select_db($conn, "emerjco_eventos");
                  mysqli_autocommit($conn, TRUE);
                  $Result1 = mysqli_query($conn, $insertSQL) or die(mysqli_error($conn));
                  //mysqli_commit($conn);
                  $acao = "inserido";

              }
          }
          else
          {
            $insertSQL = sprintf("INSERT INTO registroponto (codEvento, codParticipante, data, hora, tipoPorta, tipoPresenca) VALUES ('".$codEvento."', '".$codParticipante."', current_timestamp(), current_timestamp(), '".$tipoPorta."', 1);",
            GetSQLValueString($_POST["codInscricao"], "text"));
            mysqli_select_db($conn, "emerjco_eventos");	
            mysqli_autocommit($conn, TRUE);	
            $Result1 = mysqli_query($conn, $insertSQL) or die(mysqli_error($conn));
            //mysqli_commit($conn);
            $acao = "inserido";
                 
          }
        }
        
        else
            
        {          
            mysqli_rollback($conn);
            $acao = "naoinserido";
                  
          mysqli_autocommit($conn, TRUE);
        }
      }
?>


    <script language="javascript">


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
        var verifica = document.getElementById('txtMascaraCpf');
         if(verifica.value == ""){
             alert('Preenchar o campo CPF');
             return false;
        }  else {
         document.form1.hdAcao.value = "buscar";
         document.form1.submit();
        }
    }

    function inscreverManual() {
        if (document.form1.codInscricao.value != "") {
            document.form1.hdAcao.value = "inscreverManual";
            document.form1.submit();
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
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="X-UA-Compatible" content="IE=11">
        <script language="JavaScript" src="../js/validador.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        
        <script>
        
           setTimeout(function() { document.getElementById('codInscricao').focus(); }, 10);
        
        
        
        </script>
        
        <link rel="stylesheet" href="../css/alterar_evento.css">
        <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Baloo&display=swap" rel="stylesheet">
        
        
    <style>
        
        /* OCULTA AS SETAS NO INPUT DE NUMBER */

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }
        
        /* Firefox */
        input[type=number] {
        -moz-appearance:textfield;
        }
        
        /* OCULTA OS ELEMENTOS REPETIDOS EM REGISTRO PRESENCA E ALINHA */
        #iframe, #title, #eventodia, #legenda{
            display:none;
        }
        
        #codInscricao, #labelCod, #txtMascaraCpf, #valor, #valor2{
          margin-left:-20px;
        }
        
        h2{
            font-family: 'Baloo', cursive;
        }
        
        #buscar {
            display: none;
        }

        @media only screen and (min-width: 320px) and (max-width: 900px){
            
         #codInscricao, #labelCod, #txtMascaraCpf, #valor, #valor2, #buscar, #inscrever{
          margin-left: 90px;
        }
            
            #sub{
                 margin-left: 70px;
                 font-size: 1.6em;
            }
                
        }         

      </style> 
    </head>
    
    <body>
        
     <div class="container">    
        <form id="form1" name="form1" style="padding: 2%;" action="<?php echo $editFormAction; ?>" method="post">
           
                <fieldset>
                    
            <!-- SubTitulo da página -->        
             <center><h2 id="sub"><?php if (!isset($titulo[1])){echo "NENHUM EVENTO SELECIONADO";}else{echo $titulo[1];} ?></h2></center> 
     
        <br>            
                    
    <div class="row">
      <div class="form-group">
        <div class="col-md-5 col-xs-6">
            <br>
            <input onclick="PegarValor2()" type="radio" name="valor" id="valor" checked>
            <label style="font-size: 17px;" for="valor">Nº da Inscrição:</label></input>
            <input class='form-control' placeholder='14 Digitos' name="codInscricao" id="codInscricao" maxlength="14" type="text">
        </div>
        
        <div class="col-md-2 col-xs-3">
              <input style="margin-top:45px;" name="inscrever" type="submit" class="textoNormal form-control btn btn-primary" id="inscrever" onClick="inscreverManual();" value="Registra"/>
              <input name="hdAcao" type="hidden" id="hdAcao" value="<?php if (isset($_SESSION["codInscricao"])) echo('alterar'); else  echo('inserir') ?>"/>
              <input name="codEvento" type="hidden" id="codEvento" />              
        </div> 
       </div>
     </div>
                    
    <br><br><br>
                    
                    
    <div class="row">
      <div class="form-group">
          <div class="col-md-5 col-xs-6">
               <input  onclick="PegarValor2()" type="radio" name="valor" id="valor2"><label style="font-size: 17px;" for="valor2"> &nbsp CPF - (Busca Manual): </label></input>
          
              <input maxlength="14" onkeydown="javascript: fMasc( this, mCPF );" disabled type="text" class="form-control" type="text" id="txtMascaraCpf" name="txtMascaraCpf" required placeholder="CPF do participante" value="<?php if (isset($_POST['txtMascaraCpf'])) echo($_POST['txtMascaraCpf']); ?>">
          </div>
      
          
      <div class="col-md-2 col-xs-3">
              <input style="margin-top: 25px;" class="form-control btn btn-primary" type="button" id="buscar" name="buscar" onClick="buscarEvento();" value="Registrar(CPF)">        
          </div>
     </div>
</div>
                    
                    
  <?php
         
         $codParticipante2 = NULL;
         $codEvento2 = NULL;
                    
    if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "buscar")){

        if(isset($_POST['txtMascaraCpf'])) {
            $cpf = $_POST['txtMascaraCpf'];


            //SELECT PARA ACHAR CODIGO INSCRIÇÃO, ATRAVÉS DO CPF

            mysqli_select_db($conn, "emerjco_eventos");

            $queryEvento = 'SELECT p.nome as nomeParticipante, e.nome as nomeEvento, e.local as localEvento, po.data, p.codigo as codigoParticipante, e.codigo as codigoEvento FROM participante p, evento e, inscricoes i, porta po 
    WHERE ((i.codParticipante = p.codigo) AND (i.codEvento = e.codigo) 
    AND (i.codEvento = "' . $titulo[0] . '") 
    AND (p.cpf ="' . str_replace("-", "", (str_replace(".", "", $_POST["txtMascaraCpf"]))) . '")
    AND (po.codEvento = e.codigo) AND (po.data = CURDATE()));';


            $rsBusca = mysqli_query($conn, $queryEvento) or die(mysqli_error($conn));
            $row_rsBusca = mysqli_fetch_assoc($rsBusca);
            $totalRows_rsBusca = mysqli_num_rows($rsBusca);


            $codParticipante2 = $row_rsBusca ['codigoParticipante'];
            $codEvento2 = $row_rsBusca ['codigoEvento'];


            //SELECT PARA PESQUISAR REGISTRO PRÉ EXISTENTE PARA AQUELE PARTICIPANTE NAQUELE EVENTO
            $query_registro = 'SELECT idRegistro, codParticipante, codEvento, hora FROM registroponto 
            WHERE codParticipante ="' . $codParticipante2 . '" AND codEvento ="' . $codEvento2 . '" ORDER BY idRegistro DESC LIMIT 1';

            $buscaRegistro = mysqli_query($conn, $query_registro) or die(mysqli_error($conn));
            $row_buscaRegistro = mysqli_fetch_array($buscaRegistro);
            $totalRows_buscaRegistro = mysqli_num_rows($buscaRegistro);

            //SE NÃO EXISTE REGISTRO DAQUELE PARTICIPANTE AINDA NO EVENTO, INSERE DIRETO, SENÃO, VERIFICA HORA DO ÚLTIMO REGISTRO
            if ($totalRows_buscaRegistro == 0) {
                $insertSQL = sprintf("INSERT INTO registroponto (codEvento, codParticipante, data, hora) VALUES ('" . $codEvento2 . "', '" . $codParticipante2 . "', current_timestamp(), current_timestamp());");
                mysqli_select_db($conn, "emerjco_eventos");
                mysqli_autocommit($conn, TRUE);
                $Result2 = mysqli_query($conn, $insertSQL) or die(mysqli_error($conn));
                mysqli_commit($conn);
                $acao = "inserido";
            } else {
                //VARIÁVEIS PARA CONVERSÃO E COMPARATIVO DE HORAS
                $hora = strtotime($row_buscaRegistro[3]);//pega a última hora do banco e converte em seg
                $horaBanco = date("H:i:s", $hora);//converte em formato h:m:s
                $horaMais = strtotime("+1 minutes", $hora);//acrescenta 1 minutos ao registro do banco
                $exibeHoraMais = date("H:i:s", $horaMais);//converte em formato h:m:s
                $comparaHoraAtual = strtotime($horaAtual);//pega a hora atual converte em seg


                if ($totalRows_rsBusca > 0) {
                    if ($comparaHoraAtual < $horaMais) {
                        mysqli_rollback($conn);
                        $acao = "timeout";
                    } else {


                        $insertSQL = sprintf("INSERT INTO registroponto (codEvento, codParticipante, data, hora) VALUES ('" . $codEvento2 . "', '" . $codParticipante2 . "', current_timestamp(), current_timestamp());");

                        mysqli_select_db($conn, "emerjco_eventos");
                        mysqli_autocommit($conn, TRUE);
                        $Result2 = mysqli_query($conn, $insertSQL) or die(mysqli_error($conn));
                        mysqli_commit($conn);
                        $acao = "inserido";

                    }
                } else {
                    mysqli_rollback($conn);
                    $acao = "naoinserido";
                    mysqli_autocommit($conn, TRUE);

                }
            }
        }

    }

        
    ?>
                           
                         
 </fieldset>
        </form>
      </div>
      
    <?php  
    if ($acao == "inserido") {            
          echo("<br>");    
          echo("<br>");    
          echo("<div style='font-size:20px;' class='alert alert-success col-md-6'>Presença registrada com sucesso!</div>");
          
          echo("<br>");
          echo("<br>");
          echo("<table class='table table-striped' style='border:1px solid black;'>
          <caption style='font-size:25px; margin-bottom:10px;'><strong>DADOS DE CONFIRMAÇÃO DA INSCRIÇÃO:</strong></caption>           
          <tr>          
            <th style='background-color:#337ab7; color:white;'>Participante:</th>
            <td>" . $row_rsBusca["nomeParticipante"] . "</td>
          </tr>
          <tr>          
            <th style='background-color:#337ab7; color:white; '>Evento:</th>
            <td>" . $row_rsBusca["nomeEvento"] . "</td>
          </tr>
          <tr>          
            <th style='background-color:#337ab7; color:white; '>Local:</th>
            <td>" . $row_rsBusca["localEvento"] . "</td>
          </tr>                             
            <th style='background-color:#337ab7; color:white; '>Data do registro:</th>
            <td>" . strftime( '%d/%m/%Y ', strtotime('today') ) ." - " . $horaAtual. "</td>
          </tr>                      
        </table>
            ");
            
            
           // echo  "<script> setTimeout(function(){location.href='registroPresenca.php'} , 4000); </script>";
           
    }
           
        

    else if ($acao == "naoinserido") {
            echo("<br>");            
            echo("<div style='font-size:20px;' class='alert alert-danger col-md-6'>Número de inscrição ou CPF não encontrado para o evento selecionado. 
            Verifique se o participante está inscrito.</div>");
            echo("<br>");
            echo("<br>");
            echo("<br> <hr>");        
    }

    else if ($acao == "timeout") {
        
          echo("<br>");    
          echo("<br>");    
          echo("<div style='font-size:20px;' class='alert alert-warning col-md-6'>Menos de 1 minuto do último registro!</div>");
          
          echo("<br>");
          echo("<br>");
          echo("<table class='table table-striped' style='border:1px solid black;'>
          <caption style='font-size:25px; margin-bottom:10px;'><strong>DADOS DA INSCRIÇÃO:</strong></caption>           
          <tr>          
            <th style='background-color:#8B0000; color:white; '>Participante:</th>
            <td>" . $row_rsBusca["nomeParticipante"] . "</td>
          </tr>
          <tr>          
            <th style='background-color:#8B0000; color:white; '>Evento:</th>
            <td>" . $row_rsBusca["nomeEvento"] . "</td>
          </tr>
          <tr>          
            <th style='background-color:#8B0000; color:white; '>Local:</th>
            <td>" . $row_rsBusca["localEvento"] . "</td>
          </tr>                    
          <tr>          
            <th style='background-color:#8B0000; color:white; '>Último Registro às:</th>
            <td>" . $horaBanco. "</td>
          </tr>
          
        </table>
            ");
            
            
            echo("</div>");           

} 
    ?>
    <script>
           
        //FUNÇÃO PARA SELECIONA INPUT TEXT DE ACORDO COM INPUT RADIO ESCOLHIDO
    function PegarValor2() {
        if(document.getElementById('valor').checked == true){
            document.getElementById('codInscricao').select();
            document.getElementById('codInscricao').disabled = false;
            document.getElementById('txtMascaraCpf').disabled = true;
            document.getElementById('buscar').style.display = "none";
            
        } 
        else if(document.getElementById('valor2').checked == true){
             document.getElementById('txtMascaraCpf').select();
            document.getElementById('txtMascaraCpf').disabled = false;
           document.getElementById('codInscricao').disabled = true;
            document.getElementById('buscar').style.display = "block";
        }
    }
             
        </script>
    
    </body>
</html>