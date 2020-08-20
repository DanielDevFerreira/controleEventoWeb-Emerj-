<?php

    include_once("conexao.php");

    session_start();

?>



<!DOCTYPE html>

<html lang="en">

<head>

	<title>Controle Eventos</title>

	<meta charset="UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1">

<!--===============================================================================================-->	

	<link rel="icon" type="image/png" href="images/logo_azul_1DG_icon.ico"/>

<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">

<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">

<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">

<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">

<!--===============================================================================================-->	

	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">

<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">

<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">

<!--===============================================================================================-->	

	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">

<!--===============================================================================================-->

	<link rel="stylesheet" type="text/css" href="css/util.css">

	<link rel="stylesheet" type="text/css" href="css/main.css">

<!--===============================================================================================-->

</head>

<body style="background-color: #666666;">

	

	<div class="limiter">

		<div class="container-login100">

			<div class="wrap-login100">

				<form class="login100-form validate-form" action="valida.php" method="post">

                    <center><spam><img style="margin-top:-30px" src="images/logo_azul_1DG_icon.ico"></spam></center>

					<span class="login100-form-title p-b-43">

						Controle Eventos

					</span>

					<p class="text-center text-danger">

					    <?php if(isset($_SESSION['loginSucesso'])){

                        echo $_SESSION['loginSucesso'];

                        unset($_SESSION['loginSucesso']);

                    }?>

					    <?php if(isset($_SESSION['loginErro'])){

                        echo $_SESSION['loginErro'];

                        unset($_SESSION['loginErro']);

                    }?>

					</p>

					<p class="text-center text-success">

					    <?php 

                    if(isset($_SESSION['logindeslogado'])){

                    echo $_SESSION['logindeslogado'];

                    unset($_SESSION['logindeslogado']);

                    }

                    ?>

					</p>

					<div class="wrap-input100 validate-input" data-validate = "Informe um usuário cadastrado">

						<input class="input100" type="email" name="email" id="email">

						<span class="focus-input100"></span>

						<span class="label-input100">Email</span>

					</div>

					

					

					<div class="wrap-input100 validate-input" data-validate="Informe a Senha">

						<input class="input100" type="password" name="senha" id="senha">

						<span class="focus-input100"></span>

						<span class="label-input100">Senha</span>

					</div>



					
					<div class="flex-sb-m w-full p-t-3 p-b-32">

						<div class="contact100-form-checkbox">

							<!--
							<input class="input-checkbox100" id="remember" type="checkbox" name="remember">

							<label class="label-checkbox100" for="remember">

								Remember me

							</label>
							-->

					</div>
					


						<div>

                            <a href="mailto:emerjsite@tjrj.jus.br">

								Esqueceu a senha?

							</a>

						</div>

					</div>

			



					<div class="container-login100-form-btn">

						<button class="login100-form-btn">

							Login

						</button>

					</div>

					

					<div class="text-center p-t-46 p-b-20">

						<span class="txt2">

							Versão Beta

						</span>

					</div>

				</form>

				<div class="login100-more" style="background-image: url('images/emerj.jpg');">

				</div>

			</div>

		</div>

	</div>
	

<!--===============================================================================================-->

	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>

<!--===============================================================================================-->

	<script src="vendor/animsition/js/animsition.min.js"></script>

<!--===============================================================================================-->

	<script src="vendor/bootstrap/js/popper.js"></script>

	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<!--===============================================================================================-->

	<script src="vendor/select2/select2.min.js"></script>

<!--===============================================================================================-->

	<script src="vendor/daterangepicker/moment.min.js"></script>

	<script src="vendor/daterangepicker/daterangepicker.js"></script>

<!--===============================================================================================-->

	<script src="vendor/countdowntime/countdowntime.js"></script>

<!--===============================================================================================-->

	<script src="js/main.js"></script>



</body>

</html>