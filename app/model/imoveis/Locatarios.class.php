<?php
/**
 * Locatarios Active Record
 * @author  <your-name-here>
 */
class Locatarios extends TRecord
{
    const TABLENAME = 'locatarios';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $imovel;
    private $pessoa;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('contrato_locacao_id');
        parent::addAttribute('pessoa_id');
        parent::addAttribute('participacao');
    }
    
    /**
     * Method set_imovel
     * Sample of usage: $locatarios->imovel = $object;
     * @param $object Instance of Imoveis
     */
    public function set_imovel(Imoveis $object)
    {
        $this->imovel = $object;
        $this->imovel_id = $object->id;
    }
    
    /**
     * Method get_imovel
     * Sample of usage: $locatarios->imovel->attribute;
     * @returns Imoveis instance
     */
    public function get_imovel()
    {
        // loads the associated object
        if (empty($this->imovel))
            $this->imovel = new Imoveis($this->imovel_id);
    
        // returns the associated object
        return $this->imovel;
    }    
    
    /**
     * Method set_pessoa
     * Sample of usage: $locatarios->pessoa = $object;
     * @param $object Instance of Pessoas
     */
    public function set_pessoa(Pessoas $object)
    {
        $this->pessoa = $object;
        $this->pessoa_id = $object->id;
    }
    
    /**
     * Method get_pessoa
     * Sample of usage: $locatarios->pessoa->attribute;
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
