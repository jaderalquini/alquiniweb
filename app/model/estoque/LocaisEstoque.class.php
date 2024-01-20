<?php
/**
 * LocaisEstoque Active Record
 * @author  <your-name-here>
 */
class LocaisEstoque extends TRecord
{
    const TABLENAME = 'locais_estoque';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $tipo_local_estoque;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('tipo_local_estoque_id');
    }
    
    /**
     * Method set_tipo_local_estoque
     * Sample of usage: $locais_estoque->tipo_local_estoque = $object;
     * @param $object Instance of TiposLocalEstoque
     */
    public function set_tipo_local_estoque(TiposLocalEstoque $object)
    {
        $this->tipo_local_estoque = $object;
        $this->tipo_local_estoque_id = $object->id;
    }
    
    /**
     * Method get_tipo_local_estoque
     * Sample of usage: $locais_estoque->tipo_local_estoque->attribute;
     * @returns TiposLocalEstoque instance
     */
    public function get_tipo_local_estoque()
    {
        // loads the associated object
        if (empty($this->tipo_local_estoque))
            $this->tipo_local_estoque = new TiposLocalEstoque($this->tipo_local_estoque_id);
    
        // returns the associated object
        return $this->tipo_local_estoque;
    }
}
