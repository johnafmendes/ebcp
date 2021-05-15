<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class RecursoCandidatoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
		
	public function listarRecursosPorIdConcurso($idConcurso, $idTipoRecurso, $inicio, $registros, $idInscricao){
		$query = "SELECT rc.*, c.titulo, tr.tipos_recursos, e.titulo as titulo_edital, e.caminho_arquivo "
			. "FROM recurso_candidato rc "
			. "INNER JOIN recurso r ON r.id_recurso = rc.id_recurso "
			. "INNER JOIN concurso c ON c.id_concurso = r.id_concurso "
			. "INNER JOIN tipos_recursos tr ON tr.id_tipos_recursos = r.id_tipos_recursos "
			. "LEFT JOIN edital e ON e.id_edital = rc.id_edital_resposta "
			. "WHERE c.id_concurso = ? ";
			$query .= $idTipoRecurso != null ? " and tr.id_tipos_recursos = ? " : "";
			$query .= $idInscricao != null ? " and rc.id_inscricao = ? " : "";
			$query .= isset($inicio) ? " LIMIT " . $inicio . ", " . $registros : "";
// 				echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idConcurso, PDO::PARAM_INT);
		$idTipoRecurso != null ? $stmt->bindParam(2, $idTipoRecurso, PDO::PARAM_INT) : "";
		$idInscricao != null ? ($idTipoRecurso != null ? $stmt->bindParam(3, $idInscricao, PDO::PARAM_INT) : $stmt->bindParam(2, $idInscricao, PDO::PARAM_INT)) : "";
		$stmt->execute();

		$parametros = $idTipoRecurso != null ? ($idInscricao != null ? array($idConcurso, $idTipoRecurso, $idInscricao) : array($idConcurso, $idTipoRecurso)) : ($idInscricao != null ? array($idConcurso, $idInscricao) : array($idConcurso));
		$this->loggerDAO->salvar(null, "SELECT", "recurso_candidato", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function getTotalRecursosPorIdConcurso($idConcurso, $idTipoRecurso){
		$query = "SELECT count(*) as total "
			. "FROM recurso_candidato rc "
			. "INNER JOIN recurso r ON r.id_recurso = rc.id_recurso "
			. "INNER JOIN concurso c ON c.id_concurso = r.id_concurso "
			. "INNER JOIN tipos_recursos tr ON tr.id_tipos_recursos = r.id_tipos_recursos "
			. "LEFT JOIN edital e ON e.id_edital = rc.id_edital_resposta "
			. "WHERE c.id_concurso = ? ";
		$query .= $idTipoRecurso != null ? " and tr.id_tipos_recursos = ?" : "";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idConcurso, PDO::PARAM_INT);
		$idTipoRecurso != null ? $stmt->bindParam(2, $idTipoRecurso, PDO::PARAM_INT) : "";
		$stmt->execute();
	
		$parametros = array($idConcurso);
		$this->loggerDAO->salvar(null, "SELECT", "recurso_candidato", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt->fetch(PDO::FETCH_OBJ)->total;
	}
	
	public function getRecursoPorID($idRecurso) {
		$query = "SELECT rc.*, c.nome, co.titulo, tr.tipos_recursos, co.id_concurso "
			. "FROM recurso_candidato rc "
			. "INNER JOIN inscricao i ON i.id_inscricao = rc.id_inscricao "
			. "INNER JOIN candidato c ON c.id_candidato = i.id_candidato "
			. "INNER JOIN concurso co ON co.id_concurso = i.id_concurso "
			. "INNER JOIN recurso r ON r.id_recurso = rc.id_recurso "
			. "INNER JOIN tipos_recursos tr ON tr.id_tipos_recursos = r.id_tipos_recursos "
			. "WHERE id_recurso_candidato = ?";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idRecurso, PDO::PARAM_INT);
		$stmt->execute();
	
		$parametros = array($idRecurso);
		$this->loggerDAO->salvar(null, "SELECT", "recurso_candidato", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
		
    public function salvar($recurso){
    	$query = "INSERT INTO recurso_candidato (id_recurso, id_inscricao, data_hora_recurso, texto_recurso, arquivo_anexo) "
    				. "VALUES (?, ?, ?, ?, ?)";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $recurso->getidrecurso(), PDO::PARAM_INT);
    	$stmt->bindParam(2, $recurso->getidinscricao(), PDO::PARAM_INT);
    	$stmt->bindParam(3, $recurso->getdatahorarecurso(), PDO::PARAM_STR);
    	$stmt->bindParam(4, $recurso->gettextorecurso(), PDO::PARAM_STR);
    	$stmt->bindParam(5, $recurso->getarquivoanexo(), PDO::PARAM_STR);
    	$stmt->execute();
    	
    	
    	if ($stmt->rowCount() == 0) {
    		return 0;
    	}

    	$parametros = array($recurso->getidrecurso(), $recurso->getidinscricao(), $recurso->getdatahorarecurso(), 
    			$recurso->gettextorecurso(), $recurso->getarquivoanexo());
    	$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "recurso_candidato", $this->diversos->montaQuery($query, $parametros));
    	
    	return $this->conn->lastInsertId();
    }
    
    public function updateArquivoRecurso($idRecursoCandidato, $arquivo){
    	$query = "UPDATE recurso_candidato "
    			. "SET arquivo_anexo = ? "
    			. "WHERE id_recurso_candidato = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $arquivo, PDO::PARAM_STR);
    	$stmt->bindParam(2, $idRecursoCandidato, PDO::PARAM_INT);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}

    	$parametros = array($arquivo, $idRecursoCandidato);
    	$this->loggerDAO->salvar($idRecursoCandidato, "UPDATE", "recurso_candidato", $this->diversos->montaQuery($query, $parametros));
    	
    	return true;
    }
    
    public function update($recurso){
    	$query = "UPDATE recurso_candidato "
    		. "SET id_edital_resposta = ? "
    		. "WHERE id_recurso_candidato = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $recurso->getideditalresposta(), PDO::PARAM_INT);
    	$stmt->bindParam(2, $recurso->getidrecursocandidato(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}

    	$parametros = array($recurso->getideditalresposta(), $recurso->getidrecursocandidato());
    	$this->loggerDAO->salvar($recurso->getidrecursocandidato(), "UPDATE", "recurso_candidato", $this->diversos->montaQuery($query, $parametros));
    	
    	return true;
    }
    
    function __destruct() {
    	unset($this->conn);
    }
}
?>