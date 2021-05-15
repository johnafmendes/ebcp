<?php
	include("view/template.class.php");
	include("model/dao/ConcursoDAO.php");
	include("controller/coluna_lateral.php");
	
	$menu = new Template("view/menu.tpl");
	$menu->set("trabalhe", "active");
	
	/**
	 * Creates a new template for the user's profile.
	 * Fills it with mockup data just for testing.
	 */
	
	$conteudo = <<<FORM
				<form name="login-form" class="login-form" action="?nivel=envia_curriculo" id="curriculo"
					method="post" enctype="multipart/form-data" onSubmit="return validaCamposCurriculo()">  
					<table>
						<tr><td>*Nome:</td>
						<td><input required id="nome" name="nome" tabindex="1" type="text" placeholder="Nome Completo" style="width:400px;" />
						</td></tr>
					
						<tr><td>*Data Nascimento:</td>
						<td><input required id="datanascimento" name="datanascimento" tabindex="2" type="text" placeholder="Data de Nascimento (dd/mm/aaaa)" style="width:400px;"/>(dd/mm/aaaa)
						</td></tr>
			
						<tr><td>*Cidade:</td>
						<td><input required id="cidade" name="cidade" tabindex="3" type="text" placeholder="Cidade" />
						</td></tr>
			
						<tr><td>*Estado:</td>
						<td><input required id="estado" name="estado" tabindex="4" type="text" placeholder="Estado" />
						</td></tr>
			
						<tr><td>*Telefone:</td>
						<td><input required id="telefone" name="telefone" tabindex="5" type="tel" placeholder="Telefone com DDD" />
						</td></tr>

						<tr><td>*e-Mail:</td>
						<td><input required id="email" name="email" tabindex="6" type="text" placeholder="e-Mail" style="width:400px;"/>
						</td></tr>

						<tr><td>*Cargo:</td>
						<td><select required id="cargo" name="cargo" tabindex="8">
  								<option value="fiscal">Fiscal</option>
  								<option value="coordenador">Coordenador</option>
								<option value="banca">Banca</option>
							</select>
						</td></tr>
			
						<tr><td>*Mini Currículo:</td>
						<td><textarea required id="curriculo" name="curriculo" tabindex="8" type="text" placeholder="Mini Currículo" cols="45" rows="5" style="width:400px;"></textarea>
						<br/>Para o cargo de Banca, insira o link com seu Lattes.</td></tr>
					</table>
					<p>*Campos obrigatórios</p>
					<div class="rodapeBotoes">
						<input type="submit" name="submit" value="Cadastrar" class="button">
		            </div>
	 </form>
FORM;
	
	$corpo = new Template("view/paginaInterna.tpl");
	$corpo->set("titulo", "Seja um Colaborador da EBCP");
	$corpo->set("conteudo", $conteudo);
	$corpo->set("tituloLateral", "Últimas Notícias");
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
	
?>