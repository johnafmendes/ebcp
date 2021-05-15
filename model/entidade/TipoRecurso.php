<?php
class TipoRecurso {
	private $idtiporecurso;
	private $tiporecurso;
	    
	public function gettiporecurso() 
	{
	  return $this->tiporecurso;
	}
	
	public function settiporecurso($value) 
	{
	  $this->tiporecurso = $value;
	}    
	public function getidtiporecurso() 
	{
	  return $this->idtiporecurso;
	}
	
	public function setidtiporecurso($value) 
	{
	  $this->idtiporecurso = $value;
	}
}