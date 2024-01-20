<?php
/**
 * ImoveisTiposNegocio Active Record
 * @author  <your-name-here>
 */
class ImoveisTiposNegocio extends TRecord
{
    const TABLENAME = 'imoveis_tipos_negocio';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo_negocio_id');
        parent::addAttribute('imovel_id');
    }
}
