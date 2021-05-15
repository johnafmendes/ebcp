<?php
class Concurso {
	private $idconcurso;
	private $titulo;
	private $subtitulo;
	private $idinstituicao;
	private $inicioinscricao;
	private $finalinscricao;
	private $vencimentoboleto;
	private $idboleto;
	private $homologado;
	private $ativo;
	private $destaque;
	private $idtipoconcurso;
	private $isencaodoadorsangue;
	private $doadorsanguedatainicio;
	private $doadorsanguedatafim;
	private $isencaonis;
	private $nisdatainicio;
	private $nisdatafim;
	private $cotaracial;
	private $multiplasinscricoes;
	private $horainicioinscricao;
	private $horafinalinscricao;
	private $horainiciodoadorsangue;
	private $horafinaldoadorsangue;
	private $horainicioisencaonis;
	private $horafinalisencaonis;
	    
	public function gethorafinalisencaonis() 
	{
	  return $this->horafinalisencaonis;
	}
	
	public function sethorafinalisencaonis($value) 
	{
	  $this->horafinalisencaonis = $value;
	}    
	public function gethorainicioisencaonis() 
	{
	  return $this->horainicioisencaonis;
	}
	
	public function sethorainicioisencaonis($value) 
	{
	  $this->horainicioisencaonis = $value;
	}    
	public function gethorafinaldoadorsangue() 
	{
	  return $this->horafinaldoadorsangue;
	}
	
	public function sethorafinaldoadorsangue($value) 
	{
	  $this->horafinaldoadorsangue = $value;
	}    
	public function gethorainiciodoadorsangue() 
	{
	  return $this->horainiciodoadorsangue;
	}
	
	public function sethorainiciodoadorsangue($value) 
	{
	  $this->horainiciodoadorsangue = $value;
	}    
	public function gethorafinalinscricao() 
	{
	  return $this->horafinalinscricao;
	}
	
	public function sethorafinalinscricao($value) 
	{
	  $this->horafinalinscricao = $value;
	}    
	public function gethorainicioinscricao() 
	{
	  return $this->horainicioinscricao;
	}
	
	public function sethorainicioinscricao($value) 
	{
	  $this->horainicioinscricao = $value;
	}
	    
	public function getmultiplasinscricoes() 
	{
	  return $this->multiplasinscricoes;
	}
	
	public function setmultiplasinscricoes($value) 
	{
	  $this->multiplasinscricoes = $value;
	}    
	public function getcotaracial() 
	{
	  return $this->cotaracial;
	}
	
	public function setcotaracial($value) 
	{
	  $this->cotaracial = $value;
	}    
	public function getnisdatafim() 
	{
	  return $this->nisdatafim;
	}
	
	public function setnisdatafim($value) 
	{
	  $this->nisdatafim = $value;
	}    
	public function getnisdatainicio() 
	{
	  return $this->nisdatainicio;
	}
	
	public function setnisdatainicio($value) 
	{
	  $this->nisdatainicio = $value;
	}    
	public function getisencaonis() 
	{
	  return $this->isencaonis;
	}
	
	public function setisencaonis($value) 
	{
	  $this->isencaonis = $value;
	}    
	public function getdoadorsanguedatafim() 
	{
	  return $this->doadorsanguedatafim;
	}
	
	public function setdoadorsanguedatafim($value) 
	{
	  $this->doadorsanguedatafim = $value;
	}    
	public function getdoadorsanguedatainicio() 
	{
	  return $this->doadorsanguedatainicio;
	}
	
	public function setdoadorsanguedatainicio($value) 
	{
	  $this->doadorsanguedatainicio = $value;
	}    
	public function getisencaodoadorsangue() 
	{
	  return $this->isencaodoadorsangue;
	}
	
	public function setisencaodoadorsangue($value) 
	{
	  $this->isencaodoadorsangue = $value;
	}    
	public function getidtipoconcurso() 
	{
	  return $this->idtipoconcurso;
	}
	
	public function setidtipoconcurso($value) 
	{
	  $this->idtipoconcurso = $value;
	}    
	public function getdestaque() 
	{
	  return $this->destaque;
	}
	
	public function setdestaque($value) 
	{
	  $this->destaque = $value;
	}    
	public function getativo() 
	{
	  return $this->ativo;
	}
	
	public function setativo($value) 
	{
	  $this->ativo = $value;
	}    
	public function gethomologado() 
	{
	  return $this->homologado;
	}
	
	public function sethomologado($value) 
	{
	  $this->homologado = $value;
	}    
	public function getidboleto() 
	{
	  return $this->idboleto;
	}
	
	public function setidboleto($value) 
	{
	  $this->idboleto = $value;
	}    
	public function getvencimentoboleto() 
	{
	  return $this->vencimentoboleto;
	}
	
	public function setvencimentoboleto($value) 
	{
	  $this->vencimentoboleto = $value;
	}    
	public function getfinalinscricao() 
	{
	  return $this->finalinscricao;
	}
	
	public function setfinalinscricao($value) 
	{
	  $this->finalinscricao = $value;
	}    
	public function getinicioinscricao() 
	{
	  return $this->inicioinscricao;
	}
	
	public function setinicioinscricao($value) 
	{
	  $this->inicioinscricao = $value;
	}    
	public function getidinstituicao() 
	{
	  return $this->idinstituicao;
	}
	
	public function setidinstituicao($value) 
	{
	  $this->idinstituicao = $value;
	}    
	public function getsubtitulo() 
	{
	  return $this->subtitulo;
	}
	
	public function setsubtitulo($value) 
	{
	  $this->subtitulo = $value;
	}    
	public function gettitulo() 
	{
	  return $this->titulo;
	}
	
	public function settitulo($value) 
	{
	  $this->titulo = $value;
	}    
	public function getidconcurso() 
	{
	  return $this->idconcurso;
	}
	
	public function setidconcurso($value) 
	{
	  $this->idconcurso = $value;
	}
}