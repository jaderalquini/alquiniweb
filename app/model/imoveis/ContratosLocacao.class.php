<?php
/**
 * ContratosLocacao Active Record
 * @author  <your-name-here>
 */
class ContratosLocacao extends TRecord
{
    const TABLENAME = 'contratos_locacao';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $imovel;
    private $tipo_grantia;
    private $imovel_garantia;
    private $locatarios;
    private $fiadores;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('imovel_id');
        parent::addAttribute('dtinicio');
        parent::addAttribute('qtdeparc');
        parent::addAttribute('dtfim');
        parent::addAttribute('vlacrescido');
        parent::addAttribute('percdesc');
        parent::addAttribute('valor');
        parent::addAttribute('vldesc');
        parent::addAttribute('qtdeparcdesc');
        parent::addAttribute('vlseguro');
        parent::addAttribute('diavencto');
        parent::addAttribute('tipo_garantia_id');
        parent::addAttribute('dtcadastro');
        parent::addAttribute('liquidado');
        parent::addAttribute('dtinicaucao');
        parent::addAttribute('dtfimcaucao');
        parent::addAttribute('vlcaucao');
        parent::addAttribute('imovel_garantia_id');
        parent::addAttribute('pessoas');        
    }
    
    public static function getListFields()
    {
        return array(
                     'id' => _t('Id'), 
                     'imovel_id' => _t('Property'),
                     'dtinicio' => _t('Begin Date'),
                     'qtdeparc' => _t('Number of Installments'),
                     'dtfim' => _t('End Date'),
                     'vlacrescido' => _t('Brute Amount'),
                     'percdesc' => '% ' . _t('Discount'),
                     'valor' => _t('Value'),
                     'vldesc' => _t('Discount Value'),
                     'qtdeparcdesc' => _t('Discount Month Quantity'),
                     'vlseguro' => _t('Insurance Price'),
                     'diavencto' => _t('Due Date'),
                     'tipo_garantia_id' => _t('Warranty Type'),
                     'dtcadastro' => _t('Register Date'),
                     'liquidado' => _t('Paid Off'),
                     'dtinicaucao' => _t('Begin Date') . '(' . _t('Deposit') . ')',
                     'dtfimcaucao' => _t('End Date') . '(' . _t('Deposit') . ')',
                     'vlcaucao' => _t('Value') . '(' . _t('Deposit') . ')',
                     'Fiadores_list_pessoa_id' => _t('Guarantor'),
                     'imovel_garantia_id' => _t('Warranty Property') );
    }
    
    /**
     * Method set_imovel
     * Sample of usage: $contratos->imovel = $object;
     * @param $object Instance of Imoveis
     */
    public function set_imovel(Imoveis $object)
    {
        $this->imovel = $object;
        $this->imovel_id = $object->id;
    }
    
    /**
     * Method get_imovel
     * Sample of usage: $contratos->imovel->attribute;
     * @returns Imoveis instance
     */
    public function get_imovel()
    {
        // loads the associated object
        if (empty($this->imovel))
            $this->imovel = new Imoveis($this->imovel_id);
    
        // returns the associated object
        return $this->imovel;
    }
    
    /**
     * Method set_tipo_grantia
     * Sample of usage: $contratos->tipo_grantia = $object;
     * @param $object Instance of Tiposgrantia
     */
    public function set_tipo_grantia(Tiposgrantia $object)
    {
        $this->tipo_grantia = $object;
        $this->tipo_grantia_id = $object->id;
    }
    
    /**
     * Method get_tipo_grantia
     * Sample of usage: $contratos->tipo_grantia->attribute;
     * @returns Tiposgrantia instance
     */
    public function get_tipo_grantia()
    {
        // loads the associated object
        if (empty($this->tipo_grantia))
            $this->tipo_grantia = new Tiposgrantia($this->tipo_grantia_id);
    
        // returns the associated object
        return $this->tipo_grantia;
    }
    
    /**
     * Method set_imovel
     * Sample of usage: $contratos->imovel_garantia = $object;
     * @param $object Instance of Imoveis
     */
    public function set_imovel_garantia(Imoveis $object)
    {
        $this->imovel_garantia = $object;
        $this->imovel_garantia_id = $object->id;
    }
    
    /**
     * Method get_imovel
     * Sample of usage: $contratos->imovel_garantia->attribute;
     * @returns Imoveis instance
     */
    public function get_imovel_garantia()
    {
        // loads the associated object
        if (empty($this->imovel_garantia))
            $this->imovel_garantia = new Imoveis($this->imovel_garantia_id);
    
        // returns the associated object
        return $this->imovel;
    }    
    
    /**
     * Method addLocatarios
     * Add a Locatarios to the Contratos
     * @param $object Instance of Locatarios
     */
    public function addLocatarios(Locatarios $object)
    {
        $this->locatarios[] = $object;
    }
    
    /**
     * Method getLocatarios
     * Return the Contratos' Locatarios's
     * @return Collection of Locatarios
     */
    public function getLocatarios()
    {
        return $this->locatarios;
    }
    
    /**
     * Method addFiadores
     * Add a Fiadores to the Contratos
     * @param $object Instance of Fiadores
     */
    public function addFiadores(Fiadores $object)
    {
        $this->fiadores[] = $object;
    }
    
    /**
     * Method getFiadores
     * Return the Contratos' Fiadores's
     * @return Collection of Fiadores
     */
    public function getFiadores()
    {
        return $this->fiadores;
    }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->locatarios = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
        $this->locatarios = parent::loadComposite('Locatarios', 'contrato_locacao_id', $id);
        $this->fiadores = parent::loadComposite('Fiadores', 'contrato_locacao_id', $id);
    
        // load the object itself
        return parent::load($id);
    }

    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        parent::deleteComposite('Locatarios', 'contrato_locacao_id', $id);
    
        // delete the object itself
        parent::delete($id);
    }
    
    public function onImportData($objects)
    {
        try
        { 
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    TTransaction::open(TSession::getValue('unit_database'));
                    
                    $contratos = ContratosLocacao::where('id', '=', $object->id)->load();
                    
                    if (!$contratos)
                    {
                        $imoveis = Imoveis::where('id', '=', $object->bem_id)->load();
                        
                        if ($imoveis)
                        {
                            foreach ($imoveis as $imovel)
                            {
                                $contrato = new ContratosLocacao();
                                $contrato->id = $object->id;
                                $contrato->imovel_id = $object->bem_id;
                                $contrato->dtinicio = $object->dtinicio;
                                $contrato->qtdeparc = $object->qtdeparc;
                                $contrato->dtfim = $object->dtfim;
                                $contrato->vlacrescido = $object->vlacrescido;
                                $contrato->percdesc = $object->percdesc;
                                $contrato->valor = $object->valor;
                                $contrato->vldesc = $object->vldesc;
                                $contrato->qtdeparcdesc = $object->qtdeparcdesc;
                                $contrato->vlseguro = $object->vlseguro;
                                $contrato->diavencto = $object->diavencto;
                                $contrato->dtcadastro = $object->dtcadastro;
                                $contrato->tipo_garantia_id = $object->tipogarantia_id;
                                $contrato->liquidado = $object->liquidado;
                                
                                $proprietarios = Proprietarios::where('imovel_id', '=', $contrato->imovel_id)->load();
                                    
                                if ($proprietarios)
                                {
                                    $array_pessoas = [];
                                    foreach ($proprietarios as $proprietario)
                                    {
                                        $pessoas = Pessoas::where('id', '=', $proprietario->pessoa_id)->load();
                                            
                                        if ($pessoas)
                                        {
                                            foreach ($pessoas as $pessoa)
                                            {
                                                $tipo_proprietario = new TiposProprietario($proprietario->tipo_proprietario_id);
                                                $array_pessoas[] = $pessoa->nome . ' (' . $tipo_proprietario->descricao . ' ' . number_format($proprietario->repasse, 0, '', '.') . '%)';
                                            }
                                        }
                                    }
                                }
                                
                                if ($contrato->tipo_garantia_id == 1)
                                {
                                    $contrato->dtinicaucao = $contrato->dtinicio;
                                    $contrato->dtfimcaucao = $contrato->dtinicio;
                                    $contrato->vlcaucao = 3 * $contrato->vlacrescido;
                                }
                                
                                $contrato->store();
                                
                                if ($contrato->tipo_garantia_id == 2)
                                {
                                    if (!empty($object->avalista_id))
                                    {
                                        $pessoas = Pessoas::where('id', '=', $object->avalista_id)->load();
                                        
                                        if ($pessoas)
                                        {
                                            foreach ($pessoas as $pessoa)
                                            {
                                                $fiador = new Fiadores();
                                                $fiador->contrato_locacao_id = $contrato->id;
                                                $fiador->pessoa_id = $pessoa->id;
                                                $fiador->store();
                                            }
                                        }
                                    }
                                    
                                    if (!empty($object->avalista2_id))
                                    {
                                        $pessoas = Pessoas::where('id', '=', $object->avalista2_id)->load();
                                        
                                        if ($pessoas)
                                        {
                                            foreach ($pessoas as $pessoa)
                                            {
                                                $fiador = new Fiadores();
                                                $fiador->contrato_locacao_id = $contrato->id;
                                                $fiador->pessoa_id = $pessoa->id;
                                                $fiador->store();
                                            }
                                        }
                                    }
                                }
                                
                                $pessoas = Pessoas::where('id', '=', $object->cliente_id)->load();
                                $participacao = empty($object->cliente2_id) ? 100 : 50;
                                    
                                if ($pessoas)
                                {
                                    foreach ($pessoas as $pessoa)
                                    {
                                        $locatario = new Locatarios;
                                        $locatario->contrato_locacao_id = $contrato->id;
                                        $locatario->pessoa_id = $pessoa->id;
                                        $locatario->participacao = $participacao;
                                        $locatario->store();
                                            
                                        $array_pessoas[] = $pessoa->nome . ' (' . $participacao . '%)';
                                    }
                                }
                                    
                                if (!empty($object->cliente2_id))
                                {
                                    $pessoas = Pessoas::where('id', '=', $object->cliente2_id)->load();
                                        
                                    if ($pessoas)
                                    {
                                        foreach ($pessoas as $pessoa)
                                        {
                                            $locatario = new Locatarios;
                                            $locatario->contrato_locacao_id = $contrato->id;
                                            $locatario->pessoa_id = $pessoa->id;
                                            $locatario->participacao = $participacao;
                                            $locatario->store();
                                                
                                            $array_pessoas[] = $pessoa->nome . ' (' . $participacao . '%)';
                                        }
                                    }
                                }
                                
                                if ($contrato->liquidado == 'N')
                                {
                                    $imovel = new Imoveis($contrato->imovel_id);
                                    $imovel->pessoas = implode("\n", $array_pessoas);
                                    $imovel->store();
                                }
                                
                                $contrato = new ContratosLocacao($contrato->id);
                                $contrato->pessoas = implode("\n", $array_pessoas);
                                $contrato->store();
                            }
                        }
                    }                            
                    
                    TTransaction::close();
                }                
            }
            
            new TMessage('info', 'Importação efetuada com sucesso!');
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            //TTransaction::rollback();
        }
    }
}
