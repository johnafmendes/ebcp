<?php
include_once 'Database.php';
include_once 'LoggerDAO.php';
$origem = strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ? "../" : strpos($_SERVER["REQUEST_URI"], "/cliente/") !== false ? "../" : "";
include_once $origem.'controller/diversos/Diversos.php';

class InscricaoDAO extends Database{
	
	private $conn;
	private $diversos;
	private $loggerDAO;
	
	public function __construct(){
		parent::__construct();
		$this->conn = parent::getConnection();
		$this->diversos = new Diversos();
		$this->loggerDAO = new LoggerDAO();
	}
		
	public function getInscricaoPorCPF($cpf){
		$query = "SELECT i.*, c.titulo, ca.titulo as cargo, ci.cidade, can.nome "
			. "FROM inscricao i "
			. "INNER JOIN concurso c ON c.id_concurso = i.id_concurso "
			. "INNER JOIN cargo ca ON ca.id_cargo = i.id_cargo "
			. "INNER JOIN cidade_prova cp ON cp.id_cidade_prova = i.id_cidade_prova "
			. "INNER JOIN cidade ci ON ci.id_cidade = cp.id_cidade "
			. "INNER JOIN candidato can ON can.id_candidato = i.id_candidato "
			. "WHERE can.cpf = ? ";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $cpf, PDO::PARAM_STR);
		$stmt->execute();

		$parametros = array($cpf);
		$this->loggerDAO->salvar(null, "SELECT", "inscricao", $this->diversos->montaQuery($query, $parametros));
		
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}
	
	public function getInscricaoPorIdCandidato($idCandidato){
		$query = "SELECT i.*, c.titulo, ca.titulo as cargo, ci.cidade "
				. "FROM inscricao i "
				. "INNER JOIN concurso c ON c.id_concurso = i.id_concurso "
				. "INNER JOIN cargo ca ON ca.id_cargo = i.id_cargo "
				. "INNER JOIN cidade_prova cp ON cp.id_cidade_prova = i.id_cidade_prova "
				. "INNER JOIN cidade ci ON ci.id_cidade = cp.id_cidade "
				. "WHERE i.id_candidato = ? ";
		// 		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $idCandidato, PDO::PARAM_INT);
		$stmt->execute();
		 
		$parametros = array($idCandidato);
		$this->loggerDAO->salvar(null, "SELECT", "inscricao", $this->diversos->montaQuery($query, $parametros));
		
		if ($stmt->rowCount() == 0) {
			return null;
		}
		return $stmt;
	}

	public function salvar($inscricao) {
    	$query = "INSERT INTO inscricao (id_candidato, id_concurso, id_cargo, id_cidade_prova, id_lingua_estrangeira, data_inscricao, "    			
    			. "isencao, doador_sangue, id_pne, pne_especifico, nis, afrodescendente, prova_ampliada, sala_facil_acesso, "
    			. "auxilio_transcricao, outra_solicitacao) "
    			. "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $inscricao->getidcandidato(), PDO::PARAM_INT);
    	$stmt->bindParam(2, $inscricao->getidconcurso(), PDO::PARAM_INT);
    	$stmt->bindParam(3, $inscricao->getidcargo(), PDO::PARAM_INT);
    	$stmt->bindParam(4, $inscricao->getidcidadeprova(), PDO::PARAM_INT);
    	$stmt->bindParam(5, $inscricao->getidlinguaestrangeira(), PDO::PARAM_INT);
    	$stmt->bindParam(6, $inscricao->getdatainscricao(), PDO::PARAM_STR);
    	$stmt->bindParam(7, $inscricao->getisencao(), PDO::PARAM_INT);
    	$stmt->bindParam(8, $inscricao->getdoadorsangue(), PDO::PARAM_INT);
    	$stmt->bindParam(9, $inscricao->getidpne(), PDO::PARAM_INT);
    	$stmt->bindParam(10, $inscricao->getpneespecifico(), PDO::PARAM_STR);
    	$stmt->bindParam(11, $inscricao->getnis(), PDO::PARAM_STR);
    	$stmt->bindParam(12, $inscricao->getafrodescendente(), PDO::PARAM_INT);
    	$stmt->bindParam(13, $inscricao->getprovaampliada(), PDO::PARAM_INT);
    	$stmt->bindParam(14, $inscricao->getsalafacilacesso(), PDO::PARAM_INT);
    	$stmt->bindParam(15, $inscricao->getauxiliotranscricao(), PDO::PARAM_INT);
    	$stmt->bindParam(16, $inscricao->getoutrasolicitacao(), PDO::PARAM_STR);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return 0;
    	}
    	$parametros = array($inscricao->getidcandidato(), $inscricao->getidconcurso(), $inscricao->getidcargo(), 
    			$inscricao->getidcidadeprova(), $inscricao->getidlinguaestrangeira(), $inscricao->getdatainscricao(), 
    			$inscricao->getisencao(), $inscricao->getdoadorsangue(), $inscricao->getidpne(), 
    			$inscricao->getpneespecifico(), $inscricao->getnis(), $inscricao->getafrodescendente(), 
    			$inscricao->getprovaampliada(), $inscricao->getsalafacilacesso(), $inscricao->getauxiliotranscricao(), 
    			$inscricao->getoutrasolicitacao());
    	$this->loggerDAO->salvar($this->conn->lastInsertId(), "INSERT", "inscricao", $this->diversos->montaQuery($query, $parametros));
    	return $this->conn->lastInsertId();
    }
    
    public function update($inscricao) {
    	$query = "UPDATE inscricao SET id_cargo = ?, id_cidade_prova = ?, id_lingua_estrangeira = ?, "
    			. "isencao = ?, doador_sangue = ?, id_pne = ?, pne_especifico = ?, nis = ?, afrodescendente = ?, "
    			. "prova_ampliada = ?, sala_facil_acesso = ?, auxilio_transcricao = ?, outra_solicitacao = ?, "
    			. "homologa_isento = ?, homologado = ? "
    			. "WHERE id_inscricao = ?";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $inscricao->getidcargo(), PDO::PARAM_INT);
    	$stmt->bindParam(2, $inscricao->getidcidadeprova(), PDO::PARAM_INT);
    	$stmt->bindParam(3, $inscricao->getidlinguaestrangeira(), PDO::PARAM_INT);
    	$stmt->bindParam(4, $inscricao->getisencao(), PDO::PARAM_INT);
    	$stmt->bindParam(5, $inscricao->getdoadorsangue(), PDO::PARAM_INT);
    	$stmt->bindParam(6, $inscricao->getidpne(), PDO::PARAM_INT);
    	$stmt->bindParam(7, $inscricao->getpneespecifico(), PDO::PARAM_STR);
    	$stmt->bindParam(8, $inscricao->getnis(), PDO::PARAM_STR);
    	$stmt->bindParam(9, $inscricao->getafrodescendente(), PDO::PARAM_INT);
    	$stmt->bindParam(10, $inscricao->getprovaampliada(), PDO::PARAM_INT);
    	$stmt->bindParam(11, $inscricao->getsalafacilacesso(), PDO::PARAM_INT);
    	$stmt->bindParam(12, $inscricao->getauxiliotranscricao(), PDO::PARAM_INT);
    	$stmt->bindParam(13, $inscricao->getoutrasolicitacao(), PDO::PARAM_STR);
    	$stmt->bindParam(14, $inscricao->gethomologaisento(), PDO::PARAM_INT);
    	$stmt->bindParam(15, $inscricao->gethomologado(), PDO::PARAM_INT);
    	$stmt->bindParam(16, $inscricao->getidinscricao(), PDO::PARAM_INT);
    	$stmt->execute();
    
    	
    	if ($stmt->rowCount() == 0) {
    		return false;
    	}
    	$parametros = array($inscricao->getidcandidato(), $inscricao->getidconcurso(), $inscricao->getidcargo(),
    			$inscricao->getidcidadeprova(), $inscricao->getidlinguaestrangeira(), $inscricao->getdatainscricao(),
    			$inscricao->getisencao(), $inscricao->getdoadorsangue(), $inscricao->getidpne(),
    			$inscricao->getpneespecifico(), $inscricao->getnis(), $inscricao->getafrodescendente(),
    			$inscricao->getprovaampliada(), $inscricao->getsalafacilacesso(), $inscricao->getauxiliotranscricao(),
    			$inscricao->getoutrasolicitacao(), $inscricao->getidinscricao());
    	$this->loggerDAO->salvar($inscricao->getidinscricao(), "UPDATE", "inscricao", $this->diversos->montaQuery($query, $parametros));
    	return true;
    }
    
    public function getInscricaoPorId($idInscricao){
    	$query = "SELECT i.*, can.nome, c.titulo "
    			. "FROM inscricao i "
    			. "INNER JOIN candidato can ON can.id_candidato = i.id_candidato "
    			. "INNER JOIN concurso c ON c.id_concurso = i.id_concurso "
    			. "WHERE i.id_inscricao = ? ";
    	// 		echo $query;
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $idInscricao, PDO::PARAM_INT);
    	$stmt->execute();
    	
    	$parametros = array($idInscricao);
    	$this->loggerDAO->salvar(null, "SELECT", "inscricao", $this->diversos->montaQuery($query, $parametros));
    	
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