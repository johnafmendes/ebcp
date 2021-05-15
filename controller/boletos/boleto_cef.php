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

// +----------------------------------------------------------------------+
// | Equipe Coordenação Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
// | Desenvolvimento Boleto CEF SIGCB: Davi Nunes Camargo				  |
// +----------------------------------------------------------------------+


// ------------------------- DADOS DINÂMICOS DO SEU CLIENTE PARA A GERAÇÃO DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formulário c/ POST, GET ou de BD (MySql,Postgre,etc)	//
foreach($_POST as $chave => $valor){
	$var[$chave] = htmlspecialchars(trim($valor));
}
// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = 5;
$taxa_boleto = 2.95;
$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
$valor_cobrado = "2950,00"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

// Composição Nosso Numero - CEF SIGCB
$dadosboleto["nosso_numero1"] = "000"; // tamanho 3
$dadosboleto["nosso_numero_const1"] = "2"; //constanto 1 , 1=registrada , 2=sem registro
$dadosboleto["nosso_numero2"] = "000"; // tamanho 3
$dadosboleto["nosso_numero_const2"] = "4"; //constanto 2 , 4=emitido pelo proprio cliente
$dadosboleto["nosso_numero3"] = isset($var['nossoNumero']) ? $var['nossoNumero'] : "000000002"; // tamanho 9


$dadosboleto["numero_documento"] = isset($var['numDocumento']) ? $var['numDocumento'] : "00.000000.10";	// Num do pedido ou do documento
$dadosboleto["data_vencimento"] = isset($var['vencimento']) ? $var['vencimento'] : $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = isset($var['valor']) ? $var['valor'] : $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] = isset($var['sacado_nome']) ? $var['sacado_nome'] : "John Mendes";
$dadosboleto["endereco1"] = isset($var['sacado_endereco']) ? $var['sacado_endereco'] : "Rua Moscados";
$dadosboleto["endereco2"] = isset($var['sacado_endereco1']) ? $var['sacado_endereco1'] : "MaringÃ¡, PR -  CEP: 00000-000";

// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = isset($var['demonstrativo1']) ? $var['demonstrativo1'] : "Pagamento de InscriÃ§Ã£o de Concurso PÃºblico";
$dadosboleto["demonstrativo2"] = isset($var['demonstrativo2']) ? $var['demonstrativo2'] : "Concurso: x";
$dadosboleto["demonstrativo3"] = isset($var['demonstrativo3']) ? $var['demonstrativo3'] : "Cargo: x";

// INSTRUÇÕES PARA O CAIXA
$dadosboleto["instrucoes1"] = isset($var['instrucoes1']) ? $var['instrucoes1'] : "- Sr. Caixa, nÃ£o cobrar multa nem juros";
$dadosboleto["instrucoes2"] = isset($var['instrucoes2']) ? $var['instrucoes2'] : "- NÃ£o receber apÃ³s o vencimento";
$dadosboleto["instrucoes3"] = isset($var['instrucoes3']) ? $var['instrucoes3'] : "- Em caso de dÃºvidas entre em contato conosco: concurso@ebcpconcursos.com.br";
$dadosboleto["instrucoes4"] = isset($var['instrucoes4']) ? $var['instrucoes4'] : "- Maiores informaÃ§Ãµes acesse www.ebcpconcursos.com.br";

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";		
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


// DADOS DA SUA CONTA - CEF
$dadosboleto["agencia"] = isset($var['agencia']) ? $var['agencia'] : "1546"; // Num da agencia, sem digito
$dadosboleto["conta"] = isset($var['conta']) ? $var['conta'] : "4299";  	// Num da conta, sem digito
$dadosboleto["conta_dv"] = isset($var['conta_dv']) ? $var['conta_dv'] : "0"; 	// Digito do Num da conta

// DADOS PERSONALIZADOS - CEF
$dadosboleto["conta_cedente"] = isset($var['cedente_codigo']) ? $var['cedente_codigo'] : "463970"; // ContaCedente do Cliente, sem digito (Somente NÃºmeros)
$dadosboleto["conta_cedente_dv"] = isset($var['cedente_codigo_dv']) ? $var['cedente_codigo_dv'] : "7"; // Digito da ContaCedente do Cliente
$dadosboleto["carteira"] = "SR";  // CÃ³digo da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)

// SEUS DADOS
$dadosboleto["identificacao"] = isset($var['cedente_identificacao']) ? $var['cedente_identificacao'] : "EBCP Concursos";
$dadosboleto["cpf_cnpj"] = isset($var['cedente_cpf_cnpj']) ? $var['cedente_cpf_cnpj'] : "13.309.336/0001-82";
$dadosboleto["endereco"] = isset($var['cedente_endereco']) ? $var['cedente_endereco'] : "Rua Colipheu de Azevedo Marques, 65";
$dadosboleto["cidade_uf"] = isset($var['cedente_cidade_uf']) ? $var['cedente_cidade_uf'] : "MaringÃ¡, PR - 87030-250";
$dadosboleto["cedente"] = isset($var['cedente_razao_social']) ? $var['cedente_razao_social'] : "Empresa Brasileira de Concursos PÃºblicos ltda.";

// NÃO ALTERAR!
include("funcoes_cef_sigcb.php"); 
include("../../view/layout_cef.php");
?>