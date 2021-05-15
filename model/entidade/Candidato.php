<?php
class Candidato {
	private $idcandidato;
	private $nome;
	private $rg;
	private $orgaoemissorrg;
	private $dataemissaorg;
	private $cpf;
	private $estadocivil;
	private $sexo;
	private $datanascimento;
	private $endereco;
	private $numeroendereco;
	private $complementoendereco;
	private $bairro;
	private $cidade;
	private $idestado;
	private $cep;
	private $telefone;
	private $email;
	private $senha;	
	private $senha2;
	private $idescolaridade;
	private $nomepai;
	private $nomemae;
	    
	public function getnomemae() 
	{
	  return $this->nomemae;
	}
	
	public function setnomemae($value) 
	{
	  $this->nomemae = $value;
	}    
	public function getnomepai() 
	{
	  return $this->nomepai;
	}
	
	public function setnomepai($value) 
	{
	  $this->nomepai = $value;
	}
	    
	public function getsenha2() 
	{
	  return $this->senha2;
	}
	
	public function setsenha2($value) 
	{
	  $this->senha2 = $value;
	}
	    
	public function getidescolaridade() 
	{
	  return $this->idescolaridade;
	}
	
	public function setidescolaridade($value) 
	{
	  $this->idescolaridade = $value;
	}
	    

	public function getidcandidato() 
	{
	  return $this->idcandidato;
	}
	
	public function setidcandidato($value) 
	{
	  $this->idcandidato = $value;
	}
	
	public function getsenha() 
	{
	  return $this->senha;
	}
	
	public function setsenha($value) 
	{
	  $this->senha = $value;
	}
	
	public function getcep()
	{
		return $this->cep;
	}
	
	public function setcep($value)
	{
		$this->cep = $value;
	}
	
	public function getbairro()
	{
		return $this->bairro;
	}
	
	public function setbairro($value)
	{
		$this->bairro = $value;
	}
	
	public function getcomplementoendereco()
	{
		return $this->complementoendereco;
	}
	
	public function setcomplementoendereco($value)
	{
		$this->complementoendereco = $value;
	}
	
	public function getnumeroendereco()
	{
		return $this->numeroendereco;
	}
	
	public function setnumeroendereco($value)
	{
		$this->numeroendereco = $value;
	}
	
	public function getendereco()
	{
		return $this->endereco;
	}
	
	public function setendereco($value)
	{
		$this->endereco = $value;
	}
	
	public function getsexo() 
	{
	  return $this->sexo;
	}
	
	public function setsexo($value) 
	{
	  $this->sexo = $value;
	}
	
	public function getestadocivil() 
	{
	  return $this->estadocivil;
	}
	
	public function setestadocivil($value) 
	{
	  $this->estadocivil = $value;
	}
	
	public function getcpf() 
	{
	  return $this->cpf;
	}
	
	public function setcpf($value) 
	{
	  $this->cpf = $value;
	}

	public function getdataemissaorg() 
	{
	  return $this->dataemissaorg;
	}
	
	public function setdataemissaorg($value) 
	{
	  $this->dataemissaorg = $value;
	}
	
	public function getorgaoemissorrg() 
	{
	  return $this->orgaoemissorrg;
	}
	
	public function setorgaoemissorrg($value) 
	{
	  $this->orgaoemissorrg = $value;
	}
	
	public function getrg() 
	{
	  return $this->rg;
	}
	
	public function setrg($value) 
	{
	  $this->rg = $value;
	}
	
	public function getnome() 
	{
	  return $this->nome;
	}
	
	public function setnome($value) 
	{
	  $this->nome = $value;
	}
	
	public function getdatanascimento()
	{
		return $this->datanascimento;
	}
	
	public function setdatanascimento($value)
	{
		$this->datanascimento = $value;
	}
	
	public function getcidade()
	{
		return $this->cidade;
	}
	
	public function setcidade($value)
	{
		$this->cidade = $value;
	}
	
	public function getidestado()
	{
		return $this->idestado;
	}
	
	public function setidestado($value)
	{
		$this->idestado = $value;
	}
	
	public function gettelefone()
	{
		return $this->telefone;
	}
	
	public function settelefone($value)
	{
		$this->telefone = $value;
	}
	
	public function getemail()
	{
		return $this->email;
	}
	
	public function setemail($value)
	{
		$this->email = $value;
	}
	
}