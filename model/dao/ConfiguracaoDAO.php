<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class ConfiguracaoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
		
	public function getConfiguracao(){
		$query = "SELECT * "
				. "FROM configuracoes "
				. "WHERE id_configuracoes = 1";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		
		$parametros = array();
		$this->loggerDAO->salvar(null, "SELECT", "configuracoes", $this->diversos->montaQuery($query, $parametros));
		
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function update($configuracao){
		$query = "UPDATE configuracoes "
				. "SET autenticacao_ldap_local = ?, ldap_base_dn = ?, ldap_dominio = ?, ldap_ip = ?, ldap_porta = ?, ldap_grupo = ?, "
				. "ireport_url = ?, ireport_usuario = ?, ireport_password = ? "
				. "WHERE id_configuracoes = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $configuracao->getautenticacaoldaplocal(), PDO::PARAM_INT);
		$stmt->bindParam(2, $configuracao->getldapbasedn(), PDO::PARAM_STR);
		$stmt->bindParam(3, $configuracao->getldapdominio(), PDO::PARAM_STR);
		$stmt->bindParam(4, $configuracao->getldapip(), PDO::PARAM_STR);
		$stmt->bindParam(5, $configuracao->getldapporta(), PDO::PARAM_STR);
		$stmt->bindParam(6, $configuracao->getldapgrupo(), PDO::PARAM_STR);
		$stmt->bindParam(7, $configuracao->getireporturl(), PDO::PARAM_STR);
		$stmt->bindParam(8, $configuracao->getireportusuario(), PDO::PARAM_STR);
		$stmt->bindParam(9, $configuracao->getireportpassword(), PDO::PARAM_STR);
		$stmt->bindParam(10, $configuracao->getidconfiguracoes(), PDO::PARAM_INT);
		$stmt->execute();
		

		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($configuracao->getautenticacaoldaplocal(), $configuracao->getldapbasedn(), 
				$configuracao->getldapdominio(), $configuracao->getldapip(), $configuracao->getldapporta(), 
				$configuracao->getldapgrupo(), $configuracao->getireporturl(), $configuracao->getireportusuario(), 
				$configuracao->getireportpassword(), $configuracao->getidconfiguracoes());
		$this->loggerDAO->salvar($configuracao->getidconfiguracoes(), "UPDATE", "configuracoes", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
    function __destruct() {
    	unset($this->conn);
    }
}
?>