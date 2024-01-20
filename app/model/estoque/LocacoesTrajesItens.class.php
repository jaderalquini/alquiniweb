<?php
/**
 * LocacoesTrajesItens Active Record
 * @author  <your-name-here>
 */
class LocacoesTrajesItens extends TRecord
{
    const TABLENAME = 'locacoes_trajes_itens';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    //use SystemChangeLogTrait;
    
    private $produto;
    private $tipo_venda;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('locacao_traje_id');
        parent::addAttribute('produto_id');
        parent::addAttribute('qtde');
        parent::addAttribute('preco_unitario');
        parent::addAttribute('tipo_venda_id');
    }

    
    /**
     * Method set_produto
     * Sample of usage: $locacoes_trajes_itens->produto = $object;
     * @param $object Instance of Produtos
     */
    public function set_produto(Produtos $object)
    {
        $this->produto = $object;
        $this->produto_id = $object->id;
    }
    
    /**
     * Method get_produto
     * Sample of usage: $locacoes_trajes_itens->produto->attribute;
     * @returns Produtos instance
     */
    public function get_produto()
    {
        // loads the associated object
        if (empty($this->produto))
            $this->produto = new Produtos($this->produto_id);
    
        // returns the associated object
        return $this->produto;
    }
    
    
    /**
     * Method set_tipo_venda
     * Sample of usage: $locacoes_trajes_itens->tipo_venda = $object;
     * @param $object Instance of TiposVenda
     */
    public function set_tipo_venda(TiposVenda $object)
    {
        $this->tipo_venda = $object;
        $this->tipo_venda_id = $object->id;
    }
    
    /**
     * Method get_tipo_venda
     * Sample of usage: $locacoes_trajes_itens->tipo_venda->attribute;
     * @returns TiposVenda instance
     */
    public function get_tipo_venda()
    {
        // loads the associated object
        if (empty($this->tipo_venda))
            $this->tipos_venda = new TiposVenda($this->tipo_venda_id);
    
        // returns the associated object
        return $this->tipo_venda;
    }
}
