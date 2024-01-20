<?php
/**
 * Pessoas Active Record
 * @author  <your-name-here>
 */
class Pessoas extends TRecord
{
    const TABLENAME = 'pessoas';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $tipo_residencia;
    private $nacionalidade;
    private $estado_civil;
    private $conjuge;
    private $local_trabalho;
    private $profissao;
    private $ramo_atividade;
    private $forma_pagamento;
    private $banco;
    private $tipo_conta;
    private $representantes;
    private $vendedor;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo');
        parent::addAttribute('cpfcnpj');
        parent::addAttribute('nome');
        parent::addAttribute('fone1');
        parent::addAttribute('fone2');
        parent::addAttribute('fone3');
        parent::addAttribute('email');
        parent::addAttribute('site');
        parent::addAttribute('cep');
        parent::addAttribute('rua');
        parent::addAttribute('numero');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('cidade');
        parent::addAttribute('estado');
        parent::addAttribute('pais');
        parent::addAttribute('ponto_referencia');
        parent::addAttribute('tipo_residencia_id');
        parent::addAttribute('tempo_residencia');
        parent::addAttribute('dtcadastro');
        parent::addAttribute('dtnasc');
        parent::addAttribute('nacionalidade_id');
        parent::addAttribute('cidadenacto');
        parent::addAttribute('estadonacto');
        parent::addAttribute('paisnacto');
        parent::addAttribute('nomepais');
        parent::addAttribute('sexo');
        parent::addAttribute('estado_civil_id');
        parent::addAttribute('conjuge_id');
        parent::addAttribute('rg');
        parent::addAttribute('orgaorg');
        parent::addAttribute('estadorg_id');
        parent::addAttribute('dtemissaorg');
        parent::addAttribute('local_trabalho_id');
        parent::addAttribute('profissao_id');
        parent::addAttribute('rendamensal');
        parent::addAttribute('ie');
        parent::addAttribute('im');
        parent::addAttribute('junta_comercial');
        parent::addAttribute('ramo_atividae_id');
        parent::addAttribute('forma_pagamento_id');
        parent::addAttribute('observacao');
        parent::addAttribute('status');
        parent::addAttribute('contato');
        parent::addAttribute('endereco');
        parent::addAttribute('grupos_pessoa');
    }
    
    /**
     * Method set_tipo_residencia
     * Sample of usage: $pessoas->tipo_residencia = $object;
     * @param $object Instance of TiposResidencia
     */
    public function set_tipo_residencia(TiposResidencia $object)
    {
        $this->tipo_residencia = $object;
        $this->tipo_residencia_id = $object->id;
    }
    
    /**
     * Method get_tipo_residencia
     * Sample of usage: $pessoas->tipo_residencia->attribute;
     * @returns TiposResidencia instance
     */
    public function get_tipo_residencia()
    {
        // loads the associated object
        if (empty($this->tipo_residencia))
            $this->tipo_residencia = new TiposResidencia($this->tipo_residencia_id);
    
        // returns the associated object
        return $this->tipo_residencia;
    }
    
    /**
     * Method set_nacionalidade
     * Sample of usage: $pessoas->nacionalidade = $object;
     * @param $object Instance of Nacionalidades
     */
    public function set_nacionalidade(Nacionalidades $object)
    {
        $this->nacionalidade = $object;
        $this->nacionalidade_id = $object->id;
    }
    
    /**
     * Method get_nacionalidade
     * Sample of usage: $pessoas->nacionalidade->attribute;
     * @returns Nacionalidades instance
     */
    public function get_nacionalidade()
    {
        // loads the associated object
        if (empty($this->nacionalidade))
            $this->nacionalidade = new Nacionalidades($this->nacionalidade_id);
    
        // returns the associated object
        return $this->nacionalidade;
    }
    
    /**
     * Method set_estado_civil
     * Sample of usage: $pessoas->estado_civil = $object;
     * @param $object Instance of EstadosCivis
     */
    public function set_estado_civil(EstadosCivis $object)
    {
        $this->estado_civil = $object;
        $this->estado_civil_id = $object->id;
    }
    
    /**
     * Method get_estado_civil
     * Sample of usage: $pessoas->estado_civil->attribute;
     * @returns EstadosCivis instance
     */
    public function get_estado_civil()
    {
        // loads the associated object
        if (empty($this->estado_civil))
            $this->estado_civil = new EstadosCivis($this->estado_civil_id);
    
        // returns the associated object
        return $this->estado_civil;
    }
    
    /**
     * Method set_conjuge
     * Sample of usage: $pessoas->conjuge = $object;
     * @param $object Instance of Pessoas
     */
    public function set_conjuge(Pessoas $object)
    {
        $this->conjuge = $object;
        $this->conjuge_id = $object->id;
    }
    
    /**
     * Method get_conjuge
     * Sample of usage: $pessoas->conjuge->attribute;
     * @returns Pessoas instance
     */
    public function get_conjuge()
    {
        // loads the associated object
        if (empty($this->conjuge))
            $this->conjuge = new Pessoas($this->conjuge_id);
    
        // returns the associated object
        return $this->conjuge;
    }
    
    /**
     * Method set_local_trabalho
     * Sample of usage: $pessoas->local_trabalho = $object;
     * @param $object Instance of LocaisTrabalho
     */
    public function set_local_trabalho(LocaisTrabalho $object)
    {
        $this->local_trabalho = $object;
        $this->local_trabalho_id = $object->id;
    }
    
    /**
     * Method get_local_trabalho
     * Sample of usage: $pessoas->local_trabalho->attribute;
     * @returns LocaisTrabalho instance
     */
    public function get_local_trabalho()
    {
        // loads the associated object
        if (empty($this->local_trabalho))
            $this->local_trabalho = new LocaisTrabalho($this->local_trabalho_id);
    
        // returns the associated object
        return $this->local_trabalho;
    }
    
    /**
     * Method set_profissao
     * Sample of usage: $pessoas->profissao = $object;
     * @param $object Instance of Profissoes
     */
    public function set_profissao(Profissoes $object)
    {
        $this->profissao = $object;
        $this->profissao_id = $object->id;
    }
    
    /**
     * Method get_profissao
     * Sample of usage: $pessoas->profissao->attribute;
     * @returns Profissoes instance
     */
    public function get_profissao()
    {
        // loads the associated object
        if (empty($this->profissao))
            $this->profissao = new Profissoes($this->profissao_id);
    
        // returns the associated object
        return $this->profissao;
    }
    
    /**
     * Method set_ramo_atividade
     * Sample of usage: $pessoas->ramo_atividade = $object;
     * @param $object Instance of RamosAtividade
     */
    public function set_ramo_atividade(RamosAtividade $object)
    {
        $this->ramo_atividade = $object;
        $this->ramo_atividade_id = $object->id;
    }
    
    /**
     * Method get_ramo_atividade
     * Sample of usage: $pessoas->ramo_atividade->attribute;
     * @returns RamosAtividade instance
     */
    public function get_ramo_atividade()
    {
        // loads the associated object
        if (empty($this->ramo_atividade))
            $this->ramo_atividade = new RamosAtividade($this->ramo_atividade_id);
    
        // returns the associated object
        return $this->ramo_atividade;
    }
    
    /**
     * Method set_forma_pagamento
     * Sample of usage: $pessoas->forma_pagamento = $object;
     * @param $object Instance of FormasPagamento
     */
    public function set_forma_pagamento(FormasPagamento $object)
    {
        $this->forma_pagamento = $object;
        $this->forma_pagamento_id = $object->id;
    }
    
    /**
     * Method get_forma_pagamento
     * Sample of usage: $pessoas->forma_pagamento->attribute;
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
    
    /**
     * Method set_banco
     * Sample of usage: $pessoas->banco = $object;
     * @param $object Instance of Bancos
     */
    public function set_banco(Bancos $object)
    {
        $this->banco = $object;
        $this->banco_id = $object->id;
    }
    
    /**
     * Method get_banco
     * Sample of usage: $pessoas->banco->attribute;
     * @returns Bancos instance
     */
    public function get_banco()
    {
        // loads the associated object
        if (empty($this->banco))
            $this->banco = new Bancos($this->banco_id);
    
        // returns the associated object
        return $this->banco;
    }
    
    /**
     * Method set_tipo_conta
     * Sample of usage: $pessoas->tipo_conta = $object;
     * @param $object Instance of TiposConta
     */
    public function set_tipo_conta(TiposConta $object)
    {
        $this->tipo_conta = $object;
        $this->tipo_conta_id = $object->id;
    }
    
    /**
     * Method get_tipo_conta
     * Sample of usage: $pessoas->tipo_conta->attribute;
     * @returns TiposConta instance
     */
    public function get_tipo_conta()
    {
        // loads the associated object
        if (empty($this->tipo_conta))
            $this->tipo_conta = new TiposConta($this->tipo_conta_id);
    
        // returns the associated object
        return $this->tipo_conta;
    }
    
    /**
     * Method addRepresentante
     * Add a Pessoas to the Imoveis
     * @param $object Instance of Pessoas
     */
    public function addRepresentante(Pessoas $pessoa, Pessoas $representante)
    {
        $object = new Representantes;
        $object->pessoa_id = $pessoa->id;
        $object->representante_id = $representante->id;
        $object->store();
    }
    
    /**
     * Method getRepresentante
     * Return the Imoveis' Pessoas's
     * @return Collection of Pessoas
     */
    public function getRepresentante()
    {
        return $this->representantes;
    }
    
    /**
     * Add a GrupoPessoas to the pessoa
     * @param $object Instance of GruposPessoa
     */
    public function addGruposPessoa(GruposPessoa $grupo_pessoa)
    {
        $object = new PessoasGruposPessoa;
        $object->grupo_pessoa_id = $grupo_pessoa->id;
        $object->pessoa_id = $this->id;
        $object->store();
    }
    
    /**
     * Return the pessoa' grupo's
     * @return Collection of GruposPessoa
     */
    public function getGruposPessoa()
    {
        return parent::loadAggregate('GruposPessoa', 'PessoasGruposPessoa', 'pessoa_id', 'grupo_pessoa_id', $this->id);
    }
    
    public function setGruposPessoa()
    {
        $grupos_pessoa = PessoasGruposPessoa::where('pessoa_id', '=', $this->id)->load();
        
        if ($grupos_pessoa)
        {
            $grupos = [];
            foreach ($grupos_pessoa as $grupo_pessoa)
            {
                $grupo = new GruposPessoa($grupo_pessoa->grupo_pessoa_id);
                $grupos[] = $grupo->descricao;
            }
            $this->grupos_pessoa = implode("\n", $grupos);
        }
    }
    
    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        Representantes::where('pessoa_id', '=', $this->id)->delete();
        PessoasGruposPessoa::where('pessoa_id', '=', $this->id)->delete();
    }
    
    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        $this->representantes = Representantes::where('pessoa_id', '=', $id)->load();
        $this->grupos_pessoas = PessoasGruposPessoa::where('pessoa_id', '=', $id)->load();    
       
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
        parent::deleteComposite('Representantes', 'pessoa_id', $id);
        parent::deleteComposite('PessoasGruposPessoa', 'pessoa_id', $id);
    
        // delete the object itself
        parent::delete($id);
    }
    
    public function store()
    {
        $this->cpfcnpj = FuncoesExtras::retiraFormatacao($this->cpfcnpj);
        if ($this->tipo=='F')
        {
            $this->cpfcnpj = FuncoesExtras::mask($this->cpfcnpj, '###.###.###-##');
        }
        else
        {
            $this->cpfcnpj = FuncoesExtras::mask($this->cpfcnpj, '##.###.###/####-##');
        }
                
        $endereco = '';
        
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
            TTransaction::close();
            TTransaction::open('cep');
        
            $estados = Estados::where('sigla','=', $this->estado)->load();
            
            if ($estados)
            {
                foreach ($estados as $estado)
                {
                    $pais = new Paises($estado->pais_id);
                    $this->pais = $pais->sigla;
                }
            }
            
            TTransaction::close();
            TTransaction::open(TSession::getValue('unit_database'));
            
            $endereco .= ' - ' . $this->estado;
        }
                            
        if (!empty($this->cep))
        {
            $endereco .= "\n" . $this->cep;
        }
               
        $this->endereco = $endereco;
                
        $contato = array();
                
        if (!empty($this->fone1))
        {
            $contato[] = $this->fone1;
        }
        
        if (!empty($this->fone2))
        {
            $contato[] = $this->fone2;
        }
        
        if (!empty($this->fone3))
        {
            $contato[] = $this->fone3;
        }
                    
        if (!empty($this->email))
        {
            $contato[] = $this->email;
        }
                    
        if (!empty($this->site))
        {
            $contato[] = $this->site;
        }
                
        $this->contato = implode("\n", $contato);
        
        parent::store();
        
        $this->setGruposPessoa();
        
        parent::store();
    }
    
    public static function onImportData()
    {
        try
        {
            $unit_id = TSession::getValue('userunitid');
            switch ($unit_id)
            {                    
                case 1:
                    TTransaction::open('caeb');
                    $conn = TTransaction::get();
                    
                    $query = "select * from person";
                    $results = $conn->query($query);
                    
                    TTransaction::close();
                    
                    TTransaction::open(TSession::getValue('unit_database'));
                    
                    $repository = new TRepository('Pessoas');
                    $repository->delete();
                    
                    TTransaction::close();
                    
                    if ($results)
                    {
                        foreach ($results as $result)
                        {
                            TTransaction::open(TSession::getValue('unit_database'));
                            
                            switch ($result['maritalstatus'])
                            {
                                case 'CASADO(A)':
                                    $estado_civil_id = 1;
                                    break;
                                    
                                case 'UNIÃO ESTAVEL':
                                    $estado_civil_id = 2;
                                    break;
                                    
                                case 'SOLTEIRO(A)':
                                    $estado_civil_id = 3;
                                    break;
                                    
                                case 'SEPARADO(A) JUDICIALMENTE':
                                    $estado_civil_id = 4;
                                    break;
                                    
                                case 'DIVORCIADO(A)':
                                    $estado_civil_id = 5;
                                    break;
                                    
                                case 'VIÚVO(A)':
                                    $estado_civil_id = 6;
                                    break;
                                    
                                case 'DESQUITADO(A)':
                                    $estado_civil_id = 7;
                                    break;
                                    
                                default:
                                    $estado_civil_id = 0;
                                    break;
                            }
                            
                            $pessoa = new Pessoas();
                            $pessoa->id = $result['id'];
                            $pessoa->tipo = 'F';
                            $pessoa->nome = $result['name'];
                            $pessoa->rg = $result['rg'];
                            $pessoa->cpfcnpj = $result['cpf'];
                            $pessoa->dtnasc = $result['birthdate'];
                            $pessoa->estado_civil_id = $estado_civil_id;
                            $pessoa->endereco = $result['address'];
                            $pessoa->bairro = $result['neighborhood'];
                            $pessoa->cep = $result['zip'];
                            $pessoa->cidade = $result['city'];
                            $pessoa->estado = $result['state_id'];
                            $pessoa->fone1 = $result['phone'];
                            $pessoa->fone2 = $result['celphone'];
                            $pessoa->email = $result['email'];
                            $pessoa->status = $result['status'];
                            $pessoa->dtcadastro = $result['registerdate'];   
                            $pessoa->grupos_pessoa = $result['assignmenter'] == 'S' ? 'TAREFEIRO' : 'PACIENTE';
                            $pessoa->store();
                            
                            $grupo = new PessoasGruposPessoa();
                            $grupo->pessoa_id = $pessoa->id;
                            $grupo->grupo_pessoa_id = $result['assignmenter'] == 'S' ? 1 : 2;
                            $grupo->store();
                            
                            TTransaction::close();
                        }
                    }
                    
                    break;
                    
                case 2:
                    break;
                case 3:
                    break;
                case 4:
                    break;
                case 5:
                    break;
                case 6:
                    $target_folder = 'tmp/CSV/';
                    $target_file   = $target_folder . 'Pessoas.csv';
                    
                    $i = 1;
                    $linhas = file($target_file);
                    foreach ($linhas as $linha)
                    {
                        if ($i > 1) {
                            TTransaction::open(TSession::getValue('unit_database'));

                            $sep = explode(";", $linha);
                            $pessoa = new Pessoas();
                            $pessoa->id = (int) $sep[0];
                            $pessoa->tipo = 'F';
                            $pessoa->nome = trim($sep[1]);
                            if (strpos($sep[2], ",") !== false) {
                                $endereco = explode(",", trim($sep[2]));
                                $pessoa->rua = $endereco[0];
                                $pessoa->numero = $endereco[1];
                            } else {
                                $pessoa->rua = trim($sep[2]);
                            }
                            $pessoa->cidade = trim($sep[3]);
                            $pessoa->bairro = trim($sep[4]);
                            $pessoa->estado = trim($sep[5]);
                            $pessoa->cep = trim($sep[6]);
                            if (!empty($sep[8])) {
                                $pessoa->tipo = 'J';
                                $pessoa->cpfcnpj = trim($sep[8]);
                            }
                            $pessoa->ie = trim($sep[9]);
                            $pessoa->cpfcnpj = trim($sep[10]);
                            $pessoa->fone1 = trim($sep[11]);
                            if (strpos($sep[12], "/") !== false) {
                                $dtcadastro = explode("/", $sep[12]);
                                $pessoa->dtcadastro = trim($dtcadastro[2]) . "-" . str_pad($dtcadastro[1], 2, '0', STR_PAD_LEFT) . "-" . str_pad($dtcadastro[0], 2, '0', STR_PAD_LEFT);
                            }
                            if (strpos($sep[13], "/") !== false) {
                                $ultima_compra = explode("/", $sep[13]);
                                $pessoa->ultima_compra = trim($ultima_compra[2]) . "-" . str_pad($ultima_compra[1], 2, '0', STR_PAD_LEFT) . "-" . str_pad($ultima_compra[0], 2, '0', STR_PAD_LEFT);
                            }
                            $pessoa->maior_atraso = (float) trim($sep[14]);
                            $pessoa->ativo = trim($sep[15]);
                            $pessoa->recebe_mala_direta = trim($sep[16]);
                            $pessoa->rg = trim($sep[17]);
                            if (strpos($sep[18], "/") !== false) {
                                $dtnasc = explode("/", $sep[18]);
                                $pessoa->dtnasc = trim($dtnasc[2]) . "-" . str_pad($dtnasc[1], 2, '0', STR_PAD_LEFT) . "-" . str_pad($dtnasc[0], 2, '0', STR_PAD_LEFT);
                            }
                            //$pessoa->vededor_id = trim($sep[19]);
                            $pessoa->observacao = trim($sep[20] . " " . $sep[21] . "" . $sep[22]);
                            $pessoa->complemento = trim($sep[23]);
                            if (!empty(trim($sep[24]))) {
                                $repository = new TRepository('LocaisTrabalho');
                                $critaria = new TCriteria;
                                $critaria->add(new TFilter('nome', '=', trim($sep[24])));
                                $objects = $repository->load($critaria);

                                if ($objects)
                                {
                                    foreach ($objects as $object)
                                    {
                                        $pessoa->local_trabalho_id = (int) $object->id;
                                    }
                                } 
                                else  
                                {
                                    $local_trabalho = new LocaisTrabalho();
                                    $local_trabalho->nome = trim($sep[24]);
                                    $local_trabalho->store();
                                    
                                    $pessoa->fabricante_id = (int) $local_trabalho->id; 
                                }
                            }
                            $pessoa->setor = trim($sep[25]);
                            $pessoa->fone2 = trim($sep[26]);
                            if (strpos($sep[27], "/") !== false) {
                                $ultima_locacao = explode("/", $sep[27]);
                                $pessoa->ultima_locacao = trim($ultima_locacao[2]) . "-" . str_pad($ultima_locacao[1], 2, '0', STR_PAD_LEFT) . "-" . str_pad($ultima_locacao[0], 2, '0',STR_PAD_LEFT);
                            }
                            $pessoa->fone3 = trim($sep[28]);
                            $pessoa->email = trim($sep[29]);
                            $pessoa->grupos_pessoa = "CLIENTE";
                            var_dump($pessoa);
                            $pessoa->store();

                            $pessoa_grupo_pessoa = new PessoasGruposPessoa();
                            $pessoa_grupo_pessoa->pessoa_id = $pessoa->id;
                            $pessoa_grupo_pessoa->grupo_pessoa_id = 1;
                            $pessoa_grupo_pessoa->store();

                            TTransaction::close();
                        }
                        $i++;
                    }
                    break;
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }    
}
