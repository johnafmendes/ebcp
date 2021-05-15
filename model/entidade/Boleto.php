<?php
class Boleto {
	private $idboleto;
	private $descricao;
	private $bancoagencia;
	private $contanumero;
	private $contadv;
	private $cedentecodigo;
	private $cedentecodigodv;
	private $cedenteidentificacao;
	private $cedentecnpj;
	private $cedenteendereco;
	private $cedentecidade;
	private $cedenteestado;
	private $cedenterazaosocial;
	private $nomearquivo;
	private $instrucoescaixa1;
	private $instrucoescaixa2;
	private $instrucoescaixa3;
	private $instrucoescaixa4;
	    
	public function getcedentecodigodv() 
	{
	  return $this->cedentecodigodv;
	}
	
	public function setcedentecodigodv($value) 
	{
	  $this->cedentecodigodv = $value;
	}
	public function getinstrucoescaixa4() 
	{
	  return $this->instrucoescaixa4;
	}
	
	public function setinstrucoescaixa4($value) 
	{
	  $this->instrucoescaixa4 = $value;
	}    
	public function getinstrucoescaixa3() 
	{
	  return $this->instrucoescaixa3;
	}
	
	public function setinstrucoescaixa3($value) 
	{
	  $this->instrucoescaixa3 = $value;
	}    
	public function getinstrucoescaixa2() 
	{
	  return $this->instrucoescaixa2;
	}
	
	public function setinstrucoescaixa2($value) 
	{
	  $this->instrucoescaixa2 = $value;
	}    
	public function getinstrucoescaixa1() 
	{
	  return $this->instrucoescaixa1;
	}
	
	public function setinstrucoescaixa1($value) 
	{
	  $this->instrucoescaixa1 = $value;
	}
	
	public function getnomearquivo() 
	{
	  return $this->nomearquivo;
	}
	
	public function setnomearquivo($value) 
	{
	  $this->nomearquivo = $value;
	}    
	public function getcedenterazaosocial() 
	{
	  return $this->cedenterazaosocial;
	}
	
	public function setcedenterazaosocial($value) 
	{
	  $this->cedenterazaosocial = $value;
	}    
	public function getcedenteestado() 
	{
	  return $this->cedenteestado;
	}
	
	public function setcedenteestado($value) 
	{
	  $this->cedenteestado = $value;
	}    
	public function getcedentecidade() 
	{
	  return $this->cedentecidade;
	}
	
	public function setcedentecidade($value) 
	{
	  $this->cedentecidade = $value;
	}    
	public function getcedenteendereco() 
	{
	  return $this->cedenteendereco;
	}
	
	public function setcedenteendereco($value) 
	{
	  $this->cedenteendereco = $value;
	}    
	public function getcedentecnpj() 
	{
	  return $this->cedentecnpj;
	}
	
	public function setcedentecnpj($value) 
	{
	  $this->cedentecnpj = $value;
	}    
	public function getcedenteidentificacao() 
	{
	  return $this->cedenteidentificacao;
	}
	
	public function setcedenteidentificacao($value) 
	{
	  $this->cedenteidentificacao = $value;
	}    
	public function getcedentecodigo() 
	{
	  return $this->cedentecodigo;
	}
	
	public function setcedentecodigo($value) 
	{
	  $this->cedentecodigo = $value;
	}    
	public function getcontadv() 
	{
	  return $this->contadv;
	}
	
	public function setcontadv($value) 
	{
	  $this->contadv = $value;
	}    
	public function getcontanumero() 
	{
	  return $this->contanumero;
	}
	
	public function setcontanumero($value) 
	{
	  $this->contanumero = $value;
	}    
	public function getbancoagencia() 
	{
	  return $this->bancoagencia;
	}
	
	public function setbancoagencia($value) 
	{
	  $this->bancoagencia = $value;
	}    
	public function getdescricao() 
	{
	  return $this->descricao;
	}
	
	public function setdescricao($value) 
	{
	  $this->descricao = $value;
	}    
	public function getidboleto() 
	{
	  return $this->idboleto;
	}
	
	public function setidboleto($value) 
	{
	  $this->idboleto = $value;
	}
}