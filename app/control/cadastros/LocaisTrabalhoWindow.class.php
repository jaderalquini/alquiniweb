<?php
/**
 * LocaisTrabalhoWindow Registration
 * @author  <your name here>
 */
class LocaisTrabalhoWindow extends TWindow
{
    protected $form; // form
    
    use Adianti\Base\AdiantiStandardFormTrait; // Standard form methods

    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        parent::setTitle( AdiantiCoreTranslator::translate('Work Place') . ': ' .
                          AdiantiCoreTranslator::translate('New Record') );
        parent::setSize(0.7, null);        
        
        $this->setDatabase(TSession::getValue('unit_database'));              // defines the database
        $this->setActiveRecord('LocaisTrabalho');     // defines the active record
        $this->form->setClientValidation(TRUE);
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_LocaisTrabalho');
        $this->form->setClientValidation(TRUE);

        // create the form fields
        $id = new TNumeric('id', 0, ',', '.');
        $nome = new TEntry('nome');
        $fone = new TEntry('fone');
        $cep = new TEntry('cep');
        $rua = new TDBEntry('rua', 'cep', 'Ruas', 'id', 'nome');
        $numero = new TEntry('numero');
        $complemento = new TEntry('complemento');
        $bairro = new TDBEntry('bairro', 'cep', 'Bairros', 'id', 'nome');
        $cidade = new TDBEntry('cidade', 'cep', 'Cidades', 'id', 'nome');
        $estado = new TDBCombo('estado', 'cep', 'Estados', 'id', 'sigla', 'sigla');

        // add the fields
        $this->form->addFields( [ new TLabel(_t('Id')) ], [ $id ], [ new TLabel(_t('Name'), 'red') ], [ $nome ] );
        $this->form->addFields( [ new TLabel(_T('Phone')) ], [ $fone ], [ new TLabel(_t('ZIP')) ], [ $cep ] );
        $this->form->addFields( [ new TLabel(_t('Street')) ], [ $rua ], [ new TLabel(_t('Number') . '/' . _t('Complement')) ], [ $numero, $complemento ] );
        $this->form->addFields( [ new TLabel(_t('Neighborhood')) ], [ $bairro ], [ new TLabel(_t('City') . '/' . _t('State')) ], [ $cidade, $estado ] );

        // set sizes
        $id->setSize(100);
        $nome->setSize('100%');
        $fone->setSize(100);
        $cep->setSize(100);
        $rua->setSize('calc(100% - 30px)');
        $numero->setSize('20%');
        $complemento->setSize('80%');
        $bairro->setSize('100%');
        $cidade->setSize('80%');
        $estado->setSize('20%');

        $id->setEditable(FALSE);
        $nome->autofocus = 'autofocus';
        $nome->forceUpperCase(); 
        $fone->class = 'phones';
        $cep->setMask('99999-999');
        $cep->setExitAction(new TAction( [ $this, 'onExitCep' ] ));

        // validations
        $nome->addValidation(_t('Name'), new TRequiredValidator);

        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
    
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction([$this, 'onEdit']), 'fa:plus green');
        $this->form->addActionLink( _t('Back'), new TAction(array('LocaisTrabalhoList','onReload')),  'far:arrow-alt-circle-left blue' );

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'LocaisTrabalhoList'));
        $container->add($this->form);

        $script = new TElement('script');
        $script->type = 'text/javascript';
        $script->add("var maskBehavior = function (val) { 
                            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009'; 
                         }, options = {onKeyPress: function(val, e, field, options) { 
                             field.mask(maskBehavior.apply({}, arguments), options); } 
                         }; $('.phones').mask(maskBehavior, options);");
        parent::add($script);

        parent::add($container);
    }
    
    public function onSave()
    {
        try
        {
            // open a transaction with database
            TTransaction::open($this->database);

            // get the form data
            $object = $this->form->getData($this->activeRecord);

            // validate data
            $this->form->validate();

            // stores the object
            $object->store();

            // fill the form with the active record data
            $this->form->setData($object);

            // close the transaction
            TTransaction::close();

            TSession::setValue(__CLASS__.'_object', $object);

            // shows the success message
            if (isset($this->useMessages) AND $this->useMessages === false)
            {
                AdiantiCoreApplication::loadPageURL( $this->afterSaveAction->serialize() );
            }
            else
            {
                new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $this->afterSaveAction);
            }
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
    
    public static function onExitRua($param)
    {
        try
        {
            TTransaction::open('cep');
            
            if (isset($param['rua_id'])&&isset($param['field_name'])&&$param['field_name']=='rua_id')
            {
                $rua_id = $param['rua_id'];
                
                $objects = Ruas::where('id', '=', $rua_id)->load();
                $count = sizeof($objects);
                
                if ($objects&&$count==1)
                {
                    foreach ($objects as $object)
                    {                    
                        $obj = new StdClass;
                        $obj->cep = $object->cep;
                        $obj->bairro_id = $object->bairro_id;
                        $obj->cidade_id = $object->cidade_id;
                        $obj->estado_id = $object->estado_id;
                        $obj->pais_id = $object->pais_id;
                        
                        TForm::sendData('form_LocaisTrabalho', $obj);
                    }
                }
            }

            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    public function onSetup($param=NULL)
    {
        TSession::setValue(__CLASS__.'_receive_form', $param['receive_form']);
        TSession::setValue(__CLASS__.'_receive_fields', $param['receive_fields']);

        $this->onEdit($param);
    }
    
    public function onClose()
    {
        if (!empty(TSession::getValue(__CLASS__.'_receive_form'))&&!empty(TSession::getValue(__CLASS__.'_receive_fields')))
        {
            $receive_form = TSession::getValue(__CLASS__.'_receive_form');
            $receive_fields = TSession::getValue(__CLASS__.'_receive_fields');
            
            $object = TSession::getValue(__CLASS__.'_object');
            $obj = new StdClass;
            
            foreach ($receive_fields as $receive => $send)
            {
                $obj->$receive = $object->$send;
            }

            TForm::sendData($receive_form, $obj);
        }
        
        parent::closeWindow();
    }
}
