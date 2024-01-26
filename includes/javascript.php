<!-- ================== Função Bloqueia ENTER =========================================================== -->
function getKey(event) {
        return event?(event.keyCode?event.keyCode:(event.which?event.which:event.charCode)):null;
}
<!-- ==================================================================================================== -->


<!-- ================== Função Abre Janela Pop-up =========================================================== -->
function abrir(programa,janela)
	{
		if(janela=="") janela = "janela";
		window.open(programa,janela,'height=265,width=260');
	}
<!-- ======================================================================================================= -->


<!-- ====================   Voltar / Avançar / Atualizar   ========================================================== -->
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



<!-- =================================================== Função Máscara ====================================== -->
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}


function data(v){
    v=v.replace(/\D/g,"")                 //Remove tudo o que não é dígito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")    //Coloca hífen entre o quarto e o quinto dígitos
    v=v.replace(/(\d{2})(\d)/,"$1/$2")    //Coloca hífen entre o quarto e o quinto dígitos
    return v
}

function num_cnpj(v){
    v=v.replace(/\D/g,"")                           //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/,"$1.$2")             //Coloca ponto entre o segundo e o terceiro dígitos
    v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3") //Coloca ponto entre o quinto e o sexto dígitos
    v=v.replace(/\.(\d{3})(\d)/,".$1/$2")           //Coloca uma barra entre o oitavo e o nono dígitos
    v=v.replace(/(\d{4})(\d)/,"$1-$2")              //Coloca um hífen depois do bloco de quatro dígitos
    return v
}

function num_cpf(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
                                             //de novo (para o segundo bloco de números)
    v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2") //Coloca um hífen entre o terceiro e o quarto dígitos
    return v
}


function num_parcela(v){
    v=v.replace(/(\d{2})(\d)/,"$1 de $2")    //Coloca hífen entre o quarto e o quinto dígitos
    return v
}

function numero(v){
    v=v.replace(/\D/g,"")                 //Remove tudo o que não é dígito
    return v
}


function numero_real(v){
    v=v.replace(/\D/g,".")                 //Remove tudo o que não é dígito exceto "."
    return v
}


function num_cep(v){
    v=v.replace(/\D/g,"")                           //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/,"$1.$2")             //Coloca ponto entre o segundo e o terceiro dígitos
    v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2-$3") //Coloca ponto entre o quinto e o sexto dígitos
    return v
}

function num_telefone(v){
    v=v.replace(/^(\d{2})(\d)/,"($1) $2")             //Coloca ponto entre o segundo e o terceiro dígitos
    return v
}

function mtempo(v){
    v=v.replace(/\D/g,"");                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{2})(\d{2})(\d{2})/,"$1:$2:$3");    
    return v;
}

function mhora(v){
    v=v.replace(/\D/g,"");                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{2})(\d)/,"$1h$2");       
    return v;
}

function mrg(v){                                           //  RG - número Identidade
	v=v.replace(/\D/g,"");                                      //Remove tudo o que não é dígito
	v=v.replace(/(\d)(\d{7})$/,"$1.$2");    //Coloca o . antes dos últimos 3 dígitos, e antes do verificador
	v=v.replace(/(\d)(\d{4})$/,"$1.$2");    //Coloca o . antes dos últimos 3 dígitos, e antes do verificador
	v=v.replace(/(\d)(\d)$/,"$1-$2");               //Coloca o - antes do último dígito
    return v;
}

function conta_banco(v){
	v=v.replace(/[^X0-9]/g,"");				//Remove tudo o que não é dígito exceto a letra "x"
	v=v.replace(/(\d)(\d{7})$/,"$1.$2");    //Coloca o . antes dos últimos 3 dígitos, e antes do verificador
	v=v.replace(/(\d)(\d{4})$/,"$1.$2");    //Coloca o . antes dos últimos 3 dígitos, e antes do verificador
	v=v.replace(/(\d)(\d)$/,"$1-$2");		//Coloca o - antes do último dígito
    return v;
}


function mvalor(v){				// NÚMERO REAL R$ ex.: 1.562,00
    v=v.replace(/\D/g,"");//Remove tudo o que não é dígito
    v=v.replace(/(\d)(\d{8})$/,"$1.$2");//coloca o ponto dos milhões
    v=v.replace(/(\d)(\d{5})$/,"$1.$2");//coloca o ponto dos milhares
        
    v=v.replace(/(\d)(\d{2})$/,"$1,$2");//coloca a virgula antes dos 2 últimos dígitos
    return v;
}



function Valor(v){				// NÚMERO DECIMAL SEM "PONTO" NO MILHAR ex.: 1562,00
	v=v.replace(/\D/g,"") //Remove tudo o que não é dígito
	v=v.replace(/^([0-9]{3}\.?){3}-[0-9]{2}$/,"$1.$2");
//v=v.replace(/(\d{3})(\d)/g,"$1,$2")
v=v.replace(/(\d)(\d{2})$/,"$1,$2") //Coloca VIRGULA antes dos 2 últimos digitos
    v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2") //Coloca um hífen entre o terceiro e o quarto dígitos
return v
}



function m_quantidade(v){		// NÚMERO SEM CASA DECIMAL R$ ex.: 12.562
    v=v.replace(/\D/g,"");//Remove tudo o que não é dígito
    v=v.replace(/(\d)(\d{9})$/,"$1.$2");//coloca o ponto dos trilhões
    v=v.replace(/(\d)(\d{6})$/,"$1.$2");//coloca o ponto dos milhões
    v=v.replace(/(\d)(\d{3})$/,"$1.$2");//coloca o ponto dos milhares
    return v;
}

function m_quantidade_kg(v){		// NÚMERO COM 3 CASAS DECIMAIS R$ ex.: 2,558
    v=v.replace(/\D/g,"");//Remove tudo o que não é dígito
    v=v.replace(/(\d)(\d{6})$/,"$1.$2");//coloca o ponto dos milhões
    v=v.replace(/(\d)(\d{3})$/,"$1,$2");//coloca o ponto dos milhares
    return v;
}

<!-- ==================================================================================================== -->



<!-- ======================================== Função Troca Vírgula ====================================== -->
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









<!-- ======================================== Função converte minusculas em MAIUSCULAS ====================================== -->
function alteraMaiusculo(lstr){ // converte minusculas em maiusculas
var str=lstr.value; //obtem o valor
lstr.value=str.toUpperCase(); //converte as strings e retorna ao campo
}
<!-- ==================================================================================================== -->


<!-- ======================================== Função converte MAIUSCULAS em minusculas ====================================== -->
function alteraMinusculo(lstr){ // converte maiusculas em minusculas
var str=lstr.value; //obtem o valor
lstr.value=str.toLowerCase(); //converte as strings e retorna ao campo
}
<!-- ==================================================================================================== -->




<!-- ======================================== CALENDÁRIO JQUERY ====================================== -->
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

<!-- ======================================== CALENDÁRIO JQUERY ====================================== -->
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


<!-- ======================================== CALENDÁRIO JQUERY ====================================== -->
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




