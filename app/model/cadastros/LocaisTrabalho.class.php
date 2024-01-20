<?php
/**
 * LocaisTrabalho Active Record
 * @author  <your-name-here>
 */
class LocaisTrabalho extends TRecord
{
    const TABLENAME = 'locais_trabalho';
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
        parent::addAttribute('fone');
        parent::addAttribute('cep');
        parent::addAttribute('rua');
        parent::addAttribute('numero');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('cidade');
        parent::addAttribute('estado');
        parent::addAttribute('pais');
        parent::addAttribute('endereco');        
    }
    
    public function store()
    {        
        TTransaction::close();
        TTransaction::open('cep');
        
        $endereco = '';
        
        if ($this->rua)
        {
            $endereco = $this->rua;
            
            if (!empty($this->numero))
            {
                $endereco .= ', ' . $this->numero;
            }
            
            if (!empty($this->bairro))
            {
                $endereco .= ' - ' . $this->bairro;
            }
                        
            if (!empty($this->cidade))
            {
                $endereco .= "\n" . $this->cidade;
            }
                            
            if (!empty($this->estado))
            {
                $endereco .= ' - ' . $this->estado;
            }
            
            if (!empty($this->cep))
            {
                $endereco .= "\n" . $this->cep;   
            }
        }
                
        TTransaction::close();
        TTransaction::open(TSession::getValue('unit_database'));
        
        $this->endereco = $endereco;
        
        parent::store();
    }
}
