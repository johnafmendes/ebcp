<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class EditalDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
			
	public function getEditalPorId($idEdital){
		$query = "SELECT e.*, c.titulo as concurso "
			. "FROM edital e "
			. "INNER JOIN concurso c ON c.id_concurso=e.id_concurso "
			. "WHERE id_edital = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idEdital, PDO::PARAM_INT);
		$stmt->execute();

		$parametros = array($idEdital);
		$this->loggerDAO->salvar(null, "SELECT", "edital", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function excluirPorID($idEdital){
		$query = "DELETE from edital "
				. "WHERE id_edital = ?";
		// 		echo $query;
		try{
			$stmt = $this->conn->prepare( $query );
			$stmt->bindParam(1, $idEdital, PDO::PARAM_INT);
			$stmt->execute();
			
			
		} catch (Exception $e){
			return false;
		}
	
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($idEdital);
		$this->loggerDAO->salvar($idEdital, "DELETE", "edital", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function update($edital) {
		$query = "UPDATE edital set id_concurso = ?, titulo = ?, data = ?, atualizacao = ?, caminho_arquivo = ? "
				. "WHERE id_edital = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $edital->getidconcurso(), PDO::PARAM_INT);
		$stmt->bindParam(2, $edital->gettitulo(), PDO::PARAM_STR);
		$stmt->bindParam(3, $edital->getdata(), PDO::PARAM_STR);
		$stmt->bindParam(4, $edital->getatualizacao(), PDO::PARAM_STR);
		$stmt->bindParam(5, $edital->getcaminhoarquivo(), PDO::PARAM_STR);
		$stmt->bindParam(6, $edital->getidedital(), PDO::PARAM_INT);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($edital->getidconcurso(), $edital->gettitulo(), $edital->getdata(), 
				$edital->getatualizacao(), $edital->getcaminhoarquivo(), $edital->getidedital());
		$this->loggerDAO->salvar($edital->getidedital(), "UPDATE", "edital", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function salvar ($edital){
		$query = "INSERT INTO edital (id_concurso, titulo, data, atualizacao) "
				. "VALUES (?, ?, ?, ?)";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $edital->getidconcurso(), PDO::PARAM_INT);
		$stmt->bindParam(2, $edital->gettitulo(), PDO::PARAM_STR);
		$stmt->bindParam(3, $edital->getdata(), PDO::PARAM_STR);
		$stmt->bindParam(4, $edital->getatualizacao(), PDO::PARAM_STR);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		$parametros = array($edital->getidconcurso(), $edital->gettitulo(), $edital->getdata(),
				$edital->getatualizacao(), $edital->getcaminhoarquivo());
		$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "edital", $this->diversos->montaQuery($query, $parametros));
		return $this->conn->lastInsertId();
	}
	
	public function updateArquivoEdital($idEdital, $arquivo){
		$query = "UPDATE edital "
			. "SET caminho_arquivo = ? "
			. "WHERE id_edital = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $arquivo, PDO::PARAM_STR);
		$stmt->bindParam(2, $idEdital, PDO::PARAM_INT);
		$stmt->execute();
	
		
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($arquivo, $idEdital);
		$this->loggerDAO->salvar($idEdital, "UPDATE", "edital", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function listarUltimosEditais() {
		$query = "SELECT e.atualizacao, e.id_concurso, c.titulo "
				. "FROM edital e "
				. "INNER JOIN concurso c ON c.id_concurso=e.id_concurso "
				. "ORDER BY e.id_edital DESC LIMIT 10";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$parametros = array();
		$this->loggerDAO->salvar(null, "SELECT", "edital", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
    }	
    
    public function getEditaisPorIdConcurso($idConcurso) {
    	$query = "SELECT * "
    			. "FROM edital "
    			. "WHERE id_concurso = ? "
				. "ORDER BY id_edital DESC";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $idConcurso, PDO::PARAM_INT);
    	$stmt->execute();
    
    	$parametros = array($idConcurso);
    	$this->loggerDAO->salvar(null, "SELECT", "edital", $this->diversos->montaQuery($query, $parametros));
    	
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