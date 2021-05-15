<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';


class CidadeProvaDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
		
	public function excluirPorID($idCidade){
		$query = "DELETE from cidade_prova "
				. "WHERE id_cidade_prova = ?";
		// 		echo $query;
		try{
			$stmt = $this->conn->prepare( $query );
			$stmt->bindParam(1, $idCidade, PDO::PARAM_INT);
			$stmt->execute();

				
		} catch (Exception $e){
			return false;
		}
	
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($idCidade);
		$this->loggerDAO->salvar($idCidade, "DELETE", "cidade_prova", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function getCidadePorConcurso($idConcurso){
		$query = "SELECT cp.*, c.titulo, ci.cidade "
				. "FROM cidade_prova cp "
				. "INNER JOIN concurso c ON c.id_concurso = cp.id_concurso "
				. "INNER JOIN cidade ci ON ci.id_cidade = cp.id_cidade "
				. "WHERE cp.id_concurso = ? ";
		//     			echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idConcurso, PDO::PARAM_INT);
		$stmt->execute();
	
		$parametros = array($idConcurso);
		$this->loggerDAO->salvar(null, "SELECT", "cidade_prova", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function update($cidade) {
		$query = "UPDATE cidade_prova set id_concurso = ?, id_cidade = ? "
				. "WHERE id_cidade_prova = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $cidade->getidconcurso(), PDO::PARAM_INT);
		$stmt->bindParam(2, $cidade->getidcidade(), PDO::PARAM_INT);
		$stmt->bindParam(3, $cidade->getidcidadeprova(), PDO::PARAM_INT);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($cidade->getidconcurso(), $cidade->getidcidade(), $cidade->getidcidadeprova());
		$this->loggerDAO->salvar($cidade->getidcidadeprova(), "UPDATE", "cidade_prova", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function salvar ($cidade){
		$query = "INSERT INTO cidade_prova (id_concurso, id_cidade) "
				. "VALUES (?, ?)";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $cidade->getidconcurso(), PDO::PARAM_INT);
		$stmt->bindParam(2, $cidade->getidcidade(), PDO::PARAM_INT);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		$parametros = array($cidade->getidconcurso(), $cidade->getidcidade());
		$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "cidade_prova", $this->diversos->montaQuery($query, $parametros));
		return $stmt;
	}
	
	public function getCidadePorID($idCidadeProva){
		$query = "SELECT cp.*, c.cidade, c.id_estado, co.titulo "
				. "FROM cidade_prova cp "
				. "INNER JOIN cidade c ON c.id_cidade = cp.id_cidade "
				. "INNER JOIN concurso co ON co.id_concurso = cp.id_concurso "
		 		. "WHERE cp.id_cidade_prova = ? ";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idCidadeProva, PDO::PARAM_INT);
		$stmt->execute();
		
		$parametros = array($idCidadeProva);
		$this->loggerDAO->salvar(null, "SELECT", "cidade_prova", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
		
	public function listarCidadesProvaPorConcurso($idConcurso) {
		$query = "SELECT cp.*, c.cidade, e.sigla "
			 . "FROM cidade_prova cp "
			 . "INNER JOIN cidade c ON cp.id_cidade = c.id_cidade "
			 . "INNER JOIN estado e ON c.id_estado = e.id_estado "
			 . "WHERE cp.id_concurso = ? ";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idConcurso, PDO::PARAM_INT);
		$stmt->execute();

		$parametros = array($idConcurso);
		$this->loggerDAO->salvar(null, "SELECT", "cidade_prova", $this->diversos->montaQuery($query, $parametros));
		
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