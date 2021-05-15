<?php
class Estado {
	private $idestado;
	private $nome;
	private $sigla;
	    
	public function getsigla() 
	{
	  return $this->sigla;
	}
	
	public function setsigla($value) 
	{
	  $this->sigla = $value;
	}    
	public function getnome() 
	{
	  return $this->nome;
	}
	
	public function setnome($value) 
	{
	  $this->nome = $value;
	}
	public function getidestado() 
	{
	  return $this->idestado;
	}
	
	public function setidestado($value) 
	{
	  $this->idestado = $value;
	}
}