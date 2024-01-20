<?php
/**
 * SolicitacoesAtendimentoFraterno Active Record
 * @author  <your-name-here>
 */
class SolicitacoesAtendimentoFraterno extends TRecord
{
    const TABLENAME = 'solicitacoes_atendimento_fraterno';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id');
        parent::addAttribute('pensamentos');
        parent::addAttribute('diasdasemana');
    }
}
