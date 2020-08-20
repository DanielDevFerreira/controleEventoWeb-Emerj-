    /******* Função exluir todos os dados da porta no unico campo que foi adicionado ******/     
   function ExcluirPorta()
  {
    var i;
    for (i=0; i < document.form1.portas.options.length ; i++)    
    {      
      if (document.form1.portas.options[i].selected == true)
        document.form1.portas.options.remove(i);
    }
  }


function exibeCampos(campo)
 {
  if (campo == "Fórum Permanente em Conjunto"){
  document.getElementById("forumEmConjunto").style.display = "block";
  } 
}

  /*********** Função para inserir os dados da Porta em um unico campo **********/
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

  function validaCampos() 
  {
  	var ports="";
    //Pego todos os telefones incluidos pelo usuario e coloco num campo hidden
    for (i=0; i < document.form1.portas.options.length ; i++)
      ports = ports + document.form1.portas.options[i].value;    
    document.form1.hdPortas.value = ports;
	document.form1.submit();
  }

function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}

var camposLocalEndereco = null;

function exibeCamposLocalEndereco(campo){

camposLocalEndereco = campo;

  if (camposLocalEndereco == 1){ 
  	document.getElementById("exibeLocalEvento").innerHTML = "<div class='row'><div class='form-group'><div class='col-md-6'><input class='form-control col-md-6 ' name='local' type='text' class='textoNormal col-md-6' id='local' maxlength='200' /></div></div></div>";
 	document.getElementById("exibeEnderecoEvento").innerHTML = "<div class='row'><div class='form-group'><div class='col-md-6'><input class='form-control col-md-6 textoNormal' type='text' id='endereco' name='endereco' placeholder='Endereço do Evento' maxlength='400'></div></div></div>";
  }
  else if (camposLocalEndereco == 0){  
  document.getElementById("exibeLocalEvento").innerHTML = "<div class='row'><div class='form-group'><div class='col-md-6'><select class='form-control col-md-6' name='local' id='local' onchange='javascript:functionExibeEndereco(this.value);'><option value='' onClick='functionExibeEndereco(this.value)'></option><option value='AUDITÓRIO DES. PAULO ROBERTO LEITE VENTURA' onClick='functionExibeEndereco(this.value)'>AUDITÓRIO DES. PAULO ROBERTO LEITE VENTURA</option><option value='AUDITÓRIO DES. JOAQUIM ANTÔNIO DE VIZEU PENALVA SANTOS' onClick='functionExibeEndereco(this.value)'>AUDITÓRIO DES. JOAQUIM ANTÔNIO DE VIZEU PENALVA SANTOS</option><option value='AUDITÓRIO ANTONIO CARLOS AMORIM' onClick='functionExibeEndereco(this.value)'>AUDITÓRIO ANTONIO CARLOS AMORIM</option><option value='AUDITÓRIO DES. NELSON RIBEIRO ALVES' onClick='functionExibeEndereco(this.value)'>AUDITÓRIO DES. NELSON RIBEIRO ALVES</option><option value='AUDITÓRIO DESEMBARGADOR JOSÉ NAVEGA CRETTON' onClick='functionExibeEndereco(this.value)'>AUDITÓRIO DESEMBARGADOR JOSÉ NAVEGA CRETTON</option><option value='BIBLIOTECA TJERJ/EMERJ' onClick='functionExibeEndereco(this.value)'>BIBLIOTECA TJERJ/EMERJ</option><option value='TRIBUNAL PLENO DO TJERJ' onClick='functionExibeEndereco(this.value)'>TRIBUNAL PLENO DO TJERJ</option></select></div></div></div>";
  document.getElementById("exibeEnderecoEvento").innerHTML = "<div class='row'><div class='form-group'><div class='col-md-6'></div></div></div>";
  }
}

function functionExibeEndereco(campo) {
		if (campo == "AUDITÓRIO DES. PAULO ROBERTO LEITE VENTURA") {
			document.getElementById("exibeEnderecoEvento").innerHTML = "<div class='row'><div class='form-group'><div class='col-md-6'><select class='form-control col-md-6' name='endereco' id='endereco'><option></option><option value='RUA DOM MANUEL, Nº 25 - 1º ANDAR' selected='selected'>RUA DOM MANUEL, Nº 25 - 1º ANDAR</option><option value='RUA DOM MANUEL, Nº 25 – 2º ANDAR'>RUA DOM MANUEL, Nº 25 – 2º ANDAR</option><option value='RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III'>RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III</option><option value='RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I'>RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I</option></select></div></div></div>";
		} else if (campo == "AUDITÓRIO DES. JOAQUIM ANTÔNIO DE VIZEU PENALVA SANTOS") {
			document.getElementById("exibeEnderecoEvento").innerHTML = "<div class='row'><div class='form-group'><div class='col-md-6'><select class='form-control col-md-6' name='endereco' id='endereco'><option></option><option value='RUA DOM MANUEL, Nº 25 – 1º ANDAR'>RUA DOM MANUEL, Nº 25 - 1º ANDAR</option><option value='RUA DOM MANUEL, Nº 25 - 2º ANDAR' selected='selected'>RUA DOM MANUEL, Nº 25 - 2º ANDAR</option><option value='RUA DOM MANUEL, S/N - 4º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III'>RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III</option><option value='RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I'>RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I</option></select></div></div></div>";
		} else if (campo == "AUDITÓRIO ANTONIO CARLOS AMORIM") {
			document.getElementById("exibeEnderecoEvento").innerHTML = "<div class='row'><div class='form-group'><div class='col-md-6'><select class='form-control col-md-6' name='endereco' id='endereco'><option></option><option value='RUA DOM MANUEL, Nº 25 – 1º ANDAR'>RUA DOM MANUEL, Nº 25 – 1º ANDAR</option><option value='RUA DOM MANUEL, Nº 25 – 2º ANDAR'>RUA DOM MANUEL, Nº 25 – 2º ANDAR</option><option value='RUA DOM MANUEL, S/N - 4º ANDAR - LÂMINA I' selected='selected'>RUA DOM MANUEL, S/N - 4º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III'>RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III</option><option value='RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I'>RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I</option></select></div></div></div>";
		} else if (campo == "AUDITÓRIO DES. NELSON RIBEIRO ALVES") {
			document.getElementById("exibeEnderecoEvento").innerHTML = "<div class='row'><div class='form-group'><div class='col-md-6'><select class='form-control col-md-6' name='endereco' id='endereco'><option></option><option value='RUA DOM MANUEL, Nº 25 – 1º ANDAR'>RUA DOM MANUEL, Nº 25 - 1º ANDAR</option><option value='RUA DOM MANUEL, Nº 25 – 2º ANDAR'>RUA DOM MANUEL, Nº 25 – 2º ANDAR</option><option value='RUA DOM MANUEL, S/N - 4º ANDAR - LÂMINA I' selected='selected'>RUA DOM MANUEL, S/N - 4º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III'>RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III</option><option value='RUA DOM MANUEL, S/N - 10º ANDAR – LÂMINA I'>RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I</option></select></div></div></div>";
		} else if (campo == "AUDITÓRIO DESEMBARGADOR JOSÉ NAVEGA CRETTON") {
			document.getElementById("exibeEnderecoEvento").innerHTML = "<div class='row'><div class='form-group'><div class='col-md-6'><select class='form-control col-md-6' name='endereco' id='endereco'><option></option><option value='RUA DOM MANUEL, Nº 25 – 1º ANDAR'>RUA DOM MANUEL, Nº 25 – 1º ANDAR</option><option value='RUA DOM MANUEL, Nº 25 – 2º ANDAR'>RUA DOM MANUEL, Nº 25 – 2º ANDAR</option><option value='RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I' selected='selected'>RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III'>RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III</option><option value='RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I'>RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I</option></select></div></div></div>";
		} else if (campo == "BIBLIOTECA TJERJ/EMERJ") {
			document.getElementById("exibeEnderecoEvento").innerHTML = "<div class='row'><div class='form-group'><div class='col-md-6'><select class='form-control col-md-6' name='endereco' id='endereco'><option></option><option value='RUA DOM MANUEL, Nº 25 – 1º ANDAR'>RUA DOM MANUEL, Nº 25 - 1º ANDAR</option><option value='RUA DOM MANUEL, Nº 25 – 2º ANDAR'>RUA DOM MANUEL, Nº 25 – 2º ANDAR</option><option value='RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III' selected='selected'>RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III</option><option value='RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I'>RUA DOM MANUEL, S/N – 10º ANDAR – LÂMINA I</option></select></div></div></div>";
		} else if (campo == "TRIBUNAL PLENO DO TJERJ") {
			document.getElementById("exibeEnderecoEvento").innerHTML = "<div class='row'><div class='form-group'><div class='col-md-6'><select class='form-control col-md-6' name='endereco' id='endereco'><option></option><option value='RUA DOM MANUEL, Nº 25 – 1º ANDAR'>RUA DOM MANUEL, Nº 25 – 1º ANDAR</option><option value='RUA DOM MANUEL, Nº 25 – 2º ANDAR'>RUA DOM MANUEL, Nº 25 – 2º ANDAR</option><option value='RUA DOM MANUEL S/N - 4º ANDAR - LÂMINA I'>RUA DOM MANUEL, S/N – 4º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I' selected='selected'>RUA DOM MANUEL, S/N - 7º ANDAR - LÂMINA I</option><option value='RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III'>RUA DOM MANUEL, Nº 37 - TÉRREO - LÂMINA III</option><option value='RUA DOM MANUEL, S/N - 10º ANDAR - LÂMINA I' selected='selected'>RUA DOM MANUEL, S/N - 10º ANDAR - LÂMINA I</option></select></div></div></div>";
		} else if (campo == ""){
			document.getElementById("exibeEnderecoEvento").innerHTML ="<span style='color:#900;'><strong>Selecione um LOCAL para cadastrar o evento.</strong></span>";
		}
	}