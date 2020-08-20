// Funções de limitação de digitação
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

function alterarDados() {
		if (validaCPF()) {
			if (document.form1.txtSenha.value != "") {
				document.form1.hdAcao.value = "alterar";
				document.form1.submit();
			} else {
				alert("O campo SENHA deve ser preenchido corretamente\n");
			}
		} else {
			alert("O campo CPF está incorreto.");
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

function verificaNumeros(e) {
	if (document.all) // Internet Explorer
		var tecla = e.keyCode;
	else if(document.layers) // Nestcape
		var tecla = e.which;
	if  (!((tecla == 8) || (tecla > 47 && tecla < 58))) // numeros de 0 a 9
		e.keyCode = 0;
	return true;
}

function  verificaLetras(e) {
	var	resultadoValidacao = false
	if (document.all) // Internet Explorer
		var tecla = e.keyCode;
	else if(document.layers) // Nestcape
		var tecla = e.which;
	switch (tecla) {
		case 8:   // backspace
		case 32:  // espaço
		case 39:  // '
		case 127: // delete
			resultadoValidacao = true;
	}
	if (tecla > 64 && tecla < 91) //  letras maiusculas e minusculas
		resultadoValidacao = true;
	if (tecla > 96 && tecla < 123) {
		e.keyCode = e.keyCode - 32;
		resultadoValidacao = true;
	}
	if (resultadoValidacao == false)
		e.keyCode = 0; //resultadoValidacao = false;		
	return true;
}

// funções de máscaras de digitação
function mascara_cpf(evento, cpf) {
	if (verificaNumeros(evento)) {
	    var mycpf = '';
	    mycpf = mycpf + cpf;
	    if (mycpf.length == 3) {
	        mycpf = mycpf + '.';
	        document.form1.txtMascaraCpf.value = mycpf;
	    }
	    if (mycpf.length == 7) {
	        mycpf = mycpf + '.';
	        document.form1.txtMascaraCpf.value = mycpf;
	    }
	    if (mycpf.length == 11) {
	        mycpf = mycpf + '-';
	        document.form1.txtMascaraCpf.value = mycpf;
	    }
	}
}


function mascara_celular(evento, celular) {
	if (verificaNumeros(evento)) {
	    var mycelular = '';
	    mycelular = mycelular + celular;
	    if (mycelular.length == 0) {
	        mycelular = mycelular + '(';
	        document.form1.txtMascaraCelular.value = mycelular;
	    }
		if (mycelular.length == 3) {
	        mycelular = mycelular + ') ';
	        document.form1.txtMascaraCelular.value = mycelular;
	    }
	    if (mycelular.length == 9) {
	        mycelular = mycelular + '-';
	        document.form1.txtMascaraCelular.value = mycelular;
	    }
	    
	}
}


function mascara_telefone(evento, telefone) {
	if (verificaNumeros(evento)) {
	    var mytelefone = '';
	    mytelefone = mytelefone + telefone;
	    if (mytelefone.length == 0) {
	        mytelefone= mytelefone + '(';
	        document.form1.txtMascaraTelefone.value = mytelefone;
	    }
		if (mytelefone.length == 3) {
	        mytelefone = mytelefone + ') ';
	        document.form1.txtMascaraTelefone.value = mytelefone;
	    }
	    if (mytelefone.length == 9) {
	        mytelefone = mytelefone + '-';
	        document.form1.txtMascaraTelefone.value = mytelefone;
	    }
	    
	}
}


function mascara_matricula(evento, mat) {
	if (verificaNumeros(evento)) {
	    var matricula = '';
	    matricula = matricula + mat;
	    if (matricula.length == 4) {
	        matricula = matricula + '.';
	        document.form1.matriculaEMERJ.value = matricula;
	    }
	    if (matricula.length == 6) {
	        matricula = matricula + '.';
	        document.form1.matriculaEMERJ.value = matricula;
	    }
	    if (matricula.length == 8) {
	        matricula = matricula + '.';
	        document.form1.matriculaEMERJ.value = matricula;
	    }
	}
}

function mascara_cep(evento, varCep) {
	if (verificaNumeros(evento)) {
	    var cep = '';
	    cep = cep + varCep;
	    if (cep.length == 5) {
	        cep = cep + '-';
	        document.form1.cep.value = cep;
	    }
	}
}

function mascara_data(evento, txtData) {
	if (verificaNumeros(evento)) {
	    var data = '';
	    data = data + txtData;
	    if (data.length == 2) {
	        data = data + '/';
	        document.form1.txtData.value = data;
	    }
	    if (data.length == 5) {
	        data = data + '/';
	        document.form1.txtData.value = data;
	    }
	}
}

function mascara_hora(evento, txtHora, obj) {
	if (verificaNumeros(evento)) {
	    var hora = '';
	    hora = hora + txtHora;
	    if (hora.length == 2) {
	        hora = hora + ':';
	        document.form1[obj].value = hora;
	    }
	}
}

// Funções de validação
function validaCPF() {
	var cpfTXT = document.form1.txtMascaraCpf.value;
	var resultadoValidacao = true;
	var a = [];
	var b = new Number;
	var c = 11;

	if (cpfTXT.length != 14)
		resultadoValidacao = false;
	else {
		//remove a máscara de digitação
		myCpf = cpfTXT.substring(0, 3) + cpfTXT.substring(4, 7) + cpfTXT.substring(8, 11) + cpfTXT.substring(12,14);
		
		if (myCpf == "00000000000" || myCpf == "11111111111" || myCpf == "22222222222" || myCpf == "33333333333" || myCpf == "44444444444" || myCpf == "55555555555" || myCpf == "66666666666" || myCpf == "77777777777" || myCpf == "88888888888" || myCpf == "99999999999")
			resultadoValidacao = false;

		for (i=0; i<11; i++) {
			a[i] = myCpf.charAt(i);
			if (i < 9) b += (a[i] * --c);
		}
		if ((x = b % 11) < 2)
			a[9] = 0 
		else
			a[9] = 11-x;
		b = 0;
		c = 11;
		for (y=0; y<10; y++) 
			b += (a[y] * c--); 
		if ((x = b % 11) < 2)
			a[10] = 0
		else
			a[10] = 11-x; 

		if ((myCpf.charAt(9) != a[9]) || (myCpf.charAt(10) != a[10]))
			resultadoValidacao = false;
	}
	
	return(resultadoValidacao);		
}

function validaData(data) {
	var dataPossivel = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/\d{4}$/;

	if (dataPossivel.test(data))
		alert(data + " é uma data válida.")
	else if (data != null && data != "")
		alert(data + " NÃO é uma data válida.");
}

function validaHora(hora) {
	var horaPossivel = /^([0-1]\d|2[0-3]):[0-5]\d$/;

	if (horaPossivel.test(hora))
		alert(hora + " é um horário válido.")
	else if (hora != null && hora != "")
		alert(hora + " nÃO é um horário válido.");
}

function verificaEmail() {
	var regstr = "^[a-zA-Z0-9_\\-\\.]+\\@([a-zA-Z0-9_\\-\\.]+\\.){1,4}[a-zA-Z]{2,4}$";
	var reg = new RegExp(regstr);
	if (!reg.test(document.form1.email.value))
		return false
	else
		return true;
}