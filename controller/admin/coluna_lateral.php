<?php
$areaLogin = <<<AREA
	  <div class="borda">
        <div class="titulo"><b>Área do Administrador</b></div>
        <div class="corpo">
          <form name="loginform" class="login-form" action="?nivel=painel_inicio&acao=login" method="post">
            <div class="content">
              <input name="login" type="text" class="input username" placeholder="Login" />
              <input name="senha" type="password" class="input password" placeholder="Senha" />
            </div>
            <div class="rodapeBotoes">
				<table width="100%" cellspacing="0" cellpadding="0" border="0" >
					<tr><td><input type="submit" name="submit" value="Acessar" class="button" /></td>
				</table>
            </div>
          </form>
        </div>
      </div>
	  <div class="espacoBranco"></div>
AREA;
?>