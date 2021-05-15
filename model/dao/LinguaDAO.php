<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';


class LinguaDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
	
	public function excluirPorID($idLingua){
		$query = "DELETE from lingua "
				. "WHERE id_lingua = ?";
		// 		echo $query;
		try{
			$stmt = $this->conn->prepare( $query );
			$stmt->bindParam(1, $idLingua, PDO::PARAM_INT);
			$stmt->execute();
			
				
		} catch (Exception $e){
			return false;
		}
	
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($idLingua);
		$this->loggerDAO->salvar($idLingua, "DELETE", "lingua", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function update($lingua) {
		$query = "UPDATE lingua set lingua = ? "
			. "WHERE id_lingua = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $lingua->getlingua(), PDO::PARAM_STR);
		$stmt->bindParam(2, $lingua->getidlingua(), PDO::PARAM_INT);
		$stmt->execute();
		
	
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($lingua->getlingua(), $lingua->getidlingua());
		$this->loggerDAO->salvar($lingua->getidlingua(), "UPDATE", "lingua", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function salvar ($lingua){
		$query = "INSERT INTO lingua (lingua) "
			. "VALUES (?)";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $lingua->getlingua(), PDO::PARAM_STR);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		$parametros = array($lingua->getlingua());
		$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "lingua", $this->diversos->montaQuery($query, $parametros));
		return $stmt;
	}
	
	public function getLinguaPorID($idLingua){
		$query = "SELECT * "
				. "FROM lingua "
				. "WHERE id_lingua = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idLingua, PDO::PARAM_INT);
		$stmt->execute();
		
		$parametros = array($idLingua);
		$this->loggerDAO->salvar(null, "SELECT", "lingua", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function listarLinguas() {
		$query = "SELECT * "
			 . "FROM lingua ";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		
		$parametros = array();
		$this->loggerDAO->salvar(null, "SELECT", "lingua", $this->diversos->montaQuery($query, $parametros));

		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
    }	
    
    function __destruct() {
    	unset($this->conn);
    }
}
?>