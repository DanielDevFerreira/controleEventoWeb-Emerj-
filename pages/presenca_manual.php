<?php     require_once('../conexao.php');       //require_once('../recurso/enviaMail.php');    session_start();    if (isset($_SESSION['evendodia'])){        $titulo = explode(".", $_SESSION['evendodia']);    }    date_default_timezone_set('America/Bahia');//NA PRODUÇÃO RETIRAR ESTA LINHA, PROBLEMA DE HORÁRIO DE VERÃO    //session_start();     //header("Content-Type: text/html; charset=utf-8", true);    $horaAtual = date('H:i:s');//PEGA A HORA ATUAL    $acao = NULL;    if (!isset($_SESSION['usuarioNome']) or !isset($_SESSION['usuarioId']) or !isset ($_SESSION['usuarioNiveisAcessoId']) or !isset($_SESSION['usuarioEmail'])){	unset(		$_SESSION['usuarioId'],		$_SESSION['usuarioNome'],		$_SESSION['usuarioNiveisAcessoId'],		$_SESSION['usuarioEmail']			);        //redirecionar o usuario para a página de login	header("Location: ../../index.php");}$nivelLogado = $_SESSION['usuarioNiveisAcessoId'];header("Content-Type: text/html; charset=utf8", true);   function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")     {      $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;      switch ($theType) {        case "text":          $theValue = ($theValue != "") ? "UPPER('" . $theValue . "')" : "NULL";          break;            case "int":          $theValue = ($theValue != "") ? intval($theValue) : "NULL";          break;        }      return $theValue;    }$editFormAction = $_SERVER["PHP_SELF"];if (isset($_SERVER["QUERY_STRING"])) {  $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);   }?>    <script language="javascript">             function validaCampos()    {            var d = document.form1;            var cod = document.getElementById('codInscricao').value;                        if (cod == ""){               alert("O nº de inscrição deve ser preenchido!");		            }					            else if (cod != "" && cod.length < 12)            {			                alert('Nº de inscrição inválido, verifique se contém 14 digitos!');		            }            else if (cod != "" && cod.length >= 12)            {	                document.form1.hdAcao.value = "pesquisar";                enviaFormulario();            }			    }                function validaCampos2()    {            var codigoEvento = document.getElementById('codigoEvento').value;            var cpf = document.getElementById('txtMascaraCpf').value;            var data = document.getElementById('txtData').value;                        if (codigoEvento == "" && cpf  == "" && data == "" ){               alert("Informe todos os campos !");		            }					                        else            {	                document.form1.hdAcao.value = "pesquisar2";                enviaFormulario();            }			    }               function confirm() {                    var registroEntrada  = document.getElementById('registroEntrada').value;               var registroSaida    = document.getElementById('registroSaida').value;            var tipoPresenca     = document.form2.tiposelecionado.value         var valorselecionado = document.form2.valorselecionado.value                               if ((valorselecionado != "") && (registroEntrada != "") && (registroSaida != "") && (tipoPresenca != "")) {                document.form2.hdAcao.value = "inserir_hora";                document.form2.submit();            } else if(valorselecionado == ""){                alert("Você deve selecionar um evento.");                }else if( tipoPresenca == ""){                alert("Você deve selecionar um tipo Presença.");                      } else if((registroEntrada == "") || (registroSaida == "")){                alert("Informe os campos de Registro de Entrada/Saida");                  }            }                /*function confirm() {                   var entrada = document.getElementById('registroEntrada').value;        var saida   = document.getElementById('registroSaida').value;           //var hdAcao = document.getElementById('hdAcao');                    if (entrada != "" && saida !="") {               document.form1.hdAcao.value = "inserir_hora";                document.form1.submit();             alert(entrada);            } else {                alert("Informe os Registros.");            }             }*/              function buscarEvento() {        var verifica = document.getElementById('txtMascaraCpf');         if(verifica.value == ""){             alert('Preenchar o campo CPF');             return false;        }  else {         document.form1.hdAcao.value = "buscar";         document.form1.submit();        }    }                    // ENVIAR OS DADOS DO FORM              function enviaFormulario()       {        document.form1.submit();      }             function MM_callJS(jsStr) { //v2.0        return eval(jsStr)     }    // MASCARA PARA O CAMPO CPF     function fMasc(objeto,mascara) {        obj=objeto        masc=mascara        setTimeout("fMascEx()",1)                }     function fMascEx() {        obj.value=masc(obj.value)                }    function mCPF(cpf){        cpf=cpf.replace(/\D/g,"")        cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")        cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")        cpf=cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")        return cpf        }                function mascara_data(evento, txtData) {	if (verificaNumeros(evento)) {	    var data = '';	    data = data + txtData;	    if (data.length == 2) {	        data = data + '/';	        document.form1.txtData.value = data;	    }	    if (data.length == 5) {	        data = data + '/';	        document.form1.txtData.value = data;	    }	}}        //--></script>    <!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR">    <head>        <meta http-equiv="X-UA-Compatible" content="IE=edge">        <meta http-equiv="X-UA-Compatible" content="IE=11">        <script language="JavaScript" src="../js/validador.js"></script>        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>                <script>                   setTimeout(function() { document.getElementById('codInscricao').focus(); }, 10);                        </script>                <link rel="stylesheet" href="../css/alterar_evento.css">        <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">        <link href="https://fonts.googleapis.com/css?family=Baloo&display=swap" rel="stylesheet">                    <style>             /* DISPLAY NONE NOS ELEMENTOS DO CPF, CASO NÃO SEJA SELECIONADO */        #buscar, #txtMascaraCpf, #campos_cpf {            display: none;        }        #tamanho_font_radio{            font-size: 1.1em;        }    </style>            </head>        <body>            <div id="title">        <center>            <spam>PRESENÇA MANUAL DO PARTICIPANTE</spam>        </center>    </div>             <div class="container">            <form id="form1" name="form1" style="padding: 2%;" action="<?php echo $editFormAction; ?>" method="post">                           <fieldset>        <br>                                    <div class="row">      <div class="form-group">        <div class="col-md-5 col-xs-6">            <br>            <input onclick="PegarValor2()" type="radio" name="valor" id="valor" checked>            <label style="font-size: 17px;" for="valor">Nº da Inscrição:</label>            <input class='form-control' placeholder='14 Digitos' name="codInscricao" id="codInscricao" maxlength="14" type="text">        </div>                <div class="col-md-2 col-xs-3">              <input style="margin-top:45px;" name="inscrever" type="submit" class="textoNormal form-control btn btn-primary" id="inscrever" onClick="MM_callJS('validaCampos();')" value="Pesquisa"/>              <input name="hdAcao" type="hidden" id="hdAcao" value="<?php if (isset($_SESSION["codInscricao"])) echo('alterar'); else  echo('inserir') ?>"/>              <input name="codEvento" type="hidden" id="codEvento" />                      </div>        </div>     </div>                        <br><br><br>                                               <!-- PESQUISA PELO Nº DE CPF, CASO FOR SELECIONADO PELO USUÁRIO -->                       <legend>Pesquisa pelo Nº do CPF</legend>                            <div class="row">      <div class="form-group">          <div class="col-md-3 col-xs-6">               <input  onclick="PegarValor2()" type="radio" name="valor" id="valor2"><label style="font-size: 17px;" for="valor2"> &nbsp Nº do CPF: </label>                        <input maxlength="14" onkeydown="javascript: fMasc( this, mCPF );" disabled type="text" class="form-control" type="text" id="txtMascaraCpf" name="txtMascaraCpf" placeholder="Somente Número do CPF" value="<?php if (isset($_POST['txtMascaraCpf'])) echo($_POST['txtMascaraCpf']); ?>">                 </div>     </div></div>                            <br>              <!-- ELEMENTOS DO CPF, COM DISPLAY NONE POR PADRÃO -->           <div id="campos_cpf"><!-- DIV PARA DAR DISPLAY NONE NOS ELEMENTOS DO CPF -->            <div class="row">      <div class="form-group">        <div class="col-md-3 col-xs-6">             <label for="codInscricao">Código Evento:</label>            <input class='form-control' placeholder='Código Evento' name="codigoEvento" id="codigoEvento" type="text" class="textoNormal"/>             </div>                    <div class="col-md-3 col-xs-5">            <label for="codInscricao">Data:</label>            <input class='form-control textoNormal' placeholder='data do evento' name="txtData" id="txtData" type="text" onKeyPress="mascara_data(event, this.value);" maxlength="10"/>            </div>                <div class="col-md-2 col-xs-3">            <input style="margin-top: 25px;" class="form-control btn btn-primary" type="button" id="buscar" name="buscar" onClick="MM_callJS('validaCampos2();')" value="Pesquisar (CPF)">                </div>        </div>    </div>       </div>                        </fieldset>    </form> </div>                                                                    <div class="container">               <?php             if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "pesquisar")) {             mysqli_select_db($conn, "emerjco_eventos");             $numeroInscricao = $_POST["codInscricao"];             $tamanhoNumeroInscricao = strlen($numeroInscricao);             $codEvento = substr($numeroInscricao, 2, 4);             if ($tamanhoNumeroInscricao == 12) {                 /* se o código com 12 dígitos */                 $codParticipante = substr($numeroInscricao, -6, 7);             } else if ($tamanhoNumeroInscricao == 14) {                 /* se o código com 14 dígitos */                 $codParticipante = substr($numeroInscricao, -7, 8);             }             if (strlen($numeroInscricao) < 12) {                 echo("<script>alert('Nº de inscrição inválido, verifique se contém 14 digitos!');                       </script>");             } else {                 //SELECT PARA PESQUISAR INSCRIÇÃO                 $queryEvento = trim("SELECT e.codigo, e.nome, po.data, p.nome, p.codigo, po.horaInicio, po.horaFim, po.tipo FROM evento e, participante p, inscricoes i, porta po             WHERE  (i.codEvento = $codEvento) AND (i.codEvento = po.codEvento) AND (i.codParticipante = $codParticipante )             AND (i.codParticipante = p.codigo) AND (i.codEvento = e.codigo)");                 $result = mysqli_query($conn, $queryEvento) or die(mysqli_error($conn));                 $totalRegistrosInscricoes = mysqli_num_rows($result);                                  if ($totalRegistrosInscricoes == 0) {                            echo("<script>alert('Não há inscrição no evento.');</script>");                                } else if ($totalRegistrosInscricoes != 0) {                 echo("<br><br><p style='font-size:20px;'><strong>Dados do Evento e Participante</strong></p><br>");                 while ($rowResultado = mysqli_fetch_row($result)) {                     $tipoPorta = $rowResultado[7];                         $padraoFormatadoData = date('d/m/Y', strtotime($rowResultado[2]));                         //Tabela com resultado Evento e do Participante                         echo("<table border=1 class='table table-striped' style='border:1px solid black'>");                         echo("<tr style='background-color:#337AB7; color:white;'>            <th ></th>            <th >Código do evento</th>            <th>Evento</th>            <th>Data do evento</th>            <th>Hora Início</th>               <th>Hora Fim</th>              <th>Nome participante</th>            <th>Código participante</th>                          </tr>                      <tr>            <td><input name='portas' type='radio' id='portas' value='" . $rowResultado[7] . "." . $rowResultado[4] . "." . $rowResultado[2] . "." . $rowResultado[5] . "." . $rowResultado[6] . "." .$rowResultado[0]."' onclick='selecionaValor(this.value);' /></td>            <td>" . $rowResultado[0] . "</td>            <td>" . $rowResultado[1] . "</td>            <td>" . $padraoFormatadoData . "</td>            <td>" . $rowResultado[5] . "</td>            <td>" . $rowResultado[6] . "</td>            <td>" . $rowResultado[3] . "</td>            <td>" . $rowResultado[4] . "</td>                                  </tr>                    </table>                    <br><br>  ");                         $dataDoEvento = $rowResultado[2];                         $timestamp = strtotime($dataDoEvento . "-365 days");                         //echo "1 ano atrás: " . date('d/m/Y', $timestamp);                         // Calcula a data atual daqui 1 ano atrás                         $dataAtual = date("Y/m/d");                         $timestamp1Ano = strtotime($dataAtual . "-365 days");                         $umAnoAntes = date('Y-m-d', $timestamp1Ano);                         if ($dataDoEvento <= $umAnoAntes) {                             echo "<p align='center' style='font-size:15px; color:#900';'><strong>Evento ocorreu há mais de 1 ano atrás.</strong></p>";                         }                         $dataAtual = date("Y/m/d");                         if ($rowResultado[4] > $dataAtual) {                             echo "Evento ainda não ocorreu.";                         }                     }                 }             }                                }                        ?>            </div>        &nbsp;                 <div class="container">                      <?php                // BUSCA PELO CPF, SE NÃO SOUBER O CODIGO DE INSCRIÇÃO DO PARTICIPANTE                        if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "pesquisar2")){                 mysqli_select_db($conn, "emerjco_eventos");                        if(isset($_POST['txtMascaraCpf']) && isset($_POST['codigoEvento'])){                        $cpf = str_replace("-","",(str_replace(".","", $_POST['txtMascaraCpf'])));            $codigoEventoQuery =$_POST['codigoEvento'];        }               $dataInvertida = $_POST["txtData"];       $retornaAnoInvertida = substr($dataInvertida, -4);       $retornaMesInvertida = substr($dataInvertida, 2, -4);       $retornaDiaInvertida = substr($dataInvertida, 0, 2);       $retornaDataInvertida = $retornaAnoInvertida . $retornaMesInvertida . $retornaDiaInvertida;       str_replace("/","",$retornaDataInvertida);    //SELECT PARA ACHAR CODIGO INSCRIÇÃO, ATRAVÉS DO CPF             $queryEvento = 'SELECT e.codigo, e.nome, po.data, p.nome, p.codigo, po.horaInicio, po.horaFim, po.tipo FROM evento e, participante p, inscricoes i, porta po      WHERE ((i.codParticipante = p.codigo) AND (i.codEvento = e.codigo)      AND (i.codEvento = "'.$codigoEventoQuery.'")      AND (p.cpf ="'.$cpf.'")     AND (po.codEvento = e.codigo) AND (po.data = "'.$retornaDataInvertida.'"))';     $result = mysqli_query($conn, $queryEvento) or die(mysqli_error($conn));      $totalRegistrosInscricoes = mysqli_num_rows($result);             if ($totalRegistrosInscricoes == 0) {                echo("<script>alert('Não há inscrição no evento nesse CPF.');</script>");                    } else if ($totalRegistrosInscricoes != 0) {        echo("<br><br><p style='font-size:20px;'><strong>Dados do Evento e Participante</strong></p><br>");                    while($rowResultado = mysqli_fetch_row($result)){                $tipoPorta = $rowResultado[7];                     $padraoFormatadoData = date('d/m/Y', strtotime($rowResultado[2]));                         //Tabela com resultado Evento e do Participante                     echo("<table border=1 class='table table-striped' style='border:1px solid black'>");                     echo("<tr style='background-color:#337AB7; color:white;'>            <th ></th>            <th >Código do evento</th>            <th>Evento</th>            <th>Data do evento</th>            <th>Hora Início</th>               <th>Hora Fim</th>              <th>Nome participante</th>            <th>Código participante</th>                          </tr>                      <tr>            <td><input name='portas' type='radio' id='portas' value='" . $rowResultado[7] . "." . $rowResultado[4] . "." . $rowResultado[2] . "." . $rowResultado[5] . "." . $rowResultado[6] . "." .$rowResultado[0]."' onclick='selecionaValor(this.value);' /></td>            <td>" . $rowResultado[0] . "</td>            <td>" . $rowResultado[1] . "</td>            <td>" . $padraoFormatadoData . "</td>            <td>" . $rowResultado[5] . "</td>            <td>" . $rowResultado[6] . "</td>            <td>" . $rowResultado[3] . "</td>            <td>" . $rowResultado[4] . "</td>                                  </tr>                    </table>                    <br><br>  ");                         $dataDoEvento = $rowResultado[2];                         $timestamp = strtotime($dataDoEvento . "-365 days");                         //echo "1 ano atrás: " . date('d/m/Y', $timestamp);                         // Calcula a data atual daqui 1 ano atrás                         $dataAtual = date("Y/m/d");                         $timestamp1Ano = strtotime($dataAtual . "-365 days");                         $umAnoAntes = date('Y-m-d', $timestamp1Ano);                         if ($dataDoEvento <= $umAnoAntes) {                             echo "<p align='center' style='font-size:15px; color:#900';'><strong>Evento ocorreu há mais de 1 ano atrás.</strong></p>";                         }                         $dataAtual = date("Y/m/d");                         if ($rowResultado[4] > $dataAtual) {                             echo "Evento ainda não ocorreu.";                     }                 }             }        }        ?>      </div>                                      <div class="container">    <form name="form2" id="form2" action="<?php echo $editFormAction; ?>" method="post">        <p id="teste"></p>        <p id="teste2"></p>            <div class='row'>        <div class='form-group'>           <div class='col-md-12'>            <div id="tamanho_font_radio">                          <label>Tipo Presença: &nbsp;</label>                <input onclick='selecionaTipoPresenca(this.value);' class="tipoPresenca" type='radio' id='tipoPresenca1' name='tipoPresenca' value='1'>           <label for="tipoPresenca1">1 - Normal &nbsp; </label>                 <input onclick='selecionaTipoPresenca(this.value);' class="tipoPresenca"type='radio' id='tipoPresenca2' name='tipoPresenca' value='2'>           <label for="tipoPresenca2"> 2 - Manual &nbsp; </label>                <input onclick='selecionaTipoPresenca(this.value);' class="tipoPresenca" type='radio' id='tipoPresenca3' name='tipoPresenca' value='3'>           <label for="tipoPresenca3"> 3 - Videoconferência &nbsp; </label>                <input onclick='selecionaTipoPresenca(this.value);' class="tipoPresenca" type='radio' id='tipoPresenca4' name='tipoPresenca' value='4'>           <label for="tipoPresenca4"> 4 - Requerimento</label>                           <input type='hidden' name='tiposelecionado' id='tiposelecionado'>               <br><br>               <div id="obs" style="display: none">                   <label for="obs2">Observações:</label>                   <input style="border: none; border-bottom: 1px solid silver;" type="text" name="obs2" id="obs2">               </div>            </div>           </div>        </div>      </div>                  <br><br>      <div class='row'>        <div class='form-group'>           <div class='col-md-2'>             <label>Inserir Registro de Entrada:</label>             <input class='form-control' id='registroEntrada' name="registroEntrada" value="<?php if ((isset($rowResultado)) && ($rowResultado > 0)) echo($rowResultado[5]) ?>" type='text'>            </div>                        <div class='col-md-2'>             <label>Inserir Registro de Saída:</label>             <input class='form-control' id='registroSaida' name="registroSaida" value="<?php if ((isset($rowResultado)) && ($rowResultado > 0)) echo($rowResultado[6]) ?>"  type='text'>            </div>                        <div class='col-md-3'>              <input style='margin-top: 45px;' class='btn btn-primary col-md-6' name='insert' type='button' class='textoNormal' onClick='confirm();' value='Enviar' />                            <input type='hidden' name='valorselecionado' id='valorselecionado'>             <input name='hdAcao' type='hidden' id='hdAcao' value="<?php $_POST['hdAcao'] ?> "/>                                       </div>           </div>          </div>          </form>    </div>                 <br><br><br>         <?php         // VALOR DO RADIO ESCOLHIDO PELO USUARIO, ELE PEGA TODOS OS PARAMETROS PARA REALIZAR A VERIFICAR DA CONSULTA E PARA DAR O INSERT OU UPDATE         if(isset($_POST['valorselecionado']) && isset($_POST['tiposelecionado'])){        $valor_query = $_POST['valorselecionado'];                 $valor_Tipo_presenca = $_POST['tiposelecionado'];                 $valorReal = explode(".",$valor_query);                        }                                     if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "inserir_hora")) {        //PRIMEIRO VAI VERIFICAR SE TEM ALGUM REGISTRO NO REGISTRAPONTO        mysqli_select_db($conn, "emerjco_eventos");        $queryRegistroPonto = "SELECT * FROM registroponto WHERE codParticipante = ".$valorReal[1]." and codEvento = ".$valorReal[5]."";        $result_query = mysqli_query($conn, $queryRegistroPonto);        $result_row = mysqli_fetch_row($result_query);        // APÓS VERIFICA, SE ROW = 0 ELE VAI DAR INSERT, SE ROW = 1 ELE VAI DAR UPDATE        //INSERT        if( $result_row == 0){          mysqli_select_db($conn, "emerjco_eventos");              $insertSQL = "INSERT INTO registroponto (codEvento, codParticipante, data, hora, tipoPorta, tipoPresenca, observacoes) VALUES          ('". $valorReal[5]."', '".$valorReal[1]."', '".$valorReal[2]."','".$_POST['registroEntrada']."','". $valorReal[0]."','". $valor_Tipo_presenca ."' ,'".$_POST['obs2']."');";          $insertSQL2 = "INSERT INTO registroponto(codEvento, codParticipante, data, hora, tipoPorta, tipoPresenca, observacoes ) VALUES          ('". $valorReal[5]."', '".$valorReal[1]."', '".$valorReal[2]."','".$_POST['registroSaida']."', '". $valorReal[0]."', '". $valor_Tipo_presenca ."','".$_POST['obs2']."');";          $Result = mysqli_query($conn, $insertSQL) or die(mysqli_error($conn));          $Result2 = mysqli_query($conn, $insertSQL2) or die(mysqli_error($conn));          $linhaAfetadas = mysqli_affected_rows($conn);          if($linhaAfetadas == 0){              echo "<script>              alert('Erro no insert');</script>";          } else {              echo "<script>             alert('Insert com Sucesso!');</script>";        }    }            //DELETE + INSERT     else {             mysqli_select_db($conn, "emerjco_eventos");              $deleteSQL = "DELETE from  registroponto WHERE codParticipante = '".$valorReal[1]."' AND codEvento = '".$valorReal[5]."' AND tipoPorta = '".$valorReal[0]."'; ";             $Result = mysqli_query($conn, $deleteSQL) or die(mysqli_error($conn));             $insertSQL = "INSERT INTO registroponto (codEvento, codParticipante, data, hora, tipoPorta, tipoPresenca, observacoes) VALUES            ('". $valorReal[5]."', '".$valorReal[1]."', '".$valorReal[2]."','".$_POST['registroEntrada']."', '". $valorReal[0]."', '". $valor_Tipo_presenca."' ,'".$_POST['obs2']."');";             $insertSQL2 = "INSERT INTO registroponto (codEvento, codParticipante, data, hora, tipoPorta, tipoPresenca, observacoes) VALUES            ('". $valorReal[5]."', '".$valorReal[1]."', '".$valorReal[2]."','".$_POST['registroSaida']."', '". $valorReal[0]."', '". $valor_Tipo_presenca."','".$_POST['obs2']."');";             $Result2 = mysqli_query($conn, $insertSQL) or die(mysqli_error($conn));             $Result3 = mysqli_query($conn, $insertSQL2) or die(mysqli_error($conn));             $linhaAfetadas2 = mysqli_affected_rows($conn);             if($linhaAfetadas2 == 0){                echo "<script>                alert('Erro no update');</script>";          } else {                  echo "<script>                  alert('Update com Sucesso!');</script>";            }        }     }    ?>   <script>        //FUNÇÃO PARA SELECIONA INPUT TEXT DE ACORDO COM INPUT RADIO ESCOLHIDO    function PegarValor2() {        if(document.getElementById('valor').checked == true){            document.getElementById('codInscricao').select();            document.getElementById('codInscricao').disabled = false;            document.getElementById('txtMascaraCpf').disabled = true;            document.getElementById('buscar').style.display = "none";            document.getElementById('txtMascaraCpf').style.display = "none";            document.getElementById('campos_cpf').style.display = "none";        }        else if(document.getElementById('valor2').checked == true){            document.getElementById('txtMascaraCpf').select();            document.getElementById('txtMascaraCpf').disabled = false;            document.getElementById('codInscricao').disabled = true;            document.getElementById('buscar').style.display = "block";            document.getElementById('txtMascaraCpf').style.display = "block";            document.getElementById('campos_cpf').style.display = "block";        }    }                  function selecionaValor(valor){            //alert(valor);            $("#valorselecionado").val(valor);            var tipoPorta = valor.substr(0,1);            if(tipoPorta == 1){                var texto = " - Turno da Manhã";            } else if (tipoPorta == 2){                var texto = " - Turno da Tarde";            } else if (tipoPorta == 3){                var texto = " - Turno da Noite";            }            var arrayValor = valor.split(".");            var horaInicio = arrayValor[3];            var horaFim    = arrayValor[4];            $("#teste").text("Porta do tipo: " + tipoPorta + texto).css('font-size','1.3em');            $("#registroEntrada").val(horaInicio);            $("#registroSaida").val(horaFim);        }               function selecionaTipoPresenca(valor){            //alert(valor);            $("#tiposelecionado").val(valor);                if(valor == 3 || valor == 4) {                    $("#obs").css('display', 'block');                } else {                    $("#obs").css('display', 'none');                }        }        </script>        </body></html>