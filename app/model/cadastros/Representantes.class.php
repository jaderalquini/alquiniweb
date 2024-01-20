<?php
/**
 * Representantes Active Record
 * @author  <your-name-here>
 */
class Representantes extends TRecord
{
    const TABLENAME = 'representantes';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $pessoa;
    private $representante;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id');
        parent::addAttribute('representante_id');
    }

    /**
     * Method set_pessoa
     * Sample of usage: $representantes->pessoa = $object;
     * @param $object Instance of Pessoas
     */
    public function set_pessoa(Pessoas $object)
    {
        $this->pessoa = $object;
        $this->pessoa_id = $object->id;
    }
    
    /**
     * Method get_pessoa
     * Sample of usage: $representantes->pessoa->attribute;
     * @returns Pessoas instance
     */
    public function get_pessoa()
    {
        // loads the associated object
        if (empty($this->pessoa))
            $this->pessoa = new Pessoas($this->pessoa_id);
    
        // returns the associated object
        return $this->pessoa;
    }
    
    /**
     * Method set_representante
     * Sample of usage: $representantes->representante = $object;
     * @param $object Instance of Pessoas
     */
    public function set_representante(Pessoas $object)
    {
        $this->representante = $object;
        $this->representante_id = $object->id;
    }
    
    /**
     * Method get_representante
     * Sample of usage: $representantes->representante->attribute;
     * @returns Pessoas instance
     */
    public function get_representante()
    {
        // loads the associated object
        if (empty($this->representante))
            $this->representante = new Pessoas($this->representante_id);
    
        // returns the associated object
        return $this->representante;
    }
}
