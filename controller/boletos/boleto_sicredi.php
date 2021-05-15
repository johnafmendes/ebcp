<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
// +----------------------------------------------------------------------+
// | BoletoPhp - Vers�o Beta |
// +----------------------------------------------------------------------+
// | Este arquivo est� dispon�vel sob a Licen�a GPL dispon�vel pela Web |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License |
// | Voc� deve ter recebido uma c�pia da GNU Public License junto com |
// | esse pacote; se n�o, escreva para: |
// | |
// | Free Software Foundation, Inc. |
// | 59 Temple Place - Suite 330 |
// | Boston, MA 02111-1307, USA. |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colabora��es de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do |
// | PHPBoleto de Jo�o Prado Maia e Pablo Martins F. Costa |
// | |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Equipe Coordena��o Projeto BoletoPhp: <boletophp@boletophp.com.br> |
// | Desenv Boleto SICREDI: Rafael Azenha Aquini <rafael@tchesoft.com> |
// | Marco Antonio Righi <marcorighi@tchesoft.com> |
// | Homologa��o e ajuste de algumas rotinas. |
// | Marcelo Belinato <mbelinato@gmail.com> |
// +----------------------------------------------------------------------+

// ------------------------- DADOS DIN�MICOS DO SEU CLIENTE PARA A GERA��O DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formul�rio c/ POST, GET ou de BD (MySql,Postgre,etc) //


foreach($_POST as $chave => $valor){
	$var[$chave] = htmlspecialchars(trim($valor));
}

// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = 5;
$taxa_boleto = 0.0;
$data_venc = date ( "d/m/Y", time () + ($dias_de_prazo_para_pagamento * 86400) ); // Prazo de X dias OU informe data: "13/04/2006";
$valor_cobrado = isset($var['valor']) ? $var['valor'] : "0,10"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace ( ",", ".", $valor_cobrado );
$valor_boleto = number_format ( $valor_cobrado + $taxa_boleto, 2, ',', '' );

$dadosboleto ["inicio_nosso_numero"] = date ( "y" ); // Ano da geração do título ex: 07 para 2007
$dadosboleto ["nosso_numero"] = isset($var['nossoNumero']) ? $var['nossoNumero'] : "00002"; // Nosso numero (máx. 5 digitos) - Numero sequencial de controle.
$dadosboleto ["numero_documento"] = isset($var['numDocumento']) ? $var['numDocumento'] : "00.000000.10"; // Num do pedido ou do documento
$dadosboleto ["data_vencimento"] = isset($var['vencimento']) ? $var['vencimento'] : $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto ["data_documento"] = date ( "d/m/Y" ); // Data de emiss�o do Boleto
$dadosboleto ["data_processamento"] = date ( "d/m/Y" ); // Data de processamento do boleto (opcional)
$dadosboleto ["valor_boleto"] = isset($var['valor']) ? $var['valor'] : $valor_boleto; // Valor do Boleto - REGRA: Com v�rgula e sempre com duas casas depois da virgula
                                              
// DADOS DO SEU CLIENTE
$dadosboleto ["sacado"] = isset($var['sacado_nome']) ? $var['sacado_nome'] : "John Mendes";
$dadosboleto ["endereco1"] = isset($var['sacado_endereco']) ? $var['sacado_endereco'] : "Rua Moscados";
$dadosboleto ["endereco2"] = isset($var['sacado_endereco1']) ? $var['sacado_endereco1'] : "Maringá, PR -  CEP: 00000-000";

// INFORMACOES PARA O CLIENTE
$dadosboleto ["demonstrativo1"] = isset($var['demonstrativo1']) ? $var['demonstrativo1'] : "Pagamento de Inscrição de Concurso Público";
$dadosboleto ["demonstrativo2"] = isset($var['demonstrativo2']) ? $var['demonstrativo2'] : "Concurso: x";
$dadosboleto ["demonstrativo3"] = isset($var['demonstrativo3']) ? $var['demonstrativo3'] : "Cargo: x";

// INSTRUÇÕES PARA O CAIXA
$dadosboleto ["instrucoes1"] = isset($var['instrucoes1']) ? $var['instrucoes1'] : "- Sr. Caixa, não cobrar multa nem juros";
$dadosboleto ["instrucoes2"] = isset($var['instrucoes2']) ? $var['instrucoes2'] : "- Não receber após o vencimento";
$dadosboleto ["instrucoes3"] = isset($var['instrucoes3']) ? $var['instrucoes3'] : "- Em caso de dúvidas entre em contato conosco: concurso@ebcpconcursos.com.br";
$dadosboleto ["instrucoes4"] = isset($var['instrucoes4']) ? $var['instrucoes4'] : "- Maiores informações acesse www.ebcpconcursos.com.br";

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto ["quantidade"] = "";
$dadosboleto ["valor_unitario"] = "";
$dadosboleto ["aceite"] = "N"; // N - remeter cobran�a sem aceite do sacado (cobran�as n�o-registradas)
                              // S - remeter cobran�a apos aceite do sacado (cobran�as registradas)
$dadosboleto ["especie"] = "R$";
$dadosboleto ["especie_doc"] = "A"; // OS - Outros segundo manual para cedentes de cobran�a SICREDI
                                   
// ---------------------- DADOS FIXOS DE CONFIGURA��O DO SEU BOLETO --------------- //
                                   
// DADOS DA SUA CONTA - SICREDI
$dadosboleto ["agencia"] = isset($var['agencia']) ? $var['agencia'] : "0718"; // Num da agencia (4 digitos), sem Digito Verificador
$dadosboleto ["conta"] = isset($var['conta']) ? $var['conta'] : "61340"; // Num da conta (5 digitos), sem Digito Verificador
$dadosboleto ["conta_dv"] = isset($var['conta_dv']) ? $var['conta_dv'] : "7"; // Digito Verificador do Num da conta
                                
// DADOS PERSONALIZADOS - SICREDI
$dadosboleto ["posto"] = "28"; // Código do posto da cooperativa de crédito
$dadosboleto ["byte_idt"] = "2"; // Byte de identificação do cedente do bloqueto utilizado para compor o nosso número.
                               // 1 - Idtf emitente: Cooperativa | 2 a 9 - Idtf emitente: Cedente
$dadosboleto ["carteira"] = "A"; // Código da Carteira: A (Simples)
                                
// SEUS DADOS
$dadosboleto ["identificacao"] = isset($var['cedente_identificacao']) ? $var['cedente_identificacao'] : "EBCP Concursos";
$dadosboleto ["cpf_cnpj"] = isset($var['cedente_cpf_cnpj']) ? $var['cedente_cpf_cnpj'] : "13.309.336/0001-82";
$dadosboleto ["endereco"] = isset($var['cedente_endereco']) ? $var['cedente_endereco'] : "Rua Colipheu de Azevedo Marques, 65";
$dadosboleto ["cidade_uf"] = isset($var['cedente_cidade_uf']) ? $var['cedente_cidade_uf'] : "Maringá, PR - 87030-250";
$dadosboleto ["cedente"] = isset($var['cedente_razao_social']) ? $var['cedente_razao_social'] : "Empresa Brasileira de Concursos Públicos ltda.";

// N�O ALTERAR!
include ("funcoes_sicredi.php");
include ("../../view/layout_sicredi.php");
?>