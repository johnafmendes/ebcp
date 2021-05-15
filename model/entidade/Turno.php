<?php
class Turno {
	private $idturno;
	private $turno;
	    
	public function getturno() 
	{
	  return $this->turno;
	}
	
	public function setturno($value) 
	{
	  $this->turno = $value;
	}    
	public function getidturno() 
	{
	  return $this->idturno;
	}
	
	public function setidturno($value) 
	{
	  $this->idturno = $value;
	}	
}