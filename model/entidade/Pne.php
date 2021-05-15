<?php
class Pne {
	private $idpne;
	private $tipo;
	    
	public function gettipo() 
	{
	  return $this->tipo;
	}
	
	public function settipo($value) 
	{
	  $this->tipo = $value;
	}    
	public function getidpne() 
	{
	  return $this->idpne;
	}
	
	public function setidpne($value) 
	{
	  $this->idpne = $value;
	}
}