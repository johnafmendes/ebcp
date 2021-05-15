<?php
class Cidade {
	private $idcidade;
	private $cidade;
	private $idestado;
	    
	public function getidestado() 
	{
	  return $this->idestado;
	}
	
	public function setidestado($value) 
	{
	  $this->idestado = $value;
	}    
	public function getcidade() 
	{
	  return $this->cidade;
	}
	
	public function setcidade($value) 
	{
	  $this->cidade = $value;
	}    
	public function getidcidade() 
	{
	  return $this->idcidade;
	}
	
	public function setidcidade($value) 
	{
	  $this->idcidade = $value;
	}
}