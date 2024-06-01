<?php

use Adianti\Widget\Form\TDate;
use Adianti\Widget\Util\TImage;

/**
 * RelAgendamentos Listing
 * @author  <your name here>
 */
class RelAgendamentos extends TPage
{
    private $form; // form

    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder('form_Customer_report');

        // create the form fields
        $dtini = new TDate('dtini');
        $dtfim = new TDate('dtfim');
        $pessoa_id = new TDBUniqueSearch('pessoa_id', TSession::getValue('unit_database'), 'Pessoas', 'id', 'nome', 'nome');
        $terapia_id = new TDBCombo('terapia_id', TSession::getValue('unit_database'), 'Terapias', 'id', 'nome', 'nome');
        $output_type  = new TRadioGroup('output_type');

        // add the fields
        $this->form->addFields( [new TLabel(_t('Initial Date'))], [$dtini] );
        $this->form->addFields( [new TLabel(_t('Final Date'))], [$dtfim] );
        $this->form->addFields( [new TLabel(_t('Person'))], [$pessoa_id] );
        $this->form->addFields( [new TLabel(_t('Therapy'))], [$terapia_id] );
        $this->form->addFields( [new TLabel(_t('Output'))], [$output_type] );

        $dtini->setSize(150);
        $dtini->setMask('dd/mm/yyyy');
        $dtini->setDatabaseMask('yyyy-mm-dd');
        $dtfim->setSize(150);
        $dtfim->setMask('dd/mm/yyyy');
        $dtfim->setDatabaseMask('yyyy-mm-dd');
        $pessoa_id->setSize('100%');
        $terapia_id->setSize('100%');
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
            
            $repository = new TRepository('TerapiasAgendamentos');
            $criteria   = new TCriteria;
            $filters = [];
            if ($data->dtini)
            {
                $criteria->add(new TFilter('dtagendamento', '>=', "{$data->dtini}"));
                $filters[] = _t("Date from") . ": " . TDate::date2br($data->dtini);
            }

            if ($data->dtfim)
            {
                $criteria->add(new TFilter('dtagendamento', '<=', "{$data->dtfim}"));
                $filters[] = _t("Date until") . ": " . TDate::date2br($data->dtfim);
            }

            if ($data->pessoa_id)
            {
                $criteria->add(new TFilter('pessoa_id', '=', $data->pessoa_id));
                $pessoa = new Pessoas($data->pessoa_id);
                $filters[] = _t("Person") . ": " . $pessoa->nome;
            }

            if ($data->terapia_id)
            {
                $criteria->add(new TFilter('terapia_id', '=', $data->terapia_id));
                $terapia = new Terapias($data->terapia_id);
                $filters[] = _t("Therapy") . ": " . $terapia->nome;
            }

            $objects = $repository->load($criteria);
            $count = $repository->count($criteria);
            $format  = $data->output_type;

            if ($objects)
            {
                $widths = array(60, 200, 200, 70);

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
                    $table->addCell(_t("Report of") . " " . _t("Schedules"), 'center', 'header', 4);
                    $table->addRow();
                    $table->addRow();
                    if (sizeof($filters))
                    {
                        $table->addCell(_t("Filters Used") . ":", 'left', 'titlef', 2);
                    }
                    $table->addCell(_t("Date") . ": " . date('d/m/Y h:i:s'), 'rigth', 'titlef', 2);


                    if (sizeof($filters))
                    {
                        foreach($filters as $filter)
                        {
                            $table->addRow();
                            $table->addCell($filter, 'left', 'filters', 4);
                        }
                    }

                    $table->addRow();

                    $table->setHeaderCallback( function($table) {
                        $table->addRow();
                        $table->addCell(_t("Date"),         'left', 'title');
                        $table->addCell(_t("Person"),       'left', 'title');
                        $table->addCell(_t("Therapy"),      'left', 'title');
                        $table->addCell(_t("Showed Up"),    'left', 'title');
                    });

                    // controls the background filling
                    $colour= FALSE;

                    // data rows
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';
                        $table->addRow();
                        $table->addCell(TDate::date2br($object->dtagendamento),     'left',    $style);
                        $table->addCell($object->pessoa->nome,                      'left',    $style);
                        $table->addCell($object->terapia->nome,                     'left',    $style);
                        if  ($object->compareceu == "N")
                            $table->addCell(utf8_decode(_t('No')),                  'left',    $style);
                        else
                            $table->addCell(utf8_decode(_t('Yes')),                 'left',    $style);

                        $colour = !$colour;
                    }

                    $table->addRow();
                    $table->addCell("Total de Agendamentos",    'rigth',    'footer', 3);
                    $table->addCell($count,                     'left',     'footer');

                    $output = "app/output/RelAgendamentos.{$format}";

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
