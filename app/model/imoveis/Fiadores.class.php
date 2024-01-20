<?php
/**
 * Fiadores Active Record
 * @author  <your-name-here>
 */
class Fiadores extends TRecord
{
    const TABLENAME = 'fiadores';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $contrato_locacao;
    private $pessoa;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('contrato_locacao_id');
        parent::addAttribute('pessoa_id');
    }

    /**
     * Method set_contrato_locacao
     * Sample of usage: $fiadores->contrato_locacao = $object;
     * @param $object Instance of ContratosLocacao
     */
    public function set_contrato_locacao(ContratosLocacao $object)
    {
        $this->contrato_locacao = $object;
        $this->contrato_locacao_id = $object->id;
    }
    
    /**
     * Method get_contrato_locacao
     * Sample of usage: $fiadores->contrato_locacao->attribute;
     * @returns ContratosLocacao instance
     */
    public function get_contrato_locacao()
    {
        // loads the associated object
        if (empty($this->contrato_locacao))
            $this->contrato_locacao = new ContratosLocacao($this->contrato_locacao_id);
    
        // returns the associated object
        return $this->contrato_locacao;
    }
    
    /**
     * Method set_pessoa
     * Sample of usage: $fiadores->pessoa = $object;
     * @param $object Instance of Pessoas
     */
    public function set_pessoa(Pessoas $object)
    {
        $this->pessoa = $object;
        $this->pessoa_id = $object->id;
    }
    
    /**
     * Method get_pessoa
     * Sample of usage: $fiadores->pessoa->attribute;
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
