<?php
/**
 * Estados Active Record
 * @author  <your-name-here>
 */
class Estados extends TRecord
{
    const TABLENAME = 'estados';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    
    private $pais;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pais_id');
        parent::addAttribute('nome');
        parent::addAttribute('sigla');
    }

    
    /**
     * Method set_pais
     * Sample of usage: $estados->paises = $object;
     * @param $object Instance of Paises
     */
    public function set_pais(Paises $object)
    {
        $this->pais = $object;
        $this->pais_id = $object->id;
    }
    
    /**
     * Method get_pais
     * Sample of usage: $estados->paises->attribute;
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

