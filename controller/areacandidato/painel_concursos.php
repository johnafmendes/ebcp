<?php
	include("view/template.class.php");
	include("model/dao/ConcursoDAO.php");
	include("model/dao/CidadeProvaDAO.php");
	include("model/dao/CandidatoDAO.php");
 	include("model/dao/BoletoDAO.php");
	include("model/dao/CargoDAO.php");
	include("model/dao/RecursoDAO.php");	
	include("model/dao/RecursoCandidatoDAO.php");
	include("model/dao/ProvaDAO.php");
	include("model/dao/InscricaoDAO.php");
	include("model/entidade/RecursoCandidato.php");	
	include("controller/coluna_lateral.php");
	include("controller/areacandidato/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idconcurso']) ? $idConcurso = $_GET['idconcurso'] : $idConcurso = "";
	isset($_GET['idinscricao']) ? $idInscricao = $_GET['idinscricao'] : $idInscricao = "";
	
 	$recursoCandidato = new RecursoCandidato();
	/*dados do formulário*/
	
	isset($_POST['idrecurso']) ? $recursoCandidato->setidrecurso($_POST['idrecurso']) : $recursoCandidato->setidrecurso(null);
	isset($_POST['idinscricao']) ? $recursoCandidato->setidinscricao($_POST['idinscricao']) : $recursoCandidato->setidinscricao(null);
	isset($_POST['recurso']) ? $recursoCandidato->settextorecurso($_POST['recurso']) : $recursoCandidato->settextorecurso(null);	
	
	$titulo = "Falha na Autenticação";
	$conteudo = "Seu CPF ou Senha estão errados. Por favor, digite novamente, ou solicite a recuperação da senha.";
	$tituloLateral = "";
	$conteudoLateral = "";
	
	$concursoDAO = new ConcursoDAO();
// 	$inscricaoDAO = new InscricaoDAO();
	
	switch ($acao){
		case "meus_concursos" :
			if (isset($_SESSION['autenticado'])){
				$listaConcursos = $concursoDAO->listarConcursosInscritosPorIdCandidato($_SESSION['idCandidato']);
				if($listaConcursos != null){
					$conteudo = montaFormularioConcursosInscritos($listaConcursos);
				} else {
					$conteudo = "Você não esta inscrito em nenhum concurso";
				}
				$titulo = "Concursos Inscritos";
// 				$corpoTemplate = new Template("view/areaCandidato.tpl");
				$tituloLateral = $tituloLateralAutenticado;
				$conteudoLateral = $conteudoLateralAutenticado;
			} else {
				//falha na autenticacao
			}
			break;
			
		case "detalhe_concurso" :		
			if (isset($_SESSION['autenticado'])){
				$concurso = $concursoDAO->getConcursoPorID($idConcurso)->fetch(PDO::FETCH_OBJ);
				
				$recursoDAO = new RecursoDAO();
				$listaRecursos = $recursoDAO->listarRecursosPorIdConcurso($idConcurso);

				$recursoCandidatoDAO = new RecursoCandidatoDAO();
				$listaRecursosSolicitados = $recursoCandidatoDAO->listarRecursosPorIdConcurso($idConcurso, null, null, null, $idInscricao);
				
				$editalDAO = new EditalDAO();
				$listaEditais = $editalDAO->getEditaisPorIdConcurso($idConcurso);
				
				$inscricaoDAO = new InscricaoDAO();
				$inscricao = $inscricaoDAO->getInscricaoPorId($idInscricao)->fetch(PDO::FETCH_OBJ);
				
				$provaDAO = new ProvaDAO();
				$listaProvas = $provaDAO->listarProvasPorIdCargo($inscricao->id_cargo) ;
				
				$conteudo  = "<div class=\"tituloConcurso\">" . $concurso->titulo . "</div>"
						. "<div class=\"subtituloConcurso\">" . $concurso->subtitulo . "</div><br/>"
						. "Inscrição Número: " . $idInscricao . "<br/>"
						. "Período de Inscrições: " . date("d/m/Y", strtotime($concurso->inicio_inscricao)) . " a " . date("d/m/Y", strtotime($concurso->final_inscricao))
						. "<br/><br/>"
						. montaCidadeProva($concurso->id_concurso, $inscricao->id_cidade_prova)
						. montaFormularioRecursos($listaRecursos, $idInscricao, $listaRecursosSolicitados)
						. montaListaEditais($listaEditais)
						. montaListaProva($listaProvas);
				
				$titulo = "Detalhes do Concurso";
// 				$corpoTemplate = new Template("view/areaCandidato.tpl");
				$tituloLateral = $tituloLateralAutenticado;
				$conteudoLateral = $conteudoLateralAutenticado;
			} else {
				//falha na autenticacao
			}				
			break;
			
		case "enviar_recurso" :
			if (isset($_SESSION['autenticado'])){
				$conteudoErro = "Seu recurso NÂO foi enviado. Dados NÃO foram salvos. <br/><br/>Tente novamente e se o problema persistir, contate-nos.";
				$allowedExts = array("rar", "zip", "pdf", "doc", "docx");
				$temp = explode(".", $_FILES["file"]["name"]);
				$extension = end($temp);
				$uploaded = false;
				$erroUpload = false;
				if($_FILES['file']['error'] === UPLOAD_ERR_OK){//checa se tem arquivo para upload
					if ((($_FILES["file"]["type"] == "application/pdf")
						|| ($_FILES["file"]["type"] == "application/msword") //doc
						|| ($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")//docx
						|| ($_FILES["file"]["type"] == "application/octet-stream")//rar application/x-rar-compressed, 
						|| ($_FILES["file"]["type"] == "application/octet-stream"))//zip application/zip, 
						&& ($_FILES["file"]["size"] < 10485760)//10mb
						&& in_array($extension, $allowedExts)) {
						if ($_FILES["file"]["error"] > 0) {
							$erroUpload = true;
							$conteudoErro = "Erro ao enviar arquivo. <br/><br/>Return Code: " . $_FILES["file"]["error"] . "<br/><br/>" 
									. $conteudoErro . "<br/><br/><a href=\"javascript: window.history.back();\">Voltar</a>";
						} else {
							$uploaded = true;
	// 						echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	// 						echo "Type: " . $_FILES["file"]["type"] . "<br>";
	// 						echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
	// 						echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
	// 						echo "Extensao:" . $extension;
	// 						if (file_exists("upload/" . $_FILES["file"]["name"])) {
	// 							echo $_FILES["file"]["name"] . " already exists. ";
	// 						} else {
	// 							move_uploaded_file($_FILES["file"]["tmp_name"],
	// 							"upload/" . $_FILES["file"]["name"]);
	// 							echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
	// 						}
						}
					} else {
						$erroUpload = true;
	// 					echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	// 					echo "Type: " . $_FILES["file"]["type"] . "<br>";
	// 					echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
	// 					echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
	// 					echo "Extensao:" . $extension;
						$conteudoErro = "Arquivo Inválido. (Tipos de arquivos permitidos - doc, docx, rar, zip e pdf com no máximo 10Mb)<br/><br/>" 
							. $conteudoErro . "<br/><br/><a href=\"javascript: window.history.back();\">Voltar</a>";
					}
				}
						
				if(!$erroUpload){
					$recursoCandidatoDAO = new RecursoCandidatoDAO();			
					$recursoCandidato->setdatahorarecurso(date("Y-m-d H:i:s"));
					$idRecursoCandidato = $recursoCandidatoDAO->salvar($recursoCandidato);
					
					if($idRecursoCandidato > 0){
						if($uploaded){
							$arquivo = $idRecursoCandidato.".".$extension;
							move_uploaded_file($_FILES["file"]["tmp_name"],	"admin/arquivosrecurso/" . $arquivo);
							$recursoCandidatoDAO->updateArquivoRecurso($idRecursoCandidato, $arquivo);
						}
						enviaEmailConfirmacaoRecurso($idInscricao, $idRecursoCandidato);
						$conteudo = montaFormularioConfirmacaoRecurso($idInscricao, $idRecursoCandidato);
					} else {
						$conteudo = $conteudoErro;
					}
				} else {
					$conteudo = $conteudoErro;
				}
				$titulo = "Resultado da Inscrição";
// 				$corpoTemplate = new Template("view/areaCandidato.tpl");
				$tituloLateral = $tituloLateralAutenticado;
				$conteudoLateral = $conteudoLateralAutenticado;
			} else {
				//falha na autenticacao
			}
			break;
	}
		
	$menu = new Template("view/menu.tpl");
	$menu->set("", "active");
	
	if(isset($_SESSION['autenticado'])){
		$corpoTemplate = new Template("view/areaCandidato.tpl");
	}
	
	isset($corpoTemplate) ? $corpo = $corpoTemplate : $corpo = new Template("view/paginaInterna.tpl");
	$corpo->set("titulo", $titulo);
	$corpo->set("conteudo", $conteudo);
	$corpo->set("tituloLateral", $tituloLateral);
	$corpo->set("conteudoLateral", $conteudoLateral);
	$corpo->set("areaCandidato", $areaCandidato);
	
	/**
	 * Loads our layout template, settings its title and content.
	 */
	$layout = new Template("view/layout.tpl");
	$layout->set("title", "EBCP Concursos");
	$layout->set("menu", $menu->output());
	$layout->set("content", $corpo->output());
	
	/**
	 * Outputs the page with the user's profile.
	 */
	echo $layout->output();

	function montaCidadeProva($idConcurso, $idCidadeProva){
		$cidadeProvaDAO = new CidadeProvaDAO();
		
		$listaCidades = $cidadeProvaDAO->listarCidadesProvaPorConcurso($idConcurso);
		$str = "";
		$numCidades = 0;
		$cidadeProva = "";
		if($listaCidades != null){
			while($cidade = $listaCidades->fetch(PDO::FETCH_OBJ)){
				if($cidade->id_cidade_prova == $idCidadeProva){
					$cidadeProva = $cidade->cidade;
				}
				$numCidades++;
			}
		}
		
		if($numCidades > 1){
			$str = "Cidade de Prova: " . $cidadeProva . "<br/><br/>"; 
		}
		
		return $str;
	}
	
	function montaFormularioConcursosInscritos($listaConcursos){
		$candidatoDAO = new CandidatoDAO();
		$cargoDAO = new CargoDAO();
		$boletoDAO = new BoletoDAO();
		
		$str = "<div class=\"corpo\">"
        			. "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">"
						. "<tr style=\"background-color: #7bb181; color: #355239;\">"
							. "<td><b>Inscrição</b></td>"
							. "<td><b>Concurso</b></td>"
							. "<td><b>Cargo</b></td>"
// 							. "<td><b>Cidade da Prova</b></td>"
							. "<td><b><center>Opções</center></b></td>"
							. "<td><b><center>Boleto</center></b></td>"
						. "</tr>"; 
			while ($concurso = $listaConcursos->fetch(PDO::FETCH_OBJ)){
				$candidato = $candidatoDAO->getCandidatoPorID($concurso->id_candidato)->fetch(PDO::FETCH_OBJ);
				$cargo = $cargoDAO->getCargoPorID($concurso->id_cargo)->fetch(PDO::FETCH_OBJ);
				$boleto = $boletoDAO->getBoletoPorID($concurso->id_boleto)->fetch(PDO::FETCH_OBJ);
				
				$str .= "<tr>"
						. "<td>" . $concurso->id_inscricao . "</td>"
						. "<td>" . $concurso->concurso . "</td>"
						. "<td>" . $concurso->cargo . "</td>"
// 						. "<td>" . $concurso->cidade . "</td>"
						. "<td><center><a href=\"?nivel=areacandidato/painel_concursos&acao=detalhe_concurso&idconcurso=" 
								. $concurso->id_concurso . "&idinscricao=" . $concurso->id_inscricao . "\">Mais Opções</a></center></td>"
						. "<td><center>";
							if($concurso->homologado == 0 && $concurso->vencimento_boleto >= date("Y-m-d")){
								$str .= "<form method=\"POST\" action=\"controller/boletos/" . $boleto->nome_arquivo . "\" target=\"_blank\" name=\"formBoleto".$concurso->id_inscricao."\" id=\"formBoleto".$concurso->id_inscricao."\">"
								. "<input name=\"agencia\" type=\"hidden\" id=\"agencia\" value=\"" . $boleto->banco_agencia . "\">"
								. "<input name=\"conta\" type=\"hidden\" id=\"conta\" value=\"" . $boleto->conta_numero . "\">"
								. "<input name=\"conta_dv\" type=\"hidden\" id=\"conta_dv\" value=\"" . $boleto->conta_dv . "\">"
								. "<input name=\"cedente_codigo\" type=\"hidden\" id=\"cedente_codigo\" value=\"" . $boleto->cedente_codigo . "\">"
								. "<input name=\"cedente_codigo_dv\" type=\"hidden\" id=\"cedente_codigo_dv\" value=\"" . $boleto->cedente_codigo_dv . "\">"
								. "<input name=\"cedente_identificacao\" type=\"hidden\" id=\"cedente_identificacao\" value=\"" . $boleto->cedente_identificacao . "\">"
								. "<input name=\"cedente_cpf_cnpj\" type=\"hidden\" id=\"cedente_cpf_cnpj\" value=\"" . $boleto->cedente_cnpj . "\">"
								. "<input name=\"cedente_endereco\" type=\"hidden\" id=\"cedente_endereco\" value=\"" . $boleto->cedente_endereco . "\">"
								. "<input name=\"cedente_cidade_uf\" type=\"hidden\" id=\"cedente_cidade_uf\" value=\"" . $boleto->cedente_cidade . ", " . $boleto->cedente_estado . "\">"
								. "<input name=\"cedente_razao_social\" type=\"hidden\" id=\"cedente_razao_social\" value=\"" . $boleto->cedente_razao_social . "\">"									
								. "<input name=\"demonstrativo1\" type=\"hidden\" id=\"demonstrativo1\" value=\"Concurso: " . $concurso->concurso . "\">"
								. "<input name=\"demonstrativo2\" type=\"hidden\" id=\"demonstrativo2\" value=\"Cargo: " . $concurso->cargo . "\">"
								. "<input name=\"demonstrativo3\" type=\"hidden\" id=\"demonstrativo3\" value=\"Cidade da Prova: " . $concurso->cidade . "\">"
								. "<input name=\"demonstrativo4\" type=\"hidden\" id=\"demonstrativo4\" value=\"Inscrição: " . $concurso->id_inscricao . "\">"
								. "<input name=\"nossoNumero\" type=\"hidden\" id=\"nossoNumero\" value=\"" . $concurso->id_inscricao . "\">"
								. "<input name=\"numDocumento\" type=\"hidden\" id=\"numDocumento\" value=\"" . $concurso->id_inscricao . "\">"
								. "<input name=\"vencimento\" type=\"hidden\" id=\"vencimento\" value=\"" . date("d/m/Y", strtotime($concurso->vencimento_boleto)) . "\">"
								. "<input name=\"valor\" type=\"hidden\" id=\"valor\" value=\"" . number_format($cargo->valor_inscricao, 2, ",", ".") . "\">"
								. "<input name=\"sacado_nome\" type=\"hidden\" id=\"sacado_nome\" value=\"" . $candidato->nome . "\">"
								. "<input name=\"sacado_endereco\" type=\"hidden\" id=\"sacado_endereco\" value=\"" . $candidato->endereco . "\">"
								. "<input name=\"sacado_endereco1\" type=\"hidden\" id=\"sacado_endereco1\" value=\"" . $candidato->cidade . ", ". $candidato->sigla . " - " . $candidato->cep . "\">"
								. "<input onClick=\"document.formBoleto".$concurso->id_inscricao.".submit()\" name=\"Button\" type=\"button\" value=\"Gerar Boleto\">"
								. "</form>";
// 								echo $cargo->valor_inscricao;
							} else if($concurso->homologado == 1) {
								$str .= "Homologado";
							}
						$str .= "</center></td>"
						. "</tr>";
			}
				
		$str .= "</table>" 
      		. "</div>"
	  		. "<div class=\"espacoBranco\"></div>";
		
		return $str;
	}
	
	function montaFormularioRecursos($listaRecursos, $idInscricao, $listaRecursosSolicitados){
		$str = "<div class=\"titulo\"><b>Recursos</b></div>"
        	. "<div class=\"corpo\">"
			. "* Todos os recursos são permitidos somente em dias úteis.<br/><br/>";
		if($listaRecursos != null){
			while($recurso = $listaRecursos->fetch(PDO::FETCH_OBJ)){
				if($recurso->inicio_recurso <= date("Y-m-d H:i:s") && $recurso->final_recurso >= date("Y-m-d H:i:s") && DiaUtil(date("d-m-Y"))){
					$str .= date("d/m/Y H:i:s", strtotime($recurso->inicio_recurso)) . " a " . date("d/m/Y H:i:s", strtotime($recurso->final_recurso))
						. "<br/><b>" . $recurso->tipos_recursos . "</b><br/>"
						.	"<form method=\"POST\" action=\"?nivel=areacandidato/painel_concursos&acao=enviar_recurso&idconcurso=" 
								. $recurso->id_concurso . "&idinscricao=" . $idInscricao . "\" name=\"formRecurso".$recurso->id_recurso."\" id=\"formRecurso\""
								. "enctype=\"multipart/form-data\">"
							. "<input name=\"idrecurso\" type=\"hidden\" id=\"idrecurso\" value=\"" . $recurso->id_recurso . "\">"
							. "<input name=\"idinscricao\" type=\"hidden\" id=\"idinscricao\" value=\"" . $idInscricao . "\">"
							. "<textarea name=\"recurso\" id=\"recurso\" value=\"\" cols=\"85\" rows=\"8\"></textarea><br/>"
							. "<input type=\"file\" name=\"file\" id=\"file\">(doc, docx, pdf, rar e zip com até 10Mb)<br>"
							. "<input onClick=\"document.formRecurso".$recurso->id_recurso.".submit()\" name=\"Button\" type=\"button\" value=\"Enviar Recurso\">"
						. "</form>";
				} else {
					$str .= "<br/>" . date("d/m/Y H:i:s", strtotime($recurso->inicio_recurso)) . " a " . date("d/m/Y H:i:s", strtotime($recurso->final_recurso)) . " - " . $recurso->tipos_recursos;  
				}
			}
		} else {
			$str .= "Não há recursos no momento.";
		}
		
		$str .= "<br/><div class=\"titulo\"><b>Recursos Solicitados</b></div>"
				. "<div class=\"corpo\">";
		if($listaRecursosSolicitados != null){
			$str .= "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">"
				. "<tr style=\"background-color: #7bb181; color: #355239;\">"
// 					. "<td align=\"left\">Inscrição</td>"
					. "<td align=\"left\">No. Recurso</td>"
					. "<td align=\"left\">Tipo Recurso</td>"
					. "<td align=\"center\">Recurso Solicitado</td>"
					. "<td align=\"center\">Resposta Recurso</td>"
				. "</tr>";			
			while($recurso = $listaRecursosSolicitados->fetch(PDO::FETCH_OBJ)){
				$str .= "<tr>"
// 					. "<td align=\"left\">" . $recurso->id_inscricao . "</td>"
					. "<td align=\"left\">" . $recurso->id_recurso_candidato . "</td>"
					. "<td align=\"left\">" . $recurso->tipos_recursos . "</td>"
					. "<td align=\"center\">" . $recurso->texto_recurso . ($recurso->arquivo_anexo != null ? "<br/><br/>Arquivo Recurso: <a href=\"admin/arquivosrecurso/" . $recurso->arquivo_anexo . "\" target=\"_blank\">Anexo</a>" : "") . "</td>"
					. "<td align=\"center\">" . ($recurso->caminho_arquivo != null ? "<a href=\"admin/arquivoseditais/" . $recurso->caminho_arquivo . "\" target=\"_blank\">Edital</a>" : "Aguardando") . "</td>"
				. "</tr>";
			}
			$str .= "</table>";
		} else {
			$str .= "Nenhum Recurso Solicitado";
		}
		$str .= "</div>";		
		
		$str .= "</div>";
		return $str;
	}
	
	//LISTA DE FERIADOS NO ANO
	/*Abaixo criamos um array para registrar todos os feriados existentes durante o ano.*/
	function Feriados($ano,$posicao){
		$dia = 86400;
		$datas = array();
		$datas['pascoa'] = easter_date($ano);
		$datas['sexta_santa'] = $datas['pascoa'] - (2 * $dia);
		$datas['carnaval'] = $datas['pascoa'] - (47 * $dia);
		$datas['corpus_cristi'] = $datas['pascoa'] + (60 * $dia);
		$feriados = array (
				'01/01',
				'02/02', // Navegantes
				date('d/m',$datas['carnaval']),
				date('d/m',$datas['sexta_santa']),
				date('d/m',$datas['pascoa']),
				'21/04',
				'01/05',
				date('d/m',$datas['corpus_cristi']),
				'20/09', // Revolução Farroupilha \m/
				'12/10',
				'02/11',
				'15/11',
				'25/12',
		);

		return $feriados[$posicao]."/".$ano;
	}
	
	//FORMATA COMO TIMESTAMP
	/*Esta função é bem simples, e foi criada somente para nos ajudar a formatar a data já em formato  TimeStamp facilitando nossa soma de dias para uma data qualquer.*/
	function dataToTimestamp($data){
		$ano = substr($data, 6,4);
		$mes = substr($data, 3,2);
		$dia = substr($data, 0,2);
		return mktime(0, 0, 0, $mes, $dia, $ano);
	}
	
	//CALCULA DIAS UTEIS
	/*É nesta função que faremos o calculo. Abaixo podemos ver que faremos o cálculo normal de dias ($calculoDias), após este cálculo, faremos a comparação de dia a dia, verificando se este dia é um sábado, domingo ou feriado e em qualquer destas condições iremos incrementar 1*/
	
	function DiaUtil($data){
	
		$diaFDS = 0; //dias não úteis(Sábado=6 Domingo=0)
		$diasUteis = 0;
		 
		$diaSemana = date("w", dataToTimestamp($data));
		if($diaSemana==0 || $diaSemana==6){
			//se SABADO OU DOMINGO, SOMA 01
			$diaFDS++;
		}else{
			//senão vemos se este dia é FERIADO
			for($i=0; $i<=12; $i++){
				if($data==Feriados(date("Y"),$i)){
					$diaFDS++;
				}
			}
		}
		if($diaFDS > 0) {
			return false;
		} else {
			return true;
		}
	}
	
	function montaListaEditais($listaEditais){
		$str = "<br/><div class=\"titulo\"><b>Editais</b></div>"
				. "<div class=\"corpo\">";
		if($listaEditais != null){
			while($edital = $listaEditais->fetch(PDO::FETCH_OBJ)){
					$str .= "<div class=\"tituloConcurso\">"
							. "<a href=\"admin/arquivoseditais/" . $edital->caminho_arquivo . "\" target=\"_blank\">" . $edital->titulo . "</a>"
						. "</div>";
			}
		} else {
			$str .= "Não há editais no momento.";
		}
		$str .= "</div>";
		return $str;
	}
	
	function montaListaProva($listaProva){
		$str = "<br/><div class=\"titulo\"><b>Cadernos de Prova</b></div>"
				. "<div class=\"corpo\">";
		if($listaProva != null){
			while($prova = $listaProva->fetch(PDO::FETCH_OBJ)){
				if($prova->data_inicio <= date("Y-m-d H:i:s") && $prova->data_fim >= date("Y-m-d H:i:s") && DiaUtil(date("d-m-Y"))){
					$str .= "<div class=\"tituloConcurso\">"
							. "<a href=\"admin/arquivosprovas/" . $prova->caminho_prova . "\" target=\"_blank\">" . $prova->titulo . "</a>"
						. "</div>";
				} else {
					$str .= "Visualização da prova somente no período de: " . date("d/m/Y H:i:s", strtotime($prova->data_inicio)) . " a " . date("d/m/Y H:i:s", strtotime($prova->data_fim));
				}
			}
		} else {
			$str .= "Não há provas no momento.";
		}
		$str .= "</div>";
		return $str;
	}
	
	function enviaEmailConfirmacaoRecurso($idInscricao, $idRecursoCandidato){
		$candidatoDAO = new CandidatoDAO();
		$candidato = $candidatoDAO->getCandidatoPorID($_SESSION['idCandidato'])->fetch(PDO::FETCH_OBJ);
	
		$inscricaoDAO = new InscricaoDAO();
		$inscricao = $inscricaoDAO->getInscricaoPorId($idInscricao)->fetch(PDO::FETCH_OBJ);
		
		$concursoDAO = new ConcursoDAO();
		$concurso = $concursoDAO->getConcursoPorID($inscricao->id_concurso)->fetch(PDO::FETCH_OBJ);
	
		$cargoDAO = new CargoDAO();
		$cargo = $cargoDAO->getCargoPorID($inscricao->id_cargo)->fetch(PDO::FETCH_OBJ);
	
		$para = $candidato->email;
		$assunto = "EBCP Concursos - Confirmação de Envio de Recurso";
		$body = "EBCP Concursos informa que seu recurso foi enviado com sucesso: <br/><br/>"
			. "Sr(a) " . $candidato->nome . ", <br/><br/>"
			. "Seu recurso no concurso: " . $concurso->titulo . " foi confirmado em nosso sistema com os seguintes dados:"
			. "<br/><br/>CPF: " . $candidato->cpf
			. "<br/>Cargo: " . $cargo->titulo
			. "<br/>Recurso Número: " . $idRecursoCandidato
			. "<br/><br/>Para dúvidas, <a href=\"mailto:concurso@ebcpconcursos.com.br\">concurso@ebcpconcursos.com.br</a> ou "
			. "ligue para (44) 3333-6666"
			. "<br/><br/>Acompanhe as nossas atualizações em nosso site."
			. "<br/><br/>EBCP Concursos";
		$headers  = "MIME-Version: 1.0 \r\n"
				. "Content-type: text/html; charset=utf-8 \r\n"
						. "From: concurso@ebcpconcursos.com.br \r\n";
			
		if(mail($para, $assunto, $body, $headers)){
			return true;
		} else {
			return false;
		}
	}
	
	function montaFormularioConfirmacaoRecurso($idInscricao, $idRecursoCandidato){
		$candidatoDAO = new CandidatoDAO();
		$candidato = $candidatoDAO->getCandidatoPorID($_SESSION['idCandidato'])->fetch(PDO::FETCH_OBJ);
		
		$inscricaoDAO = new InscricaoDAO();
		$inscricao = $inscricaoDAO->getInscricaoPorId($idInscricao)->fetch(PDO::FETCH_OBJ);
		
		$concursoDAO = new ConcursoDAO();
		$concurso = $concursoDAO->getConcursoPorID($inscricao->id_concurso)->fetch(PDO::FETCH_OBJ);
		
		$cargoDAO = new CargoDAO();
		$cargo = $cargoDAO->getCargoPorID($inscricao->id_cargo)->fetch(PDO::FETCH_OBJ);
		
		$str = "EBCP Concursos informa que seu recurso foi enviado com sucesso: <br/><br/>"
				. "Sr(a) " . $candidato->nome . ", <br/><br/>"
				. "Seu recurso no concurso: " . $concurso->titulo . " foi confirmado em nosso sistema com os seguintes dados:"
				. "<br/><br/>CPF: " . $candidato->cpf
				. "<br/>Cargo: " . $cargo->titulo
				. "<br/>Recurso Número: " . $idRecursoCandidato
				. "<br/><br/>Para dúvidas, <a href=\"mailto:concurso@ebcpconcursos.com.br\">concurso@ebcpconcursos.com.br</a> ou "
				. "ligue para (44) 3333-6666"
				. "<br/><br/>Um e-mail com esses dados foram enviados para sua conta de e-mail: " . $candidato->email
				. "<br/><br/>Acompanhe as nossas atualizações em nosso site."
				. "<br/><br/>EBCP Concursos";
		
		return $str;
	}
?>