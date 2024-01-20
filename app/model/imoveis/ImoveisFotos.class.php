<?php
/**
 * ImoveisFotos Active Record
 * @author  <your-name-here>
 */
class ImoveisFotos extends TRecord
{
    const TABLENAME = 'imoveis_fotos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('imovel_id');
        parent::addAttribute('foto');
    }
}
