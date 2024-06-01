<?php

use Adianti\Widget\Form\TCombo;

/**
 * RelAniversariantes Listing
 * @author  <your name here>
 */
class RelAniversariantes extends TPage
{
    private $form; // form
    private $meses;

    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();

        $this->meses = [
            '01' => _t('January'),
            '02' => _t('February'),
            '03' => _t('March'),
            '04' => _t('April'),
            '05' => _t('May'),
            '06' => _t('June'),
            '07' => _t('July'),
            '08' => _t('August'),
            '09' => _t('September'),
            '10' => _t('October'),
            '11' => _t('November'),
            '12' => _t('December')
        ];

        // creates the form
        $this->form = new BootstrapFormBuilder('form_Customer_report');

        // create the form fields
        $mes         = new TCombo('mes');
        $grupos_pessoa = new TDBRadioGroup('grupos_pessoa', TSession::getValue('unit_database'), 'GruposPessoa', 'descricao', 'descricao', 'descricao');
        $output_type  = new TRadioGroup('output_type');

        $this->form->addFields( [new TLabel(_t('Month'))], [$mes] );
        $this->form->addFields( [new TLabel(_t('Group of People'))], [$grupos_pessoa] );
        $this->form->addFields( [new TLabel(_t('Output'))], [$output_type] );

        // define field properties
        $mes->setSize( 200 );
        $mes->addItems( $this->meses );
        $grupos_pessoa->setLayout('horizontal');
        $grupos_pessoa->setUseButton(TRUE);
        $options = ['html' =>'HTML', 'pdf' =>'PDF', 'rtf' =>'RTF', 'xls' =>'XLS'];
        $output_type->addItems($options);
        $output_type->setValue('pdf');
        $output_type->setLayout('horizontal');
        $output_type->setUseButton(true);

        $this->form->addAction( _t('Generate'), new TAction(array($this, 'onGenerate')), 'fa:print blue');

        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        
        parent::add($vbox);
    }
    /**
     * method onGenerate()
     * Executed whenever the user clicks at the generate button
     */
    function onGenerate()
    {
        try
        {
            // open a transaction with database 'samples'
            TTransaction::open(TSession::getValue('unit_database'));

            // get the form data into
            $data = $this->form->getData();

            $repository = new TRepository('Pessoas');
            $criteria   = new TCriteria;
            $filters = [];
            if ($data->mes)
            {
                $criteria->add(new TFilter("strftime('%m', dtnasc)", '=', "{$data->mes}"));
                $filters[] = _t("Month") . ": " . $this->meses[$data->mes];
            }

            if ($data->grupos_pessoa)
            {
                $criteria->add(new TFilter("grupos_pessoa", 'LIKE', "{$data->grupos_pessoa}"));
                $filters[] = _t("Group of People") . ": " . $data->grupos_pessoa;
            }

            $criteria->add(new TFilter('dtnasc', '<>', "''"));

            $criteria->setProperty('order', "strftime('%m', dtnasc), strftime('%d', dtnasc)");
            $objects = $repository->load($criteria);
            $format  = $data->output_type;

            if ($objects)
            {
                $widths = array(420, 100);

                switch ($format)
                {
                    case 'html':
                        $table = new TTableWriterHTML($widths);
                        break;
                    case 'pdf':
                        $table = new TTableWriterPDF($widths);
                        break;
                    case 'rtf':
                        $table = new TTableWriterRTF($widths);
                        break;
                    case 'xls':
                        $table = new TTableWriterXLS($widths);
                        break;
                }
                
                if (!empty($table))
                {
                    // create the document styles
                    $table->addStyle('header',  'Helvetica', '16', 'B', '#000000', '#ffffff', '');
                    $table->addStyle('titlef',  'Helvetica', '10', 'B', '#000000', '#ffffff', '');
                    $table->addStyle('filters', 'Helvetica', '10', '',  '#000000', '#ffffff', '');
                    $table->addStyle('title',   'Helvetica', '10', 'B', '#ffffff', '#617FC3', '');
                    $table->addStyle('datap',   'Helvetica', '10', '',  '#000000', '#E3E3E3', '');
                    $table->addStyle('datai',   'Helvetica', '10', '',  '#000000', '#ffffff', '');
                    $table->addStyle('footer',  'Helvetica', '10', 'B',  '#2B2B2B','#B4CAFF', '');

                    $table->addRow();
                    $table->addCell(_t("Report of") . " " . _t("Birthdays"), 'center', 'header', 2);
                    $table->addRow();
                    $table->addRow();
                    if (sizeof($filters))
                    {
                        $table->addCell(_t("Filters Used") . ":", 'left', 'titlef');
                    }
                    $table->addCell(_t("Date") . ": " . date('d/m/Y h:i:s'), 'rigth', 'titlef');


                    if (sizeof($filters))
                    {
                        foreach($filters as $filter)
                        {
                            $table->addRow();
                            $table->addCell($filter, 'left', 'filters', 2);
                        }
                    }

                    $table->addRow();

                    $table->setHeaderCallback( function($table) {
                        $table->addRow();
                        $table->addCell(_t('Person'), 'left', 'title');
                        $table->addCell(_t('Birth Date'), 'center', 'title');
                    });

                    // controls the background filling
                    $colour= FALSE;

                    // data rows
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';
                        $table->addRow();
                        $table->addCell($object->nome, 'left', $style);
                        $table->addCell(TDate::date2br($object->dtnasc), 'center', $style);

                        $colour = !$colour;
                    }

                    $output = "app/output/RelAniversariantes.{$format}";

                    // stores the file
                    if (!file_exists($output) OR is_writable($output))
                    {
                        $table->save($output);
                        parent::openFile($output);
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied') . ': ' . $output);
                    }
                }
            }
            else
            {
                new TMessage('error', AdiantiCoreTranslator::translate('No records found'));
            }

            // fill the form with the active record data
            $this->form->setData($data);

            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
