<?php
/**
 * Ruas Active Record
 * @author  <your-name-here>
 */
class Ruas extends TRecord
{
    const TABLENAME = 'ruas';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $bairro;
    private $cidade;
    private $estado;
    private $pais;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('bairro_id');
        parent::addAttribute('cidade_id');
        parent::addAttribute('estado_id');
        parent::addAttribute('pais_id');
        parent::addAttribute('cep');
        parent::addAttribute('nome');
    }
    
    /**
     * Method set_bairro
     * Sample of usage: $ruas->bairros = $object;
     * @param $object Instance of Bairros
     */
    public function set_bairro(Bairros $object)
    {
        $this->bairro = $object;
        $this->bairro_id = $object->id;
    }
    
    /**
     * Method get_bairro
     * Sample of usage: $ruas->bairros->attribute;
     * @returns Bairros instance
     */
    public function get_bairro()
    {
        // loads the associated object
        if (empty($this->bairro))
            $this->bairro = new Bairros($this->bairro_id);
    
        // returns the associated object
        return $this->bairro;
    }    
    
    /**
     * Method set_cidade
     * Sample of usage: $ruas->cidades = $object;
     * @param $object Instance of Cidades
     */
    public function set_cidade(Cidades $object)
    {
        $this->cidade = $object;
        $this->cidade_id = $object->id;
    }
    
    /**
     * Method get_cidade
     * Sample of usage: $ruas->cidades->attribute;
     * @returns Cidades instance
     */
    public function get_cidade()
    {
        // loads the associated object
        if (empty($this->cidade))
            $this->cidade = new Cidades($this->cidade_id);
    
        // returns the associated object
        return $this->cidade;
    }    
    
    /**
     * Method set_estado
     * Sample of usage: $ruas->estados = $object;
     * @param $object Instance of Estados
     */
    public function set_estado(Estados $object)
    {
        $this->estado = $object;
        $this->estado_id = $object->id;
    }
    
    /**
     * Method get_estado
     * Sample of usage: $ruas->estados->attribute;
     * @returns Estados instance
     */
    public function get_estado()
    {
        // loads the associated object
        if (empty($this->estado))
            $this->estado = new Estados($this->estado_id);
    
        // returns the associated object
        return $this->estado;
    }    
    
    /**
     * Method set_pais
     * Sample of usage: $ruas->paises = $object;
     * @param $object Instance of Paises
     */
    public function set_pais(Paises $object)
    {
        $this->pais = $object;
        $this->pais_id = $object->id;
    }
    
    /**
     * Method get_pais
     * Sample of usage: $ruas->paises->attribute;
     * @returns Paises instance
     */
    public function get_pais()
    {
        // loads the associated object
        if (empty($this->pais))
            $this->pais = new Paises($this->pais_id);
    
        // returns the associated object
        return $this->pais;
    }
}