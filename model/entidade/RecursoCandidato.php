<?php

class RecursoCandidato {
    private $idrecursocandidato;
	private $idrecurso;
	private $idinscricao;
	private $datahorarecurso;
	private $textorecurso;
	private $arquivoanexo;
	private $ideditalresposta;
        
    public function getidrecursocandidato() 
    {
      return $this->idrecursocandidato;
    }
    
    public function setidrecursocandidato($value) 
    {
      $this->idrecursocandidato = $value;
    }
	public function getideditalresposta() 
	{
	  return $this->ideditalresposta;
	}
	
	public function setideditalresposta($value) 
	{
	  $this->ideditalresposta = $value;
	}    
	public function getarquivoanexo() 
	{
	  return $this->arquivoanexo;
	}
	
	public function setarquivoanexo($value) 
	{
	  $this->arquivoanexo = $value;
	}    
	public function gettextorecurso() 
	{
	  return $this->textorecurso;
	}
	
	public function settextorecurso($value) 
	{
	  $this->textorecurso = $value;
	}    
	public function getdatahorarecurso() 
	{
	  return $this->datahorarecurso;
	}
	
	public function setdatahorarecurso($value) 
	{
	  $this->datahorarecurso = $value;
	}    
	public function getidinscricao() 
	{
	  return $this->idinscricao;
	}
	
	public function setidinscricao($value) 
	{
	  $this->idinscricao = $value;
	}
	public function getidrecurso() 
	{
	  return $this->idrecurso;
	}
	
	public function setidrecurso($value) 
	{
	  $this->idrecurso = $value;
	}	
}