<!-- ================== Fun��o Bloqueia ENTER =========================================================== -->
function getKey(event) {
        return event?(event.keyCode?event.keyCode:(event.which?event.which:event.charCode)):null;
}
<!-- ==================================================================================================== -->


<!-- ================== Fun��o Abre Janela Pop-up =========================================================== -->
function abrir(programa,janela)
	{
		if(janela=="") janela = "janela";
		window.open(programa,janela,'height=265,width=260');
	}
<!-- ======================================================================================================= -->


<!-- ====================   Voltar / Avan�ar / Atualizar   ========================================================== -->
function avancar()
{
window.history.forward();
}

function voltar()
{
window.history.back();
}

function voltar_duas_paginas()
{
window.history.go(-2);
}

function atualiza_pagina()
{
window.location.reload();
}
<!-- ==================================================================================================== -->



<!-- ========================   Foco   ================================================================== -->
function foco(id)
{
document.getElementById(id).focus();
}
<!-- ==================================================================================================== -->



<!-- ========================   IMPRIMIR   ================================================================== -->
function imprimir() {
window.print();
}
<!-- ==================================================================================================== -->



<!-- =================================================== Fun��o M�scara ====================================== -->
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}


function data(v){
    v=v.replace(/\D/g,"")                 //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")    //Coloca h�fen entre o quarto e o quinto d�gitos
    v=v.replace(/(\d{2})(\d)/,"$1/$2")    //Coloca h�fen entre o quarto e o quinto d�gitos
    return v
}

function num_cnpj(v){
    v=v.replace(/\D/g,"")                           //Remove tudo o que n�o � d�gito
    v=v.replace(/^(\d{2})(\d)/,"$1.$2")             //Coloca ponto entre o segundo e o terceiro d�gitos
    v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3") //Coloca ponto entre o quinto e o sexto d�gitos
    v=v.replace(/\.(\d{3})(\d)/,".$1/$2")           //Coloca uma barra entre o oitavo e o nono d�gitos
    v=v.replace(/(\d{4})(\d)/,"$1-$2")              //Coloca um h�fen depois do bloco de quatro d�gitos
    return v
}

function num_cpf(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto d�gitos
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto d�gitos
                                             //de novo (para o segundo bloco de n�meros)
    v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2") //Coloca um h�fen entre o terceiro e o quarto d�gitos
    return v
}


function num_parcela(v){
    v=v.replace(/(\d{2})(\d)/,"$1 de $2")    //Coloca h�fen entre o quarto e o quinto d�gitos
    return v
}

function numero(v){
    v=v.replace(/\D/g,"")                 //Remove tudo o que n�o � d�gito
    return v
}


function numero_real(v){
    v=v.replace(/\D/g,".")                 //Remove tudo o que n�o � d�gito exceto "."
    return v
}


function num_cep(v){
    v=v.replace(/\D/g,"")                           //Remove tudo o que n�o � d�gito
    v=v.replace(/^(\d{2})(\d)/,"$1.$2")             //Coloca ponto entre o segundo e o terceiro d�gitos
    v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2-$3") //Coloca ponto entre o quinto e o sexto d�gitos
    return v
}

function num_telefone(v){
    v=v.replace(/^(\d{2})(\d)/,"($1) $2")             //Coloca ponto entre o segundo e o terceiro d�gitos
    return v
}

function mtempo(v){
    v=v.replace(/\D/g,"");                    //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{2})(\d{2})(\d{2})/,"$1:$2:$3");    
    return v;
}

function mhora(v){
    v=v.replace(/\D/g,"");                    //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{2})(\d)/,"$1h$2");       
    return v;
}

function mrg(v){                                           //  RG - n�mero Identidade
	v=v.replace(/\D/g,"");                                      //Remove tudo o que n�o � d�gito
	v=v.replace(/(\d)(\d{7})$/,"$1.$2");    //Coloca o . antes dos �ltimos 3 d�gitos, e antes do verificador
	v=v.replace(/(\d)(\d{4})$/,"$1.$2");    //Coloca o . antes dos �ltimos 3 d�gitos, e antes do verificador
	v=v.replace(/(\d)(\d)$/,"$1-$2");               //Coloca o - antes do �ltimo d�gito
    return v;
}

function conta_banco(v){
	v=v.replace(/[^X0-9]/g,"");				//Remove tudo o que n�o � d�gito exceto a letra "x"
	v=v.replace(/(\d)(\d{7})$/,"$1.$2");    //Coloca o . antes dos �ltimos 3 d�gitos, e antes do verificador
	v=v.replace(/(\d)(\d{4})$/,"$1.$2");    //Coloca o . antes dos �ltimos 3 d�gitos, e antes do verificador
	v=v.replace(/(\d)(\d)$/,"$1-$2");		//Coloca o - antes do �ltimo d�gito
    return v;
}


function mvalor(v){				// N�MERO REAL R$ ex.: 1.562,00
    v=v.replace(/\D/g,"");//Remove tudo o que n�o � d�gito
    v=v.replace(/(\d)(\d{8})$/,"$1.$2");//coloca o ponto dos milh�es
    v=v.replace(/(\d)(\d{5})$/,"$1.$2");//coloca o ponto dos milhares
        
    v=v.replace(/(\d)(\d{2})$/,"$1,$2");//coloca a virgula antes dos 2 �ltimos d�gitos
    return v;
}



function Valor(v){				// N�MERO DECIMAL SEM "PONTO" NO MILHAR ex.: 1562,00
	v=v.replace(/\D/g,"") //Remove tudo o que n�o � d�gito
	v=v.replace(/^([0-9]{3}\.?){3}-[0-9]{2}$/,"$1.$2");
//v=v.replace(/(\d{3})(\d)/g,"$1,$2")
v=v.replace(/(\d)(\d{2})$/,"$1,$2") //Coloca VIRGULA antes dos 2 �ltimos digitos
    v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2") //Coloca um h�fen entre o terceiro e o quarto d�gitos
return v
}



function m_quantidade(v){		// N�MERO SEM CASA DECIMAL R$ ex.: 12.562
    v=v.replace(/\D/g,"");//Remove tudo o que n�o � d�gito
    v=v.replace(/(\d)(\d{9})$/,"$1.$2");//coloca o ponto dos trilh�es
    v=v.replace(/(\d)(\d{6})$/,"$1.$2");//coloca o ponto dos milh�es
    v=v.replace(/(\d)(\d{3})$/,"$1.$2");//coloca o ponto dos milhares
    return v;
}

function m_quantidade_kg(v){		// N�MERO COM 3 CASAS DECIMAIS R$ ex.: 2,558
    v=v.replace(/\D/g,"");//Remove tudo o que n�o � d�gito
    v=v.replace(/(\d)(\d{6})$/,"$1.$2");//coloca o ponto dos milh�es
    v=v.replace(/(\d)(\d{3})$/,"$1,$2");//coloca o ponto dos milhares
    return v;
}

<!-- ==================================================================================================== -->



<!-- ======================================== Fun��o Troca V�rgula ====================================== -->
function troca(c){
	campo=c;
	setTimeout("exec_troca()",1);
}
function exec_troca(){
	campo.value=virgula(campo.value);
}
function virgula(texto) {
	texto=texto.replace(",",".");
	return texto;
}
<!-- ==================================================================================================== -->









<!-- ======================================== Fun��o converte minusculas em MAIUSCULAS ====================================== -->
function alteraMaiusculo(lstr){ // converte minusculas em maiusculas
var str=lstr.value; //obtem o valor
lstr.value=str.toUpperCase(); //converte as strings e retorna ao campo
}
<!-- ==================================================================================================== -->


<!-- ======================================== Fun��o converte MAIUSCULAS em minusculas ====================================== -->
function alteraMinusculo(lstr){ // converte maiusculas em minusculas
var str=lstr.value; //obtem o valor
lstr.value=str.toLowerCase(); //converte as strings e retorna ao campo
}
<!-- ==================================================================================================== -->




<!-- ======================================== CALEND�RIO JQUERY ====================================== -->
$(function() {
    $("#calendario").datepicker({
        changeMonth: true,
        changeYear: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Ter&ccedil;a','Quarta','Quinta','Sexta','S&aacute;bado','Domingo'],
        dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','S&aacute;b','Dom'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','S&aacute;b','Dom'],
        monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']

    });
});
<!-- ==================================================================================================== -->

<!-- ======================================== CALEND�RIO JQUERY ====================================== -->
$(function() {
    $("#calendario_2").datepicker({
        changeMonth: true,
        changeYear: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Ter&ccedil;a','Quarta','Quinta','Sexta','S&aacute;bado','Domingo'],
        dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','S&aacute;b','Dom'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','S&aacute;b','Dom'],
        monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']

    });
});
<!-- ==================================================================================================== -->


<!-- ======================================== CALEND�RIO JQUERY ====================================== -->
$(function() {
    $("#calendario_3").datepicker({
        changeMonth: true,
        changeYear: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Ter&ccedil;a','Quarta','Quinta','Sexta','S&aacute;bado','Domingo'],
        dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','S&aacute;b','Dom'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','S&aacute;b','Dom'],
        monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']

    });
});
<!-- ==================================================================================================== -->




