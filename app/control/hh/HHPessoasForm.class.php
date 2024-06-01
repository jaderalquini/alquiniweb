<?php

use Adianti\Control\TAction;
use Adianti\Registry\TSession;

/**
 * HHPessoasForm Registration
 * @author  <your name here>
 */
class HHPessoasForm extends TPage
{
    protected $form; // form
    protected $representantes_list;
    protected $history_list;
    protected $schedules_list;

    use Adianti\Base\AdiantiStandardListTrait;

    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct($param)
    {
        parent::__construct();

        $pessoa_id = isset($param['key']) ? $param['key'] : $param['pessoa_id'] ?? NULL;

        parent::setTargetContainer('adianti_right_panel');

        $this->setDatabase(TSession::getValue('unit_database'));              // defines the database
        $this->setActiveRecord('Pessoas');     // defines the active record
        $this->setDefaultOrder('id', 'asc');    // defines the default order

        // creates the form
        $this->form = new BootstrapFormBuilder('form_Pessoas');
        $this->form->setFormTitle(_t('Person'));
        $this->form->enableClientValidation();

        // create the form fields
        $id = new TNumeric('id', 0, ',', '.');
        $tipo = new TRadioGroup('tipo');
        $cpfcnpj = new TEntry('cpfcnpj');
        $nao_tem_cpf = new TCheckGroup('nao_tem_cpf');
        $nome = new TEntry('nome');
        $grupos_pessoa = new TDBCheckGroup('grupos_pessoa_array', TSession::getValue('unit_database'), 'GruposPessoa', 'id', 'descricao', 'descricao');
        $fone1 = new TEntry('fone1');
        $fone2 = new TEntry('fone2');
        $fone3 = new TEntry('fone3');
        $email = new TEntry('email');
        $site = new TEntry('site');
        $cep = new TEntry('cep');
        $rua = new TEntry('rua');
        $numero = new TNumeric('numero', 0, '', '.');
        $complemento = new TEntry('complemento');
        $bairro = new TEntry('bairro');
        $cidade = new TDBEntry('cidade', 'cep', 'Cidades', 'nome', 'nome');
        $estado = new TDBCombo('estado', 'cep', 'Estados', 'sigla', 'sigla', 'sigla');
        $ponto_referencia = new TEntry('ponto_referencia');
        $tipo_residencia_id = new TDBCombo('tipo_residencia_id', TSession::getValue('unit_database'), 'TiposResidencia', 'id', 'descricao', 'descricao');
        $tempo_residencia = new TEntry('tempo_residencia');        
        $dtcadastro = new TDate('dtcadastro');
        $dtnasc = new TDate('dtnasc');
        $nacionalidade_id = new TDBCombo('nacionalidade_id',TSession::getValue('unit_database'),'Nacionalidades','id','descricao','descricao');
        $cidadenacto = new TDBEntry('cidadenacto', 'cep', 'Cidades', 'nome', 'nome');
        $estadonacto = new TDBCombo('estadonacto', 'cep', 'Estados', 'sigla', 'sigla', 'sigla');
        $paisnacto = new TDBCombo('paisnacto', 'cep', 'Paises', 'sigla', 'nome', 'nome');
        $nomepais = new TEntry('nomepais');
        $sexo = new TRadioGroup('sexo');
        $estado_civil_id = new TDBCombo('estado_civil_id', TSession::getValue('unit_database'), 'EstadosCivis', 'id', 'descricao', 'descricao');
        $conjuge_id = new TDBCombo('conjuge_id', TSession::getValue('unit_database'), 'Pessoas', 'id', 'nome', 'nome');
        $rg = new TEntry('rg');
        $orgaorg = new TEntry('orgaorg');
        $estadorg_id = new TDBCombo('estadorg_id','cep','Estados','id','sigla','sigla');
        $dtemissaorg = new TDate('dtemissaorg');
        $local_trabalho_id = new TDBCombo('local_trabalho_id', TSession::getValue('unit_database'), 'LocaisTrabalho', 'id', 'nome', 'nome');
        $profissao_id = new TDBCombo('profissao_id', TSession::getValue('unit_database'), 'Profissoes', 'id', 'descricao', 'descricao');
        $rendamensal = new TNumeric('rendamensal', 2, ',', '.');
        $ie = new TEntry('ie');
        $im = new TEntry('im');
        $junta_comercial = new TEntry('junta_comercial');
        $ramo_atividade_id = new TDBCombo('ramo_atividade_id', TSession::getValue('unit_database'), 'RamosAtividade', 'id', 'descricao', 'descricao');
        $forma_pagamento_id = new TDBCombo('forma_pagamento_id', TSession::getValue('unit_database'), 'FormasPagamento', 'id', 'descricao', 'descricao');
        $observacao = new TText('observacao');

        $action = new TAction( [ 'HHPessoasWindow', 'onSetup' ] );
        $action->setParameter('new', TRUE);
        $action->setParameter('receive_form', 'form_Pessoas');
        $action->setParameter('receive_fields[conjuge_id]', 'id');

        $button = new TActionLink('', $action, 'green', null, null, 'fa:plus-circle');
        $button->class = 'btn btn-default inline-button';
        $button->title = _t('New');
        $conjuge_id->after($button);

        $action = new TAction( [ 'ProfissoesWindow', 'onSetup' ] );
        $action->setParameter('new', TRUE);
        $action->setParameter('receive_form', 'form_Pessoas');
        $action->setParameter('receive_fields[profissao_id]', 'id');

        $button = new TActionLink('', $action, 'green', null, null, 'fa:plus-circle');
        $button->class = 'btn btn-default inline-button';
        $button->title = _t('New');
        $profissao_id->after($button);

        $action = new TAction( [ 'LocaisTrabalhoWindow', 'onSetup' ] );
        $action->setParameter('new', TRUE);
        $action->setParameter('receive_form', 'form_Pessoas');
        $action->setParameter('receive_fields[local_trabalho_id]', 'id');

        $button = new TActionLink('', $action, 'green', null, null, 'fa:plus-circle');
        $button->class = 'btn btn-default inline-button';
        $button->title = _t('New');
        $local_trabalho_id->after($button);

        $action = new TAction( [ 'TiposResidenciaWindow', 'onSetup' ] );
        $action->setParameter('new', TRUE);
        $action->setParameter('receive_form', 'form_Pessoas');
        $action->setParameter('receive_fields[tipo_residencia_id]', 'id');

        $button = new TActionLink('', $action, 'green', null, null, 'fa:plus-circle');
        $button->id = 'new_tipo_residencia';
        $button->class = 'btn btn-default inline-button';
        $button->title = _t('New');
        $tipo_residencia_id->after($button);

        $this->form->appendPage(_t('Person'));

        $this->form->addFields( [ new TLabel(_t('Id')) ], [ $id ], [ new TLabel(_t('Type'), 'red') ], [ $tipo ] );
        $this->form->addFields( [ new TLabel('CPF/CNPJ', 'red') ], [ $cpfcnpj, $nao_tem_cpf ], [ new TLabel(_t('Name'), 'red') ], [ $nome ] );
        $this->form->addFields( [ new TLabel(_t('Groups'), 'red') ], [ $grupos_pessoa ] );
        $this->form->addFields( [ new TLabel(_t('Phone') . ' 1') ], [ $fone1 ], [ new TLabel(_t('Phone') . ' 2') ], [ $fone2 ] );
        $this->form->addFields( [ new TLabel(_t('Phone') . ' 3') ], [ $fone3 ], [ new TLabel('Email') ], [ $email ] );
        $this->form->addFields( [ new TLabel('Site') ], [ $site ], [ new TLabel(_t('ZIP')) ], [ $cep ] );
        $this->form->addFields( [ new TLabel(_t('Street')) ], [ $rua ], [ new TLabel(_t('Number') . '/' . _t('Complement')) ], [ $numero, $complemento ] );
        $this->form->addFields( [ new TLabel(_t('Neighborhood')) ], [ $bairro ], [ new TLabel(_t('City') . '/' . _t('State')) ], [ $cidade, $estado ] );
        $this->form->addFields( [ new TLabel(_t('Reference Point')) ], [ $ponto_referencia ], [ new TLabel(_t('Residence Type')) ], [ $tipo_residencia_id ] );
        $this->form->addFields( [ new TLabel(_t('Residence Time')) ], [ $tempo_residencia ], [ new TLabel(_t('Register Date')) ], [ $dtcadastro ] );

        // set sizes
        $id->setSize(100);
        $tipo->setSize('100%');
        $cpfcnpj->setSize(150);
        $nao_tem_cpf->setLayout('horizontal');
        $nao_tem_cpf->setId('nao_tem_cpf');
        $nao_tem_cpf->addItems( [ 'S' => 'Não possui CPF'] );
        $nao_tem_cpf->setChangeAction(new TAction(array($this, 'onChangeNaoPossuiCPF')));
        $nome->setSize('100%');
        $fone1->setSize(150);
        $fone2->setSize(150);
        $fone3->setSize(150);
        $email->setSize('100%');
        $site->setSize('100%');
        $cep->setSize(100);
        $rua->setSize('100%');
        $numero->setSize('20%');
        $complemento->setSize('80%');
        $bairro->setSize('100%');
        $cidade->setSize('80%');
        $estado->setSize('20%');
        $ponto_referencia->setSize('100%');
        $tipo_residencia_id->setSize('calc(100% - 30px)');
        $tipo_residencia_id->enableSearch();
        $tempo_residencia->setSize(150);
        $dtcadastro->setSize(100);
        $dtnasc->setSize(100);
        $nacionalidade_id->setSize('100%');
        $cidadenacto->setSize('80%');
        $estadonacto->setSize('20%');
        $paisnacto->setSize('100%');
        $nomepais->setSize('100%');
        $sexo->setSize('100%');        
        $estado_civil_id->setSize('100%');
        $conjuge_id->setSize('calc(100% - 30px)');
        $conjuge_id->enableSearch();
        $rg->setSize(100);
        $orgaorg->setSize(100);
        $estadorg_id->setSize(75);
        $dtemissaorg->setSize(100);
        $local_trabalho_id->setSize('calc(100% - 30px)');
        $local_trabalho_id->enableSearch();
        $profissao_id->setSize('calc(100% - 30px)');
        $profissao_id->enableSearch();
        $rendamensal->setSize(100);
        $ie->setSize(150);
        $im->setSize(150);
        $junta_comercial->setSize(150);
        $ramo_atividade_id->setSize('calc(100% - 30px)');
        $ramo_atividade_id->enableSearch();
        $forma_pagamento_id->setSize('100%');
        $observacao->setSize('100%');

        $id->setEditable(FALSE);
        $tipo->addItems( [ 'F' => 'Pessoa Física', 'J' => 'Pessoa Jurídica' ]);
        $tipo->setLayout('horizontal');
        $tipo->setUseButton();
        $tipo->setChangeAction(new TAction(array($this, 'onChangeTipo')));
        $tipo->addValidation(_t('Type'), new TRequiredValidator);
        $cpfcnpj->autofocus = 'autofocus';
        $cpfcnpj->class = 'cpfcnpj';
        $cpfcnpj->setExitAction(new TAction(array($this, 'onExitCpfCnpj')));
        $nome->autofocus = 'autofocus';
        $nome->forceUpperCase();
        $nome->addValidation(_t('Name'), new TRequiredValidator);
        $grupos_pessoa->setLayout('horizontal');
        $grupos_pessoa->setUseButton(TRUE);
        $grupos_pessoa->addValidation(_t('Groups'), new TRequiredValidator);
        $fone1->class = 'phones';
        $fone2->class = 'phones';
        $fone3->class = 'phones'; 
        $email->forceLowerCase();
        $site->forceLowerCase();
        $cep->setMask('99999-999');
        $cep->setExitAction(new TAction( [ $this, 'onExitCep' ] ));
        $complemento->forceUpperCase();
        $cidade->setExitAction(new TAction( [ $this, 'onExiCidade' ] ));
        $estado->enableSearch(TRUE);
        $ponto_referencia->forceUpperCase();
        $tempo_residencia->forceUpperCase();
        $dtcadastro->setEditable(FALSE);
        $dtcadastro->setMask('dd/mm/yyyy');
        $dtcadastro->setDatabaseMask('yyyy-mm-dd');

        $this->form->appendPage(_t('Physical Person'));

        $this->form->addFields( [ new TLabel(_t('Birth Date')) ], [ $dtnasc ], [ new TLabel(_t('Sex')) ], [ $sexo ] );
        $this->form->addFields( [ new TLabel(_t('Nationality')) ], [ $nacionalidade_id ], [ new TLabel(_t('City/State of Birth')) ], [ $cidadenacto,$estadonacto ] );
        $this->form->addFields( [ new TLabel(_t('Country of Birth')) ], [ $paisnacto ], [ new TLabel(_t('Parent\'s Name')) ], [ $nomepais ] );
        $this->form->addFields( [ new TLabel(_t('Marital Status')) ], [ $estado_civil_id ], [ new TLabel(_t('Partner')) ], [ $conjuge_id ] );
        $this->form->addFields( [ new TLabel('RG') ], [ $rg ], [ new TLabel(_t('Issuing Body')) ], [ $orgaorg ] );
        $this->form->addFields( [ new TLabel('UF') ], [ $estadorg_id ], [ new TLabel(_t('Issue Date')) ], [ $dtemissaorg ] );
        $this->form->addFields( [ new TLabel(_t('Occupation')) ], [ $profissao_id ], [ new TLabel(_t('Work Place')) ], [ $local_trabalho_id ] );
        $this->form->addFields( [ new TLabel(_t('Monthly Income')) ], [ $rendamensal ], [], [] );

        $dtnasc->setMask('dd/mm/yyyy');
        $dtnasc->setDatabaseMask('yyyy-mm-dd');
        $dtnasc->setSize(150);
        $estadonacto->enableSearch();
        $paisnacto->enableSearch();
        $nomepais->forceUpperCase();
        $sexo->addItems([ 'M' => 'MASCULINO', 'F' => 'FEMININO'] );
        $sexo->setLayout('horizontal');
        $sexo->setUseButton();
        $nacionalidade_id->setChangeAction(new TAction(array($this, 'onChangeNacionalidade')));  
        $cidadenacto->setExitAction(new TAction(array($this, 'onExitCidadeNacto')));
        $estadonacto->enableSearch(TRUE);
        $estado_civil_id->enableSearch(TRUE);        
        $orgaorg->forceUpperCase();
        $estadorg_id->enableSearch(TRUE);
        $dtemissaorg->setMask('dd/mm/yyyy');
        $dtemissaorg->setDatabaseMask('yyyy-mm-dd');
        $dtemissaorg->setSize(150);

        $this->form->appendPage(_t('Legal Person'));

        $action = new TAction( [ 'RamosAtividadeWindow', 'onSetup' ] );
        $action->setParameter('new', TRUE);
        $action->setParameter('receive_form', 'form_Pessoas');
        $action->setParameter('receive_fields[ramo_atividade_id]', 'id');

        $button = new TActionLink('new_ramo_atividade', $action, 'green', NULL, NULL, 'fa:plus-circle');
        $button->class = 'btn btn-default inline-button';
        $button->title = _t('New');
        $ramo_atividade_id->after($button);

        $this->form->addFields( [ new TLabel(_t('State Registration')) ], [ $ie ], [ new TLabel(_t('Municipal Registration')) ], [ $im ] );
        $this->form->addFields( [ new TLabel(_t('Commercial Board')) ], [ $junta_comercial ], [ new TLabel(_t('Branch of Aactivity')) ], [ $ramo_atividade_id ] );

        $this->form->addContent( [ new TFormSeparator(_t('Agents')) ] );

        $representantes_uniqid = new THidden('representantes_uniqid');
        $representantes_id = new THidden('representantes_id');
        $representantes_representante_id = new TDBCombo('representantes_representante_id', TSession::getValue('unit_database'), 'Pessoas', 'id', 'nome', 'nome');

        $action = new TAction( [ 'PessoasWindow', 'onSetup' ] );
        $action->setParameter('new', TRUE);
        $action->setParameter('receive_form', 'form_Pessoas');
        $action->setParameter('receive_fields[representantes_representante_id]', 'id');

        $button = new TButton('new_representante');
        $button->setAction($action);
        $button->setImage('fa:plus-circle green');
        $button->class = 'btn btn-default inline-button';
        $button->title = _t('New');
        $representantes_representante_id->after($button);
        $representantes_representante_id->enableSearch();
        $this->form->addField($button);

        $this->form->addFields( [$representantes_uniqid] );
        $this->form->addFields( [$representantes_id] );

        $this->form->addFields( [new TLabel(_t('Person'))], [$representantes_representante_id] );

        $representantes_representante_id->setSize('50%');

        $add = TButton::create('add', [$this, 'onRepresentanteAdd'], _t('Add'), 'fa:plus green');
        $add->getAction()->setParameter('static','1');
        $this->form->addFields( [], [$add] );

        $this->representantes_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->representantes_list->setId('Representantes_list');
        $this->representantes_list->generateHiddenFields();
        $this->representantes_list->style = "min-width: 700px; width:100%;margin-bottom: 10px";

        // items
        $this->representantes_list->addColumn( new TDataGridColumn('uniqid', 'Uniqid', 'center') )->setVisibility(false);
        $this->representantes_list->addColumn( new TDataGridColumn('id', 'Id', 'center') )->setVisibility(false);
        $this->representantes_list->addColumn( new TDataGridColumn('representante_id', _t('Person'), 'left') )->setVisibility(false);
        $this->representantes_list->addColumn( new TDataGridColumn('representante_nome', _t('Person'), 'left') );

        // detail actions
        $action1 = new TDataGridAction([$this, 'onRepresentanteEdit'] );
        $action1->setFields( ['uniqid', '*'] );

        $action2 = new TDataGridAction([$this, 'onRepresentanteDelete']);
        $action2->setField('uniqid');

        // add the actions to the datagrid
        //$this->representantes_list->addAction($action1, _t('Edit'), 'fa:edit blue');
        $this->representantes_list->addAction($action2, _t('Delete'), 'far:trash-alt red');

        $this->representantes_list->createModel();

        $panel = new TPanelGroup;
        $panel->add($this->representantes_list);
        $panel->getBody()->style = 'overflow-x:auto';
        $this->form->addContent( [$panel] );

        $this->form->appendPage(_t('Observation'));

        $this->form->addFields( [ $observacao ] );

        $this->form->appendPage(_t('History'));

        $this->history_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->history_list->setHeight(500);
        $this->history_list->makeScrollable();
        $this->history_list->setId('history_list');
        $this->history_list->generateHiddenFields();
        $this->history_list->style = "min-width: 700px; width:100%;margin-bottom: 10px";

        $col_dtagendamento   = new TDataGridColumn( 'dtagendamento', _t('Date'), 'left', '70%');
        $col_retorno   = new TDataGridColumn( 'retorno', _t('Return'), 'left', '15%');
        $col_compareceu   = new TDataGridColumn( 'compareceu', _t('Showed Up'), 'left', '15%');

        $this->history_list->addColumn( $col_dtagendamento );
        $this->history_list->addColumn( $col_retorno );
        $this->history_list->addColumn( $col_compareceu );

        $col_dtagendamento->setTransformer( function($value) {
            if ($value)
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
            return $value;
        });

        $col_retorno->setTransformer( function($value) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });

        $col_compareceu->setTransformer( function($value) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });

        $this->history_list->createModel();

        $this->form->addFields( [$this->history_list] );

        $this->form->appendPage(_t('Schedules'));

        $this->schedules_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->schedules_list->setHeight(500);
        $this->schedules_list->makeScrollable();
        $this->schedules_list->setId('schedules_list');
        $this->schedules_list->generateHiddenFields();
        $this->schedules_list->style = "min-width: 700px; width:100%;margin-bottom: 10px";

        $col_id   = new TDataGridColumn( 'id', _t('Id'), 'left', '10%');
        $col_pessoa_id = new TDataGridColumn( 'pessoa_id', _t('Person'), 'left', '10%');
        $col_dtagendamento   = new TDataGridColumn( 'dtagendamento', _t('Date'), 'left', '15%');
        $col_terapia   = new TDataGridColumn( '{terapia->nome}', _t('Therapy'), 'left', '70%');
        $col_retorno   = new TDataGridColumn( 'retorno', _t('Return'), 'left', '15%');

        $this->schedules_list->addColumn( $col_id );
        $this->schedules_list->addColumn( $col_dtagendamento );
        $this->schedules_list->addColumn( $col_terapia );
        $this->schedules_list->addColumn( $col_retorno );

        $col_id->setTransformer([$this, 'formatRow']);

        $col_dtagendamento->setTransformer( function($value) {
            if ($value)
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
            return $value;
        });

        $col_retorno->setTransformer( function($value) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });

        $col_id->setVisibility(false);
        $col_pessoa_id->setVisibility(false);

        // creates the datagrid actions
        $action1 = new TDataGridAction([$this, 'onSelect'], ['id' => '{id}', 'pessoa_id' => '{pessoa_id}', 'register_state' => 'false']);
        //$action1->setUseButton(TRUE);
        $action1->setButtonClass('btn btn-default');

        // add the actions to the datagrid
        $this->schedules_list->addAction($action1, _t('Select'), 'far:square fa-fw black');

        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('far:trash-alt red');
        $action_del->setField('id');
        $this->schedules_list->addAction($action_del);

        $this->schedules_list->createModel();

        $panel = new TPanelGroup;
        $panel->add($this->schedules_list)->style = 'overflow-x:auto';

        $panel->addHeaderActionLink( _t('Delete Selected'), new TAction([$this, 'onDeleteSelected'], ['static' => '1']), 'far:trash-alt red');
        $panel->addHeaderActionLink( _t('Mark All'), new TAction([$this, 'onMarkAll'], ['pessoa_id' => $pessoa_id]), 'fa:check');
        $panel->addHeaderActionLink( _t('Unmark All'), new TAction([$this, 'onUnmarkAll'], ['pessoa_id' => $pessoa_id]), 'far:square');

        $this->form->addFields( [$panel] );

        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
    
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave'], ['static' => '1']), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');

        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        //$container->add(new TXMLBreadCrumb('menu.xml', 'PessoasList'));
        $container->add($this->form);

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
    public function deleteSelected($param)
    {
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');

        if ($selected_objects)
        {
            TTransaction::open($this->database);
            foreach ($selected_objects as $id)
            {
                $object = TerapiasAgendamentos::find($id);
                if ($object)
                {
                    $object->delete();
                }
            }
            TTransaction::close();

            new TMessage('info', 'Records deleted');
        }
        TSession::setValue(__CLASS__.'_selected_objects', []);
        $this->onEdit( ['key' => $param['pessoa_id'], 'selected_objects' => $selected_objects] );
        $this->form->setCurrentPage(5);
    }

    public function onMarkAll($param)
    {
        TTransaction::open(TSession::getValue('unit_database'));
        $schedules_items = TerapiasAgendamentos::where('pessoa_id', '=', $param['pessoa_id'])->where('dtagendamento', '>=', date('Y-m-d'))->load();
        TTransaction::close();

        $selected_objects = [];
        foreach( $schedules_items as $item )
        {
            $selected_objects[$item->id] = $item->id;
        }

        TSession::setValue(__CLASS__.'_selected_objects', $selected_objects); // put the array back to the session
        $this->onEdit( ['key' => $param['pessoa_id'], 'selected_objects' => $selected_objects] );
        $this->form->setCurrentPage(5);
    }

    public function onUnmarkAll($param)
    {
        TSession::setValue(__CLASS__.'_selected_objects', []);
        $this->onEdit( ['key' => $param['pessoa_id'], 'selected_objects' => []] );
        $this->form->setCurrentPage(5);
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
        $this->onEdit( ['key' => $param['pessoa_id'], 'selected_objects' => $selected_objects] );
        $this->form->setCurrentPage(5);
    }

    /**
     * Ask before deletion
     */
    public function onDelete($param)
    {
        $id = $param['id'];

        TTransaction::open(TSession::getValue('unit_database'));
        $terapias_agendamentos = new TerapiasAgendamentos($id);
        TTransaction::close();

        // define the delete action
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead

        // shows a dialog to the user
        new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);

        $this->onEdit( func_get_arg(0) );
    }

    /**
     * Delete a record
     */
    public function Delete($param)
    {
        try
        {
            $key=$param['key']; // get the parameter $key
            TTransaction::open(TSession::getValue('unit_database')); // open a transaction with database
            $object = new TerapiasAgendamentos($key); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction
            $this->onSetContrato($param);
            new TMessage('info', AdiantiCoreTranslator::translate('Record deleted')); // success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
        
        $this->onSetContrato($param);
    }

    /**
     * Pre load some data
     */
    public function onLoad($param)
    {
        $data = new stdClass;
        $data->pessoa_id   = $param['id'];
        $this->form->setData($data);
    }

    /**
     * Clear form
     * @param $param URL parameters
     */
    public function onClear($param)
    {
        $this->form->clear(TRUE);
    }

    /**
     * Add detail item
     * @param $param URL parameters
     */
    public function onRepresentanteAdd( $param )
    {
        try
        {
            $data = $this->form->getData();
            
            $errors = array();
            $fields = array(_t('Person') => $data->representantes_representante_id);
                
            foreach ($fields as $field => $value)
            {
                try
                {
                    $validator = new TRequiredValidator;
                    $validator->validate($field, $value);
                }
                catch (Exception $e)
                {
                    $errors[] = $e->getMessage() . '.';
                }
            }

            if (count($errors) > 0)
            {
                throw new Exception(implode("<br>", $errors));
            }

            $uniqid = !empty($data->representantes_uniqid) ? $data->representantes_uniqid : uniqid();
            $id = !empty($data->representantes_id) ? $data->representantes_id : null;

            $grid_data = [];
            $grid_data['uniqid'] = $uniqid;
            $grid_data['id'] = $id;
            $grid_data['representante_id'] = $data->representantes_representante_id;

            TTransaction::open(TSession::getValue('unit_database'));
            $pessoa = new Pessoas($data->representante_representante_id);            
            TTransaction::close();

            $grid_data['representante_nome'] = $pessoa->nome;

            // insert row dynamically
            $row = $this->representantes_list->addItem( (object) $grid_data );
            $row->id = $uniqid;

            TDataGrid::replaceRowById('Representantes_list', $uniqid, $row);

            // clear detail form fields
            $data->representantes_uniqid = '';
            $data->representantes_id = '';
            $data->representantes_representante_id = '';

            // send data, do not fire change/exit events
            TForm::sendData( 'form_Pessoas', $data, false, false );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Edit detail item
     * @param $param URL parameters
     */
    public static function onRepresentanteEdit( $param )
    {
        $data = new stdClass;
        $data->representantes_uniqid = $param['uniqid'];
        $data->representantes_id = $param['id'];
        $data->representantes_representante_id = $param['representante_id'];

        // send data, do not fire change/exit events
        TForm::sendData( 'form_Pessoas', $data, false, false );
    }

    /**
     * Delete detail item
     * @param $param URL parameters
     */
    public static function onRepresentanteDelete( $param )
    {
        // clear detail form fields
        $data = new stdClass;
        $data->representantes_uniqid = '';
        $data->representantes_id = '';
        $data->representantes_representante_id = '';

        // send data, do not fire change/exit events
        TForm::sendData( 'form_Pessoas', $data, false, false );
        
        // remove row
        TDataGrid::removeRowById('Representantes_list', $param['uniqid']);
    }

    public function onSave( $param )
    {
        try
        {
            TTransaction::open(TSession::getValue('unit_database'));

            // get the form data
            $object = $this->form->getData($this->activeRecord);

            $data = $this->form->getData();
            // validate data
            $this->onValidate($param);

            // stores the object
            $object->store();

            PessoasGruposPessoa::where('pessoa_id', '=', $object->id)->delete();
            if( !empty($data->grupos_pessoa_array) )
            {
                foreach( $data->grupos_pessoa_array as $grupo )
                {
                    echo $grupo;
                    $object->addGruposPessoa( new GruposPessoa($grupo) );
                }
            }

            if (!empty($object->conjuge_id))
            {
                $pessoa = new Pessoas($object->conjuge_id);
                $pessoa->conjuge_id = $object->id;
                $pessoa->store();
            }

            Representantes::where('pessoa_id', '=', $object->id)->delete();
            if ($object->tipo == 'J')
            {
                if( isset($param['Representantes_list_representante_id']) )
                {
                    foreach( $param['Representantes_list_representante_id'] as $key => $item_id )
                    {
                        $detail = new Representantes;
                        $detail->representante_id  = $param['Representantes_list_representante_id'][$key];
                        $detail->pessoa_id = $object->id;
                        $detail->store();
                    }
                }
            }

            // fill the form with the active record data
            $data = new StdClass;
            $data->id = $object->id;

            TForm::sendData('form_Pessoas', $data);

            // close the transaction
            TTransaction::close();

            $this->afterSaveAction = new TAction(['HHPessoasList', 'onReload']);

            // shows the success message
            if (isset($this->useMessages) AND $this->useMessages === false)
            {
                AdiantiCoreApplication::loadPageURL( $this->afterSaveAction->serialize() );
            }
            else
            {
                new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $this->afterSaveAction);
            }

            return $object;
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];

                TTransaction::open(TSession::getValue('unit_database'));

                $object = new Pessoas($key);

                $this->onChangeTipo( array('tipo' => $object->tipo, 'cpfcnpj' => $object->cpfcnpj, 'nome' => $object->nome) );
                $this->onChangeNacionalidade(array('nacionalidade_id' => $object->nacionalidade));
                $this->onChangeFormaPagamento(array('forma_pagamento_id' => $object->forma_pagamento_id));

                $histtory_items = TerapiasAgendamentos::where('pessoa_id', '=', $object->id)->where('dtagendamento', '<', date('Y-m-d'))->load();
                
                foreach( $histtory_items as $item )
                {
                    $item->uniqid = uniqid();
                    $row = $this->history_list->addItem( $item );
                    $row->id = $item->uniqid;
                }

                $schedules_items = TerapiasAgendamentos::where('pessoa_id', '=', $object->id)->where('dtagendamento', '>=', date('Y-m-d'))->load();

                foreach( $schedules_items as $item )
                {
                    $item->uniqid = uniqid();
                    $row = $this->schedules_list->addItem( $item );
                    $row->id = $item->uniqid;
                }
                
                $this->form->setData($object);
                
                TTransaction::close();

                if (isset($param['selected_objects']))
                {
                    TSession::setValue(__CLASS__.'_selected_objects', $param['selected_objects']); // put the array back to the session
                }
            }
            else
            {
                $this->form->clear();

                $obj = new StdClass;
                $obj->tipo = 'F';
                $obj->dtcadastro = date('Y-m-d');

                $this->form->setData($obj);

                $this->onChangeTipo(array('tipo' => 'F'));
                $this->onChangeNacionalidade(array('nacionalidade_id' => NULL));
                $this->onChangeFormaPagamento(array('forma_pagamento_id' => NULL));
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public static function onChangeTipo($param)
    {
        $value = $param['tipo'];

        TScript::create('$($(\'form[name=form_Pessoas]\').find(".nav-tabs li")[1]).hide();');
        TScript::create('$($(\'form[name=form_Pessoas]\').find(".nav-tabs li")[2]).hide();');

        if ($value == 'F')
        {
            TScript::create('$($(\'form[name=form_Pessoas]\').find(".nav-tabs li")[1]).show();');
            TScript::create('$($(\'form[name=form_Pessoas]\').find(".nav-tabs li")[2]).hide();');

            TScript::create('$(\'input[name=cpfcnpj]\').mask(\'000.000.000-00\', {reverse: true});');
            TScript::create('$(\'input[name="nao_tem_cpf[]"]\').show();');
            TScript::create('$(\'label:contains("Não possui CPF")\').show();');
            TDBCombo::enableField('form_Pessoas', 'tipo_residencia_id');
            TScript::create('$(\'a[id=new_tipo_residencia]\').show();');
            TEntry::enableField('form_Pessoas', 'tempo_residencia');

            $obj = new StdClass;
            $obj->ie = '';
            $obj->im = '';
            $obj->junta_comercial = '';
            $obj->ramo_atividae_id = '';

            TForm::sendData('form_Pessoas', $obj);
        }

        if ($value == 'J')
        {
            TScript::create('$($(\'form[name=form_Pessoas]\').find(".nav-tabs li")[1]).hide();');
            TScript::create('$($(\'form[name=form_Pessoas]\').find(".nav-tabs li")[2]).show();');

            TScript::create('$(\'input[name=cpfcnpj]\').mask(\'00.000.000/0000-00\', {reverse: true});');
            TScript::create('$(\'input[name="nao_tem_cpf[]"]\').hide();');
            TScript::create('$(\'label:contains("Não possui CPF")\').hide();');
            TDBCombo::disableField('form_Pessoas', 'tipo_residencia_id');
            TScript::create('$(\'a[id=new_tipo_residencia]\').hide();');    
            TEntry::disableField('form_Pessoas', 'tempo_residencia');

            $obj = new StdClass;
            $obj->tipo_residencia_id = '';
            $obj->tempo_residencia = '';
            $obj->dtnasc = '';
            $obj->nacionalidade_id = '';
            $obj->cidadenacto = '';
            $obj->estadonacto = '';
            $obj->paisnacto = '';
            $obj->nomepais = '';
            $obj->sexo = '';
            $obj->estado_civil_id = '';
            $obj->conjuge_id = '';
            $obj->rg = '';
            $obj->orgaorg = '';
            $obj->estadorg_id = '';
            $obj->dtemissaorg = '';
            $obj->local_trabalho_id = '';
            $obj->profissao_id = '';
            $obj->rendamensal = '';

            TForm::sendData('form_Pessoas', $obj);
        }
    }

    public static function onExitCpfCnpj($param)
    {
        try
        {
            $cpfcnpj = FuncoesExtras::retiraFormatacao($param['cpfcnpj']);
            $cpfcnpj = strlen($cpfcnpj) == 11 ? FuncoesExtras::mask($cpfcnpj, '###.###.###-##') : FuncoesExtras::mask($cpfcnpj, '##.###.###/####-##');

            TTransaction::open(TSession::getValue('unit_database'));
            
            $pessoas = Pessoas::where('cpfcnpj', '=', $cpfcnpj)->load();
            
            if ($pessoas)
            {
                foreach ($pessoas as $pessoa)
                {
                    $object = new Pessoas($pessoa->id);
                    TForm::sendData('form_Pessoas', $object);
                }
            }

            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public static function onExitCep($param)
    {
        $cep = str_replace('-', '', $param['cep']);
        TScript::create('$(document).ready(function() {
                           cep = ' . $cep . ';
                           $.getJSON("https://brasilapi.com.br/api/cep/v2/"+ cep, function(dados) {
                               if (!("erro" in dados)) {
                                   $("input[name=rua]").val(dados.street.toUpperCase());
                                   $("input[name=bairro]").val(dados.neighborhood.toUpperCase());
                                   $("input[name=cidade]").val(dados.city.toUpperCase());
                                   $("select[name=estado]").val(dados.state);
                               }
                           });
        })');
    }

    public static function onExiCidade($param)
    {
        try
        {
            $cidade = $param['cidade'];
            TTransaction::open('cep');

            $cidades = Cidades::where('nome', '=', "$cidade")->load();

            if ($cidades)
            {
                foreach ($cidades as $cidade)
                {
                    $estado = new Estados($cidade->estado_id);

                    $obj = new StdClass;
                    $obj->estado = $estado->sigla;

                    TForm::sendData('form_Pessoas', $obj);
                }
            }

            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public static function onChangeNacionalidade($param)
    {
        $value = $param['nacionalidade_id'];

        if ($value == 'B')
        {
            $obj = new StdClass;
            $obj->paisnacto = 'BRA';

            TForm::sendData('form_Pessoas', $obj);

            TDBCombo::disableField('form_Pessoas', 'paisnacto');
        }
    }

    public static function onExitCidadeNacto($param)
    {
        try
        {
            $cidade = $param['cidadenacto'];
            TTransaction::open('cep');

            $cidades = Cidades::where('nome', '=', "$cidade")->load();

            if ($cidades)
            {
                foreach ($cidades as $cidade)
                {
                    $estado = new Estados($cidade->estado_id);
                    $pais = new Paises($estado->pais_id);

                    $obj = new StdClass;
                    $obj->estadonacto = $estado->sigla;
                    $obj->paisnacto = $pais->sigla;

                    TForm::sendData('form_Pessoas', $obj);
                }
            }

            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public static function onChangeFormaPagamento($param)
    {
        try
        {
            TTransaction::open(TSession::getValue('unit_database'));

            $forma_pagamento = new Formaspagamento($param['forma_pagamento_id']);

            if ($forma_pagamento->pedeconta=='S')
            {
                TDBCombo::enableField('form_Pessoas', 'banco_id');
                TDBCombo::enableField('form_Pessoas', 'tipo_conta_id');
                TEntry::enableField('form_Pessoas', 'agencia');
                TEntry::enableField('form_Pessoas', 'conta');
                TEntry::enableField('form_Pessoas', 'operacao');
            }
            else
            {
                TDBCombo::disableField('form_Pessoas', 'banco_id');
                TDBCombo::disableField('form_Pessoas', 'tipo_conta_id');
                TEntry::disableField('form_Pessoas', 'agencia');
                TEntry::disableField('form_Pessoas', 'conta');
                TEntry::disableField('form_Pessoas', 'operacao');
            }

            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public static function onChangeNaoPossuiCPF($param)
    {
        try
        {
            if (isset($param['nao_tem_cpf']) && $param['nao_tem_cpf'][0] == 'S')
                TEntry::disableField('form_Pessoas', 'cpfcnpj');
            else
                TEntry::enableField('form_Pessoas', 'cpfcnpj');
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onValidate($param)
    {
        $this->form->validate();

        $errors = array();

        if ($param['tipo'] == 'F')
        {
            try
            {
                $validator = new TCPFValidator;
                $validator->validate('CPF/CNPJ', $param['cpfcnpj']);
            }
            catch (Exception $e)
            {
                $errors[] = $e->getMessage() . '.';
            }

            if (!empty(trim($param['dtnasc'])))
            {
                try
                {
                    $validator = new TDateValidator;
                    $validator->validate(_t('Birth Date'), $param['dtnasc'], array('dd/mm/yyyy'));
                }
                catch (Exception $e)
                {
                    $errors[] = $e->getMessage() . '.';
                }
            }
        }

        if($param['tipo'] == 'J')
        {
            try
            {
                $validator = new TCNPJValidator;
                $validator->validate('CPF/CNPJ', $param['cpfcnpj']);
            }
            catch (Exception $e)
            {
                $errors[] = $e->getMessage() . '.';
            }
        }

        if (!empty(trim($param['email'])))
        {
            try
            {
                $validator = new TEmailValidator;
                $validator->validate(_t('Email'), $param['email']);
            }
            catch (Exception $e)
            {
                $errors[] = $e->getMessage() . '.';
            }
        }

        if (!empty($errors) > 0)
        {
            throw new Exception(implode("<br>", $errors));
        }
    }

    /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
