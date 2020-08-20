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
	header("Location: ../../index.php");
}

$nivelLogado = $_SESSION['usuarioNiveisAcessoId'];

header("Content-Type: text/html; charset=utf-8", true);

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../css/cadastrar_eventos.css">
    <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

</head>

<body>

    <div id="title">
        <center>
            <spam>ALTERAR PESQUISA</spam>
        </center>
    </div>

    <form id="cadastrar_eventos" action="" method="post">
        <div class="container">
            <fieldset>
                <legend>Informe os dados abaixo:</legend>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-4">
                            <label for="codevento">CodEvento:</label>
                            <input class="form-control" type="text" id="codevento" name="codevento" placeholder="Código do evento">
                        </div>



                        <div class="col-md-4">
                            <label for="dateevento">Data do Evento:</label>
                            <input class="form-control col-lg-6" type="date" id="dateevento" name="dateevento" placeholder="Nº de Vagas" required>
                        </div>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-5">
                            <label for="modo">Como tomou ciência do fórum:</label>
                            <select class="form-control" type="text" id="modo" name="modo" required>
                                <option>Selecione</option>
                                <option>Desafios atuais dos Juízos Cíveis</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="nome-evento">Código do próximo evento a ser cadastrado:</label>
                            <input class="form-control" type="text" id="nome-evento" name="nome-evento" required>
                        </div>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                            <input class="form-control btn btn-primary" type="search" id="pesquisar" name="pesquisar" value="Pesquisar" required>
                        </div>
                    </div>
                </div>

                <br>


                <div class="row">
                    <div class="form-group">
                        <div class="col-md-8">
                            <table class="table">
                                <caption> Código e Nome do Ultimo evento Cadastrado</caption>
                                <thead>
                                    <tr>
                                        <th class="col-md-2" scope="col">Código</th>
                                        <th class="col-md-6" scope="col">Nome do Evento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">7511</th>
                                        <td>O Pensamento de Cicero</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                <br><br>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-8">
                            <table class="table">
                                <caption> Código e Nome do Ultimo evento Cadastrado com Pesquisa/Enquete no Banco</caption>
                                <thead>
                                    <tr>
                                        <th class="col-md-2" scope="col">Código</th>
                                        <th class="col-md-6" scope="col">Nome do Evento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">7511</th>
                                        <td>O Pensamento de Cicero</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <br><br>
                <br><br>


                <button class="btn btn-primary col-md-2  col-xs-12 pull-right">Alterar Pesquisa</button>

        </div>

        </fieldset>
    </form>

</body>

</html>