<?php
/**
 * FormasPagamento Active Record
 * @author  <your-name-here>
 */
class FormasPagamento extends TRecord
{
    const TABLENAME = 'formas_pagamento';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
    }
}
