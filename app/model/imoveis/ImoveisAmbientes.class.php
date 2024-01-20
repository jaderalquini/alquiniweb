<?php
/**
 * ImoveisAmbientes Active Record
 * @author  <your-name-here>
 */
class ImoveisAmbientes extends TRecord
{
    const TABLENAME = 'imoveis_ambientes';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $ambiente;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('imovel_id');
        parent::addAttribute('ambiente_id');
        parent::addAttribute('qtde');
    }
    
    /**
     * Method set_tipoiptu
     * Sample of usage: $imoveisiptus->ambiente = $object;
     * @param $object Instance of Ambientes
     */
    public function set_ambiente(Comodos $object)
    {
        $this->ambiente = $object;
        $this->ambiente_id = $object->id;
    }
    
    /**
     * Method get_ambiente
     * Sample of usage: $imoveisiptus->ambiente->attribute;
     * @returns Ambientes instance
     */
    public function get_ambiente()
    {
        // loads the associated object
        if (empty($this->ambiente))
            $this->ambiente = new Ambientes($this->ambiente_id);
    
        // returns the associated object
        return $this->ambiente;
    }
}
