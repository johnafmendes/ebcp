<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";  
include_once $origem.'controller/diversos/Diversos.php';

class ConcursoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();		
	}
	
	public function listarConcursosPorIdInstituicao($idInstituicao){
		$query = "SELECT c.* "
			. "FROM concurso c "
			. "INNER JOIN instituicao i ON i.id_instituicao = c.id_instituicao "
			. "WHERE i.id_instituicao = ? ";
		//     			echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idInstituicao, PDO::PARAM_INT);
		$stmt->execute();
		
		$parametros = array($idInstituicao);
		$this->loggerDAO->salvar(null, "SELECT", "concurso", $this->diversos->montaQuery($query, $parametros));
		 
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function excluirPorID($idConcurso){
		$query = "DELETE from concurso "
			. "WHERE id_concurso = ?";
		// 		echo $query;
		try{
			$stmt = $this->conn->prepare( $query );
			$stmt->bindParam(1, $idConcurso, PDO::PARAM_INT);
			$stmt->execute();
			
				
		} catch (Exception $e){
			return false;
		}
		
		if ($stmt->rowCount() == 0) {
			return false;
		}
		$parametros = array($idConcurso);
		$this->loggerDAO->salvar($idConcurso, "DELETE", "concurso", $this->diversos->montaQuery($query, $parametros));
		return true;
	}
	
	public function getConcursoPorTitulo($titulo){
		$query = "SELECT * "
				. "FROM concurso "
				. "WHERE titulo like ? "
				. "ORDER BY titulo DESC";
		//     			echo $query;
		$stmt = $this->conn->prepare( $query );
// 		echo $titulocodigo;
		$titulo = "%" . $titulo . "%";
		$stmt->bindParam(1, $titulo, PDO::PARAM_STR);
		$stmt->execute();
		
		$parametros = array($titulo);
		$this->loggerDAO->salvar(null, "SELECT", "concurso", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function update($concurso) {
    	$query = "UPDATE concurso set titulo = ?, subtitulo = ?, id_instituicao = ?, inicio_inscricao = ?, final_inscricao = ?, "
    			. "vencimento_boleto = ?, id_boleto = ?, homologado = ?, ativo = ?, destaque = ?, id_tipo_concurso = ?, "
    			. "isencao_doador_sangue = ?, doador_sangue_data_inicio = ?, doador_sangue_data_fim = ?, isencao_nis = ?, "
    			. "nis_data_inicio = ?, nis_data_fim = ?, cota_racial = ?, multiplas_inscricoes = ? "
    			. "WHERE id_concurso = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $concurso->gettitulo(), PDO::PARAM_STR);
    	$stmt->bindParam(2, $concurso->getsubtitulo(), PDO::PARAM_STR);
    	$stmt->bindParam(3, $concurso->getidinstituicao(), PDO::PARAM_INT);
    	$stmt->bindParam(4, $concurso->getinicioinscricao(), PDO::PARAM_STR);
    	$stmt->bindParam(5, $concurso->getfinalinscricao(), PDO::PARAM_STR);
    	$stmt->bindParam(6, $concurso->getvencimentoboleto(), PDO::PARAM_STR);
    	$stmt->bindParam(7, $concurso->getidboleto(), PDO::PARAM_INT);
    	$stmt->bindParam(8, $concurso->gethomologado(), PDO::PARAM_INT);
    	$stmt->bindParam(9, $concurso->getativo(), PDO::PARAM_INT);
    	$stmt->bindParam(10, $concurso->getdestaque(), PDO::PARAM_INT);
    	$stmt->bindParam(11, $concurso->getidtipoconcurso(), PDO::PARAM_INT);
    	$stmt->bindParam(12, $concurso->getisencaodoadorsangue(), PDO::PARAM_INT);
    	$stmt->bindParam(13, $concurso->getdoadorsanguedatainicio(), PDO::PARAM_STR);
    	$stmt->bindParam(14, $concurso->getdoadorsanguedatafim(), PDO::PARAM_STR);
    	$stmt->bindParam(15, $concurso->getisencaonis(), PDO::PARAM_INT);
    	$stmt->bindParam(16, $concurso->getnisdatainicio(), PDO::PARAM_STR);
    	$stmt->bindParam(17, $concurso->getnisdatafim(), PDO::PARAM_STR);
    	$stmt->bindParam(18, $concurso->getcotaracial(), PDO::PARAM_INT);
    	$stmt->bindParam(19, $concurso->getmultiplasinscricoes(), PDO::PARAM_INT);
    	$stmt->bindParam(20, $concurso->getidconcurso(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	 
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	$parametros = array($concurso->gettitulo(), $concurso->getsubtitulo(), $concurso->getidinstituicao(), 
    			$concurso->getinicioinscricao(), $concurso->getfinalinscricao(), $concurso->getvencimentoboleto(), 
    			$concurso->getidboleto(), $concurso->gethomologado(), $concurso->getativo(), 
    			$concurso->getdestaque(), $concurso->getidtipoconcurso(), $concurso->getisencaodoadorsangue(), 
    			$concurso->getdoadorsanguedatainicio(), $concurso->getdoadorsanguedatafim(), 
    			$concurso->getisencaonis(), $concurso->getnisdatainicio(), $concurso->getnisdatafim(), 
    			$concurso->getcotaracial(), $concurso->getmultiplasinscricoes(), $concurso->getidconcurso());
    	$this->loggerDAO->salvar($concurso->getidconcurso(), "UPDATE", "concurso", $this->diversos->montaQuery($query, $parametros));
    	return true;
    }
    
    public function salvar ($concurso){
		$query = "INSERT INTO concurso (titulo, subtitulo, id_instituicao, inicio_inscricao, final_inscricao, vencimento_boleto, "
				. "id_boleto, homologado, ativo, destaque, id_tipo_concurso, isencao_doador_sangue, doador_sangue_data_inicio, "
				. "doador_sangue_data_fim, isencao_nis, nis_data_inicio, nis_data_fim, cota_racial, multiplas_inscricoes) "
						. "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $concurso->gettitulo(), PDO::PARAM_STR);
		$stmt->bindParam(2, $concurso->getsubtitulo(), PDO::PARAM_STR);
		$stmt->bindParam(3, $concurso->getidinstituicao(), PDO::PARAM_INT);
		$stmt->bindParam(4, $concurso->getinicioinscricao(), PDO::PARAM_STR);
		$stmt->bindParam(5, $concurso->getfinalinscricao(), PDO::PARAM_STR);
		$stmt->bindParam(6, $concurso->getvencimentoboleto(), PDO::PARAM_STR);
		$stmt->bindParam(7, $concurso->getidboleto(), PDO::PARAM_INT);
		$stmt->bindParam(8, $concurso->gethomologado(), PDO::PARAM_INT);
		$stmt->bindParam(9, $concurso->getativo(), PDO::PARAM_INT);
		$stmt->bindParam(10, $concurso->getdestaque(), PDO::PARAM_INT);
		$stmt->bindParam(11, $concurso->getidtipoconcurso(), PDO::PARAM_INT);
		$stmt->bindParam(12, $concurso->getisencaodoadorsangue(), PDO::PARAM_INT);
		$stmt->bindParam(13, $concurso->getdoadorsanguedatainicio(), PDO::PARAM_STR);
		$stmt->bindParam(14, $concurso->getdoadorsanguedatafim(), PDO::PARAM_STR);
		$stmt->bindParam(15, $concurso->getisencaonis(), PDO::PARAM_INT);
		$stmt->bindParam(16, $concurso->getnisdatainicio(), PDO::PARAM_STR);
		$stmt->bindParam(17, $concurso->getnisdatafim(), PDO::PARAM_STR);
		$stmt->bindParam(18, $concurso->getcotaracial(), PDO::PARAM_INT);
		$stmt->bindParam(19, $concurso->getmultiplasinscricoes(), PDO::PARAM_INT);
		$stmt->execute();

		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		$parametros = array($concurso->gettitulo(), $concurso->getsubtitulo(), $concurso->getidinstituicao(),
				$concurso->getinicioinscricao(), $concurso->getfinalinscricao(), $concurso->getvencimentoboleto(),
				$concurso->getidboleto(), $concurso->gethomologado(), $concurso->getativo(),
				$concurso->getdestaque(), $concurso->getidtipoconcurso(), $concurso->getisencaodoadorsangue(),
				$concurso->getdoadorsanguedatainicio(), $concurso->getdoadorsanguedatafim(),
				$concurso->getisencaonis(), $concurso->getnisdatainicio(), $concurso->getnisdatafim(),
				$concurso->getcotaracial(), $concurso->getmultiplasinscricoes());
		$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "concurso", $this->diversos->montaQuery($query, $parametros));
		return $stmt;
	}
	
	public function listarTodosConcursos(){
		$query = "SELECT * "
				. "FROM concurso "
				. "ORDER BY id_concurso DESC";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$parametros = array();
		$this->loggerDAO->salvar(null, "SELECT", "concurso", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function listarConcursosInscritosPorIdCandidato($idCandidato){
		$query = "SELECT i.*, c.*, c.titulo as concurso, ca.titulo as cargo, ci.cidade "
				. "FROM inscricao i "
				. "INNER JOIN concurso c ON c.id_concurso = i.id_concurso "
				. "INNER JOIN cargo ca ON i.id_cargo = ca.id_cargo "
				. "INNER JOIN cidade_prova cp ON i.id_cidade_prova = cp.id_cidade_prova "
				. "INNER JOIN cidade ci ON cp.id_cidade = ci.id_cidade "
				. "WHERE i.id_candidato = ? "
				. "ORDER BY i.id_inscricao DESC";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idCandidato, PDO::PARAM_INT);
		$stmt->execute();
		
		$parametros = array($idCandidato);
		$this->loggerDAO->salvar(null, "SELECT", "concurso", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
		
	public function listarConcursosAbertos() {
		$query = "SELECT * "
			 . "FROM concurso "
			 . "WHERE inicio_inscricao <= ? "
			 . "AND final_inscricao >= ? "
			 . "AND ativo = 1";
// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, date("Y-m-d H:i:s"), PDO::PARAM_STR);
		$stmt->bindParam(2, date("Y-m-d H:i:s"), PDO::PARAM_STR);
		$stmt->execute();

		$parametros = array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
		$this->loggerDAO->salvar(null, "SELECT", "concurso", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
    }	
    
    public function listarConcursosAndamento() {
    	$query = "SELECT * " 
    			. "FROM concurso "
    			. "WHERE final_inscricao < ? "
    			. "AND homologado = 0 "
    			. "AND ativo = 1 "
    			. "ORDER BY final_inscricao DESC";
//     			echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, date("Y-m-d H:i:s"), PDO::PARAM_STR);
    	$stmt->execute();
    
    	$parametros = array(date("Y-m-d H:i:s"));
    	$this->loggerDAO->salvar(null, "SELECT", "concurso", $this->diversos->montaQuery($query, $parametros));
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
    
    public function listarConcursosEncerrados() {
    	$query = "SELECT * "
    			. "FROM concurso "
    			. "WHERE final_inscricao < ? "
    			. "AND homologado = 1 "
    			. "AND ativo = 1 "
    			. "ORDER BY final_inscricao DESC";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, date("Y-m-d H:i:s"), PDO::PARAM_STR);
    	$stmt->execute();
    
    	$parametros = array(date("Y-m-d H:i:s"));
    	$this->loggerDAO->salvar(null, "SELECT", "concurso", $this->diversos->montaQuery($query, $parametros));
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
    
    public function listarConcursosFuturos() {
    	$query = "SELECT * "
    			. "FROM concurso "
				. "WHERE inicio_inscricao >= ? "
				. "AND homologado = 0 "
				. "AND ativo = 1 "
				. "ORDER BY inicio_inscricao DESC";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, date("Y-m-d H:i:s"), PDO::PARAM_STR);
    	$stmt->execute();
    
    	$parametros = array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
    	$this->loggerDAO->salvar(null, "SELECT", "concurso", $this->diversos->montaQuery($query, $parametros));
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
    
    public function listarConcursosVestibulares() {
    	$query = "SELECT * "
			 . "FROM concurso "
			 . "WHERE inicio_inscricao <= ? "
			 . "AND final_inscricao >= ? "
			 . "AND ativo = 1 "
			 . "AND tipo_concurso = 2";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, date("Y-m-d H:i:s"), PDO::PARAM_STR);
    	$stmt->bindParam(2, date("Y-m-d H:i:s"), PDO::PARAM_STR);
    	$stmt->execute();
    
    	$parametros = array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
    	$this->loggerDAO->salvar(null, "SELECT", "concurso", $this->diversos->montaQuery($query, $parametros));
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
    
    public function listarConcursosResMedica() {
    	$query = "SELECT * "
    			. "FROM concurso "
    			. "WHERE inicio_inscricao <= ? "
    			. "AND final_inscricao >= ? "
    			. "AND ativo = 1 "
    			. "AND tipo_concurso = 3";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, date("Y-m-d H:i:s"), PDO::PARAM_STR);
    	$stmt->bindParam(2, date("Y-m-d H:i:s"), PDO::PARAM_STR);
    	$stmt->execute();
    
    	$parametros = array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
    	$this->loggerDAO->salvar(null, "SELECT", "concurso", $this->diversos->montaQuery($query, $parametros));
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
    
    public function listarConcursosAvalEducacional() {
    	$query = "SELECT * "
    			. "FROM concurso "
    			. "WHERE inicio_inscricao <= ? "
    			. "AND final_inscricao >= ? "
    			. "AND ativo = 1 "
    			. "AND tipo_concurso = 4";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, date("Y-m-d H:i:s"), PDO::PARAM_STR);
    	$stmt->bindParam(2, date("Y-m-d H:i:s"), PDO::PARAM_STR);
    	$stmt->execute();
    
    	$parametros = array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
    	$this->loggerDAO->salvar(null, "SELECT", "concurso", $this->diversos->montaQuery($query, $parametros));
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
    
    public function listarConcursosSelPrivada() {
    	$query = "SELECT * "
    			. "FROM concurso "
    			. "WHERE inicio_inscricao <= ? "
    			. "AND final_inscricao >= ? "
    			. "AND ativo = 1 "
    			. "AND tipo_concurso = 5";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, date("Y-m-d H:i:s"), PDO::PARAM_STR);
    	$stmt->bindParam(2, date("Y-m-d H:i:s"), PDO::PARAM_STR);
    	$stmt->execute();
    
    	$parametros = array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
    	$this->loggerDAO->salvar(null, "SELECT", "concurso", $this->diversos->montaQuery($query, $parametros));
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
    
    public function listarConcursosDestaque() {
    	$query = "SELECT c.id_concurso, c.titulo, c.subtitulo, i.instituicao, i.logo "
    			. "FROM concurso c "
    			. "INNER JOIN instituicao i ON i.id_instituicao = c.id_instituicao "
    			. "WHERE c.destaque = 1 "
    			. "AND c.ativo = 1 ";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->execute();
    
    	$parametros = array();
    	$this->loggerDAO->salvar(null, "SELECT", "concurso", $this->diversos->montaQuery($query, $parametros));
    	
    	if ($stmt->rowCount() == 0) {
    		return null;
    	}
    	return $stmt;
    }
    
    public function getConcursoPorID($idConcurso) {
    	$query = "SELECT c.*, i.* "
    			. "FROM concurso c "
    			. "INNER JOIN instituicao i ON i.id_instituicao = c.id_instituicao "
    			. "WHERE c.id_concurso = ? ";
//     			echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $idConcurso, PDO::PARAM_INT);
    	$stmt->execute();
    
    	$parametros = array($idConcurso);
    	$this->loggerDAO->salvar(null, "SELECT", "concurso", $this->diversos->montaQuery($query, $parametros));
    	
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