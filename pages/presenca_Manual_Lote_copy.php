<?php
require_once('../conexao.php');

session_start();

if (isset($_SESSION['evendodia'])){
    $titulo = explode(".", $_SESSION['evendodia']);
}

date_default_timezone_set('America/Bahia');//NA PRODUÇÃO RETIRAR ESTA LINHA, PROBLEMA DE HORÁRIO DE VERÃO

$horaAtual = date('H:i:s');//PEGA A HORA ATUAL

$dataAtual = new DateTime('now');

$stringDataAtual =  $dataAtual -> format('Y-m-d H:i:s');

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

if(isset( $_POST["codEvento"])){
    $codEvento = $_POST["codInscricao"];
}

?>


<script language="javascript">

    // FUNÇÃO PARA VERIFICAR O CAMPO DO CÓDIGO DO EVENTO

    function validaCampos()
    {
        var d = document.form1;
        var cod = document.getElementById('codInscricao').value;

        if (cod == ""){
            alert("O nº de inscrição deve ser preenchido!");
        }
        else if (cod != "" && cod.length < 4)
        {
            alert('Nº de inscrição inválido, verifique se contém 14 digitos!');
        }
        else if (cod != "" && cod.length >= 4)
        {
            document.form1.hdAcao.value = "pesquisar";
            enviaFormulario();
        }
    }

    function confirm() {

        if( $("input[type=checkbox").is(":checked") == true){
            document.form2.hdAcao.value = "inserir_hora";
            document.form2.submit();
        } else {
            alert('Selecione os Participantes!');
        }

    }

    // ENVIAR OS DADOS DO FORM

    function enviaFormulario()
    {
        document.form1.submit();
    }

    function MM_callJS(jsStr) { //v2.0

        return eval(jsStr)

    }


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

</script>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-UA-Compatible" content="IE=11">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script>

        setTimeout(function() { document.getElementById('codInscricao').focus(); }, 10);

    </script>

    <link rel="stylesheet" href="../css/alterar_evento.css">
    <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Baloo&display=swap" rel="stylesheet">

    <style>

    </style>

</head>

<body>

<div id="title">
    <center>
        <spam>PRESENÇA MANUAL DOS PARTICIPANTES EM LOTE</spam>
    </center>
</div>
<!-- FORMULARIO PARA ENVIAR O CODIGO DO EVENTO PARA A QUERY SELECT -->
<div class="container">
        <br><br>
        <p><i>Instruções: Essa funcionalidade permitirá que se aplique uma presença manual em <strong>Lote,</strong> hà um deteminardo evento e</i></p>
        <p><i>podera-se aplicada para todos os participantes ao selecionar <strong>TODOS,</strong> ou apenas aos participantes que marcaram presença no evento!</i></p>

    <form id="form1" name="form1" style="padding: 2%;" action="<?php echo $editFormAction; ?>" method="post">
        <fieldset>

            <div class="row">
                <div class="form-group">
                    <div class="col-md-5 col-xs-6">
                        <br>
                        <input onclick="PegarValor2()" type="radio" name="valor" id="valor" checked>
                        <label style="font-size: 17px;" for="valor">Nº da Inscrição:</label>
                        <input class='form-control' placeholder='Informe o código do Evento!' name="codInscricao" id="codInscricao" maxlength="14" type="text">
                    </div>

                    <div class="col-md-2 col-xs-3">
                        <input style="margin-top:45px;" name="inscrever" type="submit" class="textoNormal form-control btn btn-primary" id="inscrever" onClick="MM_callJS('validaCampos();')" value="Pesquisa"/>
                        <input name="hdAcao" type="hidden" id="hdAcao" value="<?php if (isset($_SESSION["codInscricao"])) echo('alterar'); else  echo('inserir') ?>"/>
                        <input name="codEvento" type="hidden" id="codEvento" />
                    </div>
                </div>
            </div>

        </fieldset>
    </form>
</div>

<div class="container">
    <?php

    if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "pesquisar")) {

    mysqli_select_db($conn, "emerjco_eventos");

    $numeroInscricao = $_POST["codInscricao"];

    if (strlen($numeroInscricao) < 4) {
        echo("<script>alert('Nº de inscrição inválido, verifique se contém 14 digitos!');</script>");

    } else {

    //SELECT PARA PESQUISAR OS PARTICIPANTES DE UM DETERMINADO EVENTO

    $queryEvento = "SELECT e.codigo AS CodigoEvento, e.nome, po.data, p.cpf AS cpfParticipante, p.nome AS NomeParticipante, p.matriculatj AS MatTJ, po.horaInicio, po.horaFim, po.tipo, po.cargaHoraria, i.dataHoraInscricao, p.codigo 
                FROM evento e, participante p, inscricoes i, porta po
                WHERE (i.codEvento = $codEvento ) AND (i.codEvento = po.codEvento) AND (i.codParticipante = p.codigo) AND (i.codEvento = e.codigo)";

    $rsBuscaNome = mysqli_query($conn, $queryEvento) or die(mysqli_error($conn));

    $totalRegistrosInscricoes = mysqli_num_rows($rsBuscaNome);

    $vetHora = mysqli_fetch_assoc($rsBuscaNome);

    // QUERY PARA PEGAR A DIFERENÇA ENTRE HORA_INICIO  E DA HORA_FIM

    $queryDiferencaHora = "SELECT TIMEDIFF( po.horaFim, po.horaInicio) FROM porta po WHERE codEvento = '$codEvento'";

    $rsBuscaDiferenca = mysqli_query($conn, $queryDiferencaHora) or die(mysqli_error($conn));

    $diferencaHora = mysqli_fetch_row($rsBuscaDiferenca);

    $vetHora['data'] = implode("/",array_reverse(explode("-",$vetHora['data'])));

    if ($totalRegistrosInscricoes == 0) {

        echo("<script>alert('Não há inscrição no evento.');</script>");

    } else if ($totalRegistrosInscricoes != 0) {

    echo("<br><p style='font-size:20px;'><strong>Dados do Evento e dos Participantes</strong></p>");
    ?>

    <!-- Tabela com resultado Evento e do Participante -->

    <table width='850px' border='1' align='center' class='table table-striped'>

        <tr class='TDdata'>

            <?php
            echo"<tr style='background-color:#337AB7; color:white;' class='TDtable1'>";
            echo"<td style='font-size: 1.2em;' colspan='6' class='TDtable1' align='center'>". $vetHora['nome']. " - Hora Início ". $vetHora['horaInicio']. " - Hora Fim  ". $vetHora['horaFim']. " </td>";
            echo"<td style='font-size: 1.2em;' colspan='1' class='TDtable1' align='center'>Data: " . $vetHora['data'] . "</td>";
            echo"</tr>";
            ?>

        </tr>
        <tr style='color: black' class='bg-primary'>
            <td width='10px'></td>
            <td width='10px'>Num.</td>
            <td width='390px'>Nome</td>
            <td width='60px'>Mat. TJ</td>
            <td width='60px' align='center'>CPF</td>
            <td width='100px' align='center'>N. de Inscrição</td>
            <td width='290px'>Data de Inscrição</td>
        </tr>

        <tr style='color: black' >
            <td width='10px' align='center'><input type='checkbox' class='form-check-input' id='check_tudo'></td>
            <td width='10px' align='center'>0</td>
            <td width='390px'><strong>SELECIONAR TODOS OS PARTICIPANTES</strong></td>
            <td width='60px'></td>
            <td width='60px' align='center'></td>
            <td width='100px' align='center'></td>
            <td width='290px'></td>
        </tr>



</div>

<!-- formulario 2 para checkbox listaParticipante -->

<div class="container">
    <form name="form2" id="form2" action="<?php echo $editFormAction; ?>" method="post">

        <?php

        $i = 1;

        while ( $vet = mysqli_fetch_row($rsBuscaNome)) {

            // DADOS PARA O INSERT NA TABELA FREQUENCIA2
            $codEvento = $vet['0'];
            $codParticipante = $vet['11'];
            $cpfParticipante = $vet['3'];
            $codInscricao = sprintf("%06d%08d", $codEvento, $codParticipante);
            $nome_Participante = $vet['4'];
            $Mat_tj = $vet['5'];
            $dataInscricao = $vet['10'];
            $dataInscricao2= explode(" ",$dataInscricao);
            $dataInscricao2[0]= implode("/",array_reverse(explode("-",  $dataInscricao2[0])));
            $dataPorta = $vet['2'];
            $horaInicioPorta = $vet['6'];
            $horaFimPorta = $vet['7'];
            $cargaHoraria = $vet['9'];
            $tipoCargaHoraria = $vet['8'];


            echo "<tr class='TDtable1'>";
            echo "<td class='TDtable1' align='center'><input type='checkbox' name='listaParticipante[]' id='listaParticipante'  value='". $codEvento .",". $codParticipante .",". $dataPorta .",". $horaInicioPorta .",". $cargaHoraria .",". $tipoCargaHoraria .",". $dataInscricao .",". $diferencaHora[0] ."''></td>";
            echo "<td class='TDtable1' align='center'>" . $i . "</td>";
            echo "<td class='TDtable1' align='left'><span style='text-transform:uppercase;'>$nome_Participante</span></td>";
            echo "<td class='TDtable1' align='center'>$Mat_tj</td>";
            echo "<td class='TDtable1' align='center'>$cpfParticipante</td>";
            echo "<td class='TDtable1' align='center'>$codInscricao</td>";
            echo "<td class='TDtable1' height='26px'>$dataInscricao2[0]  $dataInscricao2[1]</td>";
            echo "</tr>";

            $i++;

                 }

            }
        }

    }

        ?> </table>
        <br>

        <!-- CONTINUAÇÃO DO SEGUNDO FORMALARIO FORM2 -->
</div>
<div class='container'>
    <div class='row'>
        <div class='form-group'>
            <div class='col-md-3'>
                <input style="margin-left: 20px; " class='btn btn-primary col-md-6' name='insert' type='button' class='textoNormal' onClick='confirm();' value='Enviar' />
                <input name='hdAcao' type='hidden' id='hdAcao' value='". $_POST['hdAcao'] ."'/>

            </div>
        </div>
    </div>
    </form>
</div>

<br><br><br>

<?php

// VALOR DO RADIO ESCOLHIDO PELO USUARIO, ELE PEGA TODOS OS PARAMETROS PARA REALIZAR A VERIFICAR DA CONSULTA E PARA DAR O INSERT OU UPDATE

if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "inserir_hora")) {

    if(!isset($_POST['listaParticipante'] )){
        exit ("<div class='container'>
                            <div class='alert alert-danger col-md-6 col-xs-8'>
                                Erro ao inserir à presença, é preciso selecionar algum <strong>Participante!</strong>
                            </div>
                      </div>  ");
    } else {

        //PRIMEIRO VAI VERIFICAR SE TEM ALGUM REGISTRO NO REGISTRAPONTO

        mysqli_select_db($conn, "emerjco_eventos");

           $k = 0;

        // FAZ UMA VARREDURA
        foreach ($_POST['listaParticipante'] as $lista) {

            $resutLista = explode(",", $lista);

            $queryInsertFrequencia = "INSERT INTO frequencia2 (codEvento, codParticipante, dataPorta, horaPorta, cargaHorariaOAB, tipoCargaHoraria, dataAtribuida, permanencia, percentual)
                        VALUES ('$resutLista[0]','$resutLista[1]','$resutLista[2]','$resutLista[3]','$resutLista[4]','3', '$stringDataAtual' ,'$resutLista[7]', '1')";

            $Result = mysqli_query($conn, $queryInsertFrequencia) or die(mysqli_error($conn));

            $k++;
        }

        $linhaAfetadas = mysqli_affected_rows($conn);


        if ($linhaAfetadas == 0) {
            echo "<script>
              alert('Erro no insert');</script>";
        } else {
            echo "<div class='container'><div class='alert alert-success col-md-6 col-xs-8' style='font-size: 1.2em;'> <strong>" . $k . "</strong> Presenças realizadas com Sucesso! </div></div>";

        }
    }
}

?>

<script>

    // MARCAR TODOS OS CHECKBOX

    $('#check_tudo').click(function () {
        // marca ou desmarca os outros checkbox

        $('input:checkbox').not(this).prop('checked', this.checked);

    });

</script>

</body>
</html>