<html>
    <head>
        <link rel="stylesheet" href="../css/cadastrar_eventos.css">
        <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
</html>

<div class="container">


<?php
require_once ("../conexao.php");

if (isset($_POST['codEventoParticiante'])){
    $codigoEventoParticipanteCPF = explode(".",trim(($_POST['codEventoParticiante'])));
    $codigoEvento = $codigoEventoParticipanteCPF[0]; //post do radio posição 0
    $cpf = $codigoEventoParticipanteCPF[2];// post do radio posição 2
    //post do radio posição 1 é o codigo do participante


    mysqli_select_db($conn, "emerjco_eventos");

    $queryEvento = strtolower(trim(stripslashes('select distinct e.codigo, e.nome, pa.nome as participante, pa.codigo, p.data, pa.cpf from evento e, porta p, participante pa, inscricoes i 
           where e.codigo = "' . $codigoEvento . '" AND p.codEvento ="'. $codigoEvento . '" AND pa.cpf ="' . $cpf .'"')));

    $result = mysqli_query($conn, $queryEvento) or die(mysqli_error($conn));
    $result2 = mysqli_query($conn, $queryEvento) or die(mysqli_error($conn));

    $rowResultado2 = mysqli_fetch_row($result2);
    $num_row = mysqli_num_rows($result);

    if ($num_row == 0) {

        echo("<script>alert('Erro na consulta, Verifique os dados digitados !.');</script>");
        echo"<br>";
        echo"<button class='btn btn-primary'><a href='consultaPonto.php'>Voltar Página Anterior</a></button>";


    } else if ($num_row != 0) {

        echo("<br><br><center><p style='font-size:20px;'><strong>Dados do Evento e Participante</strong></p></center><br>");

        echo("<p style='font-size:20px;'>Nome do Participante: $rowResultado2[2] </p><br>");
        echo("<p style='font-size:20px;'>CPF: $rowResultado2[5] </p><br>");

        while ($rowResultado = mysqli_fetch_row($result)) {


            //$padraoFormatadoData = date('d/m/Y', strtotime($rowResultado[2]));


            //Tabela com resultado Evento e do Participante
            $padraoFormatadoData = date('d/m/Y', strtotime($rowResultado[4]));

            echo("<table border=1 class='table table-striped' style='border:1px solid black'>");
            echo("<tr style='background-color:#337AB7; color:white;'>

            <th >Código do evento</th>

            <th>Evento</th>

            <th>Data do evento</th>

            <th>Código participante</th>   

          </tr>
          

          <tr>

            <td>" . $rowResultado[0] . "</td>

            <td>" . $rowResultado[1] . "</td>

            <td>" . $padraoFormatadoData . "</td>

            <td>" . $rowResultado[3] . "</td>

          </tr>

          </table>

          <br>");

            $dataDoEvento = $rowResultado[4];

            $timestamp = strtotime($dataDoEvento . "-365 days");

            //echo "1 ano atrás: " . date('d/m/Y', $timestamp);


            // Calcula a data atual daqui 1 ano atrás

            $dataAtual = date("Y/m/d");

            $timestamp1Ano = strtotime($dataAtual . "-365 days");

            $umAnoAntes = date('Y-m-d', $timestamp1Ano);


            if ($dataDoEvento <= $umAnoAntes) {

                echo "<div class='row'><div class='alert alert-danger col-md-4 col-xs-8' style='font-size: 1.2em; margin-top: 50px;'><strong>Evento ocorreu há mais de 1 ano atrás.</strong></div></div>";

            }

            $dataAtual = date("Y/m/d");

            if ($rowResultado[4] > $dataAtual) {

                echo "<div class='alert alert-danger col-md-6 col-xs-8' style='font-size: 1.2em; margin-top: 50px;'><strong>Evento ainda não ocorreu !</strong></div>";

            }

        }
    }


    mysqli_select_db($conn, "emerjco_eventos");

    $queryInscricoes = strtolower(trim(stripslashes('select codEvento, codParticipante from inscricoes where codParticipante = "' . $rowResultado2[3] . '" and codEvento = "' . $rowResultado2[0] . '"')));

    $resultInscricoes = mysqli_query($conn, $queryInscricoes) or die(mysqli_error($conn));

    $totalRegistrosInscricoes = mysqli_num_rows($resultInscricoes);


    if ($totalRegistrosInscricoes == 0){

        echo("<script>alert('Não há Inscrição desse CPF no Evento Selecionado !.');</script>");

        echo "<div class='alert alert-danger col-md-6 col-xs-8' style='font-size: 1.2em; margin-top: 50px;'>  Não existe dados do <strong>Participante</strong> no Evento Selecionado! </div>";

        echo "<br> <br>";

        echo"<button style='margin-left: 20px; margin-top: 20px; padding: 10px;' class='btn btn-primary'><a href='consultaPonto.php' style='color: white'>Voltar Página Anterior</a></button>";

    }

    else if ($totalRegistrosInscricoes != 0){

        /*Não mexer*/

        mysqli_select_db($conn, "emerjco_eventos");

        $queryRegistroPonto = strtolower(trim(stripslashes("select data, hora from registroponto where codParticipante = " . $rowResultado2[3] . " and codEvento = " . $rowResultado2[0] . " ORDER BY data, hora ASC")));

        $resultRegistroPonto = mysqli_query($conn, $queryRegistroPonto) or die(mysqli_error($conn));

        $row_RegistroPonto = mysqli_fetch_row($resultRegistroPonto);

        $totalRegistrosRegistroPonto = mysqli_num_rows($resultRegistroPonto);


        if ($totalRegistrosRegistroPonto == 0){


            echo("<p style='font-size:16px; color:#900';'>Não há registros para o número de inscrição.</p>");


        } else if ($totalRegistrosRegistroPonto != 0){

            ?>


            <div align=center>


            <?php


            echo "<p style='font-size:20px;'><strong>Ponto</strong></p>";

            echo "<br>";

            echo("<table class='table table-striped col-md-6' cellpadding='10px' border=1 style='border:#999 1px solid; border-collapse:collapse; padding:15px; font-size:15px;'>\n");


            echo("\t<tr style='background-color:#337AB7; color:white;'>\n");

            for ($n = 0; $n < 1; $n++) {

                echo("\t\t<td align='center'>&nbsp;<b>" . "Data" . "</b>&nbsp;</td>\n");

                echo("\t\t<td align='center'>&nbsp;<b>" . "Hora" . "</b>&nbsp;</td>\n");

            }

            echo("\t</tr>\n");


            for ($i = 0; $i < $totalRegistrosRegistroPonto; $i++) {

                echo("\t<tr>\n");

                for ($n = 0; $n < 1; $n++) {


                    $padraoFormatoData = date('d/m/Y', strtotime($row_RegistroPonto[0]));


                    echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;");


                    echo $padraoFormatoData;


                    echo("&nbsp;</td>\n");


                    echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_RegistroPonto[1] . "&nbsp;</td>\n");

                }

                $row_RegistroPonto = mysqli_fetch_row($resultRegistroPonto);

                echo("\t</tr>\n");

            }

            echo("</table>");

        }

        echo "<br><br>";


        /**/

        mysqli_select_db($conn, "emerjco_eventos");

        $queryFrequencia = strtolower(trim(stripslashes("select distinct f.codEvento, f.codParticipante, f.dataPorta, f.horaPorta, f.cargaHorariaOAB, f.permanencia, f.percentual, p.horaFim from frequencia2 f, porta p where f.codParticipante = " . $rowResultado2[3] . " and f.codEvento = " . $rowResultado2[0] . " and (f.codEvento = p.codEvento) and (f.horaPorta = p.horaInicio) ORDER BY f.dataPorta, f.horaPorta ASC")));

        $resultFrequencia = mysqli_query($conn, $queryFrequencia) or die(mysqli_error($conn));

        $row_Frequencia = mysqli_fetch_row($resultFrequencia);

        $totalRegistrosFrequencia = mysqli_num_rows($resultFrequencia);


        $dataAtual = date("Y/m/d");


        if ($totalRegistrosFrequencia == 0) {


            echo("<p style='font-size:16px; color:#900';'>Não há apuração para o número de inscrição.</p>");


        } else if ($totalRegistrosFrequencia != 0) {


            echo "<p style='font-size:20px;'><strong>Apuração</strong></p>";

            echo "<br>";

            echo("<table class='table table-striped cellpadding='10px' border=1 style='border:#999 1px solid; border-collapse:collapse; padding:15px; font-size:15px;'>\n");


            echo("\t<tr style='background-color:#337AB7; color:white;'>\n");

            for ($n = 0; $n < 1; $n++) {

                echo("\t\t<td align='center'>&nbsp;<b>" . "Data" . "</b>&nbsp;</td>\n");

                echo("\t\t<td align='center'>&nbsp;<b>" . "Hora Inicial" . "</b>&nbsp;</td>\n");

                echo("\t\t<td align='center'>&nbsp;<b>" . "Hora Final" . "</b>&nbsp;</td>\n");

                echo("\t\t<td align='center'>&nbsp;<b>" . "Permanência" . "</b>&nbsp;</td>\n");

                echo("\t\t<td align='center'>&nbsp;<b>" . "Percentual" . "</b>&nbsp;</td>\n");

                echo("\t\t<td align='center'>&nbsp;<b>" . "Carga Horária OAB" . "</b>&nbsp;</td>\n");

            }

            echo("\t</tr>\n");


            for ($i = 0; $i < $totalRegistrosFrequencia; $i++) {

                echo("\t<tr>\n");

                for ($n = 0; $n < 1; $n++) {


                    $padraoFormatoData = date('d/m/Y', strtotime($row_Frequencia[2]));


                    echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $padraoFormatoData . "&nbsp;</td>\n");

                    echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[3] . "&nbsp;</td>\n");

                    echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[7] . "&nbsp;</td>\n");

                    echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[5] . "&nbsp;</td>\n");


                    $percentualPortas = $row_Frequencia[6] * 100;

                    $percentualPortasFormatado = round($percentualPortas, 2);


                    echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $percentualPortasFormatado . " %&nbsp;</td>\n");

                    echo("\t\t<td align='center' style:'padding:15px;'>&nbsp;" . $row_Frequencia[4] . "&nbsp;</td>\n");

                }

                $row_Frequencia = mysqli_fetch_row($resultFrequencia);

                echo("\t</tr>\n");

            }

            echo("</table>");


            echo "<br><br>";

            echo "<strong>Tipo de presença:</strong> 1 - Normal / 2 - Manual / 3 - Videoconferência / 4 - Requerimento";

            echo "<br><br>";


        }

    }
}

?>
      </div>

    <div id="footer" style="margin-bottom:0px;">
        <br />
        <div align="center">
            <div align="center"><span class="style22"><strong>ESCOLA DA MAGISTRATURA DO ESTADO   DO RIO DE JANEIRO - EMERJ<br />
	    Rua Dom Manuel, n&ordm; 25 - Centro - Telefone:   3133-1880<br /></strong></span>
            </div>
        </div>
    </div>

    <br><br>

    <!--BOTÃO IMPRESSORA -->

    <div id="btn-print"  align="center"><a href="javascript:print();"><img src="../images/impressora.png" width="36px" height="35px" /></a></div>

    <br /><br />
