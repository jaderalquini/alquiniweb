<?php
/**
 * MovimentosCaixa Active Record
 * @author  <your-name-here>
 */
class MovimentosCaixa extends TRecord
{
    const TABLENAME = 'movimentos_caixa';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;

    private $pessoa;
    private $tipo_pagamento;
    private $forma_pagamento;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo');
        parent::addAttribute('descricao');
        parent::addAttribute('pessoa_id');
        parent::addAttribute('nrdoc');
        parent::addAttribute('tipo_pagamento_id');
        parent::addAttribute('parcela');
        parent::addAttribute('totparcelas');
        parent::addAttribute('dtvencto');
        parent::addAttribute('valor');
        parent::addAttribute('desconto');
        parent::addAttribute('juros');
        parent::addAttribute('multa');
        parent::addAttribute('dtpagto');
        parent::addAttribute('vlpago');
        parent::addAttribute('forma_pagamento_id');
        parent::addAttribute('numrecibo');
        parent::addAttribute('observacoes');
        parent::addAttribute('liquidado');
    }

    /**
     * Method set_pessoa
     * Sample of usage: $despesas->pessoa = $object;
     * @param $object Instance of Pessoas
     */
    public function set_pessoa(Pessoas $object)
    {
        $this->pessoa = $object;
        $this->pessoa_id = $object->id;
    }
    
    /**
     * Method get_pessoa
     * Sample of usage: $despesas->pessoa->attribute;
     * @returns Pessoas instance
     */
    public function get_pessoa()
    {
        // loads the associated object
        if (empty($this->pessoa))
            $this->pessoa = new Pessoas($this->pessoa_id);
    
        // returns the associated object
        return $this->pessoa;
    }

    /**
     * Method set_tipo_pagamento
     * Sample of usage: $despesas->tipo_pagamento = $object;
     * @param $object Instance of TiposPagamento
     */
    public function set_tipo_pagamento(TiposPagamento $object)
    {
        $this->tipo_pagamento = $object;
        $this->tipo_pagamento_id = $object->id;
    }
    
    /**
     * Method get_tipo_pagamento
     * Sample of usage: $despesas->tipo_pagamento->attribute;
     * @returns TiposPagamento instance
     */
    public function get_tipo_pagamento()
    {
        // loads the associated object
        if (empty($this->tipo_pagamento))
            $this->tipo_pagamento = new TiposPagamento($this->tipo_pagamento_id);
    
        // returns the associated object
        return $this->tipo_pagamento;
    }

    /**
     * Method set_forma_pagamento
     * Sample of usage: $despesas->forma_pagamento = $object;
     * @param $object Instance of FormasPagamento
     */
    public function set_forma_pagamento(Pessoas $object)
    {
        $this->forma_pagamento = $object;
        $this->forma_pagamento_id = $object->id;
    }
    
    /**
     * Method get_forma_pagamento
     * Sample of usage: $despesas->forma_pagamento->attribute;
     * @returns FormasPagamento instance
     */
    public function get_forma_pagamento()
    {
        // loads the associated object
        if (empty($this->forma_pagamento))
            $this->forma_pagamento = new FormasPagamento($this->forma_pagamento_id);
    
        // returns the associated object
        return $this->forma_pagamento;
    }
}