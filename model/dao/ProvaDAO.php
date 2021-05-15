<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class ProvaDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
			
	public function excluirPorID($idProva){
		$query = "DELETE from prova "
				. "WHERE id_prova = ?";
		// 		echo $query;
		try{
			$stmt = $this->conn->prepare( $query );
			$stmt->bindParam(1, $idProva, PDO::PARAM_INT);
			$stmt->execute();
		} catch (Exception $e){
			return false;
		}
		
		if ($stmt->rowCount() == 0) {
			return false;
		}

		$parametros = array($idProva);
		$this->loggerDAO->salvar($idProva, "DELETE", "prova", $this->diversos->montaQuery($query, $parametros));
		
		return true;
	}
	
	public function update($prova) {
		$query = "UPDATE prova set id_cargo = ?, data_inicio = ?, data_fim = ?, caminho_prova = ? "
			. "WHERE id_prova = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $prova->getidcargo(), PDO::PARAM_INT);
		$stmt->bindParam(2, $prova->getdatainicio(), PDO::PARAM_STR);
		$stmt->bindParam(3, $prova->getdatafim(), PDO::PARAM_STR);
		$stmt->bindParam(4, $prova->getcaminhoprova(), PDO::PARAM_STR);
		$stmt->bindParam(5, $prova->getidprova(), PDO::PARAM_INT);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return false;
		}

		$parametros = array($prova->getidcargo(), $prova->getdatainicio(), $prova->getdatafim(), 
				$prova->getcaminhoprova(), $prova->getidprova());
		$this->loggerDAO->salvar($prova->getidprova(), "UPDATE", "prova", $this->diversos->montaQuery($query, $parametros));
		
		return true;
	}
	
	public function salvar ($prova){
		$query = "INSERT INTO prova (id_cargo, data_inicio, data_fim) "
			. "VALUES (?, ?, ?)";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $prova->getidcargo(), PDO::PARAM_INT);
		$stmt->bindParam(2, $prova->getdatainicio(), PDO::PARAM_STR);
		$stmt->bindParam(3, $prova->getdatafim(), PDO::PARAM_STR);
		$stmt->execute();
		
	
		if ($stmt->rowCount() == 0) {
			return null;
		}

		$parametros = array($prova->getidcargo(), $prova->getdatainicio(), $prova->getdatafim(),
				$prova->getcaminhoprova());
		$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "prova", $this->diversos->montaQuery($query, $parametros));
		
		return $this->conn->lastInsertId();
	}
	
	public function updateArquivoProva($idProva, $arquivo){
		$query = "UPDATE prova "
			. "SET caminho_prova = ? "
			. "WHERE id_prova = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $arquivo, PDO::PARAM_STR);
		$stmt->bindParam(2, $idProva, PDO::PARAM_INT);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return false;
		}

		$parametros = array($arquivo, $idProva);
		$this->loggerDAO->salvar($idProva, "UPDATE", "prova", $this->diversos->montaQuery($query, $parametros));
		
		return true;
	}
	
	public function listarProvasPorIdConcurso($idConcurso){
		$query = "SELECT p.*, c.titulo "
			. "FROM prova p "
			. "INNER JOIN cargo c ON c.id_cargo = p.id_cargo "
			. "INNER JOIN concurso co ON co.id_concurso = c.id_concurso "
			. "WHERE co.id_concurso = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idConcurso, PDO::PARAM_INT);
		$stmt->execute();
		
		$parametros = array($idConcurso);
		$this->loggerDAO->salvar(null, "SELECT", "prova", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function getProvasPorId($idProva){
		$query = "SELECT p.*, c.titulo, c.id_concurso "
			. "FROM prova p "
			. "INNER JOIN cargo c ON c.id_cargo = p.id_cargo "
			. "WHERE p.id_prova = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idProva, PDO::PARAM_INT);
		$stmt->execute();
		
		$parametros = array($idProva);
		$this->loggerDAO->salvar(null, "SELECT", "prova", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function listarProvasPorIdCargo($idCargo) {
		$query = "SELECT p.*, c.titulo "
				. "FROM prova p "
				. "INNER JOIN cargo c ON c.id_cargo = p.id_cargo "
				. "WHERE p.id_cargo = ?";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idCargo, PDO::PARAM_INT);
		$stmt->execute();

		$parametros = array($idCargo);
		$this->loggerDAO->salvar(null, "SELECT", "prova", $this->diversos->montaQuery($query, $parametros));
		
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