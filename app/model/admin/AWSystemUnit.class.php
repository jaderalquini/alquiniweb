<?php
/**
 * AWSystemUnit Active Record
 * @author  <your-name-here>
 */
class AWSystemUnit extends TRecord
{
    const TABLENAME = 'system_unit';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cnpj');
        parent::addAttribute('fone');
        parent::addAttribute('site');
        parent::addAttribute('email');
        parent::addAttribute('cep');
        parent::addAttribute('rua');
        parent::addAttribute('numero');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('cidade');
        parent::addAttribute('estado');
        parent::addAttribute('logo');
    }
}
