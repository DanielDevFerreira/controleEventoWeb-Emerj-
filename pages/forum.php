<?php
require_once('../conexao2.php');
//require_once('recursos/enviaMail.php');

session_start();

header("Content-Type: text/html; charset=ISO-8859-1", true);

$acao = NULL;
$vagas = true;
$prazo = true;


header("Content-Type: text/html; charset=ISO-8859-1", true);

//mysql_select_db($dbname, $conn);

if ((isset($_GET["codEvento"])) && !(isset($_POST['hdAcao']))) {

	$query_rsBuscaVagas = ("SELECT evento.vagas, evento.nome, MIN(porta.data) AS data " .
							"FROM evento, porta " .
							"WHERE ((evento.codigo = porta.codEvento) " .
									"AND evento.codigo = " . $_GET["codEvento"] . ") " .
							"GROUP BY evento.codigo");
	$rsBuscaVagas = mysql_query($query_rsBuscaVagas, $evento) or die(mysql_error());
	$row_rsBuscaVagas = mysql_fetch_assoc($rsBuscaVagas);

	if (mysql_num_rows($rsBuscaVagas) > 0) {
		if (($row_rsBuscaVagas["vagas"] > 0) && ($row_rsBuscaVagas["data"] > date("Y-m-d"))) {
			$_SESSION["codEvento"] = $_GET["codEvento"];
			$_SESSION["nomeEvento"] = $row_rsBuscaVagas["nome"];
		}
	}

	if ((mysql_num_rows($rsBuscaVagas) <= 0) || ($row_rsBuscaVagas["vagas"] <= 0) || ($row_rsBuscaVagas["data"] <= date("Y-m-d"))) {
		$vagas = false;
		$prazo = false;
	}
}

/* ---------------------------------------------------------------------
   Ao pôr o CPF e clicar em "Inscrever" ou  em "Esqueci a senha"
--------------------------------------------------------------------- */
if ((isset($_POST["hdAcao"])) && (($_POST["hdAcao"] == "buscar") || ($_POST["hdAcao"] == "enviarSenha"))) {
	$query_rsBusca = 	"SELECT codigo, nome, cpf, email, senha, ativo " .
						"FROM participante " .
						"WHERE cpf = " . str_replace("-","",(str_replace(".","",$_POST["txtMascaraCpf"])));
	$rsBusca = mysql_query($query_rsBusca, $evento) or die(mysql_error());
	$row_rsBusca = mysql_fetch_assoc($rsBusca);
	$totalRows_rsBusca = mysql_num_rows($rsBusca);

	$_SESSION["codParticipante"] = $row_rsBusca["codigo"];
	$_SESSION["nome"] = $row_rsBusca["nome"];
	$_SESSION["cpf"] = str_replace("-","",(str_replace(".","",$_POST["txtMascaraCpf"])));
	$_SESSION["email"] = $row_rsBusca["email"];
	$_SESSION["codAtivacao"] = $row_rsBusca["senha"];
	$_SESSION["ativo"] = $row_rsBusca["ativo"];

	// Não cadastrado
	if ($totalRows_rsBusca == 0) {
		$acao = "naoEncontrado";
	}

	if ($totalRows_rsBusca == 1) {
		if ($_POST["hdAcao"] == "buscar") {
			$acao = "inscrever";
		}
		if ($_POST["hdAcao"] == "enviarSenha") {
			novaSenha();
			$acao = "novaSenha";
		}
	}
}

/* ---------------------------------------------------------------------
   Ao clicar em "clique aqui" para se cadastrar
--------------------------------------------------------------------- */
if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "cadastrar")) {
	$query_rsBusca = 	"SELECT count(codigo) as num " .
						"FROM participante ".
						"WHERE cpf = " . str_replace("-","",(str_replace(".","",$_POST["txtMascaraCpf"])));
	$rsBusca = mysql_query($query_rsBusca, $evento) or die(mysql_error());
	$row_rsBusca = mysql_fetch_assoc($rsBusca);

	if ($row_rsBusca["num"] == 0) {
		$_SESSION["fazer"] = "cadastrar";
		$_SESSION["cpf"] = str_replace("-","",(str_replace(".","",$_POST["txtMascaraCpf"])));
		$insertGoTo = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"])
										. "/participante.php";
		header(sprintf("Location: %s", $insertGoTo));
	} else {
		echo("<script language='javascript'>alert('Usu\xE1rio j\xE1 cadastrado')</script>");
		echo("<p>Usu\xE1rio j\xE1 cadastrado.</p>" .
				 "<hr><a href='inscricao.php'>voltar</a>");
	}
}

/* ---------------------------------------------------------------------
   Ao por a senha e clicar em inscrever e já estiver cadastrado
--------------------------------------------------------------------- */
if ($acao == "inscrever") {

	// Verifica se o cadastro está inativo
	if ($_SESSION["ativo"] == 0) {
		$acao = "ativar";
		enviaEmail($_SESSION["email"], "Ativa\xE7\xE3o de cadastro", "email/cad.txt", $_SESSION["nome"] . "-" . $_SESSION["codParticipante"] . "-" . $_SESSION["codAtivacao"]);

	// Se tiver ativo
	} else {
		// verifica a senha
		if  ((isset($_POST["txtSenha"])) && ($_SESSION["codAtivacao"] != strtoupper(md5($_POST["txtSenha"])))) {
			$acao = "senha";
		} else {
		// inscreve
			$acao = "tudocorreto";
		}
	}

	// Se estiver tudo correto
	if ($acao == "tudocorreto") {
		$updateSQL = ("UPDATE evento SET vagas = (vagas - 1) WHERE codigo = " . $_GET["codEvento"]);
		$insertSQL = sprintf("INSERT INTO inscricoes (codEvento, codParticipante) VALUES (%s, %s)", $_GET["codEvento"], $_SESSION["codParticipante"]);

		mysql_query("BEGIN", $evento);
		$r1 = mysql_query($updateSQL);
		$r2 = mysql_query($insertSQL);
		if (!($r1)) {
			mysql_query("ROLLBACK", $evento);
			echo("<p>Vagas esgotadas para o evento selecionado.</p>" .
				 "<hr><a href='#fechar' onClick='self.close()'>voltar</a>");
		}
		if (!($r2)) {
			mysql_query("ROLLBACK", $evento);
			$acao = "jaexistente";
		}
		if (($r1) && ($r2)) {
			mysql_query("COMMIT", $evento);
			enviaEmail($_SESSION["email"], "Inscri\xE7\xE3o realizada", "email/insc.txt", $_SESSION["nome"] . "-" . $_SESSION["codParticipante"] . "-" . $_SESSION["codAtivacao"] . "-" . $_SESSION["codEvento"] . "-" . $_SESSION["nomeEvento"]);
			$insertGoTo = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"])
									. "/confirma.php";
			header(sprintf("Location: %s", $insertGoTo));
		}
	}
}
?>

<script language="javascript">
	function buscaParticipante() {
		if (validaCPF()) {
			document.form1.hdAcao.value = "buscar";
			document.form1.submit();
		} else {
			alert("O campo CPF não foi preenchido corretamente.");
		}
	}

	function senhaParticipante() {
		if (validaCPF()) {
			document.form1.hdAcao.value = "enviarSenha";
			document.form1.submit();
		} else {
			alert("O campo CPF está incorreto.");
		}
	}

	function validaForm() {
		if (document.form1.txtSenha.value != "") {
			document.form1.submit();
		} else {
			alert("O campo SENHA deve ser preenchido corretamente\n");
		}
	}

	function alterarDados() {
		if (document.form1.txtSenha.value != "") {
			document.form1.hdAcao.value = "alterar";
			document.form1.submit();
		} else {
			alert("O campo SENHA deve ser preenchido corretamente\n");
		}
    }

	function cadastrarDados() {
		if (validaCPF()){
			document.form1.hdAcao.value = "cadastrar";
			document.form1.submit();
		} else {
			alert("O campo CPF está incorreto.");
		}
	}
</script>

<link href="css/default_eventos.css" rel="stylesheet" type="text/css" />
<link href="css/estilomenuvertical.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="recursos/validador.js"></script>

<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/controledeeventos.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<title>.: ESCOLA DA MAGISTRATURA DO ESTADO DO RIO DE JANEIRO - EMERJ :.</title>

<meta charset="utf-8">
<!--<meta http-equiv="X-UA-Compatible" content="IE=edge">-->
<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">

<link href="layout/styles/main.css" rel="stylesheet" type="text/css" media="all">
<link href="layout/styles/mediaqueries.css" rel="stylesheet" type="text/css" media="all">
<link href="css/novolayout_responsivo.css" rel="stylesheet" type="text/css" media="all">
<link href="css/header_responsivo.css" rel="stylesheet" type="text/css" media="all">
<link rel="stylesheet" type="text/css" href="layout/styles/print.css" media="print" />

<!--[if lt IE 9]>
<link href="layout/styles/ie/ie8.css" rel="stylesheet" type="text/css" media="all">
<script src="layout/scripts/ie/css3-mediaqueries.min.js"></script>
<script src="layout/scripts/ie/html5shiv.min.js"></script>
<![endif]-->

<!--[if IE]><link rel="shortcut icon" href="img/favicon.ico"><![endif]-->
<link rel="icon" href="favicon.ico" />

<style type="text/css">
@font-face {
     font-family:"Titillium Web";
     src:url(../fonte/TitilliumWeb-Regular.ttf);
	 font-weight:normal;
	 font-style:normal;
}

body {
    font-family:"Titillium Web";
	font-weight:normal;
	font-style:normal;
	font-size: 15px;
}
</style>

<style>
/* The Modal (background) */
.modal {
    display: block; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 20%; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    /*background-color: rgb(0,0,0);*/ /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>

<script>

var myWindow;

function openWin() {
    myWindow = window.open("", "myWindow", "width=400, height=200");
}

function closeWin() {
    modal.style.display = "none";
}

function checkWin() {
    if (!myWindow) {
        document.getElementById("msg").innerHTML = "'myWindow' has never been opened!";
    } else {
        if (myWindow.closed) {
            document.getElementById("msg").innerHTML = "'myWindow' has been closed!";
        } else {
            document.getElementById("msg").innerHTML = "'myWindow' has not been closed!";
        }
    }
}

</script>

</head>
<body class="">
<div class="wrapper row1">
    <div class="artdirection" title="Escola da Magistratura do Estado do Rio de Janeiro" align="center" id="centro">
    <a href="http://www.emerj.tjrj.jus.br/index.html"><div align="center"><span style="position: relative; margin-right: 55%; top: 45px;"><img src="images/logo_emerj_branco.png"></span></div></a>
  <header id="header" class="full_width clear">
    <div id="hgroup">
    </div>
  </header>
</div>
<!-- ################################################################################################ -->
<div class="wrapper row2">
  <nav id="topnav">
    <ul class="clear">
      <!--<li class="active"><a href="index.html" title="Principal">Homepage</a></li>-->
      <li><a class="drop" href="#" title="A ESCOLA">A ESCOLA</a>
        <ul>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/aescola/historia.html" title="Hist&oacute;ria">Hist&oacute;ria</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/aescola/visao-missao-valores.html" title="Miss&atilde;o, Vis&atilde;o e Valores">Miss&atilde;o, Vis&atilde;o e Valores</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/aescola/programa-de-integridade-da-EMERJ/programa-de-integridade-da-EMERJ.html" title="Programa de Integridade da EMERJ">Programa de Integridade </br> da EMERJ</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/aescola/diretoria.html" title="Diretoria">Diretoria</a></li>
           <li><a href="http://www.emerj.tjrj.jus.br/paginas/aescola/comissoes.html" title="Comiss&otilde;es">Comiss&otilde;es</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/aescola/administracao.html" title="Administra&ccedil;&atilde;o">Administra&ccedil;&atilde;o</a></li>
           <li><a href="http://www.emerj.tjrj.jus.br/paginas/aescola/nucleos/nucleos.htm" title="N&uacute;cleos de Representa&ccedil;&atilde;o">N&uacute;cleos de Representa&ccedil;&atilde;o</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/aescola/espacoemerj/espacoemerj.htm" title="Espa&ccedil;os EMERJ">Espa&ccedil;os EMERJ</a></li>
         <li><a href="http://www.emerj.tjrj.jus.br/paginas/aescola/regimento/Regimento_Interno_Publicacao_210817.pdf" target="_blank" title="Regimento Interno">Regimento Interno</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/iso9001/iso.htm" title="Certifica&ccedil;&atilde;o NBR ISO 9001:2015">Certifica&ccedil;&atilde;o NBR ISO 9001:2015</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/aescola/localizacao.html" title="Localiza&ccedil;&atilde;o">Localiza&ccedil;&atilde;o</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/aescola/contatos/contatos.htm" title="Contatos">Contatos</a></li>
        </ul>
      </li>
      <li><a class="drop" href="#" title="CURSOS">CURSOS</a>
        <ul>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/cursos/curso_especializacao/cursoespecializacaoemdireito.html" title="Curso de Especializa&ccedil;&atilde;o em </br> Direito P&uacute;blico e Privado">Curso de Especializa&ccedil;&atilde;o em </br> Direito P&uacute;blico e Privado<br></a></li>
            <li><a href="http://www.emerj.tjrj.jus.br/paginas/cursos/curso_especializacao/cursodeespecializacaointro.html" title="Curso de Especializa&ccedil;&atilde;o nas </br> &Aacute;eas do Direito </br> P&oacute;s-Gradua&ccedil;&atilde;o <i>Lato Sensu</i>">Curso de Especializa&ccedil;&atilde;o nas </br> &Aacute;reas do Direito </br> P&oacute;s-Gradua&ccedil;&atilde;o <i>Lato Sensu</i></a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/cursos/premerj/premerj.html" title="PREMERJ">PREMERJ</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/cursos/cursodeextensao/cursodeextensaoemerj.html" title="Cursos de Extens&atilde;o">Cursos de Extens&atilde;o</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/magistrados/magistrados.html" title="Cursos Oficiais para Magistrados">Cursos Oficiais para<br>Magistrados</a></li>
            <li><a href="http://www.emerj.tjrj.jus.br/cursos_livres/direito_eletronico/curso_livre_emerj.html" title="Cursos Livres" target="_blank">Cursos Livres</a></li>
            <li><a href="http://www.emerj.tjrj.jus.br/paginas/cursos/curso_mediadoresjudiciais/2017/curso_mediadoresjudiciais.html" title="Curso Forma&ccedil;&atilde;o de Mediadores Judiciais">Curso de Forma&ccedil;&atilde;o de<br>Mediadores Judiciais</a></li>
        </ul>
      </li>
      <li><a class="drop" href="#" title="BIBLIOTECA">BIBLIOTECA</a>
        <ul>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/biblioteca_videoteca/principal_biblioteca.html" title="Biblioteca e Videoteca">Biblioteca e Videoteca</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/biblioteca_videoteca/normasbiblioteca.htm" title="Normas de Atendimento">Normas de Atendimento</a></li>
          <li><a href="http://emerj.bnweb.org/scripts/bnportal/bnportal.exe/index#acao=geral&uv=vbibltip1:tipos:descricao;vbibluni0:unidades:nome_unidade;vbiblidi0:idiomas:nome;vbiblaco0:areas:nome&alias=geral&xsl=consulta" title="Consulta on-line ao acervo" target="_blank">Consulta on-line ao acervo</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/biblioteca_videoteca/monografiadosalunos.htm" title="Monografias dos Alunos">Monografias dos Alunos</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/magistrados/paginas/pesquisasparamagistrados/pesquisas-para-magistrados.html" title="Pesquisas para Magistrados">Pesquisas para Magistrados</a></li>
        </ul>
      </li>
      <li><a class="drop" href="#" title="CONCURSO">CONCURSO</a>
        <ul>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/concursos/concursosprovas_magistratura.htm" title="Para Ingresso na EMERJ">Para Ingresso na EMERJ</a></li>
        </ul>
      </li>
      <li><a class="drop" href="#" title="Licita&ccedil;&otilde;es">LICITA&Ccedil;&Otilde;ES</a>
        <ul>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/licitacao/licitacao_comunicado.html" title="Comunicado">Comunicado</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/licitacao/licitacoesnovas.htm" title="Licita&ccedil;&otilde;es Novas">Licita&ccedil;&otilde;es Novas</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/licitacao/licitacoeshomologadas_ano.html" title="Licita&ccedil;&otilde;es Homologadas">Licita&ccedil;&otilde;es Homologadas</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/licitacao/licitacoesdesertas.htm" title="Licita&ccedil;&otilde;es Desertas">Licita&ccedil;&otilde;es Desertas</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/licitacao/licitacoesrevogadas.htm" title="Licita&ccedil;&otilde;es Revogadas">Licita&ccedil;&otilde;es Revogadas</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/licitacao/licitacoesfracassadas.htm" title="Licita&ccedil;&otilde;es Fracassadas">Licita&ccedil;&otilde;es Fracassadas</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/licitacao/licitacaoespenalidades.html" title="Penalidades">Penalidades</a></li>
        </ul>
      </li>
      <li><a class="drop" href="#" title="F&Oacute;RUM PERMANENTE">F&Oacute;RUM PERMANENTE</a>
        <ul>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/forunspermanentes/forunspermanentes.htm" title="Objetivo">Objetivo</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/forunspermanentes/areasdodireito.htm" title="25 &Aacute;reas do Direito">25 &Aacute;reas do Direito</a></li>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/forunspermanentes/regulamentacaoforumpermanente.htm" title="Regulamenta&ccedil;&atilde;o">Regulamenta&ccedil;&atilde;o</a></li>
        </ul>
      </li>
      <li><a href="http://www.emerj.tjrj.jus.br/faleconosco.html" title="FALE CONOSCO">FALE CONOSCO</a></li>
      <li><a href="http://www.emerj.tjrj.jus.br/paginas/perguntas_frequentes/perguntas-frequentes.html" title="PERGUNTAS FREQUENTES">PERGUNTAS FREQUENTES</a></li>
    </ul>
  </nav>
</div>
<!-- content -->
<div class="wrapper row3">
  <div id="container">
    <!-- ################################################################################################ -->
    <div id="sidebar_1" class="sidebar one_quarter first" align="left">
      <aside>
        <!-- ########################################################################################## -->
        <h2 align="left" style="font-family:Titillium Web;font-size:20px;font-weight: bold;">Eventos EMERJ Gratuitos</h2>
        <div align="left">
        <nav>
          <ul>
          <li><a href="http://www.emerj.tjrj.jus.br/paginas/eventos/eventos_emerj_gratuitos.html">P&aacute;gina Principal</a></li>
            <li><a href="http://emerj.com.br/evento/cadastro.php">Cadastro de Participantes</a></li>
            <li><a href="http://emerj.com.br/evento/alteracadastro.php">Altera&ccedil;&atilde;o de Cadastro</a></li>
            <li><a href="http://emerj.com.br/evento/esqueceusenha.php">Esqueceu sua Senha?</a></li>
            <li><a href="http://emerj.com.br/evento/cancelainscricao.php">Cancelamento de Inscri&ccedil;&atilde;o</a></li>
            <li><a href="http://emerj.com.br/evento/reimpressaoinscricao.php">2&ordf; via de Inscri&ccedil;&atilde;o</a></li>
            <li><a href="http://emerj.com.br/evento/certificado/certificado.php">Certificado On-Line</a></li>
            <li><a href="http://emerj.com.br/evento/imprimecomprovacaodehoras.php">Comprova&ccedil;&atilde;o de Horas</a></li>
            <li><a href="http://www.emerj.tjrj.jus.br/paginas/eventos/regras_inscricoesonline.html">Regras e Inscri&ccedil;&otilde;es On-Line - Presencial</a></li>
            <li><a href="http://www.emerj.tjrj.jus.br/paginas/eventos/regras_inscricoesonline_webinar.html">Regras e Inscri&ccedil;&otilde;es On-Line - Webinar</a></li>
            <li><a href="http://www.emerj.tjrj.jus.br/paginas/eventos/duvidaseperguntas.html">D&uacute;vidas e Perguntas Frequentes - Presencial</a></li>
            <li><a href="http://www.emerj.tjrj.jus.br/paginas/eventos/politicaprivacidade.html">Pol&iacute;tica de Privacidade</a></li>
          </ul>
        </nav>
        </div>
        <!-- /nav -->
       <section>
          <h2 align="left" style="font-family:Titillium Web;font-size:20px;font-weight: bold;">Participantes</h2>
          <div align="justify">
            <address>
            <p align="justify">Programa&ccedil;&atilde;o dos eventos dispon&iacute;veis para as&nbsp;<strong>inscri&ccedil;&otilde;es&nbsp;<em>on-line</em></strong>; as inscri&ccedil;&otilde;es ser&atilde;o encerradas &agrave;s 0h:00min do dia do evento.</p>
            <br>
            <p align="justify">Na primeira inscri&ccedil;&atilde;o, ser&aacute; necess&aacute;rio preencher o&nbsp;<strong>Cadastro de Participante</strong>, com dados do usu&aacute;rio, senha e um e-mail v&aacute;lido, para o qual ser&aacute; enviada uma mensagem para&nbsp;<strong>ativa&ccedil;&atilde;o e valida&ccedil;&atilde;o do cadastro</strong>.</p>
            <br>
            <p align="justify">Somente ap&oacute;s a&nbsp;<strong>valida&ccedil;&atilde;o do cadastro</strong>&nbsp;o participante poder&aacute; realizar&nbsp;<strong>inscri&ccedil;&otilde;es&nbsp;<em>on-line</em></strong>&nbsp;nos eventos da Escola.</p>
            <br>
            <p align="justify">Para realizar inscri&ccedil;&otilde;es, o participante deve clicar no &iacute;cone do evento selecionado e, em seguida, no &iacute;cone&nbsp;"<strong>Inscreva-se</strong>"&nbsp;e digitar os dados solicitados.</p>
            <br>
            <p align="justify">Ap&oacute;s a efetiva&ccedil;&atilde;o da inscri&ccedil;&atilde;o, ser&aacute; emitido o&nbsp;<strong>Comprovante de Inscri&ccedil;&atilde;o</strong>, com c&oacute;digo de barras, que dever&aacute; ser impresso pelo participante e apresentado no evento.</p>
            </address>
          </div>
        </section>
         <section>
          <h2 align="left" style="font-family:Titillium Web;font-size:20px;font-weight: bold;">Contato</h2>
          <div align="justify">
            <address>
              Qualquer problema, sugest&atilde;o ou dificuldade na realiza&ccedil;&atilde;o da inscri&ccedil;&atilde;o, enviar&nbsp;<em>e-mail</em>&nbsp;para <a href="mailto:emerjsite@tjrj.jus.br" class="linkpadrao">emerjsite@tjrj.jus.br</a>, nos dias &uacute;teis, das 10 &agrave;s 18 hs, relatando o problema e informando seu nome completo e CPF.
            </address>
          </div>
        </section>
        <!-- /section -->
        <!-- /section -->
        <!-- ########################################################################################## -->
      </aside>
    </div>
    <!-- ################################################################################################ -->
    <div id="portfolio" class="three_quarter"><!-- InstanceBeginEditable name="conteudo_eventos" -->

<!-- <div style="display:none;"><button id="myBtn">Open modal</button></div>

<div id="myModal" class="modal">

  <div class="modal-content">
    <span class="close">&times;</span> -->
    <!-- Aquele que deixar de comparecer ao evento sem pr&eacute;vio cancelamento da inscri&ccedil;&atilde;o estar&aacute; impedido de inscrever-se em eventos futuros pelo <strong>per&iacute;odo de 10 dias</strong>. -->

    <!-- <p style="font-size:18px; font-family:'Titillium Web'"><strong>Cancelamento de Inscri&ccedil;&atilde;o em Eventos EMERJ</strong> <br><br> O cancelamento da inscri&ccedil;&atilde;o poder&aacute; ser realizado no site da EMERJ at&eacute; 23h59min do dia que antecede o evento.<br>
    <br>Leia mais: <span style="font-size:18px; font-family:'Titillium Web'"><a href="http://www.emerj.tjrj.jus.br/paginas/eventos/regras_inscricoesonline.html" target="_blank"  style="font-size:18px; font-family:'Titillium Web'">Regras e Inscri&ccedil;&otilde;es On-Line</a> e <a href="http://www.emerj.tjrj.jus.br/paginas/eventos/duvidaseperguntas.html" target="_blank"  style="font-size:18px; font-family:'Titillium Web'">D&uacute;vidas e Perguntas Frequentes</a></span>.</p><br> -->

    <!-- <p style="font-size:18px; font-family:'Titillium Web'"><strong>Semin&aacute;rio "A MAGISTRATURA QUE QUEREMOS"</strong> <br><br>
    Acontece no pr&oacute;ximo dia  17 de junho, &agrave;s 10h, no audit&oacute;rio Antonio Carlos Amorim - Rua Dom Manoel, s/n &ndash; L&acirc;mina I, 4&ordm; andar, Centro/RJ o Semin&aacute;rio &rdquo;A MAGISTRATURA QUE QUEREMOS&rdquo;, evento de grande relev&acirc;ncia para a magistratura nacional e para os profissionais do Direito em geral.
<br><br>
Debater sobre os problemas, as necessidades e os anseios da Magistratura do Brasil &eacute; fundamental para a busca de rumos e mecanismos de aperfei&ccedil;oamento do Poder Judici&aacute;rio e da presta&ccedil;&atilde;o jurisdicional.
<br><br>
Vagas Limitadas! <a href="http://www.emerj.tjrj.jus.br/paginas/eventos/eventos2019/a-magistratura-que-queremos.html" style="font-size:18px; font-family:'Titillium Web'">Saiba mais</a>.</p>
 <br>

<button onclick="closeWin()">&nbsp;Fechar&nbsp;</button> -->
  <!-- </div>

</div> -->

<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<div id="content">
<!-- end #sidebar -->
	<div id="main">
<div id="welcome" class="post">
        <p class="row5"><strong><span style="font-size:20px">INSCRI&Ccedil;&Atilde;O ON-LINE PARA EVENTO</span></strong></p>
		<br>
        <div class="story">
          <div align="center">
            <span style="font-weight:bold; font-size:20px;">
            <? // verifica se o evento selecionado tem vagas e está no prazo

if (($vagas) && ($prazo)) { ?>
<?
 header("Content-Type: text/html; charset=ISO-8859-1", true);
 echo($_SESSION["nomeEvento"]) ?> </span></p>

		<form method="post" name="form1" action="inscricao.php?codEvento=<? echo($_SESSION["codEvento"]) ?>">

		<?
	if (empty($_POST["hdAcao"])) { ?></span><br />
			<table align="center" class="tabelanew_eventos">
				<tr valign="baseline">
				  <td colspan="3" align="right" nowrap="nowrap" class="textoT&iacute;tulo"><div align="center">A inscri&ccedil;&atilde;o s&oacute; poder&aacute; ser feita por usu&aacute;rio com cadastro ativo no site da EMERJ. <br />
		         O comprovante de inscri&ccedil;&atilde;o dever&aacute; ser impresso e apresentado no evento.</div></td>
			  </tr>
				<tr valign="baseline">
				  <td colspan="3" align="right" nowrap="nowrap" class="textoT&iacute;tulo">&nbsp;</td>
			  </tr>
				<tr valign="baseline">
				  <td colspan="3" align="right" nowrap="nowrap" class="textoT&iacute;tulo"><div align="center">Digite  seu CPF e senha. Em seguida, clique em Inscrever.</div></td>
			  </tr>
				<tr valign="baseline">
				  <td align="right" nowrap="nowrap" class="textoT&iacute;tulo">&nbsp;</td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
				<tr valign="baseline">
				  <td width="176" align="right" nowrap="nowrap" class="textoT&iacute;tulo">CPF:</td>
				  <td width="221"><input name="txtMascaraCpf" type="text" class="textoNormal" onKeyPress="mascara_cpf(event, this.value);" size="31" maxlength="14"/></td>
				  <td>&nbsp;</td>
			  </tr>
				<tr valign="baseline">
				  <td align="right" nowrap="nowrap" class="textoT&iacute;tulo">Senha:</td>
				  <td><input name="txtSenha" type="password" class="textoNormal" id="txtSenha" size="31" maxlength="10" /></td>
				  <td>&nbsp;</td>
			  </tr>
			  <tr valign="baseline">
				  <td colspan="3" align="center" nowrap="nowrap"><div align="center" class="style38"><a href="http://emerj.com.br/evento/esqueceusenha.php" target="_blank">Esqueceu sua Senha?</a></div></td>
			  </tr>
				<tr valign="baseline">
				  <td align="right" nowrap="nowrap" class="textoT&iacute;tulo">&nbsp;</td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
				<tr valign="baseline">
				  <td colspan="3" align="center" nowrap="nowrap"><div align="center" class="style38">A EMERJ filmar&aacute; todo o evento. Ficam os participantes cientes de que as   grava&ccedil;&otilde;es e fotografias<br />
			        poder&atilde;o ser utilizadas para divulga&ccedil;&atilde;o e fins   institucionais, inclusive, nos cursos a dist&acirc;ncia, bem<br />
			        como a disponibiliza&ccedil;&atilde;o do material na p&aacute;gina da EMERJ na internet.</div></td>
			  </tr>
				<tr valign="baseline">
				  <td align="right" nowrap="nowrap" class="textoT&iacute;tulo">&nbsp;</td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
				<tr valign="baseline">
	    	        <td align="right" nowrap="nowrap" class="textoT&iacute;tulo">&nbsp;</td>
	        	    <td><div align="center"><span class="style1">
	        	      <input name="btInscrever" type="button" id="btInscrever" class="button_eventos" onClick="buscaParticipante();" value="Inscrever" />
        	        </span></div></td>
	            	<td width="177">&nbsp;</td>
			  </tr>
				<tr valign="baseline">
				  <td colspan="3" align="right" nowrap="nowrap" class="textoT&iacute;tulo">&nbsp;</td>
			  </tr>
				<tr valign="baseline">
				  <td colspan="3" align="right" nowrap="nowrap" class="textoT&iacute;tulo"><div align="center"></div></td>
			  </tr>
				<tr valign="baseline">
				  <td colspan="3" align="right" nowrap="nowrap" class="style2 style8">&nbsp;</td>
			  </tr>
			</table>


          <div align="center" class="style8">
		  <?
	}
	if ($acao == "ativar") {
		echo ("<script language='javascript'>alert('Seu cadastro não foi ativado.');</script>
			<p><b>Seu cadastro não foi ativado.</b>
			<br /> Foi reenviado um e-mail informando como ativá-lo.<br />
			   Verifique dentro de alguns instantes e siga as instruções para ativá-lo.<br />
			   Caso não receba o e-mail em 15 minutos, ou possua filtro anti-spam do UOL (ou 		               similar), que retardam o recebimento em até 24 horas, verifique:<br>
		       1º) O e-mail que digitou em seu cadastro, através do link Alteração de Cadastro no               site da EMERJ; <br>
			   2º) As configurações de sua caixa de entrada e desabilite filtros de spam (lixo               eletrônico/bulk); <br>
		       3º) Caso o problema persista, entre em contato conosco: emerjsite@tjrj.jus.br, nos               dias úteis, das 10 às 18 hs, relatando o problema e informando seu nome completo e               CPF.<br>
		<hr><a href='#fechar' onClick='self.close()'>voltar</a>");
	}
	if ($acao == "senha") {
		echo("<br><br><p><center>A senha est&aacute; incorreta.</center></p>
		<hr><a href='inscricaoforum.php?codEvento=" . $_GET["codEvento"] . "'>voltar</a>");
	}
	if ($acao == "jaexistente") {
		echo("<br><br><p><center>Inscri&ccedil;&atilde;o j&aacute; existente!<br>Para reimprimir a 2&ordf; via, clique no link <a href='reimpressaoinscricao.php'>Reimprimir 2&ordf; via</center></a><br>		
                <hr><a href='inscricaoforum.php?codEvento=" . $_GET["codEvento"] . "'>voltar</a>");
	}
	if ($acao == "naoEncontrado") {
		echo("<br><br><p><p><center>Usuário não encontrado.<br />
		É necessário realizar o cadastramento primeiro.</center></p>
		<hr><a href='inscricaoforum.php?codEvento=" . $_GET["codEvento"] . "'>voltar</a>");
	}
	if ($acao == "novaSenha") {
		echo("<p> Foi enviado um e-mail com as instru&ccedil;&otilde;es de como definir uma nova senha. <br />
		Verifique seu e-mail dentro de alguns instantes e siga os procedimentos.<br />
		Caso não receba o e-mail nos próximos 15 minutos, verifique as configurações de sua caixa de entrada e desabilite filtros de spam (lixo eletr&ocirc;nico/bulk) para o endereço emerjsite@tjrj.jus.br<br />
		Se possuir filtro anti-spam do UOL (ou similar), seu e-mail será enviado em até 24 horas úteis.</p>
		<hr><a href='#fechar' onClick='self.close()'>voltar</a>");
	} ?>
		  <input name="hdAcao" type="hidden" id="hdAcao" value="<? if (isset($_POST["hdAcao"])) echo ($_POST["hdAcao"]); ?>"/>
          </div>
</form>
        <span class="style8">
        <?

} else {
	echo("<div align='center'><br><br>N&atilde;o h&aacute; mais vagas dispon&iacute;veis para esse evento ou as inscri&ccedil;&otilde;es j&aacute; se encerraram.</div><br><br><hr>");
	echo("<div align='center'><br><br><a href='#fechar' onClick='self.close()'>voltar</a><br><br><br></div>");
	$ocultar = true;
} ?>
        </span>

          </div>
      </div>

      </div>
   </div>
<!-- end #main -->
</div>
<!-- end #sidebar2 -->
<!-- end #content -->
<br />
<!-- InstanceEndEditable -->



      <figcaption>
        <p>&nbsp;</p>
</figcaption>
    <!-- ####################################################################################################### --></div>
    <!-- ################################################################################################ -->
    <div class="clear"></div>
  </div>
</div>
<!-- Footer -->
<div class="wrapper row2">
  <div id="footer" class="clear">
    <!--<div class="redessociais">
        <a href="https://twitter.com/emerjoficial" target="_blank"><img src="../../images/redes_sociais/twitter.fw.png" /></a>
        <a href="https://www.youtube.com/user/EMERJeventos?feature=mhee" target="_blank"><img src="../../images/redes_sociais/youtube.fw.png" /></a>
        <a href="https://www.facebook.com/emerjoficial" target="_blank"><img src="../../images/redes_sociais/facebook.fw.png" /></a>
        <a href="https://www.instagram.com/emerjoficial/" target="_blank"><img src="../../images/redes_sociais/instagram.fw.png" /></a>
    </div>-->
  </div>
</div>
<!-- Footer -->
<div class="wrapper row4">
  <div id="copyright" class="clear">
    <p class="fl_right"><strong>Rua Dom Manuel, n&ordm; 25 - Centro - CEP 20010-090<br>
      (21) 3133-3369  / (21) 3133-3380</strong></p>
    <p><strong>ESCOLA DA MAGISTRATURA DO ESTADO DO RIO DE JANEIRO - EMERJ<br>
    <em><strong>Este site foi desenvolvido para ser melhor visualizado em resolu&ccedil;&atilde;o de 1920x1080 no Internet Explorer ou Google Chrome</strong></em></strong></p>
  </div>
</div>
<!-- Scripts -->
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script>window.jQuery || document.write('<script src="../layout/scripts/jquery-latest.min.js"><\/script>\
<script src="../layout/scripts/jquery-ui.min.js"><\/script>')</script>
<script>jQuery(document).ready(function($){ $('img').removeAttr('width height'); });</script>
<script src="layout/scripts/jquery-mobilemenu.min.js"></script>
<script src="layout/scripts/custom.js"></script>
</body>
<!-- InstanceEnd --></html>