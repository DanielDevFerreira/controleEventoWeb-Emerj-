<?php 



?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../css/registro-frequencia.css">
    <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>

<div id="title">
    <center>
        <spam>Registro de Frequência</spam>
    </center>
</div>
    <br>
    
<div class="container">
    <form method="post" action="">
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Dados da última inscrição válida</legend>

            <div class="row">
            <div class="form-group">
            <div class="col-md-6">
                <label for="evento">Nome do Evento:</label>
                <input class="form-control" type="text" id="evento" name="evento" placeholder="Nome do evento">
            </div>
            </div>
            </div>

    <br>

            <div class="row">
            <div class="form-group">
            <div class="col-md-4">
                <label for="evento">Hora do Último Registro:</label>
                <input class="form-control" type="datetime-local" id="evento" name="evento">
            </div>
            <div class="col-md-4">
                <label for="evento">Data do Último Registro:</label>
                <input class="form-control" type="datetime-local" id="evento" name="evento">
            </div>
            </div>
            </div>
        </fieldset>
        </form>
</div>
    
    <br>

    <div class="container">
        <form method="post" action="" class="col-md-9" style="margin-left:-15px;">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Registro do Ponto</legend>

            <div class="row">
            <div class="form-group">
            <div class="col-md-4">
                <input type="radio" id="n-inscricao" name="n-inscricao"> <label class="col-form-label" for="n-inscricao">Número de Inscrição</label>
            </div>
            <div class="col-md-6">
                <input class="form-control" type="text" id="evento" name="evento">
            </div>
            </div>
            </div>

    <br>
            <div class="row">
            <div class="form-group">
            <div class="col-md-4">
                <input type="radio" id="manual" name="n-inscricao"> <label for="manual" class="col-form-label" for="n-inscricao">Manual</label>
            </div>
            <div class="col-md-6">
                <label>Evento:</label>
                <select class="form-control" type="text" id="evento" name="evento" placeholder="Nome do evento">
                    <option>Selecione o Evento</option>
                    <option>xxxxxxxxxxxx</option>
                    <option>xxxxxxxxxxxx</option>
                </select>
            </div>
            </div>
            </div>

    <br>

            <div class="row">
            <div class="form-group">
            <div class="col-md-4">
            </div>

            <div class="col-md-6">
                <label for="cpf">CPF:</label>
                <input type="text" class="form-control" type="text" id="cpf" name="cpf">
            </div>
            </div>
            </div>
            </fieldset>
        </form>

            <div class="row">
            <div class="form-group">
            <div class="col-md-2 col-xs-7">
            <div>
                <img src="../images/frame.png" </div> <p id="qr-code"><i>QRCODE</i></p>
            </div>
            </div>
            </div>
            </div>
    </div>


    <br><br><br>

    <div class="container">
    <div class="row">
    <div class="form-group">
    <div class="col-md-12">
        <button class="btn btn-primary col-md-2 col-xs-12 pull-right">Sair</button>    
    </div>    
    </div>
    </div>    
    </div>
    <br><br>
</body>

</html>
