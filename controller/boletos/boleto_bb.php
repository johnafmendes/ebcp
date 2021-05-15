<?php
// +----------------------------------------------------------------------+
// | BoletoPhp - Versão Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo está disponível sob a Licença GPL disponível pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Você deve ter recebido uma cópia da GNU Public License junto com     |
// | esse pacote; se não, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colaborações de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de João Prado Maia e Pablo Martins F. Costa				        |
// | 														                                   			  |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +--------------------------------------------------------------------------------------------------------+
// | Equipe Coordenação Projeto BoletoPhp: <boletophp@boletophp.com.br>              		             				|
// | Desenvolvimento Boleto Banco do Brasil: Daniel William Schultz / Leandro Maniezo / Rogério Dias Pereira|
// +--------------------------------------------------------------------------------------------------------+


// ------------------------- DADOS DINÂMICOS DO SEU CLIENTE PARA A GERAÇÃO DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formulário c/ POST, GET ou de BD (MySql,Postgre,etc)	//

foreach($_POST as $chave => $valor){
	$var[$chave] = htmlspecialchars(trim($valor));
}

// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = 0;
$taxa_boleto = 0;
$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
$valor_cobrado = isset($var['valor']) ? $var['valor'] : "0,10"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

$dadosboleto["nosso_numero"] = isset($var['nossoNumero']) ? $var['nossoNumero'] : "00002";
$dadosboleto["numero_documento"] = isset($var['numDocumento']) ? $var['numDocumento'] : "00.000000.10";	// Num do pedido ou do documento
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = isset($var['valor']) ? $var['valor'] : $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] = isset($var['sacado_nome']) ? $var['sacado_nome'] : "John Mendes";
$dadosboleto["endereco1"] = isset($var['sacado_endereco']) ? $var['sacado_endereco'] : "Rua Moscados";
$dadosboleto["endereco2"] = isset($var['sacado_endereco1']) ? $var['sacado_endereco1'] : "Maringá, PR -  CEP: 00000-000";

// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = isset($var['demonstrativo1']) ? $var['demonstrativo1'] : "Pagamento de Inscrição de Concurso Público";
$dadosboleto["demonstrativo2"] = isset($var['demonstrativo2']) ? $var['demonstrativo2'] : "Concurso: x";
$dadosboleto["demonstrativo3"] = isset($var['demonstrativo3']) ? $var['demonstrativo3'] : "Cargo: x";

// INSTRUÇÕES PARA O CAIXA
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


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


// DADOS DA SUA CONTA - BANCO DO BRASIL
$dadosboleto["agencia"] = isset($var['agencia']) ? $var['agencia'] : "0718";; // Num da agencia, sem digito
$dadosboleto["conta"] = isset($var['conta']) ? $var['conta'] : "61340"; 	// Num da conta, sem digito

// DADOS PERSONALIZADOS - BANCO DO BRASIL
$dadosboleto["convenio"] = isset($var['cedente_codigo']) ? $var['cedente_codigo'] : "940470";  // Num do convênio - REGRA: 6 ou 7 ou 8 dígitos
$dadosboleto["contrato"] = "999999"; // Num do seu contrato
$dadosboleto["carteira"] = "18";
$dadosboleto["variacao_carteira"] = "-019";  // Variação da Carteira, com traço (opcional)

// TIPO DO BOLETO
$dadosboleto["formatacao_convenio"] = "7"; // REGRA: 8 p/ Convênio c/ 8 dígitos, 7 p/ Convênio c/ 7 dígitos, ou 6 se Convênio c/ 6 dígitos
$dadosboleto["formatacao_nosso_numero"] = "2"; // REGRA: Usado apenas p/ Convênio c/ 6 dígitos: informe 1 se for NossoNúmero de até 5 dígitos ou 2 para opção de até 17 dígitos

/*
#################################################
DESENVOLVIDO PARA CARTEIRA 18

- Carteira 18 com Convenio de 8 digitos
  Nosso número: pode ser até 9 dígitos

- Carteira 18 com Convenio de 7 digitos
  Nosso número: pode ser até 10 dígitos

- Carteira 18 com Convenio de 6 digitos
  Nosso número:
  de 1 a 99999 para opção de até 5 dígitos
  de 1 a 99999999999999999 para opção de até 17 dígitos

#################################################
*/


// SEUS DADOS
$dadosboleto["identificacao"] = isset($var['cedente_identificacao']) ? $var['cedente_identificacao'] : "EBCP Concursos";
$dadosboleto["cpf_cnpj"] = isset($var['cedente_cpf_cnpj']) ? $var['cedente_cpf_cnpj'] : "13.309.336/0001-82";
$dadosboleto["endereco"] = isset($var['cedente_endereco']) ? $var['cedente_endereco'] : "Rua Colipheu de Azevedo Marques, 65";
$dadosboleto["cidade_uf"] = isset($var['cedente_cidade_uf']) ? $var['cedente_cidade_uf'] : "Maringá, PR - 87030-250";
$dadosboleto["cedente"] = isset($var['cedente_razao_social']) ? $var['cedente_razao_social'] : "Empresa Brasileira de Concursos Públicos ltda.";

// NÃO ALTERAR!
include("funcoes_bb.php"); 
include("../../view/layout_bb.php");
?>