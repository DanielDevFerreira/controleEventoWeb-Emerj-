<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../css/cadastrar_eventos.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
    </head>

<body>

    <div id="title"><center><spam>CERTIFICADO DE PARTICIPAÇÃO EM EVENTO</spam></center></div>

    <br><br>

    <div class="container">
        <form action="../certificado/index.php" id="myForm" method="post" onsubmit="return confirm('Tem certeza de que deseja gerar esse Certificado ?');">

            <div class="row">
                <div class="form-group">
                    <div class="col-md-4 col-xs-10 ">

                        <label for="nome-evento">Código de Inscrição:</label>
                        <input type="text" class="form-control" type="text" id="codIncricao" name="codInscricao" required placeholder="código de inscrição do participante">

                    </div>

                    <div class="col-md-4 col-xs-10 ">
                        <input style="margin-top:25px;"  type="submit" class="btn btn-primary col-md-4"  value="Gerar" />

                    </div>

                </div>

            </div>

        </form>
    </div>
</body>

</html>