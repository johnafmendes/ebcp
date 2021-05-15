function validaCadastroCandidato(){
	for (var i=0; i < document.formCadastro.length; i++){
		if ((document.formCadastro[i].value == "") && (document.formCadastro[i].id == "validar")) {
			var aux = document.formCadastro[i].title.toUpperCase();
			alert("O campo " + aux + " deve ser preenchido");
			document.formCadastro[i].focus();
			return false;
		}
	}
	
	if(document.formCadastro.senha.value != document.formCadastro.senha2.value){
		alert("O campo SENHA e CONFIRMA SENHA não conferem.");
		return false;
	}
	return true;
}

function validaCadastroUsuario(){
	for (var i=0; i < document.formCadastro.length; i++){
		if ((document.formCadastro[i].value == "") && (document.formCadastro[i].id == "validar")) {
			var aux = document.formCadastro[i].title.toUpperCase();
			alert("O campo " + aux + " deve ser preenchido");
			document.formCadastro[i].focus();
			return false;
		}
	}
	
	if(document.formCadastro.mantersenha.checked == false && document.formCadastro.senha.value == ""){
		alert("O campo SENHA é obrigatório.");
		return false;
	}
	return true;
}

function validaCadastroGenerico(){
	for (var i=0; i < document.formCadastro.length; i++){
		if ((document.formCadastro[i].value == "") && (document.formCadastro[i].id == "validar")) {
			var aux = document.formCadastro[i].title.toUpperCase();
			alert("O campo " + aux + " deve ser preenchido");
			document.formCadastro[i].focus();
			return false;
		}
	}
	
	return true;
}

function confirmarExclusao(){
	return confirm("Deseja realmente excluir?");
}

function validaInscricaoCandidato(){
	for (var i=0; i < document.formCadastro.length; i++){
		if ((document.formCadastro[i].value == "") && (document.formCadastro[i].id == "validar")) {
			var aux = document.formCadastro[i].title.toUpperCase();
			alert("O campo " + aux + " deve ser preenchido");
			document.formCadastro[i].focus();
			return false;
		}
	}
	
	if(document.formCadastro.deficiente.value == "sim"){
		if(document.formCadastro.idpne.value == ""){
			alert("O campo TIPO DE NECESSIDADE ESPECIAL deve ser preenchido.");
			return false;
		}
	}
	
	return true;
}

function validaCamposContato(){
	if(contato.nomeContato.value == '')
		{
		 alert("Por favor, digite o nome para contato.");
		 return false;
		}
	if(contato.telefone.value == '')
		{
		 alert("Por favor, digite o seu telefone de contato.");
		 return false;
		}
	if(contato.email.value == '')
		{
  		 alert("Por favor, digite o seu email.");
	     return false;
		}
	if(contato.email.value.indexOf(('@' && '.'),0)== -1)
		{
  		 alert("Por favor, verifique o email digitado.");
	     return false;
		}
	if(contato.descricao.value == '')
		{
		 alert("Por favor, digite com detalhes o pedido.");
		 return false;
		}
	if(contato.descricao.value.length < 10)
		{
   		 alert("O texto está muito curto. Digite um texto com maiores detalhes, por gentileza.");
   		 return false;
		}
   return true;
  }

function validaCamposOrcamento(){
	if(orcamento.nomeContato.value == '')
		{
		 alert("Por favor, digite o nome para contato.");
		 return false;
		}
	if(orcamento.telefone.value == '')
		{
		 alert("Por favor, digite o seu telefone de contato.");
		 return false;
		}
	if(orcamento.email.value == '')
		{
  		 alert("Por favor, digite o seu email.");
	     return false;
		}
	if(orcamento.email.value.indexOf(('@' && '.'),0)== -1)
		{
  		 alert("Por favor, verifique o email digitado.");
	     return false;
		}
	if(orcamento.descricao.value == '')
		{
		 alert("Por favor, digite com detalhes o pedido.");
		 return false;
		}
	if(orcamento.descricao.value.length < 10)
		{
   		 alert("O texto está muito curto. Digite um texto com maiores detalhes, por gentileza.");
   		 return false;
		}
   return true;
  }

/*function ValidarCPF(Objcpf){
	var cpf = Objcpf.value;
	exp = /\.|\-/g
	cpf = cpf.toString().replace( exp, "" ); 
	var digitoDigitado = eval(cpf.charAt(9)+cpf.charAt(10));
	var soma1=0, soma2=0;
	var vlr =11;

	for(i=0;i<9;i++){
		soma1+=eval(cpf.charAt(i)*(vlr-1));
		soma2+=eval(cpf.charAt(i)*vlr);
		vlr--;
	}       
	soma1 = (((soma1*10)%11)==10 ? 0:((soma1*10)%11));
	soma2=(((soma2+(2*soma1))*10)%11);

	var digitoGerado=(soma1*10)+soma2;
	if(digitoGerado!=digitoDigitado){        
		alert('CPF Invalido!');
		return false;
    } else {
    	return true;
    }
}*/

function ValidarCPF(Objcpf){
	var strCPF = Objcpf.value; 
	var Soma; 
	var Resto; 
	Soma = 0; 
	if (strCPF == "00000000000") {
		alert('CPF Invalido!');
		return false; 
	}
	for (i=1; i<=9; i++) {
		Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
	}
	Resto = (Soma * 10) % 11; 
	if ((Resto == 10) || (Resto == 11)){ 
		Resto = 0; 
	}
	if (Resto != parseInt(strCPF.substring(9, 10)) ){ 
		return false; 
	}
	Soma = 0; 
	for (i = 1; i <= 10; i++){ 
		Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
	}
	Resto = (Soma * 10) % 11; 
	if ((Resto == 10) || (Resto == 11)){ 
		Resto = 0; 
	}
	if (Resto != parseInt(strCPF.substring(10, 11) ) ){ 
		alert('CPF Invalido!');
		return false; 
	}
	return true; 
}

function somenteNumeros(){
    if (event.keyCode < 48 || event.keyCode > 57){
    	event.returnValue = false;
    	return false;
    }
    return true;
}

function MascaraCep(cep){
    if(somenteNumeros(cep)==false){
    	event.returnValue = false;
    }       
    return formataCampo(cep, '00.000-000', event);
}

//adiciona mascara de data
function MascaraData(data){
	if(somenteNumeros(data)==false){
		event.returnValue = false;
	}       
	return formataCampo(data, '00/00/0000', event);
}

function MascaraHora(hora){
	if(somenteNumeros(hora)==false){
		event.returnValue = false;
	}       
	return formataCampo(hora, '00:00:00', event);
}

//adiciona mascara ao telefone
function MascaraTelefone(tel){  
	if(somenteNumeros(tel)==false){
		event.returnValue = false;
	}       
	return formataCampo(tel, '(00) 00000-0000', event);
}

//adiciona mascara ao CPF
function MascaraCPF(cpf){
	if(somenteNumeros(cpf)==false){
		event.returnValue = false;
	}       
	return formataCampo(cpf, '000.000.000-00', event);
}

//valida telefone
function ValidaTelefone(tel){
	exp = /\(\d{2}\)\ \d{4}\-\d{4}/
	if(!exp.test(tel.value)){
		alert('Numero de Telefone Invalido!');
	}
}

//valida CEP
function ValidaCep(cep){
	exp = /\d{2}\.\d{3}\-\d{3}/
	if(!exp.test(cep.value)){
		alert('Numero de Cep Invalido!');
	}
}

//valida data
function ValidaData(data){
	exp = /\d{2}\/\d{2}\/\d{4}/
	if(!exp.test(data.value)){
		alert('Data Invalida!');
	}
}


//formata de forma generica os campos
function formataCampo(campo, Mascara, evento) { 
	var boleanoMascara; 

	var Digitato = evento.keyCode;
	exp = /\-|\.|\/|\(|\)|\:| /g
	campoSoNumeros = campo.value.toString().replace( exp, "" ); 

	var posicaoCampo = 0;    
	var NovoValorCampo="";
	var TamanhoMascara = campoSoNumeros.length; 

	if (Digitato != 8) { // backspace 
		for(i=0; i<= TamanhoMascara; i++) { 
			boleanoMascara  = ((Mascara.charAt(i) == "-") || (Mascara.charAt(i) == ".")
					|| (Mascara.charAt(i) == "/")) 
            boleanoMascara  = boleanoMascara || ((Mascara.charAt(i) == "(") 
            		|| (Mascara.charAt(i) == ")") || (Mascara.charAt(i) == " ") || (Mascara.charAt(i) == ":")) 
            if (boleanoMascara) { 
            	NovoValorCampo += Mascara.charAt(i); 
            	TamanhoMascara++;
            }else { 
            	NovoValorCampo += campoSoNumeros.charAt(posicaoCampo); 
            	posicaoCampo++; 
            }              
		}      
		campo.value = NovoValorCampo;
		return true; 
	}else { 
		return true; 
	}
}


function habilitaPNE(campo){
	if(campo == "sim"){
		document.formCadastro.idpne.disabled = false;
		document.formCadastro.pneespecifico.disabled = false; 
		document.formCadastro.provaampliada.disabled = false;
		document.formCadastro.facilacesso.disabled = false;
		document.formCadastro.auxiliotranscricao.disabled = false;
		document.formCadastro.outrasolicitacao1.disabled = false;
		document.formCadastro.outrasolicitacao.disabled = false;
	} else {
		document.formCadastro.idpne.disabled = true;
		document.formCadastro.pneespecifico.disabled = true; 
		document.formCadastro.provaampliada.disabled = true;
		document.formCadastro.facilacesso.disabled = true;
		document.formCadastro.auxiliotranscricao.disabled = true;
		document.formCadastro.outrasolicitacao1.disabled = true;
		document.formCadastro.outrasolicitacao.disabled = true;
	}
}	

function habilitaNIS(campo){
	if(campo == "1"){//sim
		document.formCadastro.nis.disabled = false;
	} else {
		document.formCadastro.nis.disabled = true;
	}
}	

function validarSenha(objSenha, objSenha2){
	var senha = objSenha.value;
	var senha2 = objSenha2.value;
	
	if(senha != senha2){        
		alert('Senhas inválidas. Por favor, digite novamente.');
		return false;
    } else {
    	return true;
    }
}


function validaCadastroConcurso(){
	for (var i=0; i < document.formCadastro.length; i++){
		if ((document.formCadastro[i].value == "") && (document.formCadastro[i].id == "validar")) {
			var aux = document.formCadastro[i].title.toUpperCase();
			alert("O campo " + aux + " deve ser preenchido");
			document.formCadastro[i].focus();
			return false;
		}
	}
	
	if(document.formCadastro.isencaodoadorsangue.checked == true){
		if(document.formCadastro.datainiciodoadorsangue.value.length < 10 || document.formCadastro.datafimdoadorsangue.value.length < 10){
			alert("O campo DATA INÍCIO DOADOR DE SANGUE e DATA FIM DOADOR DE SANGUE devem ser preenchidos.");
			return false;
		}
	}

	if(document.formCadastro.isencaonis.checked == true){
		if(document.formCadastro.datainicioisencaonis.value.length < 10 || document.formCadastro.datafimisencaonis.value.length < 10){
			alert("O campo DATA INÍCIO ISENÇÃO NIS e DATA FIM ISENÇÃO NIS devem ser preenchidos.");
			return false;
		}
	}

	return true;
}


/*tabs*/
jQuery(document).ready(function() {
    jQuery('.tabs .tab-links a').on('click', function(e)  {
        var currentAttrValue = jQuery(this).attr('href');
 
        // Show/Hide Tabs
        jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
 
        // Change/remove current tab to active
        jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
 
        e.preventDefault();
    });
});
/*fim tabs*/