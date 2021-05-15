<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class InstituicaoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
		
	public function excluirPorID($idInstituicao){
		$query = "DELETE from instituicao "
				. "WHERE id_instituicao = ?";
		// 		echo $query;
		try{
			$stmt = $this->conn->prepare( $query );
			$stmt->bindParam(1, $idInstituicao, PDO::PARAM_INT);
			$stmt->execute();
			
				
		} catch (Exception $e){
			return false;
		}
	
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($idInstituicao);
		$this->loggerDAO->salvar($idInstituicao, "DELETE", "instituicao", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function getInstituicaoPorNome($nome){
		$query = "SELECT * "
				. "FROM instituicao "
				. "WHERE instituicao like ? "
				. "ORDER BY instituicao DESC";
		//     			echo $query;
		$stmt = $this->conn->prepare( $query );
		$nome = "%" . $nome . "%";
		$stmt->bindParam(1, $nome, PDO::PARAM_STR);
		$stmt->execute();

		$parametros = array($nome);
		$this->loggerDAO->salvar($nome, "SELECT", "instituicao", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function update($instituicao) {
		$query = "UPDATE instituicao set instituicao = ?, logo = ? "
			. "WHERE id_instituicao = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $instituicao->getinstituicao(), PDO::PARAM_STR);
		$stmt->bindParam(2, $instituicao->getlogo(), PDO::PARAM_STR);
		$stmt->bindParam(3, $instituicao->getidinstituicao(), PDO::PARAM_INT);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($instituicao->getinstituicao(), $instituicao->getlogo(), $instituicao->getidinstituicao());
		$this->loggerDAO->salvar($instituicao->getidinstituicao(), "UPDATE", "instituicao", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function salvar ($instituicao){
		$query = "INSERT INTO instituicao (instituicao, logo) "
			. "VALUES (?, ?)";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $instituicao->getinstituicao(), PDO::PARAM_STR);
		$stmt->bindParam(2, $instituicao->getlogo(), PDO::PARAM_STR);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		$parametros = array($instituicao->getinstituicao(), $instituicao->getlogo());
		$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "instituicao", $this->diversos->montaQuery($query, $parametros));
		return $this->conn->lastInsertId();
	}
	
	public function updateArquivoLogo($idInstituicao, $arquivo){
		$query = "UPDATE instituicao "
				. "SET logo = ? "
				. "WHERE id_instituicao = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $arquivo, PDO::PARAM_STR);
		$stmt->bindParam(2, $idInstituicao, PDO::PARAM_INT);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($arquivo, $idInstituicao);
		$this->loggerDAO->salvar($idInstituicao, "UPDATE", "instituicao", $this->diversos->montaQuery($query, $parametros));
		return true;
	}

	public function getInstituicaoPorID($idInstituicao) {
		$query = "SELECT * "
				. "FROM instituicao "
				. "WHERE id_instituicao = ? ";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idInstituicao, PDO::PARAM_INT);
		$stmt->execute();
	
		$parametros = array($idInstituicao);
		$this->loggerDAO->salvar(null, "SELECT", "instituicao", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function listarInstituicoes() {
		$query = "SELECT * "
			 . "FROM instituicao ";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$parametros = array();
		$this->loggerDAO->salvar(null, "SELECT", "instituicao", $this->diversos->montaQuery($query, $parametros));
		
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