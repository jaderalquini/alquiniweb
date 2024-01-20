<?php
/**
 * Cidades Active Record
 * @author  <your-name-here>
 */
class Cidades extends TRecord
{
    const TABLENAME = 'cidades';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    
    private $estado;
    private $pais;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('estado_id');
        parent::addAttribute('pais_id');
        parent::addAttribute('dimob');
        parent::addAttribute('nome');
    }

    
    /**
     * Method set_estado
     * Sample of usage: $cidades->estados = $object;
     * @param $object Instance of Estados
     */
    public function set_estado(Estados $object)
    {
        $this->estado = $object;
        $this->estado_id = $object->id;
    }
    
    /**
     * Method get_estado
     * Sample of usage: $cidades->estados->attribute;
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
     * Sample of usage: $cidades->paises = $object;
     * @param $object Instance of Paises
     */
    public function set_pais(Paises $object)
    {
        $this->pais = $object;
        $this->pais_id = $object->id;
    }
    
    /**
     * Method get_pais
     * Sample of usage: $cidades->paises->attribute;
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
