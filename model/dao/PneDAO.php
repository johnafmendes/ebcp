<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';


class PneDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
		

	public function update($pne) {
		$query = "UPDATE pne set tipo = ? "
			. "WHERE id_pne = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $pne->gettipo(), PDO::PARAM_STR);
		$stmt->bindParam(2, $pne->getidpne(), PDO::PARAM_INT);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($pne->gettipo(), $pne->getidpne());
		$this->loggerDAO->salvar($pne->getidpne(), "UPDATE", "pne", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function salvar ($pne){
		$query = "INSERT INTO pne (tipo) "
			. "VALUES (?)";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $pne->gettipo(), PDO::PARAM_STR);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		$parametros = array($pne->gettipo());
		$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "pne", $this->diversos->montaQuery($query, $parametros));
		return $stmt;
	}
	
	public function excluirPorID($idPne){
		$query = "DELETE from pne "
				. "WHERE id_pne = ?";
		// 		echo $query;
		try{
			$stmt = $this->conn->prepare( $query );
			$stmt->bindParam(1, $idPne, PDO::PARAM_INT);
			$stmt->execute();
			
			
		} catch (Exception $e){
			return false;
		}
	
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($idPne);
		$this->loggerDAO->salvar($idPne, "DELETE", "pne", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function getPNEPorID($idPne) {
		$query = "SELECT * "
			. "FROM pne "
			. "WHERE id_pne = ? ";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idPne, PDO::PARAM_INT);
		$stmt->execute();
	
		$parametros = array($idPne);
		$this->loggerDAO->salvar($idPne, "SELECT", "pne", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
		
	public function listarPNE() {
		$query = "SELECT * "
			 . "FROM pne ";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$parametros = array();
		$this->loggerDAO->salvar(null, "SELECT", "pne", $this->diversos->montaQuery($query, $parametros));
		
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