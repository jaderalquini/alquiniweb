<?php
/**
 * DespesasForm Registration
 * @author  <your name here>
 */
class DespesasForm extends TPage
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
        $this->setActiveRecord('MovimentosCaixa');     // defines the active record

        // creates the form
        $this->form = new BootstrapFormBuilder('form_Despesas');
        $this->form->setFormTitle(_t('Expense'));
        $this->form->enableClientValidation();

        // create the form fields
        $id = new TNumeric('id', 0, ',', '.');
        $tipo = new THidden('tipo');
        $descricao = new TEntry('descricao');
        $pessoa_id = new TDBUniqueSearch('pessoa_id[]', TSession::getValue('unit_database'), 'Pessoas', 'id', 'nome', 'nome');
        $nrdoc = new TNumeric('nrdoc', 0, ',', '.');
        $tipo_pagamento_id = new TDBCombo('tipo_pagamento_id', TSession::getValue('unit_database'), 'TiposPagamento', 'id', 'descricao', 'descricao');
        $parcela = new TNumeric('parcela', 0, ',', '.');
        $totparcelas = new TNumeric('totparcelas', 0, ',', '.');
        $dtvencto = new TDate('dtvencto');
        $valor = new TNumeric('valor', 2, ',', '.');
        $desconto = new TNumeric('desconto', 2, ',', '.');
        $juros = new TNumeric('juros', 2, ',', '.');
        $multa = new TNumeric('multa', 2, ',', '.');
        $dtpagto = new TDate('dtpagto');
        $vlpago = new TNumeric('vlpago', 2, ',', '.');
        $forma_pagamento_id = new TDBCombo('forma_pagamento_id', TSession::getValue('unit_database'), 'FormasPagamento', 'id', 'descricao', 'descricao');
        $numrecibo = new TNumeric('numrecibo', 0, ',', '.');
        $observacoes = new TText('observacoes');
        $liquidado = new TRadioGroup('liquidado');

        // add the fields
        $this->form->addFields( [ new TLabel(_t('Id')) ], [ $id, $tipo ], [ new TLabel(_t('Description'), 'red') ], [ $descricao ] );
        $this->form->addFields( [ new TLabel(_t('Creditor'), 'red') ], [ $pessoa_id ], [ new TLabel(_t('Document Number')) ], [ $nrdoc ] );
        $this->form->addFields( [ new TLabel(_t('Payment Type'), 'red')  ], [ $tipo_pagamento_id  ], [  new TLabel(_t('Duo Date'), 'red') ], [ $dtvencto  ] );
        $this->form->addFields( [ new TLabel(_t('Parcel'))  ], [ $parcela  ], [ new TLabel(_t('Total Payments'))  ], [ $totparcelas  ] );
        $this->form->addFields( [ new TLabel(_t('Value'), 'red')  ], [ $valor  ], [ new TLabel(_t('Discount'))  ], [ $desconto  ] );
        $this->form->addFields( [ new TLabel(_t('Fees'))  ], [ $juros  ], [ new TLabel(_t('Penalty'))  ], [ $multa  ] );
        $this->form->addFields( [ new TLabel(_t('Payday'))  ], [ $dtpagto  ], [ new TLabel(_t('Amount Paid'))  ], [ $vlpago  ] );
        $this->form->addFields( [ new TLabel(_t('Payment Method'))  ], [ $forma_pagamento_id  ], [ new TLabel(_t('Receipt Number'))  ], [ $numrecibo ] );
        $this->form->addFields( [ new TLabel(_t('Observation'))  ], [ $observacoes  ], [ new TLabel(_t('Liquidated'))  ], [ $liquidado  ] );

        // set sizes
        $id->setSize(100);
        $tipo->setValue("S");
        $descricao->setSize('100%');
        $pessoa_id->setSize('100%');
        $nrdoc->setSize(200);
        $tipo_pagamento_id->setSize('100%');
        $dtvencto->setSize(150);
        $dtvencto->setMask('dd/mm/yyyy');
        $dtvencto->setDatabaseMask('yyyy-mm-dd');
        $parcela->setSize(100);
        $totparcelas->setSize(100);
        $valor->setSize(200);
        $desconto->setSize(200);
        $juros->setSize(200);
        $multa->setSize(200);
        $dtpagto->setSize(150);
        $dtpagto->setMask('dd/mm/yyyy');
        $dtpagto->setDatabaseMask('yyyy-mm-dd');
        $vlpago->setSize(200);
        $forma_pagamento_id->setSize('100%');
        $numrecibo->setSize(200);
        $observacoes->setSize('100%');
        $liquidado->setSize('100%');
        $liquidado->addItems( [ 'S' => _t('Yes'), 'N' => _t('No') ]);
        $liquidado->setLayout('horizontal');
        $liquidado->setUseButton();

        $id->setEditable(FALSE);
        
        $descricao->autofocus = 'autofocus';
        $descricao->forceUpperCase();

        // validations
        $descricao->addValidation(_t('Description'), new TRequiredValidator);
        $pessoa_id->addValidation(_t('Creditor'), new TRequiredValidator);
        $tipo_pagamento_id->addValidation(_t('Payment Type'), new TRequiredValidator);
        $dtvencto->addValidation(_t('Duo Date'), new TRequiredValidator);
        $valor->addValidation(_t('Value'), new TRequiredValidator);

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
        //$container->add(new TXMLBreadCrumb('menu.xml', 'EstadosCivisList'));
        $container->add($this->form);

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

            $this->afterSaveAction = new TAction(['DespesasList', 'onReload']);

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

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];

                TTransaction::open(TSession::getValue('unit_database'));

                $object = new MovimentosCaixa($key);
                
                $this->form->setData($object);
                
                TTransaction::close();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }

    public function onValidate($param)
    {
        if (!empty(trim($param['dtvencto'])))
        {
            $dtvencto->addValidation(_t('Duo Date'), new TDateValidator);
        }

        if (!empty(trim($param['dtpagto'])))
        {
            $dtpagto->addValidation(_t('Payday'), new TDateValidator);
        }

        $this->form->validate();
    }
}