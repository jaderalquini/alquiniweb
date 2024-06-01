<?php

use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Registry\TSession;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Form\TRadioGroup;
use Adianti\Widget\Wrapper\TDBRadioGroup;

/**
 * HHPessoasList Listing
 * @author  <your name here>
 */
class HHPessoasList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;

    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();

        $ini  = AdiantiApplicationConfig::get();

        $this->setDatabase(TSession::getValue('unit_database'));            // defines the database
        $this->setActiveRecord('Pessoas');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('tipo', 'like', 'tipo'); // filterField, operator, formFieldformField
        $this->addFilterField('cpfcnpj', 'like', 'cpfcnpj'); // filterField, operator, formField
        $this->addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        $this->addFilterField('fone1', '=', 'fone1'); // filterField, operator, formField
        $this->addFilterField('fone2', '=', 'fone2'); // filterField, operator, formField
        $this->addFilterField('fone3', '=', 'fone3'); // filterField, operator, formField
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
        $this->addFilterField('observacao', 'like', 'observacao'); // filterField, operator, formField
        $this->addFilterField('status', '=', 'status'); // filterField, operator, formField
        $this->addFilterField('grupos_pessoa', 'like', 'grupos_pessoa'); // filterField, operator, formField
        parent::setLimit(TSession::getValue(__CLASS__ . '_limit') ?? 10);

        parent::setAfterSearchCallback( [$this, 'onAfterSearch' ] );

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Pessoas');
        $this->form->setFormTitle(_t('People'));

        // create the form fields
        $id = new TNumeric('id', 0, ',', '.');
        $tipo = new TEntry('tipo');
        $cpfcnpj = new TEntry('cpfcnpj');
        $nome = new TEntry('nome');
        $fone1 = new TEntry('fone1');
        $fone2 = new TEntry('fone2');
        $fone3 = new TEntry('fone3');
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
        $status = new TRadioGroup('status');
        $contato = new TEntry('contato');
        $endereco = new TEntry('endereco');
        $grupos_pessoa = new TDBRadioGroup('grupos_pessoa', TSession::getValue('unit_database'), 'GruposPessoa', 'descricao', 'descricao', 'descricao');

        // add the fields
        $this->form->addFields( [new TLabel(_t('Id'))], [$id] );
        $this->form->addFields( [new TLabel(_t('Name'))], [$nome] );
        $this->form->addFields( [new TLabel('CPF')], [$cpfcnpj] );
        $this->form->addFields( [new TLabel(_t('Phone'))], [$fone1] );
        $this->form->addFields( [new TLabel('E-mail')], [$email] );
        $this->form->addFields( [new TLabel(_t('Group'))], [$grupos_pessoa] );
        $this->form->addFields( [new TLabel(_t('Status'))], [$status] );

        $id->setSize(100);
        $tipo->setSize('100%');
        $cpfcnpj->setSize('100%');
        $nome->setSize('100%');
        $fone1->setSize(150);
        $fone2->setSize(150);
        $fone3->setSize(150);
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
        $status->setSize('100%');
        $contato->setSize('100%');
        $endereco->setSize('100%');
        $grupos_pessoa->setSize('100%');

        $fone1->class = 'phones';
        $fone2->class = 'phones';
        $fone3->class = 'phones';
        $grupos_pessoa->setLayout('horizontal');
        $grupos_pessoa->setUseButton(TRUE);
        $status->addItems( [ 'A' => 'ATIVO', 'I' => 'INATIVO' ] );
        $status->setLayout('horizontal');
        $status->setUseButton(TRUE);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Pessoas_filter_data') );

        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear Filter'), new TAction(array($this, 'onClearFilter')), 'fa:ban red');

        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', _t('Id'), 'right', 100);
        $column_tipo = new TDataGridColumn('tipo', 'Tipo', 'left');
        $column_cpfcnpj = new TDataGridColumn('cpfcnpj', 'Cpfcnpj', 'left');
        $column_nome = new TDataGridColumn('nome', _t('Name'), 'left');
        $column_fone = new TDataGridColumn('fone1', 'Fone1', 'left');
        $column_fone2 = new TDataGridColumn('fone2', 'Fone2', 'left');
        $column_fone3 = new TDataGridColumn('fone3', 'Fone3', 'left');
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
        $column_status = new TDataGridColumn('status', 'Status', 'left');
        $column_contato = new TDataGridColumn('contato', _t('Contact'), 'left');
        $column_endereco = new TDataGridColumn('endereco', _t('Address'), 'left');
        $column_grupos_pessoa_list = new TDataGridColumn('grupos_pessoa_list', _t('Groups'), 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        //$this->datagrid->addColumn($column_tipo);
        //$this->datagrid->addColumn($column_cpfcnpj);
        $this->datagrid->addColumn($column_nome);
        /*$this->datagrid->addColumn($column_fone1);
        $this->datagrid->addColumn($column_fone2);
        $this->datagrid->addColumn($column_fone3);
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
        //$this->datagrid->addColumn($column_endereco);
        $this->datagrid->addColumn($column_grupos_pessoa_list);
        $this->datagrid->addColumn($column_status);

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

        $column_status->setTransformer( function($value, $object, $row) {
            $class = ($value=='I') ? 'danger' : 'success';
            $label = ($value=='I') ? _t('Inactive') : _t('Active');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });

        // creates the datagrid actions
        $action1 = new TDataGridAction([$this, 'onSelect'], ['id' => '{id}', 'register_state' => 'false']);
        //$action1->setUseButton(TRUE);
        $action1->setButtonClass('btn btn-default');
        
        // add the actions to the datagrid
        $this->datagrid->addAction($action1, _t('Select'), 'far:square fa-fw black');

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $column_nome->setAction($order_nome);

        $order_status = new TAction(array($this, 'onReload'));
        $order_status->setParameter('order', 'status');
        $column_status->setAction($order_status);

        // create EDIT action
        $action_edit = new TDataGridAction(array('HHPessoasForm', 'onEdit'), ['register_state' => 'false'] );
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('far:edit blue');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);

        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('far:trash-alt red');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);

        $action_print_ficha = new TDataGridAction(array($this, 'onPrintFichaCadastral'), ['static' => '1'] );
        $action_print_ficha->setButtonClass('btn btn-default');
        $action_print_ficha->setLabel(_t('Print Registration Form'));
        $action_print_ficha->setImage('fas:print blue');
        $action_print_ficha->setField('id');
        $this->datagrid->addAction($action_print_ficha);

        $action_print_agendamentos = new TDataGridAction(array($this, 'onInputDialog'), ['static' => '1'] );
        $action_print_agendamentos->setButtonClass('btn btn-default');
        $action_print_agendamentos->setLabel(_t('Print Schedules'));
        $action_print_agendamentos->setImage('fas:calendar-alt red');
        $action_print_agendamentos->setField('id');
        $this->datagrid->addAction($action_print_agendamentos);

        $action_turn_onoff = new TDataGridAction(array($this, 'onTurnOnOff'));
        $action_turn_onoff->setButtonClass('btn btn-default');
        $action_turn_onoff->setLabel(_t('Activate/Deactivate'));
        $action_turn_onoff->setImage('fa:power-off orange');
        $action_turn_onoff->setField('id');
        $this->datagrid->addAction($action_turn_onoff);

        // create the datagrid model
        $this->datagrid->createModel();

        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup;
        $panel->add($this->datagrid)->style = 'overflow-x:auto';
        $panel->addFooter($this->pageNavigation);

        $btnf = TButton::create('find', [$this, 'onSearch'], '', 'fa:search');
        $btnf->style= 'height: 37px; margin-right:4px;';

        $form_search = new TForm('form_search_nome');
        $form_search->style = 'float:left;display:flex';
        $form_search->add($nome, true);
        $form_search->add($btnf, true);

        $panel->addHeaderWidget($form_search);
        
        $panel->addHeaderActionLink(_t('New'), new TAction(['HHPessoasForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');
        $panel->addHeaderActionLink( _t('Delete Selected'), new TAction([$this, 'onDeleteSelected']), 'far:trash-alt red');
        $this->filter_label = $panel->addHeaderActionLink('Filtros', new TAction([$this, 'onShowCurtainFilters']), 'fa:filter');
        $panel->addHeaderActionLink( _t('Clear Filter'), new TAction(array($this, 'onClearFilter')), 'fa:ban red');

        // header actions
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->style = 'height:37px';
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table fa-fw blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf fa-fw red' );
        $dropdown->addAction( _t('Save as XML'), new TAction([$this, 'onExportXML'], ['register_state' => 'false', 'static'=>'1']), 'fa:code fa-fw green' );
        $panel->addHeaderWidget( $dropdown );

        // header actions
        $dropdown = new TDropDown( TSession::getValue(__CLASS__ . '_limit') ?? '10', '');
        $dropdown->style = 'height:37px';
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( 10,   new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '10']) );
        $dropdown->addAction( 20,   new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '20']) );
        $dropdown->addAction( 50,   new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '50']) );
        $dropdown->addAction( 100,  new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '100']) );
        $dropdown->addAction( 1000, new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '1000']) );
        $panel->addHeaderWidget( $dropdown );

        if (TSession::getValue(get_class($this).'_filter_counter') > 0)
        {
            $this->filter_label->class = 'btn btn-primary';
            $this->filter_label->setLabel('Filtros ('. TSession::getValue(get_class($this).'_filter_counter').')');
        }

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        //$container->add($this->form);
        $container->add($panel);

        $script = new TElement('script');
        $script->type = 'text/javascript';
        $script->add("var maskPhones = function (val) { 
                          return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009'; 
                      }, options = {onKeyPress: function(val, e, field, options) { 
                          field.mask(maskPhones.apply({}, arguments), options); } 
                      }; $('.phones').mask(maskPhones, options);");
        $script->add("var maskCpfCnpj = function (val) { 
                          return val.replace(/\D/g, '').length === 14 ? '99.999.999/9999-99' : '999.999.999-99'; 
                      }, options = {onKeyPress: function(val, e, field, options) { 
                          field.mask(maskCpfCnpj.apply({}, arguments), options); } 
                      }; $('.cpfcnpj').mask(maskCpfCnpj, options);");
        parent::add($script);
        
        parent::add($container);
    }

    public function onClearFilter()
    {
        $this->form->clear();
        $this->onSearch();
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
            
            new TMessage('info', 'Records deleted');
        }
        TSession::setValue(__CLASS__.'_selected_objects', []);
        $this->onReload();
    }

    public function onAfterSearch($datagrid, $options)
    {
        if (TSession::getValue(get_class($this).'_filter_counter') > 0)
        {
            $this->filter_label->class = 'btn btn-primary';
            $this->filter_label->setLabel('Filtros ('. TSession::getValue(get_class($this).'_filter_counter').')');
        }
        else
        {
            $this->filter_label->class = 'btn btn-default';
            $this->filter_label->setLabel('Filtros');
        }

        if (!empty(TSession::getValue(get_class($this).'_filter_data')))
        {
            $obj = new stdClass;
            $obj->nome = TSession::getValue(get_class($this).'_filter_data')->nome;
            TForm::sendData('form_search_nome', $obj);
        }
    }

    public static function onChangeLimit($param)
    {
        TSession::setValue(__CLASS__ . '_limit', $param['limit'] );
        AdiantiCoreApplication::loadPage(__CLASS__, 'onReload');
    }

    public static function onShowCurtainFilters($param = null)
    {
        try
        {
            // create empty page for right panel
            $page = new TPage;
            $page->setTargetContainer('adianti_right_panel');
            $page->setProperty('override', 'true');
            $page->setPageName(__CLASS__);

            $btn_close = new TButton('closeCurtain');
            $btn_close->onClick = "Template.closeRightPanel();";
            $btn_close->setLabel("Fechar");
            $btn_close->setImage('fas:times');

            // instantiate self class, populate filters in construct 
            $embed = new self;
            $embed->form->addHeaderWidget($btn_close);

            // embed form inside curtain
            $page->add($embed->form);
            $page->setIsWrapped(true);
            $page->show();
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onPrintFichaCadastral($param)
    {
        try
        {
            $output = 'app/output/'.uniqid().'.pdf';
            $this->printFichaCadastral($output, $param);
            
            $window = TWindow::create('Export', 0.8, 0.8);
            $object = new TElement('object');
            $object->{'data'}  = $output;
            $object->{'type'}  = 'application/pdf';
            $object->{'style'} = "width: 100%; height:calc(100% - 10px)";
            $window->add($object);
            $window->show();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    public function printFichaCadastral($output, $param)
    {
        if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
        {
            TTransaction::open(TSession::getValue('unit_database'));
            $conn = TTransaction::get();
            
            $pessoa = Pessoas::find($param['id']);
            $estadocivil = EstadosCivis::find($pessoa->estado_civil_id);
            
            $replaces = [];
            $replaces["LOGO"] = TSession::getValue("userunitlogo");

            $repository = new TRepository('Terapias');
            $criteria = new TCriteria;
            $criteria->setProperty('order', 'nome');
            $objects = $repository->load($criteria);

            $terapias = [];
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    $repository = new TRepository('TerapiasAgendamentos');
                    $criteria = new TCriteria;
                    $criteria->add(new TFilter('pessoa_id', '=', $pessoa->id));
                    $criteria->add(new TFilter('terapia_id', '=', $object->id));
                    $criteria->add(new TFilter('dtagendamento', '>=', date('Y-m-d')));
                    $agendamentos = $repository->load($criteria);
                    $count = $repository->count($criteria);

                    $data = [];
                    if ($agendamentos)
                    {
                        $i = 0;
                        foreach ($agendamentos as $agendamento)
                        {
                            $i++;
                            $data[$i] = TDate::date2br($agendamento->dtagendamento);
                        }
                    }
                    
                    if ($count < 8)
                    {
                        for ($i = $count + 1; $i <= 8; $i++)
                        {
                            $data[$i] = NULL;
                        }
                    }

                    $terapias[] = [  "NOME" => $object->nome,
                                     "QUANTIDADE" => $count == 0 ? NULL : $count, 
                                     "DATA1" => $data[1], 
                                     "DATA2" => $data[2],
                                     "DATA3" => $data[3],
                                     "DATA4" => $data[4],
                                     "DATA5" => $data[5],
                                     "DATA6" => $data[6],
                                     "DATA7" => $data[7],
                                     "DATA8" => $data[8]
                    ];
                }
            }

            $replaces["TERAPIAS"] = $terapias;
            
            $query = "PRAGMA table_info(pessoas);";
            $results = $conn->query($query);

            $this->html = new THtmlRenderer('app/resources/caeb_ficha_cadastral.html');
            
            if ($results)
            {
                foreach ($results as $result)
                {
                    $field = $result['name'];
                    $source = strtoupper($field);
                    
                    switch ($result['type'])
                    {
                        case 'DATE':
                            $replaces[$source] = TDate::date2br($pessoa->$field);
                            break;
                            
                        case 'REAL':
                            $replaces[$source] = number_format($pessoa->$field, 2, ',', '.');
                            break;
                            
                        default:
                            $replaces[$source] = $pessoa->$field;
                            break;
                    }
                }
            }
            if (!empty($pessoa->dtnasc))
            {
                $dtnasc = explode('-', $pessoa->dtnasc);
                $date = explode('/', date('d/m/Y'));
                
                $idade = $date[2] - $dtnasc[0];
                
                if ($dtnasc[1] > $date[1])
                {
                    $idade = $idade - 1;
                }
                
                if ($dtnasc[1] == $date[1])
                {
                    if ($dtnasc[2] <= $date[0])
                    {
                        
                    }
                    else
                    {
                        $idade = $idade - 1;
                    }
                }
                
                $replaces['IDADE'] = $idade . ' anos';
            }
            else
            {
                $replaces['IDADE'] = NULL;
            }
            
            $replaces['DATA'] = date('d/m/Y');

            TTransaction::close();
            
            $this->html->enableSection('main', $replaces);
            
            $contents = $this->html->getContents();
            
            $options = new \Dompdf\Options();
            $options-> setChroot (getcwd());
            
            // converts the HTML template into PDF
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf-> loadHtml ($contents);
            $dompdf-> setPaper ('A4', 'portrait');
            $dompdf-> render ();
            
            // write and open file
            file_put_contents($output, $dompdf->output());
        }
    }
    
    public function onInputDialog($param)
    {
        $form = new BootstrapFormBuilder('input_form');
        $form->style = 'padding:20px';
        
        $criteria = new TCriteria;
        $criteria->add(new TFilter('grupos_pessoa', 'like', "%TAREFEIRO%"));
        $criteria->add(new TFilter('status', '=', 'A'));
        
        $atendente = new TDBUniqueSearch('atendente', TSession::getValue('unit_database'), 'Pessoas', 'id', 'nome', 'nome', $criteria);
        $retornar = new TDate('retornar');
        $id = new THidden('id');
        
        $form->addFields( [ new TLabel('Atndente') ], [ $atendente ] );
        $form->addFields( [ new TLabel('Retornar em') ], [$retornar, $id] );
        
        $atendente->setSize('100%');
        $atendente->autofocus = 'autofocus';
        $retornar->setSize(150);
        $retornar->setMask('dd/mm/yyyy');
        $id->setValue($param['id']);
        
        $atendente->addValidation('Atendente', new TRequiredValidator);
        $retornar->addValidation('Retornar em', new TRequiredValidator);
        
        $form->addAction('Confirmar', new TAction( [ $this, 'onPrintFichaAgendamentos' ], [ 'static' => '1' ] ), 'fa:check-circle green');
        
        new TInputDialog('Favor Informar', $form);
    }
    
    public function onPrintFichaAgendamentos($param)
    {
        try
        {
            $output = 'app/output/'.uniqid().'.pdf';
            $this->printFichaAgendamentos($output, $param);
            
            $window = TWindow::create('Export', 0.8, 0.8);
            $object = new TElement('object');
            $object->{'data'}  = $output;
            $object->{'type'}  = 'application/pdf';
            $object->{'style'} = "width: 100%; height:calc(100% - 10px)";
            $window->add($object);
            $window->show();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    public function printFichaAgendamentos($output, $param)
    {
        if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
        {
            $pessoa = $param['id'];
            $atendente = isset($param['atendente']) ? $param['atendente']: NULL;
            $retornar = isset($param['retornar']) ? TDate::date2us($param['retornar']) : NULL;
            
            TTransaction::open(TSession::getValue('unit_database'));

            $this->html = new THtmlRenderer("app/resources/caeb_ficha_agendamentos.html");
            
            $pessoa = Pessoas::find($param['id']);
            $atendente = Pessoas::find($atendente);
            
            $replaces = [];
            
            $repository = new TRepository('Terapias');
            $criteria = new TCriteria;
            $criteria->setProperty('order', 'nome');
            $objects = $repository->load($criteria);

            $terapias = [];
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    $repository = new TRepository('TerapiasAgendamentos');
                    $criteria = new TCriteria;
                    $criteria->add(new TFilter('pessoa_id', '=', $pessoa->id));
                    $criteria->add(new TFilter('terapia_id', '=', $object->id));
                    $criteria->add(new TFilter('dtagendamento', '>=', date('Y-m-d')));
                    $agendamentos = $repository->load($criteria);
                    $count = $repository->count($criteria);

                    $data = [];
                    if ($agendamentos)
                    {
                        $i = 0;
                        foreach ($agendamentos as $agendamento)
                        {
                            $i++;
                            $data[$i] = TDate::date2br($agendamento->dtagendamento);
                        }
                    }
                    
                    if ($count < 8)
                    {
                        for ($i = $count + 1; $i <= 8; $i++)
                        {
                            $data[$i] = NULL;
                        }
                    }

                    $terapias[] = [  "NOME" => $object->nome,
                                     "QUANTIDADE" => $count == 0 ? NULL : $count, 
                                     "DATA1" => $data[1], 
                                     "DATA2" => $data[2],
                                     "DATA3" => $data[3],
                                     "DATA4" => $data[4],
                                     "DATA5" => $data[5],
                                     "DATA6" => $data[6],
                                     "DATA7" => $data[7],
                                     "DATA8" => $data[8]
                    ];
                }
            }

            TTransaction::close();

            $replaces["TERAPIAS"] = $terapias;
            
            setlocale(LC_TIME, 'pt-BR', 'pt-BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
            
            $replaces['NOME'] = $pessoa->nome;
            $replaces['DATA'] = date('d/m/Y');
            $replaces['ATENDENTE'] = !empty($atendente) ? $atendente->nome : NULL;
            $replaces['RETORNAR'] = !empty($retornar) ? date('d/m', strtotime($retornar)) : NULL;
            $replaces['DIASEMANA'] = !empty($retornar) ? strtoupper(strftime('%A', strtotime($retornar))) : NULL;

            $this->html->enableSection('main', $replaces);
            $contents = $this->html->getContents();
            
            $options = new \Dompdf\Options();
            $options-> setChroot (getcwd());
            
            // converts the HTML template into PDF
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf-> loadHtml ($contents);
            $dompdf-> setPaper ('A4', 'portrait');
            $dompdf-> render ();
            
            // write and open file
            file_put_contents($output, $dompdf->output());
        }
    }
    
    public function onTurnOnOff($param)
    {
        try
        {
            TTransaction::open($this->database);
            $pessoa = Pessoas::find($param['id']);
            if ($pessoa instanceof Pessoas)
            {
                $pessoa->status = $pessoa->status == 'A' ? 'I' : 'A';
                $pessoa->store();
            }
            
            TTransaction::close();
            
            $this->onReload($param);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public static function openURL($param)
    {
        $url = $param['url'];
        TScript::create('window.open("'.$url.'");');
    }
}
