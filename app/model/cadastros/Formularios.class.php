<?php
/**
 * Formularios Active Record
 * @author  <your-name-here>
 */
class Formularios extends TRecord
{
    const TABLENAME = 'formularios';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('conteudo');
        parent::addAttribute('file');
    }
}
