<?php 
require_once('../../conexao.php');

    session_start();

    if (!isset($_SESSION['usuarioNome']) or !isset($_SESSION['usuarioId']) or !isset ($_SESSION['usuarioNiveisAcessoId']) or !isset($_SESSION['usuarioEmail'])){
	unset(
		$_SESSION['usuarioId'],
		$_SESSION['usuarioNome'],
		$_SESSION['usuarioNiveisAcessoId'],
		$_SESSION['usuarioEmail']
		
	);
	//redirecionar o usuario para a página de login
	header("Location: ../../login.php");
}

$nivelLogado = $_SESSION['usuarioNiveisAcessoId'];

header("Content-Type: text/html; charset=utf-8", true);

$acao = NULL;

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
	case "date":
		if ($theValue != "") {
		$theValue = explode("/", $theValue);
	   		$theValue = "'" . $theValue[2] . "-" . $theValue[1] . "-" . $theValue[0] . "'";
		} else 
			$theValue = "NULL";
		break;
    case "hour":
		if ($theValue != "") {
    		$theValue = "'" . $theValue . "'";
		} else 
			$theValue = "NULL";
		break;
  }
  return $theValue;
}

$editFormAction = $_SERVER["PHP_SELF"]; //action na própria página
if (isset($_SERVER["QUERY_STRING"])) {
  $editFormAction .= "?" . htmlentities($_SERVER["QUERY_STRING"]);
}

if (isset($_SESSION["codEvento"])) {
	$query_rsBusca = "SELECT evento.codigo, evento.nome, evento.vagas, evento.local, evento.endereco, porta.data, porta.horaInicio, porta.horaFim, porta.cargaHoraria, porta.tipo FROM evento INNER JOIN porta ON porta.codEvento = evento.codigo WHERE evento.codigo = " . $_SESSION["codEvento"];
	$rsBusca = mysqli_query($conn, $query_rsBusca) or die(mysqli_error($conn));
	$row_rsBusca = mysqli_fetch_assoc($rsBusca);
	$totalRows_rsBusca = mysqli_num_rows($rsBusca);
}

//inclui evento
if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "inserir")) {

  $insertSQL = sprintf("INSERT INTO evento (codigo, nome, vagas, local, endereco, tipoEvento1, tipoEvento2, tipoEvento3, foradasede) VALUES (%s, %s, %s, %s, %s, '%s', '%s', '%s', %s)",
					  GetSQLValueString($_POST["codigo"], "int"),
                      trim(GetSQLValueString($_POST["nome"], "text")),
                      trim(GetSQLValueString($_POST["vagas"], "int")),
                      trim(GetSQLValueString($_POST["local"], "text")),
					  trim(GetSQLValueString($_POST["endereco"], "text")),
                      $_POST["tipoEvento1"],	
                      $_POST["tipoEvento2"],
                      $_POST["tipoEvento3"], 
                      GetSQLValueString($_POST["foradasede"], "int")); /*novo campo*/

	
  	
	
		$Result1 = mysqli_query($conn, $insertSQL) or die(mysqli_error($conn));

	// separa as portas
	$portasJuntas = ($_POST["hdPortas"]);
		
	for ($i = 0; $i < (strlen($portasJuntas)/46); $i++) {
		$insertSQL = sprintf("INSERT INTO porta (codEvento, data, horaInicio, horaFim, horaFimReal, cargaHoraria, tipo) VALUES (%s, %s, %s, %s, '00:00', %s, %s)",
                       	GetSQLValueString($_POST["codigo"], "int"),
						GetSQLValueString(substr($portasJuntas, ($i*46), 10), "date"), 
						GetSQLValueString(substr($portasJuntas, ($i*46) + 15, 5), "hour"),
						GetSQLValueString(substr($portasJuntas, ($i*46) + 25, 5), "hour"),
						GetSQLValueString(substr($portasJuntas, ($i*46) + 35, 5), "hour"),								
						GetSQLValueString(substr($portasJuntas, ($i*46) + 45, 1), "int"));
		
        
  		$Result2 = mysqli_query($conn, $insertSQL) or die(mysqli_error($conn));
	}


if (($Result1) && ($Result2)) {
    echo "Evento cadastrado COM Horas OAB";
    mysqli_commit($conn);
	$acao = "inserido";
} elseif (($Result1) == ($Result2)) {
    echo "Evento cadastrado SEM Horas OAB '00:00'";
    mysqli_commit($conn);
	$acao = "oab";
} else {
	mysqli_rollback($conn);
    echo ("Não foi possível cadastrar o evento.");
	}
	mysqli_autocommit($conn, TRUE);
}



//altera evento
//$tipoEvento = $_POST["tipoEvento"];

if ((isset($_POST["hdAcao"])) && ($_POST["hdAcao"] == "alterar")) {
		if (isset($_POST["codEvento"]))
			$codEvento=$_POST["codEvento"];

		$updateSQL = sprintf("UPDATE evento SET codigo=%s, nome=%s, vagas=%s, local=%s, endereco=%s, tipoEvento1='%s', tipoEvento2='%s', tipoEvento3='%s' WHERE codigo=" . $_SESSION["codEvento"],
                       GetSQLValueString($_POST["codigo"], "int"),
                       GetSQLValueString($_POST["nome"], "text"),
                       GetSQLValueString($_POST["vagas"], "int"),
                       GetSQLValueString($_POST["local"], "text"),
					   GetSQLValueString($_POST["endereco"], "text"),
					   $_POST["tipoEvento1"],$_POST["tipoEvento2"],$_POST["tipoEvento3"]); /*novo campo*/

	
    mysqli_autocommit($conn, FALSE);
	mysqli_query("BEGIN", $conn);
	$Result1 = mysqli_query($conn, $updateSQL);
	$sql = sprintf("DELETE FROM porta WHERE codEvento = %s", GetSQLValueString($_POST["codigo"], "int"));
	$Result2 = mysqli_query($conn, $sql);

	$portasJuntas = ($_POST["hdPortas"]);
	for ($i = 0; $i < (strlen($portasJuntas)/46); $i++) {
		$insertSQL = sprintf("INSERT INTO porta (codEvento, data, horaInicio, horaFim, horaFimReal, cargaHoraria, tipo) VALUES (%s, %s, %s, %s, '00:00', %s, %s)",
                       	GetSQLValueString($_POST["codigo"], "int"),
						GetSQLValueString(substr($portasJuntas, ($i*46), 10), "date"), 
						GetSQLValueString(substr($portasJuntas, ($i*46) + 15, 5), "hour"),
						GetSQLValueString(substr($portasJuntas, ($i*46) + 25, 5), "hour"),
						GetSQLValueString(substr($portasJuntas, ($i*46) + 35, 5), "hour"),								
						GetSQLValueString(substr($portasJuntas, ($i*46) + 45, 1), "int"));
  		$Result3 = mysqli_query($conn, $insertSQL);
	}
	if (($Result1) && ($Result2) && ($Result3)) {
		mysqli_commit($conn);
		$acao = "atualizado";
	} else {
		mysqli_rollback($conn);
		echo("ERRO!!");
	}
	mysqli_autocommit($conn, TRUE);
}
?>
<script language="javascript">
 function IncluirPorta()
  {
    var oOption = document.createElement("OPTION");
    var num = document.form1.txtData.value + "     " + document.form1.txtHoraInicial.value + "     " + document.form1.txtHoraFinal.value + "     " + document.form1.txtCargaHoraria.value + "     " + document.form1.cboTipo.options[document.form1.cboTipo.selectedIndex].text;
    var num2 = document.form1.txtData.value + "     " + document.form1.txtHoraInicial.value + "     " + document.form1.txtHoraFinal.value + "     " + document.form1.txtCargaHoraria.value + "     " + document.form1.cboTipo.value;

	if ((document.form1.txtData.value != "") && (document.form1.txtHoraInicial.value != "") && (document.form1.txtHoraFinal.value != "") && (document.form1.txtCargaHoraria.value != "") && (document.form1.cboTipo.value != "")) {   
      document.form1.portas.options.add(oOption);
      oOption.innerText = num;
      oOption.value = num2;    
	  document.form1.txtData.value = "";
	  document.form1.txtHoraInicial.value = "";
	  document.form1.txtHoraFinal.value = ""; 
	  document.form1.txtCargaHoraria.value = "";
	  document.form1.cboTipo.value = "1";
    }    
  }
  
  function ExcluirPorta()
  {
    var i;
    for (i=0; i < document.form1.portas.options.length ; i++)    
    {      
      if (document.form1.portas.options[i].selected == true)
        document.form1.portas.options.remove(i);
    }
  }

  function validaCampos() 
  {
  	var ports="";
    //Pego todos os telefones incluidos pelo usuario e coloco num campo hidden
    for (i=0; i < document.form1.portas.options.length ; i++)
      ports = ports + document.form1.portas.options[i].value;    
    document.form1.hdPortas.value = ports;
	document.form1.submit();
  }
</script>

<script type="text/javascript">
var camposLocalEndereco = null;

function exibeCamposLocalEndereco(campo){

camposLocalEndereco = campo;

  if (camposLocalEndereco == 1){ 
  	document.getElementById("exibeLocalEvento").innerHTML = "<input name='local' type='text' class='textoNormal' id='local' size='100' maxlength='200' />";
 	document.getElementById("exibeEnderecoEvento").innerHTML = "<input name='endereco' type='text' class='textoNormal' id='endereco' size='100' maxlength='400' />";
  }
  else if (camposLocalEndereco == 0){  
  document.getElementById("exibeLocalEvento").innerHTML = "<select name='local' id='local' onchange='javascript:functionExibeEndereco(this.value);'><option value='' onClick='functionExibeEndereco(this.value)'></option><option value='AUDITÓRIO DES. PAULO ROBERTO LEITE VENTURA' onClick='functionExibeEndereco(this.value)'>AUDITÓRIO DES. PAULO ROBERTO LEITE VENTURA</option><option value='AUDITÓRIO DES. JOAQUIM ANTÔNIO DE VIZEU PENALVA SANTOS' onClick='functionExibeEndereco(this.value)'>AUDITÓRIO DES. JOAQUIM ANTÔNIO DE VIZEU PENALVA SANTOS</option><option value='AUDITÓRIO ANTONIO CARLOS AMORIM' onClick='functionExibeEndereco(this.value)'>AUDITÓRIO ANTONIO CARLOS AMORIM</option><option value='AUDITÓRIO DES. NELSON RIBEIRO ALVES' onClick='functionExibeEndereco(this.value)'>AUDITÓRIO DES. NELSON RIBEIRO ALVES</option><option value='AUDITÓRIO DESEMBARGADOR JOSÉ NAVEGA CRETTON' onClick='functionExibeEndereco(this.value)'>AUDITÓRIO DESEMBARGADOR JOSÉ NAVEGA CRETTON</option><option value='BIBLIOTECA TJERJ/EMERJ' onClick='functionExibeEndereco(this.value)'>BIBLIOTECA TJERJ/EMERJ</option><option value='TRIBUNAL PLENO DO TJERJ' onClick='functionExibeEndereco(this.value)'>TRIBUNAL PLENO DO TJERJ</option></select>";
  document.getElementById("exibeEnderecoEvento").innerHTML = "";
  }
}
</script>

<script type="text/javascript">
function exibeCampos(campo)
 {
  if (campo == "Fórum Permanente em Conjunto"){
  document.getElementById("forumEmConjunto").style.display = "block";
  } 
}
</script>



<!DOCTYPE html>
<html lang="pt-BR">
    
<script type="text/JavaScript">
<!--
function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}
//-->
</script>

<head>
    
<meta http-equiv="X-UA-Compatible" content="chrome=1">   
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    
<meta charset="utf-8">

<title>.: CONTROLE DE EVENTOS | CADASTRO DE EVENTOS :.</title>
<link href="../css/pagina.css" rel="stylesheet" type="text/css" />
    <script language="JavaScript" src="../../js/validador.js"></script>

<style type="text/css">

@font-face {
     font-family:"Titillium Web";
     src:url(../fonte/TitilliumWeb-Regular.ttf);
	 font-weight:normal;
	 font-style:normal;
}

body {
	font-family: "Titillium Web";
	font-weight: normal;
	font-style: normal;
}

.style22 {
	font-size: 14px;
	color: #FFFFFF;
}
body {
	background-image: url();
}
a:link {
	color: #990000;
}
.style25 {color: #000000}

.style26 {
	color: #990000;
	font-weight: bold;
	font-size:14px;
}
body,td,th {
	color: #666666;
}
</style>
<link rel="icon" href="favicon.ico" />
</head>
<body style="margin-top:0px; padding-top:0px;">
<br />
<div align="center">
  <!-- end #sidebar -->
<div align="center">
<div align="center">
		<h2 class="title"><span>CADASTRO DE EVENTOS</span> <sub>(<strong>Utilize o navegador Internet Explorer</strong>)</sub></h2>
		<br />	
      <div align="center">
        <div align="center">
            
          <? if ($acao == NULL) { ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1">
  <table width="850" align="center">
  <tr valign="baseline">
      <td width="280" align="right">Código:</td>
      <td><div align="left">
      <input name="codigo" type="text" class="textoNormal" size="5" maxlength="4" value="<?php 
        $query_rsBusca = "SELECT MAX(codigo) FROM evento";
        $rsBusca = mysqli_query($conn, $query_rsBusca) or die(mysqli_error());
        while ($vet=mysqli_fetch_row($rsBusca)) { 
            $vet[0]++;
            echo $vet[0];
        }
      ?>">
      </div>      
      </td>
    </tr>    
    <tr valign="baseline">
      <td align="right"><span class="style26">*</span> Nome:</td>
      <td><input name="nome" type="text" class="textoNormal" value="<?php if ((isset($totalRows_rsBusca)) && ($totalRows_rsBusca > 0)) echo($row_rsBusca["nome"]) ?>" size="100" maxlength="200"></td>
    </tr>    
    <tr valign="baseline">
      <td align="right"><span class="style26">*</span> Vagas:</td>
      <td><input id="vagas" name="vagas" type="text" class="textoNormal" size="10" value="<?php if ((isset($totalRows_rsBusca)) && ($totalRows_rsBusca > 0)) echo($row_rsBusca["vagas"]) ?>" /></td>
    </tr>    
     <tr valign="baseline">
      <td align="right"><span class="style26"><sup>observação 1</sup> *</span> Fora da Sede:</td>
      <td>
      <select name="foradasede" id="foradasede" onchange="javascript:exibeCamposLocalEndereco(this.value);">
        <option selected="selected">Selecione Sim ou Não</option>
        <option value="1">Sim</option>
        <option value="0">Não</option>
      </select>
      </td>
    </tr> 
    <tr valign="baseline" id="localEventoTable">
      <td align="right"><span class="style26">*</span> Local:</td>
      <td>
     
      <div id="exibeLocalEvento"></div>
   
      </td>
    </tr>
    <tr valign="baseline" id="enderecoEventoTable">
      <td align="right"><span class="style26">*</span> Endereço:</td>
      <td>
      
    <div id="exibeEnderecoEvento"></div>
    
    <div id="exibecampoEnderecoNao" style="display:none;">
      
      <script>
	function functionExibeEndereco(campo) {
		if (campo == "AUDITÓRIO DES. PAULO ROBERTO LEITE VENTURA") {
			document.getElementById("exibeEnderecoEvento").innerHTML = "<select name='endereco' id='endereco'><option></option><option value='RUA DOM MANUEL, Nº 25 - 1º ANDAR' selected='selected'>RUA DOM MANUEL, Nº 25 - 1º ANDAR</option><option value='RUA DOM MANUEL, Nº 25 – 2º ANDAR'>RUA DOM MANUEL, Nº 25 – 2º ANDAR</option><option value='RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III'>RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III</option><option value='RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I'>RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I</option></select>";
		} else if (campo == "AUDITÓRIO DES. JOAQUIM ANTÔNIO DE VIZEU PENALVA SANTOS") {
			document.getElementById("exibeEnderecoEvento").innerHTML = "<select name='endereco' id='endereco'><option></option><option value='RUA DOM MANUEL, Nº 25 – 1º ANDAR'>RUA DOM MANUEL, Nº 25 - 1º ANDAR</option><option value='RUA DOM MANUEL, Nº 25 - 2º ANDAR' selected='selected'>RUA DOM MANUEL, Nº 25 - 2º ANDAR</option><option value='RUA DOM MANUEL, S/N - 4º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III'>RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III</option><option value='RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I'>RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I</option></select>";
		} else if (campo == "AUDITÓRIO ANTONIO CARLOS AMORIM") {
			document.getElementById("exibeEnderecoEvento").innerHTML = "<select name='endereco' id='endereco'><option></option><option value='RUA DOM MANUEL, Nº 25 – 1º ANDAR'>RUA DOM MANUEL, Nº 25 – 1º ANDAR</option><option value='RUA DOM MANUEL, Nº 25 – 2º ANDAR'>RUA DOM MANUEL, Nº 25 – 2º ANDAR</option><option value='RUA DOM MANUEL, S/N - 4º ANDAR - LÂMINA I' selected='selected'>RUA DOM MANUEL, S/N - 4º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III'>RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III</option><option value='RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I'>RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I</option></select>";
		} else if (campo == "AUDITÓRIO DES. NELSON RIBEIRO ALVES") {
			document.getElementById("exibeEnderecoEvento").innerHTML = "<select name='endereco' id='endereco'><option></option><option value='RUA DOM MANUEL, Nº 25 – 1º ANDAR'>RUA DOM MANUEL, Nº 25 - 1º ANDAR</option><option value='RUA DOM MANUEL, Nº 25 – 2º ANDAR'>RUA DOM MANUEL, Nº 25 – 2º ANDAR</option><option value='RUA DOM MANUEL, S/N - 4º ANDAR - LÂMINA I' selected='selected'>RUA DOM MANUEL, S/N - 4º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III'>RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III</option><option value='RUA DOM MANUEL, S/N - 10º ANDAR – LÂMINA I'>RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I</option></select>";
		} else if (campo == "AUDITÓRIO DESEMBARGADOR JOSÉ NAVEGA CRETTON") {
			document.getElementById("exibeEnderecoEvento").innerHTML = "<select name='endereco' id='endereco'><option></option><option value='RUA DOM MANUEL, Nº 25 – 1º ANDAR'>RUA DOM MANUEL, Nº 25 – 1º ANDAR</option><option value='RUA DOM MANUEL, Nº 25 – 2º ANDAR'>RUA DOM MANUEL, Nº 25 – 2º ANDAR</option><option value='RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I' selected='selected'>RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III'>RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III</option><option value='RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I'>RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I</option></select>";
		} else if (campo == "BIBLIOTECA TJERJ/EMERJ") {
			document.getElementById("exibeEnderecoEvento").innerHTML = "<select name='endereco' id='endereco'><option></option><option value='RUA DOM MANUEL, Nº 25 – 1º ANDAR'>RUA DOM MANUEL, Nº 25 - 1º ANDAR</option><option value='RUA DOM MANUEL, Nº 25 – 2º ANDAR'>RUA DOM MANUEL, Nº 25 – 2º ANDAR</option><option value='RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III' selected='selected'>RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III</option><option value='RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I'>RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I</option></select>";
		} else if (campo == "TRIBUNAL PLENO DO TJERJ") {
			document.getElementById("exibeEnderecoEvento").innerHTML = "<select name='endereco' id='endereco'><option></option><option value='RUA DOM MANUEL, Nº 25 – 1º ANDAR'>RUA DOM MANUEL, Nº 25 – 1º ANDAR</option><option value='RUA DOM MANUEL, Nº 25 – 2º ANDAR'>RUA DOM MANUEL, Nº 25 – 2º ANDAR</option><option value='RUA DOM MANUEL S/N - 4º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I' selected='selected'>RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III'>RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III</option><option value='RUA DOM MANUEL, S/N - 10º ANDAR - LÂMINA I' selected='selected'>RUA DOM MANUEL, S/N - 10º ANDAR - LÂMINA I</option></select>";
		} else if (campo == ""){
			document.getElementById("exibeEnderecoEvento").innerHTML ="<span style='color:#900;'><strong>Selecione um LOCAL para cadastrar o evento.</strong></span>";
		}
	}
	</script>      
    </div>    
      </td>
    </tr>
    
    <tr valign="baseline">
      <td align="right"><span class="style26">*</span> Tipo do Evento:</td>
      <td>
      <select name="tipoEvento1" id="tipoEvento1">
        <option selected="selected"></option>
        <option value="Fórum Permanente da Criança, do Adolescente e da Justiça Terapêutica">Fórum Permanente da Criança, do Adolescente e da Justiça Terapêutica</option>
        <option value="Fórum Permanente da Justiça na Era Digital">Fórum Permanente da Justiça na Era Digital</option>
        <option value="Fórum Permanente de Biodireito, Bioética e Gerontologia">Fórum Permanente de Biodireito, Bioética e Gerontologia</option>
        <option value="Fórum Permanente de Culturas Jurídicas Comparadas">Fórum Permanente de Culturas Jurídicas Comparadas</option>
        <option value="Fórum Permanente de Diálogos e Debates Jurídicos">Fórum Permanente de Diálogos e Debates Jurídicos</option>
        <option value="Fórum Permanente de Direito Civil">Fórum Permanente de Direito Civil</option>
        <option value="Fórum Permanente de Direito da Cidade">Fórum Permanente de Direito da Cidade</option>
        <option value="Fórum Permanente de Direito de Arbitragem">Fórum Permanente de Direito de Arbitragem</option>
        <option value="Fórum Permanente de Direito de Família e Sucessões">Fórum Permanente de Direito de Família e Sucessões</option>
        <option value="Fórum Permanente de Direito do Ambiente">Fórum Permanente de Direito do Ambiente</option>
        <option value="Fórum Permanente de Direito do Consumidor">Fórum Permanente de Direito do Consumidor</option>
        <option value="Fórum Permanente de Direito Eleitoral">Fórum Permanente de Direito Eleitoral</option>
        <option value="Fórum Permanente de Direito Empresarial">Fórum Permanente de Direito Empresarial</option>
        <option value="Fórum Permanente de Direito Penal e Processual Penal">Fórum Permanente de Direito Penal e Processual Penal</option>
        <option value="Fórum Permanente de Direito Tributário">Fórum Permanente de Direito Tributário</option>
        <option value="Fórum Permanente de Direito, Arte e Cultura">Fórum Permanente de Direito, Arte e Cultura</option>
        <option value="Fórum Permanente de Direitos Humanos">Fórum Permanente de Direitos Humanos</option>  
        <option value="Fórum Permanente de Estudos Constitucionais, Administrativos e de Políticas Públicas">Fórum Permanente de Estudos Constitucionais, Administrativos e de Políticas Públicas</option>  
        <option value="Fórum Permanente de Execução Penal">Fórum Permanente de Execução Penal</option>                
        <option value="Fórum Permanente de Filosofia, Ética e Sistemas Jurídicos">Fórum Permanente de Filosofia, Ética e Sistemas Jurídicos</option>            
        <option value="Fórum Permanente de Hermenêutica e Decisão">Fórum Permanente de Hermenêutica e Decisão</option>         
        <option value="Fórum Permanente de História do Direito">Fórum Permanente de História do Direito</option>               
        <option value="Fórum Permanente de Métodos Adequados de Resolução de Conflitos">Fórum Permanente de Métodos Adequados de Resolução de Conflitos</option>                                     
        <option value="Fórum Permanente de Mídia e Liberdade de Expressão">Fórum Permanente de Mídia e Liberdade de Expressão</option>                                       
        <option value="Fórum Permanente de Política e Justiça Criminal">Fórum Permanente de Política e Justiça Criminal</option>                                  
        <option value="Fórum Permanente de Processo Civil">Fórum Permanente de Processo Civil</option>                          
        <option value="Fórum Permanente de Segurança Pública">Fórum Permanente de Segurança Pública</option>
        <option value="Fórum Permanente de Transparência e Probidade Administrativa">Fórum Permanente de Transparência e Probidade Administrativa</option>
        <option value="Fórum Permanente de Violência Doméstica, Familiar e de Gênero">Fórum Permanente de Violência Doméstica, Familiar e de Gênero</option>
        <option value="Fórum Permanente dos Juizados Especiais Cíveis e Criminais">Fórum Permanente dos Juizados Especiais Cíveis e Criminais</option>        
        <option value="Fórum Permanente dos Juízos Cíveis">Fórum Permanente dos Juízos Cíveis</option>   
        <option value="CEPES">Evento organizado pelo CEPES - Centro de Estudos e Pesquisas</option>
        <option value="Gabinete - GBEMERJ">Evento organizado pelo Gabinete - GBEMERJ</option>
        <option value="Organizado pela ASGET">Evento organizado pela ASGET</option>
        <option value="Organizado pelo NUPEGRE">Evento organizado pelo NUPEGRE</option>
        <option value="TJERJ">Evento organizado pelo TJERJ</option>
        <option value="Sem horas OAB">Evento sem horas OAB</option>
        <option value="Aula Magna">Aula Magna</option>
        <option value="Aula Inaugural">Aula Inaugural</option>
        <option value="Aula Aberta">Aula Aberta</option>
        <option value="Núcleo da EMERJ">Evento em Núcleo da EMERJ</option>
        <option value="Espaços EMERJ">Evento em Espaços EMERJ</option>
        <option value="Café com Conhecimento">Café com Conhecimento</option>
        <option value="Fóruns Permanentes Extintos">---- Fóruns Permanentes Extintos ----</option>
        <option value="Fórum Permanente de Direito Civil e Processo Civil">Fórum Permanente de Direito Civil e Processo Civil</option>     
        <option value="Fórum Permanente de Estudos Interdisciplinares">Fórum Permanente de Estudos Interdisciplinares</option>                               
        <option value="Fórum Permanente de Mídia e Novas Tecnologias da Informação">Fórum Permanente de Mídia e Novas Tecnologias da Informação</option>                          
        <option value="Fórum Permanente de Práticas Restaurativas e Mediação">Fórum Permanente de Práticas Restaurativas e Mediação</option> 
      </select>
      </td>
      </tr>
    <tr valign="baseline">
      <td align="right"><span class="style26"><sup>observação 2</sup></span> Fórum em Conjunto:</td>
      <td align="left">     
     
     <select name="tipoForumEmConjunto" id="tipoForumEmConjunto">
        <option selected="selected" onClick="exibeCampos(this.value);"></option>
        <option value="Fórum Permanente em Conjunto" onClick="exibeCampos(this.value);">Fórum Permanente em Conjunto (Organizado por mais de um Fórum)</option>
      </select>
    
    <div id="forumEmConjunto" name="forumEmConjunto" style="display:none;">
    <table border="0">
      <tr>
      <td align="left"><span style="font-size:13px">2:</span></td>
      <td>
      <select name="tipoEvento2" id="tipoEvento2">      
        <option value=""></option>  
        <option value="Fórum Permanente da Criança, do Adolescente e da Justiça Terapêutica">Fórum Permanente da Criança, do Adolescente e da Justiça Terapêutica</option>
        <option value="Fórum Permanente da Justiça na Era Digital">Fórum Permanente da Justiça na Era Digital</option>
        <option value="Fórum Permanente de Biodireito, Bioética e Gerontologia">Fórum Permanente de Biodireito, Bioética e Gerontologia</option>
        <option value="Fórum Permanente de Culturas Jurídicas Comparadas">Fórum Permanente de Culturas Jurídicas Comparadas</option>
        <option value="Fórum Permanente de Diálogos e Debates Jurídicos">Fórum Permanente de Diálogos e Debates Jurídicos</option>
        <option value="Fórum Permanente de Direito Civil">Fórum Permanente de Direito Civil</option>
        <option value="Fórum Permanente de Direito da Cidade">Fórum Permanente de Direito da Cidade</option>
        <option value="Fórum Permanente de Direito de Arbitragem">Fórum Permanente de Direito de Arbitragem</option>
        <option value="Fórum Permanente de Direito de Família e Sucessões">Fórum Permanente de Direito de Família e Sucessões</option>
        <option value="Fórum Permanente de Direito do Ambiente">Fórum Permanente de Direito do Ambiente</option>
        <option value="Fórum Permanente de Direito do Consumidor">Fórum Permanente de Direito do Consumidor</option>
        <option value="Fórum Permanente de Direito Eleitoral">Fórum Permanente de Direito Eleitoral</option>
        <option value="Fórum Permanente de Direito Empresarial">Fórum Permanente de Direito Empresarial</option>
        <option value="Fórum Permanente de Direito Penal e Processual Penal">Fórum Permanente de Direito Penal e Processual Penal</option>
        <option value="Fórum Permanente de Direito Tributário">Fórum Permanente de Direito Tributário</option>
        <option value="Fórum Permanente de Direito, Arte e Cultura">Fórum Permanente de Direito, Arte e Cultura</option>
        <option value="Fórum Permanente de Direitos Humanos">Fórum Permanente de Direitos Humanos</option>  
        <option value="Fórum Permanente de Estudos Constitucionais, Administrativos e de Políticas Públicas">Fórum Permanente de Estudos Constitucionais, Administrativos e de Políticas Públicas</option>  
        <option value="Fórum Permanente de Execução Penal">Fórum Permanente de Execução Penal</option>                
        <option value="Fórum Permanente de Filosofia, Ética e Sistemas Jurídicos">Fórum Permanente de Filosofia, Ética e Sistemas Jurídicos</option>            
        <option value="Fórum Permanente de Hermenêutica e Decisão">Fórum Permanente de Hermenêutica e Decisão</option>         
        <option value="Fórum Permanente de História do Direito">Fórum Permanente de História do Direito</option>               
        <option value="Fórum Permanente de Métodos Adequados de Resolução de Conflitos">Fórum Permanente de Métodos Adequados de Resolução de Conflitos</option>                                     
        <option value="Fórum Permanente de Mídia e Liberdade de Expressão">Fórum Permanente de Mídia e Liberdade de Expressão</option>                                       
        <option value="Fórum Permanente de Política e Justiça Criminal">Fórum Permanente de Política e Justiça Criminal</option>                                  
        <option value="Fórum Permanente de Processo Civil">Fórum Permanente de Processo Civil</option>                          
        <option value="Fórum Permanente de Segurança Pública">Fórum Permanente de Segurança Pública</option>
        <option value="Fórum Permanente de Transparência e Probidade Administrativa">Fórum Permanente de Transparência e Probidade Administrativa</option>
        <option value="Fórum Permanente de Violência Doméstica, Familiar e de Gênero">Fórum Permanente de Violência Doméstica, Familiar e de Gênero</option>
        <option value="Fórum Permanente dos Juizados Especiais Cíveis e Criminais">Fórum Permanente dos Juizados Especiais Cíveis e Criminais</option>        
        <option value="Fórum Permanente dos Juízos Cíveis">Fórum Permanente dos Juízos Cíveis</option>
        <option value="Organizado pelo NUPEGRE">Evento organizado pelo NUPEGRE</option>  
      </select>      
      </td>
      </tr>
      <tr>
      <td align="left"><span style="font-size:13px">3:</span></td>
      <td>
      <select name="tipoEvento3" id="tipoEvento3">  
        <option value=""></option>  
        <option value="Fórum Permanente da Criança, do Adolescente e da Justiça Terapêutica">Fórum Permanente da Criança, do Adolescente e da Justiça Terapêutica</option>
        <option value="Fórum Permanente da Justiça na Era Digital">Fórum Permanente da Justiça na Era Digital</option>
        <option value="Fórum Permanente de Biodireito, Bioética e Gerontologia">Fórum Permanente de Biodireito, Bioética e Gerontologia</option>
        <option value="Fórum Permanente de Culturas Jurídicas Comparadas">Fórum Permanente de Culturas Jurídicas Comparadas</option>
        <option value="Fórum Permanente de Diálogos e Debates Jurídicos">Fórum Permanente de Diálogos e Debates Jurídicos</option>
        <option value="Fórum Permanente de Direito Civil">Fórum Permanente de Direito Civil</option>
        <option value="Fórum Permanente de Direito da Cidade">Fórum Permanente de Direito da Cidade</option>
        <option value="Fórum Permanente de Direito de Arbitragem">Fórum Permanente de Direito de Arbitragem</option>
        <option value="Fórum Permanente de Direito de Família e Sucessões">Fórum Permanente de Direito de Família e Sucessões</option>
        <option value="Fórum Permanente de Direito do Ambiente">Fórum Permanente de Direito do Ambiente</option>
        <option value="Fórum Permanente de Direito do Consumidor">Fórum Permanente de Direito do Consumidor</option>
        <option value="Fórum Permanente de Direito Eleitoral">Fórum Permanente de Direito Eleitoral</option>
        <option value="Fórum Permanente de Direito Empresarial">Fórum Permanente de Direito Empresarial</option>
        <option value="Fórum Permanente de Direito Penal e Processual Penal">Fórum Permanente de Direito Penal e Processual Penal</option>
        <option value="Fórum Permanente de Direito Tributário">Fórum Permanente de Direito Tributário</option>
        <option value="Fórum Permanente de Direito, Arte e Cultura">Fórum Permanente de Direito, Arte e Cultura</option>
        <option value="Fórum Permanente de Direitos Humanos">Fórum Permanente de Direitos Humanos</option>  
        <option value="Fórum Permanente de Estudos Constitucionais, Administrativos e de Políticas Públicas">Fórum Permanente de Estudos Constitucionais, Administrativos e de Políticas Públicas</option>  
        <option value="Fórum Permanente de Execução Penal">Fórum Permanente de Execução Penal</option>                
        <option value="Fórum Permanente de Filosofia, Ética e Sistemas Jurídicos">Fórum Permanente de Filosofia, Ética e Sistemas Jurídicos</option>            
        <option value="Fórum Permanente de Hermenêutica e Decisão">Fórum Permanente de Hermenêutica e Decisão</option>         
        <option value="Fórum Permanente de História do Direito">Fórum Permanente de História do Direito</option>               
        <option value="Fórum Permanente de Métodos Adequados de Resolução de Conflitos">Fórum Permanente de Métodos Adequados de Resolução de Conflitos</option>                                     
        <option value="Fórum Permanente de Mídia e Liberdade de Expressão">Fórum Permanente de Mídia e Liberdade de Expressão</option>                                       
        <option value="Fórum Permanente de Política e Justiça Criminal">Fórum Permanente de Política e Justiça Criminal</option>                                  
        <option value="Fórum Permanente de Processo Civil">Fórum Permanente de Processo Civil</option>                          
        <option value="Fórum Permanente de Segurança Pública">Fórum Permanente de Segurança Pública</option>
        <option value="Fórum Permanente de Transparência e Probidade Administrativa">Fórum Permanente de Transparência e Probidade Administrativa</option>
        <option value="Fórum Permanente de Violência Doméstica, Familiar e de Gênero">Fórum Permanente de Violência Doméstica, Familiar e de Gênero</option>
        <option value="Fórum Permanente dos Juizados Especiais Cíveis e Criminais">Fórum Permanente dos Juizados Especiais Cíveis e Criminais</option>        
        <option value="Fórum Permanente dos Juízos Cíveis">Fórum Permanente dos Juízos Cíveis</option>
        <option value="Organizado pelo NUPEGRE">Evento organizado pelo NUPEGRE</option>
      </select>   
      </td>
      </tr>
      </table>
      </div>
      </td>
   </tr>    
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap>
	  <table width="100%">
         <tr>
          <td colspan="7">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="7"><h5>Portas *Evento cadastrado SEM Horas OAB '00:00'</h5></td>
        </tr>
        <tr>
          <td width="15%" class="textoT&iacute;tulo">Data</td>
          <td width="24%" class="textoT&iacute;tulo">Hor&aacute;rio Inicial</td>
          <td width="21%" class="textoT&iacute;tulo">Hor&aacute;rio Final</td>
          <td width="17%" class="textoT&iacute;tulo">Carga Hor&aacute;ria<strong>*</strong></td>
          <td width="16%" class="textoT&iacute;tulo">Tipo</td>
		   <td width="7%"><input name="acaoPorta" type="button" class="textoNormal" style="width:30px;" onClick="MM_callJS('IncluirPorta()')" value="+" /></td>
        </tr>
		<tr>
			<td><input name="txtData" type="text" class="textoNormal" id="txtData" onKeyPress="mascara_data(event, this.value);" size="10" maxlength="10" /></td>
			<td><input name="txtHoraInicial" type="text" class="textoNormal" id="txtHoraInicial" onKeyPress="mascara_hora(event,this.value,'txtHoraInicial')" size="15" maxlength="5" /></td>
			<td><input name="txtHoraFinal" type="text" class="textoNormal" id="txtHoraFinal" onKeyPress="mascara_hora(event,this.value,'txtHoraFinal');" size="15" maxlength="5" /></td>
			<td><input name="txtCargaHoraria" type="text" class="textoNormal" id="txtCargaHoraria" onKeyPress="mascara_hora(event,this.value,'txtCargaHoraria');" size="10" maxlength="5" /></td>
			<td><select name="cboTipo" class="textoNormal" id="cboTipo">
			  <option value="1" selected="selected">MANH&Atilde;</option>
			  <option value="2">TARDE</option>
			  <option value="3">NOITE</option>
			  <option value="4">INTEGRAL</option>
		      </select></td>
			
			<td><input name="acaoPorta" type="button" class="textoNormal" style="width:30px;" onClick="MM_callJS('ExcluirPorta()')" value="-" /></td>
		</tr>
      </table>	  </td>
    </tr>
	<tr>
		<td colspan="5"><hr /></td>
	</tr>
	<tr>
		<td colspan="5">
		<select name='portas' size='3' class="textoNormal" id="portas" style="width:100%">
<?php 
do {
	if ((isset($totalRows_rsBusca)) && ($totalRows_rsBusca > 0))
	echo("<option value='" . substr($row_rsBusca["data"],8,2) . "/" . substr($row_rsBusca["data"],5,2) . "/" . substr($row_rsBusca["data"],0,4) . "     " .
				substr($row_rsBusca["horaInicio"],0,5) . "     " .
				substr($row_rsBusca["horaFim"],0,5) . "     " .
				substr($row_rsBusca["cargaHoraria"],0,5) . "     " .
				$row_rsBusca["tipo"] . "'>");
	echo(substr($row_rsBusca["data"],8,2) . "/" . substr($row_rsBusca["data"],5,2) . "/" . substr($row_rsBusca["data"],0,4) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
			substr($row_rsBusca["horaInicio"],0,5) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
			substr($row_rsBusca["horaFim"],0,5) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
			substr($row_rsBusca["cargaHoraria"],0,5) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" );
	switch($row_rsBusca["tipo"]) {
		case 1: echo("MANHÃ"); break;
		case 2: echo("TARDE"); break;
		case 3: echo("NOITE"); break;
		case 4: echo("INTEGRAL"); break;
	}
	echo("</option>");
} while ($row_rsBusca = mysqli_fetch_assoc($rsBusca));
  			$rows = mysqli_num_rows($rsBusca);
  			if($rows > 0) {
      			mysqli_data_seek($rsBusca, 0);
	  			$row_rsBusca = mysqli_fetch_assoc($rsBusca);
  			}
?>
	      </select> 	    </td>
	</tr>
	<tr>
	<td colspan="2"><div align="center">
	  <input name="insert" type="button" class="textoNormal" onClick="MM_callJS('validaCampos();')" value="Cadastrar" />
      </div>
      </td>
	<tr>
  </table>
    
  <p>&nbsp;</p>
 <p>  
	  <?php
      /*EXIBE INCREMENTO++ AO ÚLTIMO VALOR DA COLUNA codEvento DA TABELA PORTA*/
        
        //$query_rsBusca = "SELECT MAX(codEvento) FROM porta";
        $query_rsBusca1 = "SELECT MAX(codigo) FROM evento";
        $query_rsBusca2 = "SELECT nome FROM evento WHERE codigo = (SELECT MAX(codigo) FROM evento)";
        $rsBusca1 = mysqli_query($conn, $query_rsBusca1) or die(mysqli_error($conn));
        $rsBusca2 = mysqli_query($conn, $query_rsBusca2) or die(mysqli_error($conn));
       	$vet1=mysqli_fetch_row($rsBusca1);
	   	$vet2=mysqli_fetch_row($rsBusca2); 			
			echo "<table width='800px' border='1' align='center' style='border:#999 1px solid; border-collapse:collapse;'>";
			echo "<tr align='center'>";
			echo "<td colspan='2' align='center'><strong><span style='font-size:15px;'>Código e nome do último evento cadastrado:</span></strong></td>";
			echo "</tr>";
			echo "<tr align='center'>";
			echo "<td width='50px' align='center'><strong><span style='font-size:15px;'>Código</span></strong></td>"; 
			echo "<td width='750px' align='center'><strong><span style='font-size:15px;'>Nome do Evento</span></strong></td>";
			echo "</tr>";
			echo "<tr align='center'>";
			echo "<td align='center'><strong><span style='font-size:14px;'>" . $vet1[0] . "</span></strong></td>";
			echo "<td align='center'><strong><span style='font-size:14px;'>" . $vet2[0] . "</span></strong></td>";
			echo "</tr>";
			echo "</table>";
      ?>  
      </p>
  <p>
    <input name="hdPortas" type="hidden" id="hdPortas" />
    <input type="hidden" name="MM_insert" value="form1">
      <input name="hdAcao" type="hidden" id="hdAcao" value="<?php if (isset($_SESSION["codEvento"])) echo('alterar'); else echo('inserir') ?>"/>
  </p>
</form>
            
<?php
if ($acao == "atualizado") {
		echo("<p>Atualização feita com sucesso!</p><hr>");
		echo("<a href='http://emerj.com.br/evento/admin/cadastraEvento.php'>voltar</a>");
}
if ($acao == "inserido") {
		echo "<script>alert('Evento cadastrado com sucesso');</script>";
		echo("<a href='http://emerj.com.br/evento/admin/admin.php'>voltar para Controle de Eventos</a>");
}

if ($acao == "oab") {
		echo "<script>alert('Evento cadastrado com sucesso SEM HORAS OAB');</script>";
		echo("<a href='http://emerj.com.br/evento/admin/cadastraEvento.php'>voltar</a>");
}
?>
            
        </div>
      </div>
            
    </div>
   </div>
<!-- end #main -->
</div>
<!-- end #sidebar2 -->
<br />
  <div align="center">
  <p class="style26">
  Observação 1: Caso o evento não ocorra em algum dos auditórios da sede, selecione a opção "Sim" no campo "Endereço Fora da Sede" para habilitar um campo de texto.<br />
  Observação 2: Caso o evento seja organizado por mais de um fórum, selecione o campo "Fórum em Conjunto" para informar o nome do 2º e 3º tipo de Fórum.<br />
  * Campos com Preenchimento Obrigatório / Somente o campo "Fórum em Conjunto" NÃO é obrigatório
  </p>
  <br />
  <p>
  Local:<strong> AUDIT&Oacute;RIO ANTONIO CARLOS AMORIM</strong> / Capacidade Real: 480 Lugares 
  <br />
  Local:<strong> AUDIT&Oacute;RIO DES. NELSON RIBEIRO ALVES</strong> / Capacidade Real: 98 Lugares  
  <br />
  Local:<strong> AUDIT&Oacute;RIO DES. PAULO ROBERTO LEITE VENTURA</strong> / Capacidade Real: 85 Lugares 
  <br />
  Local:<strong> AUDIT&Oacute;RIO DES. JOAQUIM ANTONIO DE VIZEU PENALVA SANTOS</strong> / Capacidade Real: 105 Lugares
  </p>
  </div>
<!-- end #content -->
</div>
<br />
</body>
</html>