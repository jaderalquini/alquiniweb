<?php

use Adianti\Widget\Dialog\TMessage;

/**
 * AWSystemUnitForm Registration
 * @author  <your name here>
 */
class AWSystemUnitForm extends TPage
{
    protected $form; // form
    
    use Adianti\Base\AdiantiStandardFormTrait; // Standard form methods
    use Adianti\Base\AdiantiFileSaveTrait;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('permission_aux');              // defines the database
        $this->setActiveRecord('AWSystemUnit');     // defines the active record
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_AWSystemUnit');
        $this->form->setFormTitle(_t('Unit Data'));

        // create the form fields
        $id = new THidden('id');
        $name = new TEntry('name');
        $cnpj = new TEntry('cnpj');
        $fone = new TEntry('fone');
        $site = new TEntry('site');
        $email = new TEntry('email');
        $cep = new TEntry('cep');
        $rua = new TEntry('rua');
        $numero = new TNumeric('numero', 0, '', '.');
        $complemento = new TEntry('complemento');
        $bairro = new TEntry('bairro');
        $cidade = new TDBEntry('cidade', 'cep', 'Cidades', 'nome', 'nome');
        $estado = new TDBCombo('estado', 'cep', 'Estados', 'sigla', 'sigla', 'sigla');
        $logo = new TFile('logo');

        // add the fields
        $this->form->addFields( [ new TLabel(_t('Name')) ],  [ $id, $name ], [ new TLabel('CNPJ') ], [ $cnpj ] );
        $this->form->addFields( [ new TLabel(_t('Phone')) ], [ $fone ], [ new TLabel(_t('Site')) ], [ $site ] );
        $this->form->addFields( [ new TLabel(_t('Email')) ], [ $email ], [ new TLabel(_t('ZIP')) ], [ $cep ] );
        $this->form->addFields( [ new TLabel(_t('Street')) ], [ $rua ], [ new TLabel(_t('Number') . '/' . _t('Complement')) ], [ $numero, $complemento ] );
        $this->form->addFields( [ new TLabel(_t('Neighborhood')) ], [ $bairro ], [ new TLabel(_t('City') . '/' . _t('State')) ], [ $cidade, $estado ] );
        $this->form->addFields( [ new TLabel('Logo') ], [ $logo ], [], [] );

        // set sizes
        $name->setSize('100%');
        $cnpj->setSize(150);
        $fone->setSize(150);
        $site->setSize('100%');
        $email->setSize('100%');
        $cep->setSize(100);
        $rua->setSize('100%');
        $numero->setSize('20%');
        $complemento->setSize('80%');
        $bairro->setSize('100%');
        $cidade->setSize('80%');
        $estado->setSize('20%');
        $logo->setSize('100%');

        $name->autofocus = 'autofocus';
        $name->forceUpperCase();
        $cnpj->setMask('99.999.999/9999-99');
        $fone->setMask('(99) 9999-9999');
        $site->forceLowerCase();
        $email->forceLowerCase();
        $cep->setMask('99999-999');
        $cep->setExitAction(new TAction( [ $this, 'onExitCep' ] ));
        $complemento->forceUpperCase();
        $cidade->setExitAction(new TAction( [ $this, 'onExiCidade' ] ));
        $estado->enableSearch(TRUE);
        $logo->setAllowedExtensions( ['gif', 'png', 'jpg', 'jpeg'] );
        $logo->enableFileHandling();
        $logo->enablePopover(_t('Preview'), '<img style="max-width:300px" src="download.php?file={file_name}">');

        $name->addValidation(_t('Name'), new TRequiredValidator);

        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/

        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);

        parent::add($container);
    }

    public static function onExitCep($param)
    {
        $cep = str_replace('-', '', $param['cep']);
        TScript::create('$(document).ready(function() {
                           cep = ' . $cep . ';
                           $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                               if (!("erro" in dados)) {
                                   $("input[name=rua]").val(dados.logradouro.toUpperCase());
                                   $("input[name=bairro]").val(dados.bairro.toUpperCase());
                                   $("input[name=cidade]").val(dados.localidade.toUpperCase());
                                   $("select[name=estado]").val(dados.uf);
                               }
                           });
        })');
    }

    public static function onExiCidade($param)
    {
        try
        {
            if (isset($param['cidade'])&&!empty($param['cidade']))
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
        }
        catch (Exception $e) // in case of exception
        {            
            // shows the exception error message
            new TMessage('error', $e->getMessage());
        }
    }

    public function onSave($param)
    {
        try
        {     
            TTransaction::open('permission');
            $system_unit = SystemUnit::find($param['id']);
            $system_unit->name = $param['name'];
            $system_unit->store();
            TTransaction::close();
               
            // open a transaction with database
            TTransaction::open('permission_aux');
            
            $data = $this->form->getData();
            
            // get the form data
            $object = $this->form->getData($this->activeRecord);
            
            // validate data
            $this->form->validate();
            
            // stores the object
            $object->store();
            
            $this->saveFile($object, $data, 'logo', 'files/images/system_unit_data');
            
            // fill the form with the active record data
            $this->form->setData($object);
            
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
            // get the form data
            $object = $this->form->getData();
            
            // fill the form with the active record data
            $this->form->setData($object);
            
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onEdit($param)
    {
        try
        {
            // get the parameter $key
            $key=TSession::getValue('userunitid');

            TTransaction::open('permission');
            $system_unit = SystemUnit::find($key);
            TTransaction::close();

            // open a transaction with database
            TTransaction::open($this->database);
                
            $class = $this->activeRecord;

            // instantiates object
            $object = new $class($key);
            $object->id = $system_unit->id;
            $object->name = $system_unit->name;

            // fill the form with the active record data
            $this->form->setData($object);

            // close the transaction
            TTransaction::close();

            return $object;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }
}
