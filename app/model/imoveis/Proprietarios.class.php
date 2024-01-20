<?php
/**
 * Proprietarios Active Record
 * @author  <your-name-here>
 */
class Proprietarios extends TRecord
{
    const TABLENAME = 'proprietarios';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $tipo_proprietario;
    private $pessoa;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('imovel_id');
        parent::addAttribute('pessoa_id');
        parent::addAttribute('tipo_proprietario_id');
        parent::addAttribute('repasse');
    }
    
    /**
     * Method set_tipo_proprietario
     * Sample of usage: $proprietarios->tipo_proprietario = $object;
     * @param $object Instance of TiposProprietario
     */
    public function set_tipo_proprietario(TiposProprietario $object)
    {
        $this->tipo_proprietario = $object;
        $this->tipo_proprietario_id = $object->id;
    }
    
    /**
     * Method get_tipo_proprietario
     * Sample of usage: $proprietarios->tipo_proprietario->attribute;
     * @returns TiposProprietario instance
     */
    public function get_tipo_proprietario()
    {
        // loads the associated object
        if (empty($this->tipo_proprietario))
            $this->tipo_proprietario = new TiposProprietario($this->tipo_proprietario_id);
    
        // returns the associated object
        return $this->tipo_proprietario;
    }
    
    /**
     * Method set_pessoa
     * Sample of usage: $proprietarios->pessoa = $object;
     * @param $object Instance of Pessoas
     */
    public function set_pessoa(Pessoas $object)
    {
        $this->pessoa = $object;
        $this->pessoa_id = $object->id;
    }
    
    /**
     * Method get_pessoa
     * Sample of usage: $proprietarios->pessoa->attribute;
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
}
