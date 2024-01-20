<?php
/**
 * PessoasList Listing
 * @author  <your name here>
 */
class PessoasList extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    use Adianti\base\AdiantiStandardListTrait;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase(TSession::getValue('unit_database'));            // defines the database
        $this->setActiveRecord('Pessoas');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        $this->setLimit(10);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        //$this->addFilterField('tipo', 'like', 'tipo'); // filterField, operator, formField
        //$this->addFilterField('cpfcnpj', 'like', 'cpfcnpj'); // filterField, operator, formField
        $this->addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        /*$this->addFilterField('fone', 'like', 'fone'); // filterField, operator, formField
        $this->addFilterField('cel', 'like', 'cel'); // filterField, operator, formField
        $this->addFilterField('email', 'like', 'email'); // filterField, operator, formField
        $this->addFilterField('site', 'like', 'site'); // filterField, operator, formField
        $this->addFilterField('cep', 'like', 'cep'); // filterField, operator, formField
        $this->addFilterField('rua', 'like', 'rua'); // filterField, operator, formField
        $this->addFilterField('numero', 'like', 'numero'); // filterField, operator, formField
        $this->addFilterField('complemento', 'like', 'complemento'); // filterField, operator, formField
        $this->addFilterField('bairro', 'like', 'bairro'); // filterField, operator, formField
        $this->addFilterField('cidade', 'like', 'cidade'); // filterField, operator, formField
        $this->addFilterField('estado', 'like', 'estado'); // filterField, operator, formField
        $this->addFilterField('pais', 'like', 'pais'); // filterField, operator, formField
        $this->addFilterField('dtnasc', 'like', 'dtnasc'); // filterField, operator, formField
        $this->addFilterField('sexo', 'like', 'sexo'); // filterField, operator, formField
        $this->addFilterField('nacionalidade_id', 'like', 'nacionalidade_id'); // filterField, operator, formField
        $this->addFilterField('paisnacto_id', 'like', 'paisnacto_id'); // filterField, operator, formField
        $this->addFilterField('estado_civil_id', 'like', 'estado_civil_id'); // filterField, operator, formField
        $this->addFilterField('conjuge_id', 'like', 'conjuge_id'); // filterField, operator, formField
        $this->addFilterField('rg', 'like', 'rg'); // filterField, operator, formField
        $this->addFilterField('orgaorg', 'like', 'orgaorg'); // filterField, operator, formField
        $this->addFilterField('estadorg_id', 'like', 'estadorg_id'); // filterField, operator, formField
        $this->addFilterField('dtemissaorg', 'like', 'dtemissaorg'); // filterField, operator, formField
        $this->addFilterField('local_trabalho_id', 'like', 'local_trabalho_id'); // filterField, operator, formField
        $this->addFilterField('profissao_id', 'like', 'profissao_id'); // filterField, operator, formField
        $this->addFilterField('rendamensal', 'like', 'rendamensal'); // filterField, operator, formField
        $this->addFilterField('forma_pagamento_id', 'like', 'forma_pagamento_id'); // filterField, operator, formField
        $this->addFilterField('banco_id', 'like', 'banco_id'); // filterField, operator, formField
        $this->addFilterField('tipo_conta_id', 'like', 'tipo_conta_id'); // filterField, operator, formField
        $this->addFilterField('agencia', 'like', 'agencia'); // filterField, operator, formField
        $this->addFilterField('conta', 'like', 'conta'); // filterField, operator, formField
        $this->addFilterField('digito', 'like', 'digito'); // filterField, operator, formField
        $this->addFilterField('operacao', 'like', 'operacao'); // filterField, operator, formField
        $this->addFilterField('observacao', 'like', 'observacao'); // filterField, operator, formField*/
        $this->addFilterField('contato', 'like', 'contato'); // filterField, operator, formField
        $this->addFilterField('endereco', 'like', 'endereco'); // filterField, operator, formField
        $this->addFilterField('grupos_pessoa', 'like', 'grupos_pessoa'); // filterField, operator, formField
        $this->addFilterField('ativo', '=', 'ativo'); // filterField, operator, formField

        $this->form = new TForm('form_search_Pessoas');
        
        $id = new TNumeric('id', 0, ',', '.');
        $tipo = new TEntry('tipo');
        $cpfcnpj = new TEntry('cpfcnpj');
        $nome = new TEntry('nome');
        $fone = new TEntry('fone');
        $cel = new TEntry('cel');
        $email = new TEntry('email');
        $site = new TEntry('site');
        $cep = new TEntry('cep');
        $rua = new TEntry('rua');
        $numero = new TEntry('numero');
        $complemento = new TEntry('complemento');
        $bairro = new TEntry('bairro');
        $cidade = new TEntry('cidade');
        $estado = new TEntry('estado');
        $pais = new TEntry('pais');
        $dtnasc = new TEntry('dtnasc');
        $sexo = new TEntry('sexo');
        $nacionalidade_id = new TEntry('nacionalidade_id');
        $paisnacto_id = new TEntry('paisnacto_id');
        $estado_civil_id = new TEntry('estado_civil_id');
        $conjuge_id = new TEntry('conjuge_id');
        $rg = new TEntry('rg');
        $orgaorg = new TEntry('orgaorg');
        $estadorg_id = new TEntry('estadorg_id');
        $dtemissaorg = new TEntry('dtemissaorg');
        $local_trabalho_id = new TEntry('local_trabalho_id');
        $profissao_id = new TEntry('profissao_id');
        $rendamensal = new TEntry('rendamensal');
        $forma_pagamento_id = new TEntry('forma_pagamento_id');
        $banco_id = new TEntry('banco_id');
        $tipo_conta_id = new TEntry('tipo_conta_id');
        $agencia = new TEntry('agencia');
        $conta = new TEntry('conta');
        $digito = new TEntry('digito');
        $operacao = new TEntry('operacao');
        $observacao = new TEntry('observacao');
        $ativo = new TCombo('ativo');
        $contato = new TEntry('contato');
        $endereco = new TEntry('endereco');
        $grupos_pessoa = new TDBCombo('grupos_pessoa', TSession::getValue('unit_database'), 'GruposPessoa', 'descricao', 'descricao', 'descricao');
        
        $ativo->addItems( [ 'S' => 'SIM', 'N' => 'NÃO' ] );

        $id->exitOnEnter();
        $tipo->exitOnEnter();
        $cpfcnpj->exitOnEnter();
        $nome->exitOnEnter();
        $fone->exitOnEnter();
        $cel->exitOnEnter();
        $email->exitOnEnter();
        $site->exitOnEnter();
        $cep->exitOnEnter();
        $rua->exitOnEnter();
        $numero->exitOnEnter();
        $complemento->exitOnEnter();
        $bairro->exitOnEnter();
        $cidade->exitOnEnter();
        $estado->exitOnEnter();
        $pais->exitOnEnter();
        $dtnasc->exitOnEnter();
        $sexo->exitOnEnter();
        $nacionalidade_id->exitOnEnter();
        $paisnacto_id->exitOnEnter();
        $estado_civil_id->exitOnEnter();
        $conjuge_id->exitOnEnter();
        $rg->exitOnEnter();
        $orgaorg->exitOnEnter();
        $estadorg_id->exitOnEnter();
        $dtemissaorg->exitOnEnter();
        $local_trabalho_id->exitOnEnter();
        $profissao_id->exitOnEnter();
        $rendamensal->exitOnEnter();
        $forma_pagamento_id->exitOnEnter();
        $banco_id->exitOnEnter();
        $tipo_conta_id->exitOnEnter();
        $agencia->exitOnEnter();
        $conta->exitOnEnter();
        $digito->exitOnEnter();
        $operacao->exitOnEnter();
        $observacao->exitOnEnter();
        $contato->exitOnEnter();
        $endereco->exitOnEnter();

        $id->setSize('100%');
        $tipo->setSize('100%');
        $cpfcnpj->setSize('100%');
        $nome->setSize('100%');
        $fone->setSize('100%');
        $cel->setSize('100%');
        $email->setSize('100%');
        $site->setSize('100%');
        $cep->setSize('100%');
        $rua->setSize('100%');
        $numero->setSize('100%');
        $complemento->setSize('100%');
        $bairro->setSize('100%');
        $cidade->setSize('100%');
        $estado->setSize('100%');
        $pais->setSize('100%');
        $dtnasc->setSize('100%');
        $sexo->setSize('100%');
        $nacionalidade_id->setSize('100%');
        $paisnacto_id->setSize('100%');
        $estado_civil_id->setSize('100%');
        $conjuge_id->setSize('100%');
        $rg->setSize('100%');
        $orgaorg->setSize('100%');
        $estadorg_id->setSize('100%');
        $dtemissaorg->setSize('100%');
        $local_trabalho_id->setSize('100%');
        $profissao_id->setSize('100%');
        $rendamensal->setSize('100%');
        $forma_pagamento_id->setSize('100%');
        $banco_id->setSize('100%');
        $tipo_conta_id->setSize('100%');
        $agencia->setSize('100%');
        $conta->setSize('100%');
        $digito->setSize('100%');
        $operacao->setSize('100%');
        $observacao->setSize('100%');
        $ativo->setSize(75);
        $contato->setSize('100%');
        $endereco->setSize('100%');
        $grupos_pessoa->setSize('100%');

        $id->tabindex = -1;
        $tipo->tabindex = -1;
        $cpfcnpj->tabindex = -1;
        $nome->tabindex = -1;
        $fone->tabindex = -1;
        $cel->tabindex = -1;
        $email->tabindex = -1;
        $site->tabindex = -1;
        $cep->tabindex = -1;
        $rua->tabindex = -1;
        $numero->tabindex = -1;
        $complemento->tabindex = -1;
        $bairro->tabindex = -1;
        $cidade->tabindex = -1;
        $estado->tabindex = -1;
        $pais->tabindex = -1;
        $dtnasc->tabindex = -1;
        $sexo->tabindex = -1;
        $nacionalidade_id->tabindex = -1;
        $paisnacto_id->tabindex = -1;
        $estado_civil_id->tabindex = -1;
        $conjuge_id->tabindex = -1;
        $rg->tabindex = -1;
        $orgaorg->tabindex = -1;
        $estadorg_id->tabindex = -1;
        $dtemissaorg->tabindex = -1;
        $local_trabalho_id->tabindex = -1;
        $profissao_id->tabindex = -1;
        $rendamensal->tabindex = -1;
        $forma_pagamento_id->tabindex = -1;
        $banco_id->tabindex = -1;
        $tipo_conta_id->tabindex = -1;
        $agencia->tabindex = -1;
        $conta->tabindex = -1;
        $digito->tabindex = -1;
        $operacao->tabindex = -1;
        $observacao->tabindex = -1;
        $ativo->tabindex = -1;
        $contato->tabindex = -1;
        $endereco->tabindex = -1;
        $grupos_pessoa->tabindex = -1;

        $id->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $tipo->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $cpfcnpj->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $nome->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $fone->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $cel->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $email->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $site->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $cep->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $rua->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $numero->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $complemento->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $bairro->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $cidade->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $estado->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $pais->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $dtnasc->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $sexo->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $nacionalidade_id->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $paisnacto_id->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $estado_civil_id->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $conjuge_id->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $rg->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $orgaorg->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $estadorg_id->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $dtemissaorg->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $local_trabalho_id->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $profissao_id->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $rendamensal->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $forma_pagamento_id->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $banco_id->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $tipo_conta_id->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $agencia->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $conta->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $digito->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $operacao->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $observacao->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $ativo->setChangeAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $contato->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $endereco->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $grupos_pessoa->setChangeAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', _t('Id'), 'right', 100);
        $column_tipo = new TDataGridColumn('tipo', 'Tipo', 'left');
        $column_cpfcnpj = new TDataGridColumn('cpfcnpj', 'Cpfcnpj', 'left');
        $column_nome = new TDataGridColumn('nome', _t('Name'), 'left');
        $column_fone = new TDataGridColumn('fone', 'Fone', 'left');
        $column_cel = new TDataGridColumn('cel', 'Cel', 'left');
        $column_email = new TDataGridColumn('email', 'Email', 'left');
        $column_site = new TDataGridColumn('site', 'Site', 'left');
        $column_cep = new TDataGridColumn('cep', 'Cep', 'left');
        $column_rua = new TDataGridColumn('rua', 'Rua Id', 'left');
        $column_numero = new TDataGridColumn('numero', 'Numero', 'left');
        $column_complemento = new TDataGridColumn('complemento', 'Complemento', 'left');
        $column_bairro = new TDataGridColumn('bairro', 'Bairro Id', 'left');
        $column_cidade = new TDataGridColumn('cidade', 'Cidade Id', 'left');
        $column_estado = new TDataGridColumn('estado', 'Estado Id', 'left');
        $column_pais = new TDataGridColumn('pais', 'Pais Id', 'left');
        $column_dtnasc = new TDataGridColumn('dtnasc', 'Dtnasc', 'left');
        $column_sexo = new TDataGridColumn('sexo', 'Sexo', 'left');
        $column_nacionalidade_id = new TDataGridColumn('nacionalidade_id', 'Nacionalidade Id', 'left');
        $column_paisnacto_id = new TDataGridColumn('paisnacto_id', 'Paisnacto Id', 'left');
        $column_estado_civil_id = new TDataGridColumn('estado_civil_id', 'Estadocivil Id', 'left');
        $column_conjuge_id = new TDataGridColumn('conjuge_id', 'Conjuge Id', 'left');
        $column_rg = new TDataGridColumn('rg', 'Rg', 'left');
        $column_orgaorg = new TDataGridColumn('orgaorg', 'Orgaorg', 'left');
        $column_estadorg_id = new TDataGridColumn('estadorg_id', 'Estadorg Id', 'left');
        $column_dtemissaorg = new TDataGridColumn('dtemissaorg', 'Dtemissaorg', 'left');
        $column_local_trabalho_id = new TDataGridColumn('local_trabalho_id', 'Localtrabalho Id', 'left');
        $column_profissao_id = new TDataGridColumn('profissao_id', 'Profissao Id', 'left');
        $column_rendamensal = new TDataGridColumn('rendamensal', 'Rendamensal', 'left');
        $column_forma_pagamento_id = new TDataGridColumn('forma_pagamento_id', 'Formapagamento Id', 'left');
        $column_banco_id = new TDataGridColumn('banco_id', 'Banco Id', 'left');
        $column_tipo_conta_id = new TDataGridColumn('tipo_conta_id', 'Tipoconta Id', 'left');
        $column_agencia = new TDataGridColumn('agencia', 'Agencia', 'left');
        $column_conta = new TDataGridColumn('conta', 'Conta', 'left');
        $column_digito = new TDataGridColumn('digito', 'Digito', 'left');
        $column_operacao = new TDataGridColumn('operacao', 'Operacao', 'left');
        $column_observacao = new TDataGridColumn('observacao', 'Observacao', 'left');
        $column_ativo = new TDataGridColumn('ativo', 'Ativo', 'left', 75);
        $column_contato = new TDataGridColumn('contato', _t('Contact'), 'left');
        $column_endereco = new TDataGridColumn('endereco', _t('Address'), 'left');
        $column_grupos_pessoa = new TDataGridColumn('grupos_pessoa', _t('Groups'), 'left');
        
        $column_id->setTransformer([$this, 'formatRow']);
        
        $column_nome->setTransformer( function($value, $object) {
            if ($object->tipo == 'F')
            {
                $icon  = "<i class='fa fa-user' aria-hidden='true'></i>";
                return "{$icon} " . $value;
            }
            else
            {
                $icon  = "<i class='fa fa-building' aria-hidden='true'></i>";
                return "{$icon} " . $value;
            }
        });
        
        $column_contato->setTransformer( function ($value, $object) {
            $return = array();
            
            $phone_icon  = "<i class='fa fa-phone' aria-hidden='true'></i>";
            $whatsapp_icon  = "<i class='fab fa-whatsapp' aria-hidden='true'></i>";
            
            if (!empty($object->fone1))
            {                                               
                $icons = "<a generator='adianti' href='index.php?class=PessoasList&method=openURL&url=tel:{$object->fone1}&text=Olá&static=1'> {$phone_icon} </a>";
                
                if (strlen($object->fone1) == 15)
                {
                    $icons = $icons . ' ' . "<a class='text-success' generator='adianti' href='index.php?class=PessoasList&method=openURL&url=https://web.whatsapp.com/send?phone=55{$object->fone1}&text=Olá&static=1'> {$whatsapp_icon} </a>";
                }
                
                $return[] = $icons . ' ' . $object->fone1;
            }
            
            if (!empty($object->fone2))
            {
                $icons = "<a generator='adianti' href='index.php?class=PessoasList&method=openURL&url=tel:{$object->fone2}&text=Olá&static=1'> {$phone_icon} </a>";
                
                if (strlen($object->fone2) == 15)
                {
                    $icons = $icons . ' ' . "<a class='text-success' generator='adianti' href='index.php?class=PessoasList&method=openURL&url=https://web.whatsapp.com/send?phone=55{$object->fone2}&text=Olá&static=1'> {$whatsapp_icon} </a>";
                }
                
                $return[] = $icons . ' ' . $object->fone2;
            }
            
            if (!empty($object->fone3))
            {
                $icons = "<a generator='adianti' href='index.php?class=PessoasList&method=openURL&url=tel:{$object->fone3}&text=Olá&static=1'> {$phone_icon} </a>";
                
                if (strlen($object->fone3) == 15)
                {
                    $icons = $icons . ' ' . "<a class='text-success' generator='adianti' href='index.php?class=PessoasList&method=openURL&url=https://web.whatsapp.com/send?phone=55{$object->fone3}&text=Olá&static=1'> {$whatsapp_icon} </a>";
                }
                
                $return[] = $icons . ' ' . $object->fone3;
            }
            
            if (!empty($object->email))
            {
                $icon  = "<i class='far fa-envelope' aria-hidden='true'></i>";
                $return[] = "{$icon} <a generator='adianti' href='index.php?class=SingleEmailForm&method=onLoad&scroll=0&email={$object->email}'>{$object->email}</a>";
            }
            
            if (!empty($object->site))
            {
                $icon  = "<i class='fa fa-globe' aria-hidden='true'></i>";
                $return[] = "{$icon} <a generator='adianti' href='index.php?class=PessoasList&method=openURL&url={$object->site}&static=1'>{$object->site}</a>";
            }
            
            return implode('<br>', $return);
        });
        
        $column_endereco->setTransformer( function ($value, $object) {
            $endereco = $object->rua;
            
            if (!empty($object->numero))
            {
                $endereco .= ', ' . $object->numero;
            }
               
            if (!empty($object->bairro))
            {
                $endereco .= ' - ' . $object->bairro;
            }
                
            if (!empty($object->cidade))
            {
                $endereco .= '<br>' . $object->cidade;
            }
                
            if (!empty($object->estado))
            {
                $endereco .= ' - ' . $object->estado;
            }
                
            if (!empty($object->cep))
            {
                $endereco .= '<br>' . $object->cep;   
            }
                
            $endereco_link = str_replace('<br>', ', ', $endereco);                
            $endereco_link = explode(' ', $endereco_link);
            $endereco_link = 'https://www.google.com/maps/search/' . implode('+', $endereco_link);
               
            $icon  = "<i class='fa fa-map-marked-alt' aria-hidden='true'></i>";
            if (!empty($endereco))
            {
                return "{$icon} <a generator='adianti' href='index.php?class=PessoasList&method=openURL&url={$endereco_link}&static=1'>$endereco</td></a>";
            }
        });
        
        $column_ativo->setTransformer( function($value, $object, $row) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        //$this->datagrid->addColumn($column_tipo);
        //$this->datagrid->addColumn($column_cpfcnpj);
        $this->datagrid->addColumn($column_nome);
        /*$this->datagrid->addColumn($column_fone);
        $this->datagrid->addColumn($column_cel);
        $this->datagrid->addColumn($column_email);
        $this->datagrid->addColumn($column_site);
        $this->datagrid->addColumn($column_cep);
        $this->datagrid->addColumn($column_rua);
        $this->datagrid->addColumn($column_numero);
        $this->datagrid->addColumn($column_complemento);
        $this->datagrid->addColumn($column_bairro);
        $this->datagrid->addColumn($column_cidade);
        $this->datagrid->addColumn($column_estado);
        $this->datagrid->addColumn($column_pais);
        $this->datagrid->addColumn($column_dtnasc);
        $this->datagrid->addColumn($column_sexo);
        $this->datagrid->addColumn($column_nacionalidade_id);
        $this->datagrid->addColumn($column_paisnacto_id);
        $this->datagrid->addColumn($column_estado_civil_id);
        $this->datagrid->addColumn($column_conjuge_id);
        $this->datagrid->addColumn($column_rg);
        $this->datagrid->addColumn($column_orgaorg);
        $this->datagrid->addColumn($column_estadorg_id);
        $this->datagrid->addColumn($column_dtemissaorg);
        $this->datagrid->addColumn($column_local_trabalho_id);
        $this->datagrid->addColumn($column_profissao_id);
        $this->datagrid->addColumn($column_rendamensal);
        $this->datagrid->addColumn($column_forma_pagamento_id);
        $this->datagrid->addColumn($column_banco_id);
        $this->datagrid->addColumn($column_tipo_conta_id);
        $this->datagrid->addColumn($column_agencia);
        $this->datagrid->addColumn($column_conta);
        $this->datagrid->addColumn($column_digito);
        $this->datagrid->addColumn($column_operacao);
        $this->datagrid->addColumn($column_observacao);*/
        $this->datagrid->addColumn($column_contato);
        $this->datagrid->addColumn($column_endereco);
        $this->datagrid->addColumn($column_grupos_pessoa);
        $this->datagrid->addColumn($column_ativo);
        
        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $column_nome->setAction($order_nome);
        
        $order_ativo = new TAction(array($this, 'onReload'));
        $order_ativo->setParameter('order', 'ativo');
        $column_ativo->setAction($order_ativo);
        
        $action0 = new TDataGridAction([$this, 'onSelect'], ['id' => '{id}', 'register_state' => 'false']);
        $action1 = new TDataGridAction(['PessoasForm', 'onEdit'], ['id'=>'{id}']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
        
        $action0->setButtonClass('btn btn-default');
        
        $this->datagrid->addAction($action0, _t('Select'), 'far:square fa-fw black');
        $this->datagrid->addAction($action1, _t('Edit'),   'far:edit blue');
        $this->datagrid->addAction($action2 ,_t('Delete'), 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // add datagrid inside form
        $this->form->add($this->datagrid);
        
        // create row with search inputs
        $tr = new TElement('tr');
        $this->datagrid->prependRow($tr);
        
        $tr->add( TElement::tag('td', ''));
        $tr->add( TElement::tag('td', ''));
        $tr->add( TElement::tag('td', ''));
        $tr->add( TElement::tag('td', $id));
        //$tr->add( TElement::tag('td', $tipo));
        //$tr->add( TElement::tag('td', $cpfcnpj));
        $tr->add( TElement::tag('td', $nome));
        /*$tr->add( TElement::tag('td', $fone));
        $tr->add( TElement::tag('td', $cel));
        $tr->add( TElement::tag('td', $email));
        $tr->add( TElement::tag('td', $site));
        $tr->add( TElement::tag('td', $cep));
        $tr->add( TElement::tag('td', $rua));
        $tr->add( TElement::tag('td', $numero));
        $tr->add( TElement::tag('td', $complemento));
        $tr->add( TElement::tag('td', $bairro));
        $tr->add( TElement::tag('td', $cidade));
        $tr->add( TElement::tag('td', $estado));
        $tr->add( TElement::tag('td', $pais));
        $tr->add( TElement::tag('td', $dtnasc));
        $tr->add( TElement::tag('td', $sexo));
        $tr->add( TElement::tag('td', $nacionalidade_id));
        $tr->add( TElement::tag('td', $paisnacto_id));
        $tr->add( TElement::tag('td', $estado_civil_id));
        $tr->add( TElement::tag('td', $conjuge_id));
        $tr->add( TElement::tag('td', $rg));
        $tr->add( TElement::tag('td', $orgaorg));
        $tr->add( TElement::tag('td', $estadorg_id));
        $tr->add( TElement::tag('td', $dtemissaorg));
        $tr->add( TElement::tag('td', $local_trabalho_id));
        $tr->add( TElement::tag('td', $profissao_id));
        $tr->add( TElement::tag('td', $rendamensal));
        $tr->add( TElement::tag('td', $forma_pagamento_id));
        $tr->add( TElement::tag('td', $banco_id));
        $tr->add( TElement::tag('td', $tipo_conta_id));
        $tr->add( TElement::tag('td', $agencia));
        $tr->add( TElement::tag('td', $conta));
        $tr->add( TElement::tag('td', $digito));
        $tr->add( TElement::tag('td', $operacao));
        $tr->add( TElement::tag('td', $observacao));*/
        $tr->add( TElement::tag('td', $contato));
        $tr->add( TElement::tag('td', $endereco));
        $tr->add( TElement::tag('td', $grupos_pessoa));
        $tr->add( TElement::tag('td', $ativo));

        $this->form->addField($id);
        $this->form->addField($tipo);
        $this->form->addField($cpfcnpj);
        $this->form->addField($nome);
        $this->form->addField($fone);
        $this->form->addField($cel);
        $this->form->addField($email);
        $this->form->addField($site);
        $this->form->addField($cep);
        $this->form->addField($rua);
        $this->form->addField($numero);
        $this->form->addField($complemento);
        $this->form->addField($bairro);
        $this->form->addField($cidade);
        $this->form->addField($estado);
        $this->form->addField($pais);
        $this->form->addField($dtnasc);
        $this->form->addField($sexo);
        $this->form->addField($nacionalidade_id);
        $this->form->addField($paisnacto_id);
        $this->form->addField($estado_civil_id);
        $this->form->addField($conjuge_id);
        $this->form->addField($rg);
        $this->form->addField($orgaorg);
        $this->form->addField($estadorg_id);
        $this->form->addField($dtemissaorg);
        $this->form->addField($local_trabalho_id);
        $this->form->addField($profissao_id);
        $this->form->addField($rendamensal);
        $this->form->addField($forma_pagamento_id);
        $this->form->addField($banco_id);
        $this->form->addField($tipo_conta_id);
        $this->form->addField($agencia);
        $this->form->addField($conta);
        $this->form->addField($digito);
        $this->form->addField($operacao);
        $this->form->addField($observacao);
        $this->form->addField($ativo);
        $this->form->addField($contato);
        $this->form->addField($endereco);
        $this->form->addField($grupos_pessoa);

        // keep form filled
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data'));
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        $panel = new TPanelGroup(_t('People'));
        $panel->add($this->form);
        $panel->addFooter($this->pageNavigation);
        
        // header actions
        $panel->addHeaderActionLink( _t('New'),  new TAction(['PessoasForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green' );
        $panel->addHeaderActionLink( _T('Clear Filter'), new TAction([$this, 'clearFilters']), 'fa:ban red' );
        $panel->addHeaderActionLink( _t('Delete Selected'), new TAction([$this, 'onDeleteSelected']), 'far:trash-alt red');
                
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table fa-fw blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['title' => _t('Listing of') . ' ' . _t('People'), 'orientation' => 'landscape', 'register_state' => 'false', 'static'=>'1']), 'far:file-pdf fa-fw red' );
        $dropdown->addAction( _t('Save as XML'), new TAction([$this, 'onExportXML'], ['register_state' => 'false', 'static'=>'1']), 'fa:code fa-fw green' );
        $panel->addHeaderWidget( $dropdown );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($panel);
        
        parent::add($container);
    }
    
    /**
     * Save the object reference in session
     */
    public function onSelect($param)
    {
        // get the selected objects from session 
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        
        $id = $param['id'];
        if (isset($selected_objects[$id]))
        {
            unset($selected_objects[$id]);
        }
        else
        {
            $selected_objects[$id] = $id;
        }
        TSession::setValue(__CLASS__.'_selected_objects', $selected_objects); // put the array back to the session
        
        // reload datagrids
        $this->onReload( func_get_arg(0) );
    }
    
    /**
     * Highlight the selected rows
     */
    public function formatRow($value, $object, $row)
    {
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        
        if ($selected_objects)
        {
            if (in_array( (int) $value, array_keys( $selected_objects ) ) )
            {
                $row->style = "background: #abdef9";
                
                $button = $row->find('i', ['class'=>'far fa-square fa-fw black'])[0];
                
                if ($button)
                {
                    $button->class = 'far fa-check-square fa-fw black';
                }
            }
        }
        
        return $value;
    }
    
    public function onDeleteSelected($param)
    {
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        
        if (!empty($selected_objects))
        {
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), new TAction(array($this, 'deleteSelected')));
        }
    }
    
    /**
     * Delete selected records
     */
    public function deleteSelected()
    {
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        
        if ($selected_objects)
        {
            TTransaction::open($this->database);
            foreach ($selected_objects as $id)
            {
                $object = Pessoas::find($id);
                if ($object)
                {
                    $object->delete();
                }
            }
            TTransaction::close();
            
            new TMessage('info', _t('Records deleted'));
        }
        TSession::setValue(__CLASS__.'_selected_objects', []);
        $this->onReload();
    }
    
    public static function openURL($param)
    {
        $url = $param['url'];
        TScript::create('window.open("'.$url.'");');
    }
}
