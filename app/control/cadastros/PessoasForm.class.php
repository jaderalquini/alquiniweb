<?php

use Adianti\Database\TCriteria;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Form\TDate;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Form\TNumeric;
use Adianti\Widget\Form\TRadioGroup;

/**
 * PessoasForm Registration
 * @author  <your name here>
 */
class PessoasForm extends TPage
{
    protected $form; // form
    protected $representantes_list;
    
    use Adianti\Base\AdiantiStandardFormTrait; // Standard form methods
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();        
        
        $this->setDatabase(TSession::getValue('unit_database'));              // defines the database
        $this->setActiveRecord('Pessoas');     // defines the active record
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Pessoas');
        $this->form->setFormTitle(_t('Person'));
        $this->form->setClientValidation(TRUE);        

        // create the form fields
        $id = new TNumeric('id', 0, ',', '.');
        $tipo = new TRadioGroup('tipo');
        $cpfcnpj = new TEntry('cpfcnpj');
        $nome = new TEntry('nome');
        $grupos_pessoa = new TDBCheckGroup('grupos_pessoa', TSession::getValue('unit_database'), 'GruposPessoa', 'id', 'descricao', 'descricao');
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
        $tipo_residencia_id = new TDBUniqueSearch('tipo_residencia_id', TSession::getValue('unit_database'), 'TiposResidencia', 'id', 'descricao', 'descricao');
        $tempo_residencia = new TEntry('tempo_residencia');        
        $dtcadastro = new TDate('dtcadastro');
        $status = new TRadioGroup("status");
        $dtnasc = new TDate('dtnasc');
        $nacionalidade_id = new TDBCombo('nacionalidade_id',TSession::getValue('unit_database'),'Nacionalidades','id','descricao','descricao');
        $cidadenacto = new TDBEntry('cidadenacto', 'cep', 'Cidades', 'nome', 'nome');
        $estadonacto = new TDBCombo('estadonacto', 'cep', 'Estados', 'sigla', 'sigla', 'sigla');
        $paisnacto = new TDBCombo('paisnacto', 'cep', 'Paises', 'sigla', 'nome', 'nome');
        $nomepais = new TEntry('nomepais');
        $sexo = new TRadioGroup('sexo');        
        $estado_civil_id = new TDBCombo('estado_civil_id', TSession::getValue('unit_database'), 'EstadosCivis', 'id', 'descricao', 'descricao');
        $conjuge_id = new TDBUniqueSearch('conjuge_id', TSession::getValue('unit_database'), 'Pessoas', 'id', 'nome', 'nome');
        $rg = new TEntry('rg');
        $orgaorg = new TEntry('orgaorg');
        $estadorg_id = new TDBCombo('estadorg_id','cep','Estados','id','sigla','sigla');
        $dtemissaorg = new TDate('dtemissaorg');
        $local_trabalho_id = new TDBUniqueSearch('local_trabalho_id', TSession::getValue('unit_database'), 'LocaisTrabalho', 'id', 'nome', 'nome');
        $profissao_id = new TDBUniqueSearch('profissao_id', TSession::getValue('unit_database'), 'Profissoes', 'id', 'descricao', 'descricao');
        $rendamensal = new TNumeric('rendamensal', 2, ',', '.');
        $ie = new TEntry('ie');
        $im = new TEntry('im');
        $junta_comercial = new TEntry('junta_comercial');
        $ramo_atividade_id = new TDBUniqueSearch('ramo_atividade_id', TSession::getValue('unit_database'), 'RamosAtividade', 'id', 'descricao', 'descricao');
        $forma_pagamento_id = new TDBCombo('forma_pagamento_id', TSession::getValue('unit_database'), 'FormasPagamento', 'id', 'descricao', 'descricao');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('grupos_pessoa', 'ike', '%VENDEDOR%'));
        $observacao = new TText('observacao');
        
        $action = new TAction( [ 'PessoasWindow', 'onSetup' ] );
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
        $button->class = 'btn btn-default inline-button';
        $button->title = _t('New');
        $tipo_residencia_id->after($button);
        
        $this->form->appendPage(_t('Person'));

        $this->form->addFields( [ new TLabel(_t('Id')) ], [ $id ], [ new TLabel(_t('Type'), 'red') ], [ $tipo ] );
        $this->form->addFields( [ new TLabel('CPF/CNPJ', 'red') ], [ $cpfcnpj ], [ new TLabel(_t('Name'), 'red') ], [ $nome ] );
        $this->form->addFields( [ new TLabel(_t('Groups'), 'red') ], [ $grupos_pessoa ] );
        $this->form->addFields( [ new TLabel(_t('Phone') . ' 1') ], [ $fone1 ], [ new TLabel(_t('Phone') . ' 2') ], [ $fone2 ] );
        $this->form->addFields( [ new TLabel(_t('Phone') . ' 3') ], [ $fone3 ], [ new TLabel(_t('Email')) ], [ $email ] );
        $this->form->addFields( [ new TLabel(_t('Site')) ], [ $site ], [ new TLabel(_t('ZIP')) ], [ $cep ] );
        $this->form->addFields( [ new TLabel(_t('Street')) ], [ $rua ], [ new TLabel(_t('Number') . '/' . _t('Complement')) ], [ $numero, $complemento ] );
        $this->form->addFields( [ new TLabel(_t('Neighborhood')) ], [ $bairro ], [ new TLabel(_t('City') . '/' . _t('State')) ], [ $cidade, $estado ] );
        $this->form->addFields( [ new TLabel(_t('Landmark')) ], [ $ponto_referencia ], [ new TLabel(_t('Residence Type')) ], [ $tipo_residencia_id ] );
        $this->form->addFields( [ new TLabel(_t('Residence Time')) ], [ $tempo_residencia ], [ new TLabel(_t('Register Date')) ], [ $dtcadastro ] );
        $this->form->addFields( [ new TLabel("Status", 'red') ], [ $status ] );

        // set sizes
        $id->setSize(100);
        $tipo->setSize('100%');
        $cpfcnpj->setSize(150);
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
        $tempo_residencia->setSize(150);
        $dtcadastro->setSize(100);
        $status->setSize(100);
        $dtnasc->setSize(100);
        $nacionalidade_id->setSize('100%');
        $cidadenacto->setSize('80%');
        $estadonacto->setSize('20%');
        $paisnacto->setSize('100%');
        $nomepais->setSize('100%');
        $sexo->setSize('100%');
        $estado_civil_id->setSize('100%');
        $conjuge_id->setSize('calc(100% - 30px)');
        $rg->setSize(100);
        $orgaorg->setSize(100);
        $estadorg_id->setSize(75);
        $dtemissaorg->setSize(100);
        $local_trabalho_id->setSize('calc(100% - 30px)');
        $profissao_id->setSize('calc(100% - 30px)');
        $rendamensal->setSize(100);
        $ie->setSize(150);
        $im->setSize(150);
        $junta_comercial->setSize(150);
        $ramo_atividade_id->setSize('calc(100% - 30px)');
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
        $cpfcnpj->addValidation('CPF/CNPJ', new TRequiredValidator);
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
        $status->addItems( ['S' => 'SIM', 'N' => 'NÃO'] );
        $status->setLayout('horizontal');
        $status->setUseButton();
        
        $this->form->appendPage(_t('Physical Person'));
        
        $this->form->addFields( [ new TLabel(_t('Birth Date')) ], [ $dtnasc ], [ new TLabel(_t('Gender')) ], [ $sexo ] );
        $this->form->addFields( [ new TLabel(_t('Nationality')) ], [ $nacionalidade_id ], [ new TLabel(_t('City/State of Birth')) ], [ $cidadenacto,$estadonacto ] );
        $this->form->addFields( [ new TLabel(_t('Country of Birth')) ], [ $paisnacto ], [ new TLabel(_t('Parent\'s Name')) ], [ $nomepais ] );
        $this->form->addFields( [ new TLabel(_t('Civil Status')) ], [ $estado_civil_id ], [ new TLabel(_t('Partner')) ], [ $conjuge_id ] );
        $this->form->addFields( [ new TLabel('RG') ], [ $rg, new TLabel(_t('Part')), $orgaorg, new TLabel('UF'), $estadorg_id ], [ new TLabel(_t('Issue Date')) ], [ $dtemissaorg ] );
        $this->form->addFields( [ new TLabel(_t('Occupation')) ], [ $profissao_id ], [ new TLabel(_t('Work Place')) ], [ $local_trabalho_id ] );
        $this->form->addFields( [ new TLabel(_t('Monthly Income')) ], [ $rendamensal ] );
        
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
        $representantes_representante_id = new TDBUniqueSearch('representantes_representante_id', TSession::getValue('unit_database'), 'Pessoas', 'id', 'nome', 'nome');
        
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
        
        /*$this->form->appendPage(_t('Payment Information'));
        
        $this->form->addFields( [ new TLabel(_t('Payment Method')) ], [ $forma_pagamento_id ], [ new TLabel(_t('Bank')) ], [ $banco_id ] );
        $this->form->addFields( [ new TLabel(_t('Account Type')) ], [ $tipo_conta_id ], [ new TLabel(_t('Agency')) ], [ $agencia ] );
        $this->form->addFields( [ new TLabel(_t('Account') . '/' . _t('Digit')) ], [ $conta, $digito ], [ new TLabel(_t('Operation')) ], [ $operacao ] );
        
        $forma_pagamento_id->setChangeAction(new TAction(array($this, 'onChangeFormaPagamento')));
        $agencia->setMask('9!');
        $conta->setMask('9!');
        $operacao->setMask('9!');*/
        
        $this->form->appendPage(_t('Observation'));
        
        $this->form->addFields( [ $observacao ] );
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave'], ['static' => '1']), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction([$this, 'onEdit']), 'fa:plus green');
        $this->form->addActionLink( _t('Back'), new TAction(array('PessoasList','onReload')),  'far:arrow-alt-circle-left blue' );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'PessoasList'));
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
            if( !empty($data->grupos_pessoa) )
            {
                foreach( $param['grupos_pessoa'] as $grupo )
                {
                    $object->addGruposPessoa( new GruposPessoa($grupo) );
                }
                
                $object->store();
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
                if( $param['Representantes_list_representante_id'] )
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
                
                $items = PessoasGruposPessoa::where('pessoa_id', '=', $key)->load();
                if ($items)
                {
                    $grupos = [];
                    foreach ($items as $item)
                    {
                        $grupos[] = $item->grupo_pessoa_id;
                    }
                    $object->grupos_pessoa = $grupos;
                }
                
                $items  = Representantes::where('pessoa_id', '=', $key)->load();
                
                if ($items)
                {
                    foreach( $items as $item )
                    {
                        $pessoa = new Pessoas($item->representante_id);
                        
                        $item->uniqid = uniqid();
                        $item->representante_nome = $pessoa->nome;
                        $row = $this->representantes_list->addItem( $item );
                        $row->id = $item->uniqid;
                    }
                }
                                                
                $this->onChangeTipo( array('tipo' => $object->tipo, 'cpfcnpj' => $object->cpfcnpj, 'nome' => $object->nome) );
                $this->onChangeNacionalidade(array('nacionalidade_id' => $object->nacionalidade));
                $this->onChangeFormaPagamento(array('forma_pagamento_id' => $object->forma_pagamento_id));
                
                $this->form->setData($object);
                
                TTransaction::close();
            }
            else
            {
                $this->form->clear();
                
                $obj = new StdClass;
                $obj->tipo = 'F';
                $obj->dtcadastro = date('Y-m-d');
                $obj->status = "S";
                  
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
            
            TDBUniqueSearch::enableField('form_Pessoas', 'tipo_residencia_id');
            TScript::create('$(\'button[name=new_tipo_residencia]\').show();');    
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
            
            TDBUniqueSearch::disableField('form_Pessoas', 'tipo_residencia_id');
            TScript::create('$(\'button[name=new_tipo_residencia]\').hide();');    
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
                TDBUniqueSearch::enableField('form_Pessoas', 'banco_id');
                TDBCombo::enableField('form_Pessoas', 'tipo_conta_id');
                TEntry::enableField('form_Pessoas', 'agencia');
                TEntry::enableField('form_Pessoas', 'conta');
                TEntry::enableField('form_Pessoas', 'operacao');
            }
            else
            {
                TDBUniqueSearch::disableField('form_Pessoas', 'banco_id');
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
            
            if (!isset($param['Representantes_list_representante_id']))
            {
                $errors[] = _t('There must be at least one agent.');
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
}
