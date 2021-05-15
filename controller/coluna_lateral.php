<?php
	include("model/dao/EditalDAO.php");
	

	$areaCandidato = <<<AREA
	  <div class="borda">
        <div class="titulo"><b>Área do Candidato</b></div>
        <div class="corpo">
          <form name="loginform" class="login-form" action="?nivel=areacandidato/painel_candidato&acao=login" method="post">
            <div class="content">
              <input name="cpf" type="text" class="input username" placeholder="CPF" onkeypress="somenteNumeros();" />
              <input name="senha" type="password" class="input password" placeholder="Senha" />
            </div>
            <div class="rodapeBotoes">
				<table width="100%" cellspacing="0" cellpadding="0" border="0" >
					<tr><td><input type="submit" name="submit" value="Acessar" class="button" onclick="return ValidarCPF(formCPF.cpf);" /></td>
              		<td><a href="?nivel=areacandidato/inicio_cadastro&acao=novo_cadastro" class="register">Cadastrar</a></td>
				</table>
            </div>
          </form>
          <a href="?nivel=areacandidato/esqueci_senha&acao=exibe_formulario">Esqueci minha senha.</a>
        </div>
      </div>
	  <div class="espacoBranco"></div>
AREA;
	
	
	$editalDAO = new EditalDAO();
	$listaEditais = $editalDAO->listarUltimosEditais(); 
	
	$conteudoLateral = "<table>";
	if($listaEditais != null) {
		while ($edital = $listaEditais->fetch(PDO::FETCH_OBJ)){
			$conteudoLateral .= "<tr><td>"
					. "<div class=\"tituloConcurso\">"
						. "<a href=\"?nivel=concurso&idconcurso=" . $edital->id_concurso . "\">" . $edital->titulo . "</a>"
					. "<div>"
					. "<div class=\"subtituloConcurso\">" . $edital->atualizacao . "</div>"
					. "</td></tr>"; 
		}
	} else {
		$conteudoLateral .= "<tr><td><div class=\"tituloConcurso\">Nenhuma notícia no momento</div></td></tr>";
	}
	
	$conteudoLateral .= "</table>";
	
?>