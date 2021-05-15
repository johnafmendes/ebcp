<?php
class TipoConcurso {
	private $idtipoconcurso;
	private $tipo;
	    
	public function gettipo() 
	{
	  return $this->tipo;
	}
	
	public function settipo($value) 
	{
	  $this->tipo = $value;
	}    
	public function getidtipoconcurso() 
	{
	  return $this->idtipoconcurso;
	}
	
	public function setidtipoconcurso($value) 
	{
	  $this->idtipoconcurso = $value;
	}
}