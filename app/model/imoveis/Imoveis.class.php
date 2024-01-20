<?php
/**
 * Imoveis Active Record
 * @author  <your-name-here>
 */
class Imoveis extends TRecord
{
    const TABLENAME = 'imoveis';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
        
    private $tipos_negocio;
    private $proprietarios;
    private $fotos;
    private $iptus;
    private $ambientes;
    private $tipo_imovel;
    private $administradora_condominio;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');        
        parent::addAttribute('tipo_imovel_id');
        parent::addAttribute('cep');
        parent::addAttribute('rua');
        parent::addAttribute('numero');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('cidade');
        parent::addAttribute('estado');
        parent::addAttribute('pais');
        parent::addAttribute('administradora_condominio_id');
        parent::addAttribute('edificio');
        parent::addAttribute('vlcondominio');
        parent::addAttribute('codagua');
        parent::addAttribute('codluz');
        parent::addAttribute('codgas');
        parent::addAttribute('areaterreno');
        parent::addAttribute('areaconstruida');        
        parent::addAttribute('perimetro');
        parent::addAttribute('usoimovel');
        parent::addAttribute('dtcadastro');
        parent::addAttribute('cartorio');
        parent::addAttribute('matricula');
        parent::addAttribute('vlaluguel');
        parent::addAttribute('percomaluguel');
        parent::addAttribute('pagtogar');
        parent::addAttribute('diapagto');
        parent::addAttribute('vldesc');
        parent::addAttribute('qtdemesesdesc');
        parent::addAttribute('contrato_locacao_id');
        parent::addAttribute('vlvenda');
        parent::addAttribute('percomvenda');
        parent::addAttribute('observacao');
        parent::addAttribute('pessoas');
        parent::addAttribute('tipos_negocio_valores');
        parent::addAttribute('foto');
    }
    
    public static function getListFields()
    {
        return array(
                     'id' => _t('Id'), 
                     'descricao' => _t('Description'), 
                     'tipo_imovel_id' => _t('Property Type'), 
                     'cep' => _t('ZIP'), 
                     'rua' => _t('Street'), 
                     'numero' => _t('Number'), 
                     'complemento' => _t('Complement'), 
                     'bairro' => _t('Neighborhood'), 
                     'cidade' => _t('City'), 
                     'estado' => _t('State'), 
                     'pais' => _t('Country'), 
                     'administradora_condominio_id' => _t('Condominium Administrator'), 
                     'edificio' => _t('Building'), 
                     'vlcondominio' => _t('Condominium Value'), 
                     'codagua' => _t('Water Code'), 
                     'codluz' => _t('Light Code'), 
                     'codgas' => _t('Gas Code'), 
                     'areaterreno' => _t('Terrain Area'), 
                     'areaconstruida' => _t('Construction Area'), 
                     'perimetro' => _t('Urban') . '/'. _t('Rural'), 
                     'usoimovel' => _t('Property Use'), 
                     'cartorio' => _t('Notary\'s Office'), 
                     'matricula' => _t('Matriculation'),
                     'vlaluguel' => _t('Rent Value'), 
                     'percomaluguel' => '% ' . _t('Commission'),
                     'pagtogar' => _t('Guaranteed Pay'),
                     'diapagto' => _t('Pay Day'),
                     'vldesc' => _t('Discount Value'),
                     'qtdemesesdesc' => _t('Discount Month Quantity'),
                     'vlvenda' => _t('Sale Value'),
                     'percomvenda' => '% ' . _t('Commission') . '(' . _t('Sale') . ')',
                     'contrato_locacao_id' => _t('Rent Contract'),
                     'observacao' => _t('Observation') );
    }
    
    /**
     * Method addTiposnegocio
     * Add a TiposNnegocio to the Imoveis
     * @param $object Instance of TiposNegocio
     */
    public function addTipoNegocio(TiposNegocio $object)
    {
        $this->tipos_negocio[] = $object;
    }
    
    /**
     * Method getTiposnegocio
     * Return the Imoveis' TiposNnegocio's
     * @return Collection of TiposNegocio
     */
    public function getTiposNegocio()
    {
        return $this->tipos_negocio;
    }
    
    /**
     * Method addProprietario
     * Add a Pessoas to the Imoveis
     * @param $object Instance of Pessoas
     */
    public function addProprietario(Pessoas $pessoa, Tiposproprietario $tipoproprietario, $repasse)
    {
        $object = new Proprietarios;
        $object->imovel_id = $this->id;
        $object->pessoa_id = $pessoa->id;
        $object->tipoproprietario_id = $tipoproprietario->id;
        $object->repasse = $repasse;
        $object->store();
    }
    
    /**
     * Method getProprietarios
     * Return the Imoveis' Pessoas's
     * @return Collection of Pessoas
     */
    public function getProprietarios()
    {
        return $this->proprietarios;
    }
    
    /**
     * Method addFoto
     * Add a Imoveisfotos to the Imoveis
     * @param $object Instance of Imoveisfotos
     */
    public function addFoto($foto, $descfoto)
    {
        $object = new Imoveisfotos;
        $object->imovel_id = $this->id;
        $object->foto = $foto;
        $object->store();
        
        return $object;
    }
    
    /**
     * Method getFotos
     * Return the Imoveis' Imoveisfotos's
     * @return Collection of Imoveisfotos
     */
    public function getFotos()
    {
        return $this->fotos;
    }
    
    /**
     * Method addIptu
     * Add a Iptus to the Imoveis
     * @param $object Instance of Iptus
     */
    public function addIptu($codigo, Tiposiptu $tipoiptu, $valor)
    {
        $object = new Imoveisiptus;
        $object->imovel_id = $this->id;
        $object->codigo = $codigo;
        $object->tipoiptu_id = $tipoiptu->id;
        $object->valor = $valor;
        $object->store();
    }
    
    /**
     * Method getIptus
     * Return the Imoveis' Iptus's
     * @return Collection of Iptus
     */
    public function getIptus()
    {
        return $this->iptus;
    }
    
    /**
     * Method addAmbiente
     * Add a Ambientes to the Imoveis
     * @param $object Instance of Ambientes
     */
    public function addAmbiente(Ambientes $ambiente, $qtde)
    {
        $object = new Imoveisambientes;
        $object->imovel_id = $this->id;
        $object->ambiente_id = $ambiente->id;
        $object->qtde = $qtde;
        $object->store();
    }
    
    /**
     * Method getAmbientes
     * Return the Imoveis' Ambiente's
     * @return Collection of Ambientes
     */
    public function getAmbientes()
    {
        return $this->ambientes;
    }
    
    /**
     * Method set_tipo_imovel
     * Sample of usage: $imoveis->tiposimovel = $object;
     * @param $object Instance of TiposImovel
     */
    public function set_tipo_imovel(TiposImovel $object)
    {
        $this->tipo_imovel = $object;
        $this->tipo_imovel_id = $object->id;
    }
    
    /**
     * Method get_tipo_imovel
     * Sample of usage: $imoveis->tiposimovel->attribute;
     * @returns TiposImovel instance
     */
    public function get_tipo_imovel()
    {
        // loads the associated object
        if (empty($this->tiposimovel))
            $this->tipo_imovel = new TiposImovel($this->tipo_imovel_id);
    
        // returns the associated object
        return $this->tipo_imovel;
    }
    
    /**
     * Method set_administradora_condominio
     * Sample of usage: $imoveis->administradora_condominio = $object;
     * @param $object Instance of Administradoras
     */
    public function set_administradora_condominio(AdministradorasCondominio $object)
    {
        $this->administradora_condominio = $object;
        $this->administradora_condominio_id = $object->id;
    }
    
    /**
     * Method get_administradora_condominio
     * Sample of usage: $imoveis->administradora_condominio->attribute;
     * @returns Administradoras instance
     */
    public function get_administradora_condominio()
    {
        // loads the associated object
        if (empty($this->administradora_condominio))
            $this->administradora_condominio = new AdministradorasCondominio($this->administradora_condominio_id);
    
        // returns the associated object
        return $this->administradora_condominio;
    }
    
    public function setPessoas()
    {
        $proprietarios  = Proprietarios::where('imovel_id', '=', $this->id)->load();
            
        if ($proprietarios)
        {
            $pessoas = [];
            foreach ($proprietarios as $proprietario)
            {
                $pessoa = new Pessoas($proprietario->pessoa_id);
                $tipo_proprietario = new Tiposproprietario($proprietario->tipo_proprietario_id);
                $valor = number_format($proprietario->repasse, 0);
                $pessoas[] = "$pessoa->nome ($tipo_proprietario->descricao $valor%)";
            }
            $this->pessoas = implode("\n", $pessoas);
        }
        
        $repository = new TRepository('ContratosLocacao');
        $criteria = new TCriteria;
        $criteria->add(new TFilter("imovel_id", "=", $this->id));
        $criteria->add(new TFilter("liquidado", "=", "N"));
        $contratos = $repository->load($criteria);
        
        if ($contratos)
        {
            $locatarios = Locatarios::where("contrato_locacao_id", "=", $this->id)->load();
            
            if ($locatarios)
            {
                $this->pessoas .= "\n";
                $pessoas = [];
                foreach ($locatarios as $locatario)
                {
                    $pessoa = new Pessoas($locatario->pessoa_id);
                    $valor = number_format($locatario->participacao, 0);
                    $pessoas[] = "$pessoa->nome (Contrato $locatario->contrato_locacao_id $valor%)";
                }
                $this->pessoas .= implode("\n", $pessoas);
            }
        }
    }
    
    public function setTiposNegocioValores()
    {
        $tipos_negocio  = ImoveisTiposNegocio::where('imovel_id', '=', $this->id)->load();
            
        if ($tipos_negocio)
        {
            $tipos_negocio_valores = [];
            foreach ($tipos_negocio as $tipo_negocio)
            {
                $tipo = new TiposNegocio($tipo_negocio->tipo_negocio_id);
                if (!empty($tipo->campovalor))
                {
                    $campo = $tipo->campovalor;
                    $tipos_negocio_valores[] = 'R$ ' . number_format($this->$campo, 2, ',', '.') . '(' . $tipo->descricao . ')';
                }
            }
            $this->tipos_negocio_valores = implode("\n", $tipos_negocio_valores);
        }
    }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->tipos_negocio = array();
        $this->proprietarios = array();
        $this->fotos = array();
        $this->iptus = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;    
        $this->tipos_negocio = parent::loadAggregate('TiposNegocio', 'ImoveisTiposNegocio', 'imovel_id', 'tipo_negocio_id', $id);
        $this->proprietarios = Proprietarios::where('imovel_id', '=', $id)->load();
        $this->fotos = parent::loadComposite('ImoveisFotos', 'imovel_id', $id);
        $this->iptus = parent::loadComposite('ImoveisIptus', 'imovel_id', $id);
        $this->ambientes = Imoveisambientes::where('imovel_id', '=', $id)->load();
    
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
        parent::deleteComposite('ImoveisTiposNegocio', 'imovel_id', $id);
        parent::deleteComposite('Proprietarios', 'imovel_id', $id);
        parent::deleteComposite('ImoveisFotos', 'imovel_id', $id);
        parent::deleteComposite('ImoveisIptus', 'imovel_id', $id);
        parent::deleteComposite('ImoveisAmbientes', 'imovel_id', $id);
    
        // delete the object itself
        parent::delete($id);
    }
    
    public function store()
    {        
        $tipo_imovel = new TiposImovel($this->tipo_imovel_id);
        $this->descricao = $tipo_imovel->descricao . " COM ";
            
        if ($tipo_imovel->pede_areaconstruida == 'S')
        {
            $this->descricao .= number_format($this->areaconstruida, 2, ',', '.');
        }
        else if ($tipo_imovel->pede_areaterreno == 'S')
        {
            $this->descricao .= number_format($this->areaterreno, 2, ',', '.');
        }
            
        $this->descricao .= "M2";
            
        $this->descricao .= " LOCALIZADO EM PERÌMETRO $this->perimetro";
            
        $this->descricao .= " NO BAIRRO $this->bairro - $this->rua";
            
        if (!empty($this->numero))
        {
            $this->descricao .= ", $this->numero";
        }
            
        $this->descricao .= ", PARA USO $this->usoimovel.";
        
        parent::store();      
        
        $this->setPessoas();
        
        $this->setTiposNegocioValores();
        
        parent::store();
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
                    
                    /*$imovel = Imoveis::where('id', '=', $object->id)->load();
                    
                    if (!$imovel)
                    {*/
                        $imovel = new Imoveis();
                        $imovel->id = $object->id;
                        $imovel->descricao = $object->descricao;
                        
                        switch ($object->tipobem_id)
                        {
                            case '3':
                                $imovel->tipo_imovel_id = 43;
                                break;
                                
                            case '4':
                                $imovel->tipo_imovel_id = 37;
                                break;
                                
                            case '5':
                                $imovel->tipo_imovel_id = 41;
                                break;
                            
                            case '6':
                            case '7':
                                $imovel->tipo_imovel_id = 20;
                                break;
                                
                            case '8':
                                $imovel->tipo_imovel_id = 9;
                                break;
                                
                            case '9':
                                $imovel->tipo_imovel_id = 38;
                                break;
                                
                            case '12':
                                $imovel->tipo_imovel_id = 25;
                                break;
                                
                            default:
                                $imovel->tipo_imovel_id = $object->tipobem_id;
                                break;
                        }
                        
                        $imovel->cep = $object->cep;
                        
                        $endereco_completo = explode(',', $object->endereco);
                        $endereco = trim($endereco_completo[0]);
                        $endereco = str_replace('RUA', '', $endereco);
                        $endereco = str_replace('R.', '', $endereco);
                        $endereco = str_replace('.', ' ', $endereco);
                        $endereco = explode(' ', trim($endereco));
                        $numero = $endereco_completo[1];
                        $imovel->numero = trim($numero);
                        $imovel->complemento = $object->complemento;
                        
                        TTransaction::close();
                        TTransaction::open('cep');
                        
                        $repository = new TRepository('Ruas');
                        $criteria = new TCriteria;
                        
                        if (!empty($endereco))
                        {
                            foreach ($endereco as $nome)
                            {
                                $nome = trim($nome);
                                $criteria->add(new TFilter('nome', 'like', "%$nome%"));
                            }
                        }
                        
                        $ruas = $repository->load($criteria);
                        
                        if ($ruas)
                        {
                            foreach ($ruas as $rua)
                            {
                                $imovel->cep = $rua->cep;
                                $imovel->rua = $rua->id;
                                $imovel->bairro = $rua->bairro;
                                $imovel->cidade = $rua->cidade;
                                $imovel->estado = $rua->estado;
                            }
                        }
                        else
                        {   
                            $cidades = Cidades::where('dimob', '=', $object->municipio_id)->load();
                            
                            if ($cidades)
                            {
                                foreach ($cidades as $cidade)
                                {
                                    $rua = new Ruas();
                                    $rua->nome = implode(' ', $endereco);
                                    $rua->cep = $object->cep;
                                    
                                    $bairros = Bairros::where('nome', 'like', "%$object->bairro%")->where('cidade', '=', $cidade->id)->load();
                            
                                    if ($bairros)
                                    {
                                        foreach ($bairros as $bairro)
                                        {
                                            $rua->bairro = $bairro->id;
                                        }
                                    }
                                    else
                                    {
                                        $bairro = new Bairros();
                                        $bairro->nome = $object->bairro;
                                        $bairro->cidade = $cidade->id;
                                        $bairro->estado = $cidade->estado;
                                        $bairro->store();
                                    }
                                    
                                    $rua->bairro = $bairro->id;
                                    $rua->cidade = $cidade->id;
                                    $rua->estado = $cidade->estado;
                                    $rua->store();
                                    
                                    $imovel->cep = $rua->cep;
                                    $imovel->rua = $rua->id;
                                    $imovel->bairro = $rua->bairro;
                                    $imovel->cidade = $rua->cidade;
                                    $imovel->estado = $rua->estado;
                                }
                            }
                        }
                        
                        TTransaction::close();
                        TTransaction::open(TSession::getValue('unit_database'));
                        
                        $imovel->areaterreno = $object->area_terreno;
                        $imovel->perimetro = $object->urbrural == 'U' ? 'URBANO' : 'RURAL';
                        $imovel->usoimovel = $object->rescom == 'R' ? 'RESIDENCIAL' : 'COMERCIAL';
                        $imovel->matricula = $object->matricula;
                        $imovel->vlaluguel = $object->vlaluguel;
                        $imovel->percomaluguel = $object->percomissao;
                        $imovel->pagtogar = $object->pagtogar;
                        $imovel->diapagto = $object->diapagto;                    
                        $imovel->vldesc = $object->vldesc;
                        $imovel->qtdemesesdesc = $object->qtdemes;
                        
                        $pessoas = Pessoas::where('id', '=', $object->proprietario_id)->load();
                        
                        if ($pessoas)
                        {
                            foreach ($pessoas as $pessoa)
                            {                        
                                $tipo_proprietario = new TiposProprietario(1);
                                $imovel->pessoas = $pessoa->nome . ' (' . $tipo_proprietario->descricao. ' 100%)';
                            }
                        }
                        
                        $imovel->store();
                        
                        if ($pessoas)
                        {
                            $proprietario = new Proprietarios();
                            $proprietario->imovel_id = $imovel->id;
                            $proprietario->pessoa_id = $object->proprietario_id;
                            $proprietario->tipo_proprietario_id = 1;
                            $proprietario->repasse = 100;
                            //$proprietario->store();
                        }
                        
                        $tipo_negocio = new ImoveisTiposNegocio();
                        $tipo_negocio->tipo_negocio_id = 1;
                        $tipo_negocio->imovel_id = $imovel->id;
                        //$tipo_negocio->store();
                    //}
                    
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
