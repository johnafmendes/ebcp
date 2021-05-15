<?php
// +----------------------------------------------------------------------+
// | BoletoPhp - Vers�o Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo est� dispon�vel sob a Licen�a GPL dispon�vel pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Voc� deve ter recebido uma c�pia da GNU Public License junto com     |
// | esse pacote; se n�o, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colabora��es de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do    |
// | PHPBoleto de Jo�o Prado Maia e Pablo Martins F. Costa                |
// |                                                                      |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Equipe Coordena��o Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
// | Desenvolvimento Boleto BANCOOB/SICOOB: Marcelo de Souza              |
// | Ajuste de algumas rotinas: Anderson Nuernberg                        |
// +----------------------------------------------------------------------+


// ------------------------- DADOS DIN�MICOS DO SEU CLIENTE PARA A GERA��O DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formul�rio c/ POST, GET ou de BD (MySql,Postgre,etc)	//

foreach($_POST as $chave => $valor){
	$var[$chave] = htmlspecialchars(trim($valor));
}

// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = 7;
$taxa_boleto = 0;
$data_venc = isset($var['vencimento']) ? $var['vencimento'] : $data_venc;//date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
$valor_cobrado = isset($var['valor']) ? $var['valor'] : "0,10"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

// $dadosboleto["nosso_numero"] = isset($var['nossoNumero']) ? $var['nossoNumero'] : "00002";  // At� 8 digitos, sendo os 2 primeiros o ano atual (Ex.: 08 se for 2008)


/*************************************************************************
 * +++
 *************************************************************************/

// http://www.bancoob.com.br/atendimentocobranca/CAS/2_Implanta%C3%A7%C3%A3o_do_Servi%C3%A7o/Sistema_Proprio/DigitoVerificador.htm
// http://blog.inhosting.com.br/calculo-do-nosso-numero-no-boleto-bancoob-sicoob-do-boletophp/
// http://www.samuca.eti.br
// 
// http://www.bancoob.com.br/atendimentocobranca/CAS/2_Implanta%C3%A7%C3%A3o_do_Servi%C3%A7o/Sistema_Proprio/LinhaDigitavelCodicodeBarras.htm

// Contribui��o de script por:
// 
// Samuel de L. Hantschel
// Site: www.samuca.eti.br
// 

if(!function_exists('formata_numdoc'))
{
	function formata_numdoc($num,$tamanho)
	{
		while(strlen($num)<$tamanho)
		{
			$num="0".$num; 
		}
	return $num;
	}
}

$IdDoSeuSistemaAutoIncremento = isset($var['nossoNumero']) ? $var['nossoNumero'] : "00002";; // Deve informar um numero sequencial a ser passada a fun��o abaixo, At� 6 d�gitos
$agencia = isset($var['agencia']) ? $var['agencia'] : "0718"; // Num da agencia, sem digito
$conta = isset($var['conta']) ? $var['conta'] : "61340"; // Num da conta, sem digito
$convenio = isset($var['cedente_codigo']) ? $var['cedente_codigo'] : "940470"; //Número do convênio indicado no frontend

$NossoNumero = formata_numdoc($IdDoSeuSistemaAutoIncremento,7);
$qtde_nosso_numero = strlen($NossoNumero);
$sequencia = formata_numdoc($agencia,4).formata_numdoc(str_replace("-","",$convenio),10).formata_numdoc($NossoNumero,7);
$cont=0;
$calculoDv = '';
	for($num=0;$num<=strlen($sequencia);$num++)
	{
		$cont++;
		if($cont == 1)
		{
			// constante fixa Sicoob � 3197 
			$constante = 3;
		}
		if($cont == 2)
		{
			$constante = 1;
		}
		if($cont == 3)
		{
			$constante = 9;
		}
		if($cont == 4)
		{
			$constante = 7;
			$cont = 0;
		}
		$calculoDv = $calculoDv + (substr($sequencia,$num,1) * $constante);
	}
$Resto = $calculoDv % 11;
$Dv = 11 - $Resto;
if ($Dv == 0) $Dv = 0;
if ($Dv == 1) $Dv = 0;
if ($Dv > 9) $Dv = 0;
$dadosboleto["nosso_numero"] = $NossoNumero . $Dv;

/*************************************************************************
 * +++
 *************************************************************************/



$dadosboleto["numero_documento"] = isset($var['numDocumento']) ? $var['numDocumento'] : "00.000000.10";	// Num do pedido ou do documento
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emiss�o do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com v�rgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] = isset($var['sacado_nome']) ? $var['sacado_nome'] : "John Mendes";
$dadosboleto["endereco1"] = isset($var['sacado_endereco']) ? $var['sacado_endereco'] : "Rua Moscados";
$dadosboleto["endereco2"] = isset($var['sacado_endereco1']) ? $var['sacado_endereco1'] : "Maringá, PR -  CEP: 00000-000";

// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = isset($var['demonstrativo1']) ? $var['demonstrativo1'] : "Pagamento de Inscrição de Concurso Público";
$dadosboleto["demonstrativo2"] = isset($var['demonstrativo2']) ? $var['demonstrativo2'] : "Concurso: x";
$dadosboleto["demonstrativo3"] = isset($var['demonstrativo3']) ? $var['demonstrativo3'] : "Cargo: x";

// INSTRU��ES PARA O CAIXA
$dadosboleto["instrucoes1"] = isset($var['instrucoes1']) ? $var['instrucoes1'] : "- Sr. Caixa, não cobrar multa nem juros";
$dadosboleto["instrucoes2"] = isset($var['instrucoes2']) ? $var['instrucoes2'] : "- Não receber após o vencimento";
$dadosboleto["instrucoes3"] = isset($var['instrucoes3']) ? $var['instrucoes3'] : "- Em caso de dúvidas entre em contato conosco: concurso@ebcpconcursos.com.br";
$dadosboleto["instrucoes4"] = isset($var['instrucoes4']) ? $var['instrucoes4'] : "- Maiores informações acesse www.ebcpconcursos.com.br";

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "N";		
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DM";


// ---------------------- DADOS FIXOS DE CONFIGURA��O DO SEU BOLETO --------------- //
// DADOS ESPECIFICOS DO SICOOB
$dadosboleto["modalidade_cobranca"] = "02";
$dadosboleto["numero_parcela"] = "901";


// DADOS DA SUA CONTA - BANCO SICOOB
$dadosboleto["agencia"] = $agencia; // Num da agencia, sem digito
$dadosboleto["conta"] = $conta; // Num da conta, sem digito

// DADOS PERSONALIZADOS - SICOOB
$dadosboleto["convenio"] = $convenio; // Num do conv�nio - REGRA: No m�ximo 7 d�gitos
$dadosboleto["carteira"] = "1";

// SEUS DADOS
$dadosboleto["identificacao"] = isset($var['cedente_identificacao']) ? $var['cedente_identificacao'] : "EBCP Concursos";
$dadosboleto["cpf_cnpj"] = isset($var['cedente_cpf_cnpj']) ? $var['cedente_cpf_cnpj'] : "13.309.336/0001-82";
$dadosboleto["endereco"] = isset($var['cedente_endereco']) ? $var['cedente_endereco'] : "Rua Colipheu de Azevedo Marques, 65";
$dadosboleto["cidade_uf"] = isset($var['cedente_cidade_uf']) ? $var['cedente_cidade_uf'] : "Maringá, PR - 87030-250";
$dadosboleto["cedente"] = isset($var['cedente_razao_social']) ? $var['cedente_razao_social'] : "Empresa Brasileira de Concursos Públicos ltda.";

// N�O ALTERAR!
include("funcoes_bancoob.php");
include("../../view/layout_bancoob.php");
?>