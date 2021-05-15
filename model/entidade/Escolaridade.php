<?php
class Escolaridade {
	private $idescolaridade;
	private $grauinstrucao;
	    
	public function getgrauinstrucao() 
	{
	  return $this->grauinstrucao;
	}
	
	public function setgrauinstrucao($value) 
	{
	  $this->grauinstrucao = $value;
	}    
	public function getidescolaridade() 
	{
	  return $this->idescolaridade;
	}
	
	public function setidescolaridade($value) 
	{
	  $this->idescolaridade = $value;
	}
}