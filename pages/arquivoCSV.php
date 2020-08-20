<?php

	require_once('../conexao.php');

$editFormAction = $_SERVER["PHP_SELF"];
if (isset($_SERVER["QUERY_STRING"])) {
    $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);

}


?>

<html>

    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="style_upload.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    </head>

    <body>
        <div id="up">
            <form action="#" method="post" enctype="multipart/form-data">

                <h2>Upload de Arquivo CSV</h2>
                <input class="btn btn-success" type="file" name="file" value="Upload_CSV">

                <input class="btn btn-primary" type="submit" name="button" id="button" value="Enviar Arquivo">
            </form>

            <form id="form2" action="#" method="post">

                <input type="hidden" id="hdAcao" name="hdAcao"	value="Valor Inicial" />
                <select class="form-control" id="tamanho" name="tamanho">
                    <option value="Brotinho">Escolha uma opção</option>
                    <option value="InsertNovo">Consulta e Inserir somente novos Participantes</option>
                    <option value="InsertTodos" >Consulta e Inserir todos os Participantes</option>
                    <option value="Problemas" >Consulta os Participantes com Dados incorretos</option>
                </select>

                <input class="btn btn-primary" type="submit" name="button" id="button" value="Buscar">

            </form>
        </div>

        <?php
        
        if (isset($_FILES["file"])) {
            $arquivo = $_FILES["file"]["tmp_name"];
            $nome = $_FILES["file"]["name"];

            $ext = explode(".", $nome);

            $extensao = end($ext);


            if ($extensao != "csv") {
                echo "Extensão Inválida";
            } else {
                $obj = fopen($arquivo, "r");

                echo("<div class='container'>

            <h2>Dados do Arquivo Importados</h2>
                <table class='table table-striped'>

            <tr style='background-color:#337AB7; color:white;'>
                <th>Linha CSV</th>
                <th>Participou</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>E-mail</th>
            </tr>");

                $j = 1;
                while (($dados = fgetcsv($obj, 1000, ",")) !== FALSE) {

                    $nomeCompleto = $dados[1] . " " . $dados[2];

                    echo("<tr>
                           <td>$j</td>
                           <td>$nomeCompleto</td>
                           <td>$dados[2]</td>
                           <td>$dados[3]</td>
                           <td>$dados[4]</td>
                      </tr>");

                    $j++;



                    if(($dados[0] == "Sim") && ($dados[4] != ""))  {

                        $insert = "INSERT INTO teste (nome,email,cpf) VALUES ('" . $nomeCompleto . "','" . $dados[3] . "','" . $dados[4] . "')";
                        $Result = mysqli_query($conn, $insert) or die(mysqli_error($conn));

                    } else if ($dados[0] == "Não"){

                        $insert = "INSERT INTO teste2 (nome,email,cpf) VALUES ('" . $nomeCompleto . "','" . $dados[3] . "','" . $dados[4] . "')";
                        $Result = mysqli_query($conn, $insert) or die(mysqli_error($conn));
                    }
                }

                echo("</table></div> ");
            }
        }

        if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "InsertNovo")) {

            $consultarParticipante = "SELECT p.nome, p.cpf, p.email from teste p where cpf not in (select cpf from participante) ORDER BY `nome` ASC ";
            $retorno_Query = mysqli_query($conn, $consultarParticipante) or die(mysqli_error($conn));
            $totalrow = mysqli_num_rows($retorno_Query);


            ?>


     <div class="container">

         <center><h3>Será inserido apenas os Participantes com o CPF informado corretamente !</h3></center>

    <form name="form3" id="form3" action="<?php echo $editFormAction; ?>" method="post">

    <table border=1 class='table table-striped' style='border:1px solid black'>

        <tr style='background-color:#337AB7; color:white;'>
            <th colspan="2">Nome Participante</th>
            <th>SobreNome Participante</th>
            <th>CPF Participante</th>
            <th>E-mail Participante</th>
        </tr>

        <tr>
            <th class='TDtable1' align='center'><input type='checkbox' class='form-check-input' id='check_tudo' style="margin-left: 5px;"></th>
            <th>Selecionar tudo</th>
            <th></th>
            <th></th>
        </tr>

    <?php

    $array = array();
    $nof = mysqli_num_fields($retorno_Query);
    while ($rowResult = mysqli_fetch_row($retorno_Query)){

        echo "";

       echo"
                <tr>
                    <td class='TDtable1' align='center'><input type='checkbox' name='listaParticipante[]' id='listaParticipante'  value='". $rowResult[0] .",". $rowResult[1].",". $rowResult[2]."'>
                    <td> $rowResult[0]</td>
                    <td> $rowResult[1]</td>
                    <td> $rowResult[2]</td>
               </tr>
               
               
           ";
    }

    ?>


        <tr>
            <th colspan='2' class='TDtable1'style='background-color:#337AB7; color:white; text-align: center' >Total de Registros</th>
            <th colspan='3' class='TDtable1 bg-success' style="text-align: center" ><?php echo $totalrow; ?></th>
        </tr>

        </table>

        <br><br>


        <div class='row'>
            <div class='form-group'>
                <div class='col-md-3'>
                    <input style="margin-left: 20px; " class='btn btn-primary col-md-8' name='insert' type='button' class='textoNormal' onClick='confirm();' value='Inserir Participantes' />
                    <input name='hdAcao' type='hidden' id='hdAcao' value='". $_POST['hdAcao'] ."'/>

                </div>
            </div>
        </div>
        </form>
    </div>

     <?php   }

        if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "InsertTodos")) {

                $queryTodos = "SELECT * FROM teste";
                $result = mysqli_query($conn, $queryTodos);
                $totalrow = mysqli_num_rows($result);

            echo("<div class='container'>

                    <h2>Dados de Todos os Participantes</h2>
                        <table class='table table-striped'>
        
                    <tr style='background-color:#337AB7; color:white;'>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>E-mail</th>
                        <th>Profissão</th>
                    </tr>");


            while($result_row = mysqli_fetch_row($result)){

                echo("<tr>
                           
                           <td>$result_row[1]</td>
                           <td>$result_row[2]</td>
                           <td>$result_row[3]</td>
                           <td>$result_row[4]</td>
                      </tr>");

                }

             ?>

        <tr>
            <th colspan='1' class='TDtable1'style='background-color:#337AB7; color:white; text-align: center' >Total de Registros</th>
            <th colspan='3' class='TDtable1 bg-success' style="text-align: center" ><?php echo $totalrow; ?></th>
        </tr>

        </table>

     <?php }

      if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "Problemas")) {

            echo ("<h2>EM DESENVOLVIMENTO......</h2>");

        }

     ?>

        <script type="text/javascript">

            // MARCAR TODOS OS CHECKBOX

            $('#check_tudo').click(function () {
                // marca ou desmarca os outros checkbox

                $('input:checkbox').not(this).prop('checked', this.checked);

            });

            $(function(){
                $('#tamanho').on('change', function(){
                    tamanho = $('option').filter(':selected').prop('value');
                    $('#hdAcao').not('visible').prop('value',tamanho);
                })
            })

        </script>
    </body>


</html>

