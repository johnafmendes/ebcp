<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class LinguaConcursoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
		
	public function update($linguaEstrangeira) {
		$query = "UPDATE lingua_estrangeira set id_concurso = ?, id_lingua = ? "
			. "WHERE id_lingua_estrangeira = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $linguaEstrangeira->getidconcurso(), PDO::PARAM_INT);
		$stmt->bindParam(2, $linguaEstrangeira->getidlingua(), PDO::PARAM_INT);
		$stmt->bindParam(3, $linguaEstrangeira->getidlinguaestrangeira(), PDO::PARAM_INT);
		$stmt->execute();
	
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($linguaEstrangeira->getidconcurso(), $linguaEstrangeira->getidlingua(), 
				$linguaEstrangeira->getidlinguaestrangeira());
		$this->loggerDAO->salvar($linguaEstrangeira->getidlinguaestrangeira(), "UPDATE", "lingua_estrangeira", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function salvar ($linguaEstrangeira){
		$query = "INSERT INTO lingua_estrangeira (id_concurso, id_lingua) "
			. "VALUES (?, ?)";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $linguaEstrangeira->getidconcurso(), PDO::PARAM_INT);
		$stmt->bindParam(2, $linguaEstrangeira->getidlingua(), PDO::PARAM_INT);
		$stmt->execute();
	
		if ($stmt->rowCount() == 0) {
			return null;
		}
		$parametros = array($linguaEstrangeira->getidconcurso(), $linguaEstrangeira->getidlingua());
		$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "lingua_estrangeira", $this->diversos->montaQuery($query, $parametros));
		return $stmt;
	}
	
	public function excluirPorID($idLinguaEstrangeira){
		$query = "DELETE from lingua_estrangeira "
			. "WHERE id_lingua_estrangeira = ?";
		// 		echo $query;
		try{
			$stmt = $this->conn->prepare( $query );
			$stmt->bindParam(1, $idLinguaEstrangeira, PDO::PARAM_INT);
			$stmt->execute();
			
			
		} catch (Exception $e){
			return false;
		}
	
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($idLinguaEstrangeira);
		$this->loggerDAO->salvar($idLinguaEstrangeira, "DELETE", "lingua_estrangeira", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function getLinguaEstrangeiraPorID($idLinguaEstrangeira) {
		$query = "SELECT le.*, l.lingua, c.titulo "
			. "FROM lingua_estrangeira le "
			. "INNER JOIN concurso c ON c.id_concurso = le.id_concurso "
			. "INNER JOIN lingua l ON l.id_lingua = le.id_lingua "
			. "WHERE le.id_lingua_estrangeira = ? ";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idLinguaEstrangeira, PDO::PARAM_INT);
		$stmt->execute();
	
		$parametros = array($idLinguaEstrangeira);
		$this->loggerDAO->salvar(null, "SELECT", "lingua_estrangeira", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
		
	public function listarLinguasEstrangeirasPorConcurso($idConcurso) {
		$query = "SELECT le.*, l.lingua, c.titulo "
			 . "FROM lingua_estrangeira le "
			 . "INNER JOIN lingua l ON l.id_lingua = le.id_lingua "
			 . "INNER JOIN concurso c ON c.id_concurso = le.id_concurso "
			 . "WHERE le.id_concurso = ? ";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idConcurso, PDO::PARAM_INT);
		$stmt->execute();

		$parametros = array($idConcurso);
		$this->loggerDAO->salvar(null, "SELECT", "lingua_estrangeira", $this->diversos->montaQuery($query, $parametros));
		
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