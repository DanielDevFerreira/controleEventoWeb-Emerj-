<?php

require_once ('../conexao.php');

if(isset($_POST['valorselecionado']) ){

    $valor_query = $_POST['valorselecionado'];
    $valorReal = explode("*",$valor_query);


    $query = "Call PresencaIndividual($valorReal[0], $valorReal[1])";
    $rsBusca = mysqli_query($conn, $query) or die(mysqli_error($conn));

        //ALERTS DA APURAÇÃO
        if ($rsBusca == 1){
            echo "<script>alert('Participante Apurado com Sucesso!');
            location.href = '../pages/apuraFrequenciaIndividual.php';
        </script>";

        }
        else{
            echo"<script>alert('Erro na apuração!');
            location.href = '../pages/apuraFrequenciaIndividual.php';
        </script>";

        }

    }
