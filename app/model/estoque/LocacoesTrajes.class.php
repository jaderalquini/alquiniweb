<?php
/**
 * LocacoesTrajes Active Record
 * @author  <your-name-here>
 */
class LocacoesTrajes extends TRecord
{
    const TABLENAME = 'locacoes_trajes';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    //use SystemChangeLogTrait;
    
    private $vendedor;
    private $cliente;
    private $locacoes_trajes_itenss;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('dtuso');
        parent::addAttribute('dtprova1');
        parent::addAttribute('dtprova2');
        parent::addAttribute('dtretirada');
        parent::addAttribute('dtchegada');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('cliente_id');
        parent::addAttribute('confirmado');
        parent::addAttribute('dtbase');
        parent::addAttribute('vllocacao');
        parent::addAttribute('cortesia');
        parent::addAttribute('retirado');
    }

    
    /**
     * Method set_vendedor
     * Sample of usage: $locacoes_trajes->vendedor = $object;
     * @param $object Instance of Pessoas
     */
    public function set_vendedor(Pessoas $object)
    {
        $this->vendedor = $object;
        $this->vendedor_id = $object->id;
    }
    
    /**
     * Method get_vendedor
     * Sample of usage: $locacoes_trajes->vendedor->attribute;
     * @returns Pessoas instance
     */
    public function get_vendedor()
    {
        // loads the associated object
        if (empty($this->vendedor))
            $this->vendedor = new Pessoas($this->vendedor_id);
    
        // returns the associated object
        return $this->vendedor;
    }


    /**
     * Method set_cliente
     * Sample of usage: $locacoes_trajes->cliente = $object;
     * @param $object Instance of Pessoas
     */
    public function set_cliente(Pessoas $object)
    {
        $this->cliente = $object;
        $this->cliente_id = $object->id;
    }
    
    /**
     * Method get_cliente
     * Sample of usage: $locacoes_trajes->cliente->attribute;
     * @returns Pessoas instance
     */
    public function get_cliente()
    {
        // loads the associated object
        if (empty($this->cliente))
            $this->cliente = new Pessoas($this->cliente_id);
    
        // returns the associated object
        return $this->cliente;
    }    
    
    /**
     * Method addLocacoesTrajesItens
     * Add a LocacoesTrajesItens to the LocacoesTrajes
     * @param $object Instance of LocacoesTrajesItens
     */
    public function addLocacoesTrajesItens(LocacoesTrajesItens $object)
    {
        $this->locacoes_trajes_itenss[] = $object;
    }
    
    /**
     * Method getLocacoesTrajesItenss
     * Return the LocacoesTrajes' LocacoesTrajesItens's
     * @return Collection of LocacoesTrajesItens
     */
    public function getLocacoesTrajesItenss()
    {
        return $this->locacoes_trajes_itenss;
    }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->locacoes_trajes_itenss = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
        $this->locacoes_trajes_itenss = parent::loadComposite('LocacoesTrajesItens', 'locacoes_trajes_id', $id);
    
        // load the object itself
        return parent::load($id);
    }

    /**
     * Store the object and its aggregates
     */
    public function store()
    {
        // store the object itself
        parent::store();
    
        parent::saveComposite('LocacoesTrajesItens', 'locacoes_trajes_id', $this->id, $this->locacoes_trajes_itenss);
    }

    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        parent::deleteComposite('LocacoesTrajesItens', 'locacoes_trajes_id', $id);
    
        // delete the object itself
        parent::delete($id);
    }
}
