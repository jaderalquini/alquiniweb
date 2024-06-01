<?php
/**
 * LocaisTrabalhoForm Registration
 * @author  <your name here>
 */
class LocaisTrabalhoForm extends TPage
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

        parent::setTargetContainer('adianti_right_panel');

        $this->setDatabase(TSession::getValue('unit_database'));              // defines the database
        $this->setActiveRecord('LocaisTrabalho');     // defines the active record

        // creates the form
        $this->form = new BootstrapFormBuilder('form_LocaisTrabalho');
        $this->form->setFormTitle(_t('Work Place'));
        $this->form->enableClientValidation();

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
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');

        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        //$container->add(new TXMLBreadCrumb('menu.xml', 'LocaisTrabalhoList'));
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

    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
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

            $this->afterSaveAction = new TAction(['LocaisTrabalhoList', 'onReload']);

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
            $object = $this->form->getData($this->activeRecord);
            
            // fill the form with the active record data
            $this->form->setData($object);
            
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
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
            
            if (isset($param['rua'])&&isset($param['field_name'])&&$param['field_name']=='rua')
            {
                $rua = $param['rua'];

                $objects = Ruas::where('id', '=', $rua)->load();
                $count = sizeof($objects);
                
                if ($objects&&$count==1)
                {
                    foreach ($objects as $object)
                    {
                        $obj = new StdClass;
                        $obj->cep = $object->cep;
                        $obj->bairro = $object->bairro;
                        $obj->cidade = $object->cidade;
                        $obj->estado = $object->estado;
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
}
