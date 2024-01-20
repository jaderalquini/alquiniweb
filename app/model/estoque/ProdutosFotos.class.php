<?php
/**
 * ProdutosFotos Active Record
 * @author  <your-name-here>
 */
class ProdutosFotos extends TRecord
{
    const TABLENAME = 'produtos_fotos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('produto_id');
        parent::addAttribute('foto');
    }

}
