<?php
use Adianti\Core\AdiantiCoreTranslator;

/**
 * ApplicationTranslator
 *
 * @version    7.6
 * @package    util
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class ApplicationTranslator
{
    private static $instance; // singleton instance
    private $lang;            // target language
    private $messages;
    private $sourceMessages;
    
    /**
     * Class Constructor
     */
    private function __construct()
    {
        $this->messages = [];
        $this->messages['en'] = [];
        $this->messages['pt'] = [];
        $this->messages['es'] = [];
        
        $this->messages['en'][] = 'University';
        $this->messages['pt'][] = 'Universidade';
        $this->messages['es'][] = 'Universidad';
        
        $this->messages['en'][] = 'City';
        $this->messages['pt'][] = 'Cidade';
        $this->messages['es'][] = 'Ciudad';

        $this->messages['en'][] = 'Entries';
        $this->messages['pt'][] = 'Cadastros';
        $this->messages['es'][] = 'Entradas';

        $this->messages['en'][] = 'Civil Status';
        $this->messages['pt'][] = 'Estado Civil';
        $this->messages['es'][] = 'Estado Civil';

        $this->messages['en'][] = 'Civils Status';
        $this->messages['pt'][] = 'Estados Civis';
        $this->messages['es'][] = 'Estados Civiles';

        $this->messages['en'][] = 'Id';
        $this->messages['pt'][] = 'Código';
        $this->messages['es'][] = 'Código';

        $this->messages['en'][] = 'Select';
        $this->messages['pt'][] = 'Selecionar';
        $this->messages['es'][] = 'Seleccionar';

        $this->messages['en'][] = 'Delete Selected';
        $this->messages['pt'][] = 'Apagar Selecionados';
        $this->messages['es'][] = 'Eliminar Seleccionados';

        $this->messages['en'][] = 'Work Place';
        $this->messages['pt'][] = 'Local de Trabalho';
        $this->messages['es'][] = 'Lugar de Trabajo';

        $this->messages['en'][] = 'Work Places';
        $this->messages['pt'][] = 'Locais de Trabalho';
        $this->messages['es'][] = 'Lugares de Trabajo';

        $this->messages['en'][] = 'Occupation';
        $this->messages['pt'][] = 'Profissão';
        $this->messages['es'][] = 'Profesión';

        $this->messages['en'][] = 'Occupations';
        $this->messages['pt'][] = 'Profissões';
        $this->messages['es'][] = 'Profesiones';

        $this->messages['en'][] = 'Branch of Aactivity';
        $this->messages['pt'][] = 'Ramo de Atividade';
        $this->messages['es'][] = 'Línea de Actividad';

        $this->messages['en'][] = 'Branches of Aactivity';
        $this->messages['pt'][] = 'Ramos de Atividade';
        $this->messages['es'][] = 'Líneas de Actividad';

        $this->messages['en'][] = 'ZIP';
        $this->messages['pt'][] = 'CEP';
        $this->messages['es'][] = 'Código Postal';

        $this->messages['en'][] = 'Street';
        $this->messages['pt'][] = 'Rua';
        $this->messages['es'][] = 'Calle';

        $this->messages['en'][] = 'Number';
        $this->messages['pt'][] = 'Número';
        $this->messages['es'][] = 'Número';

        $this->messages['en'][] = 'Complement';
        $this->messages['pt'][] = 'Complemento';
        $this->messages['es'][] = 'Complemento';

        $this->messages['en'][] = 'Holiday';
        $this->messages['pt'][] = 'Feriado';
        $this->messages['es'][] = 'Vacación';

        $this->messages['en'][] = 'Holidays';
        $this->messages['pt'][] = 'Feriados';
        $this->messages['es'][] = 'Vacaciones';

        $this->messages['en'][] = 'Therapy';
        $this->messages['pt'][] = 'Terapia';
        $this->messages['es'][] = 'Terapia';

        $this->messages['en'][] = 'Therapies';
        $this->messages['pt'][] = 'Terapias';
        $this->messages['es'][] = 'Terapias';

        $this->messages['en'][] = 'Vacancies';
        $this->messages['pt'][] = 'Vagas';
        $this->messages['es'][] = 'Vacantes';

        $this->messages['en'][] = 'Day of the Week';
        $this->messages['pt'][] = 'Dia da Semana';
        $this->messages['es'][] = 'Dia de la Semana';

        $this->messages['en'][] = 'Days of the Week';
        $this->messages['pt'][] = 'Dias da Semana';
        $this->messages['es'][] = 'Dias de la Semana';

        $this->messages['en'][] = 'Neighborhood';
        $this->messages['pt'][] = 'Bairro';
        $this->messages['es'][] = 'Bairro';

        $this->messages['en'][] = 'State';
        $this->messages['pt'][] = 'Estado';
        $this->messages['es'][] = 'Estado';

        $this->messages['en'][] = 'Person';
        $this->messages['pt'][] = 'Pessoa';
        $this->messages['es'][] = 'Persona';

        $this->messages['en'][] = 'People';
        $this->messages['pt'][] = 'Pessoas';
        $this->messages['es'][] = 'Personas';

        $this->messages['en'][] = 'Contact';
        $this->messages['pt'][] = 'Contato';
        $this->messages['es'][] = 'Contacto';

        $this->messages['en'][] = 'Inactive';
        $this->messages['pt'][] = 'Inativo';
        $this->messages['es'][] = 'Inactivo';

        $this->messages['en'][] = 'Clear Filter';
        $this->messages['pt'][] = 'Limpar Filtro';
        $this->messages['es'][] = 'Limpiar el Filtro';

        $this->messages['en'][] = 'Physical Person';
        $this->messages['pt'][] = 'Pessoa Física';
        $this->messages['es'][] = 'Persona Física';

        $this->messages['en'][] = 'Legal Person';
        $this->messages['pt'][] = 'Pessoa Jurídica';
        $this->messages['es'][] = 'Persona Jurídica';

        $this->messages['en'][] = 'Reference Point';
        $this->messages['pt'][] = 'Ponto de Referência';
        $this->messages['es'][] = 'Punto de Referencia';

        $this->messages['en'][] = 'Residence Type';
        $this->messages['pt'][] = 'Tipo de Residência';
        $this->messages['es'][] = 'Tipo de Residencia';

        $this->messages['en'][] = 'Residence Types';
        $this->messages['pt'][] = 'Tipos de Residência';
        $this->messages['es'][] = 'Tipos de Residencia';

        $this->messages['en'][] = 'Residence Time';
        $this->messages['pt'][] = 'Tempo de Residência';
        $this->messages['es'][] = 'Tiempo de Residencia';

        $this->messages['en'][] = 'Register Date';
        $this->messages['pt'][] = 'Data de Cadastro';
        $this->messages['es'][] = 'Fecha de Registro';

        $this->messages['en'][] = 'Birth Date';
        $this->messages['pt'][] = 'Data de Nascimento';
        $this->messages['es'][] = 'Fecha de Nacimiento';

        $this->messages['en'][] = 'Sex';
        $this->messages['pt'][] = 'Sexo';
        $this->messages['es'][] = 'Sexo';

        $this->messages['en'][] = 'Nationality';
        $this->messages['pt'][] = 'Nacionalidade';
        $this->messages['es'][] = 'Nacionalidad';

        $this->messages['en'][] = 'City/State of Birth';
        $this->messages['pt'][] = 'Cidade/Estado de Nascimento';
        $this->messages['es'][] = 'Ciudad/Estado de Nacimiento';

        $this->messages['en'][] = 'Country of Birth';
        $this->messages['pt'][] = 'País de Nascimento';
        $this->messages['es'][] = 'País de Nacimiento';

        $this->messages['en'][] = 'Parent\'s Name';
        $this->messages['pt'][] = 'Nome dos pais';
        $this->messages['es'][] = 'Nombre de los Padres';

        $this->messages['en'][] = 'Marital Status';
        $this->messages['pt'][] = 'Estado Civil';
        $this->messages['es'][] = 'Estado Civil';

        $this->messages['en'][] = 'Partner';
        $this->messages['pt'][] = 'Cônjuge';
        $this->messages['es'][] = 'Cónyuge';

        $this->messages['en'][] = 'Issuing Body';
        $this->messages['pt'][] = 'Órgão Expedidor';
        $this->messages['es'][] = 'Órgano Emisor';

        $this->messages['en'][] = 'Issue Date';
        $this->messages['pt'][] = 'Data Emissão';
        $this->messages['es'][] = 'Fecha de Emisión';

        $this->messages['en'][] = 'Monthly Income';
        $this->messages['pt'][] = 'Renda Mensal';
        $this->messages['es'][] = 'Renta Mensual';

        $this->messages['en'][] = 'Observation';
        $this->messages['pt'][] = 'Observação';
        $this->messages['es'][] = 'Observación';

        $this->messages['en'][] = 'New Record';
        $this->messages['pt'][] = 'Novo Registro';
        $this->messages['es'][] = 'Nuevo Registro';

        $this->messages['en'][] = 'History';
        $this->messages['pt'][] = 'Histórico';
        $this->messages['es'][] = 'Histórico';

        $this->messages['en'][] = 'Schedule';
        $this->messages['pt'][] = 'Agendamento';
        $this->messages['es'][] = 'Horario';

        $this->messages['en'][] = 'Schedules';
        $this->messages['pt'][] = 'Agendamentos';
        $this->messages['es'][] = 'Horarios';

        $this->messages['en'][] = 'Date';
        $this->messages['pt'][] = 'Data';
        $this->messages['es'][] = 'Fecha';

        $this->messages['en'][] = 'Return';
        $this->messages['pt'][] = 'Retorno';
        $this->messages['es'][] = 'Retorno';

        $this->messages['en'][] = 'Showed Up';
        $this->messages['pt'][] = 'Compareceu';
        $this->messages['es'][] = 'Apareció';

        $this->messages['en'][] = 'Didn\'t Show Up';
        $this->messages['pt'][] = 'Não Compareceu';
        $this->messages['es'][] = 'No Apareció';

        $this->messages['en'][] = 'Print Registration Form';
        $this->messages['pt'][] = 'Imprimir Ficha Cadastral';
        $this->messages['es'][] = 'Imprimir Formulario de Registro';

        $this->messages['en'][] = 'Print Schedules';
        $this->messages['pt'][] = 'Imprimir Agendamentos';
        $this->messages['es'][] = 'Imprimir Horarios';

        $this->messages['en'][] = 'Mark All';
        $this->messages['pt'][] = 'Marcar Todos';
        $this->messages['es'][] = 'Marcar Todos';

        $this->messages['en'][] = 'Unmark All';
        $this->messages['pt'][] = 'Desmarcar Todos';
        $this->messages['es'][] = 'Desmarcar Todos';

        $this->messages['en'][] = 'Confirm';
        $this->messages['pt'][] = 'Confirmar';
        $this->messages['es'][] = 'Confirmar';

        $this->messages['en'][] = 'Cancel';
        $this->messages['pt'][] = 'Cancelar';
        $this->messages['es'][] = 'Cancelar';

        $this->messages['en'][] = 'Report';
        $this->messages['pt'][] = 'Relatório';
        $this->messages['es'][] = 'Reporte';

        $this->messages['en'][] = 'Report of';
        $this->messages['pt'][] = 'Relatório de';
        $this->messages['es'][] = 'Reporte de';

        $this->messages['en'][] = 'Reports';
        $this->messages['pt'][] = 'Relatórios';
        $this->messages['es'][] = 'Reportes';

        $this->messages['en'][] = 'Birthdays';
        $this->messages['pt'][] = 'Aniversariantes';
        $this->messages['es'][] = 'Aniversario';

        $this->messages['en'][] = 'Month';
        $this->messages['pt'][] = 'Mês';
        $this->messages['es'][] = 'Mes';

        $this->messages['en'][] = 'All';
        $this->messages['pt'][] = 'Todos';
        $this->messages['es'][] = 'Todo';

        $this->messages['en'][] = 'January';
        $this->messages['pt'][] = 'Janeiro';
        $this->messages['es'][] = 'Enero';

        $this->messages['en'][] = 'February';
        $this->messages['pt'][] = 'Favereiro';
        $this->messages['es'][] = 'Febrero';

        $this->messages['en'][] = 'March';
        $this->messages['pt'][] = 'Março';
        $this->messages['es'][] = 'Marzo';

        $this->messages['en'][] = 'April';
        $this->messages['pt'][] = 'Abril';
        $this->messages['es'][] = 'Abril';

        $this->messages['en'][] = 'May';
        $this->messages['pt'][] = 'Maio';
        $this->messages['es'][] = 'Mayo';

        $this->messages['en'][] = 'June';
        $this->messages['pt'][] = 'Junho';
        $this->messages['es'][] = 'Junio';

        $this->messages['en'][] = 'July';
        $this->messages['pt'][] = 'Julho';
        $this->messages['es'][] = 'Julio';

        $this->messages['en'][] = 'August';
        $this->messages['pt'][] = 'Agosto';
        $this->messages['es'][] = 'Agosto';

        $this->messages['en'][] = 'September';
        $this->messages['pt'][] = 'Stembro';
        $this->messages['es'][] = 'Septiembre';

        $this->messages['en'][] = 'October';
        $this->messages['pt'][] = 'Outubro';
        $this->messages['es'][] = 'Octubre';

        $this->messages['en'][] = 'November';
        $this->messages['pt'][] = 'Novembro';
        $this->messages['es'][] = 'Noviembre';

        $this->messages['en'][] = 'December';
        $this->messages['pt'][] = 'Dezembro';
        $this->messages['es'][] = 'Diciembre';

        $this->messages['en'][] = 'Initial Date';
        $this->messages['pt'][] = 'Data Inicial';
        $this->messages['es'][] = 'Fecha de Inicio';

        $this->messages['en'][] = 'Final Date';
        $this->messages['pt'][] = 'Data Final';
        $this->messages['es'][] = 'Fecha Final';

        $this->messages['en'][] = 'Generate';
        $this->messages['pt'][] = 'Gerar';
        $this->messages['es'][] = 'Generar';

        $this->messages['en'][] = 'Format';
        $this->messages['pt'][] = 'Formato';
        $this->messages['es'][] = 'Formato';

        $this->messages['en'][] = 'Listing of';
        $this->messages['pt'][] = 'Lista de';
        $this->messages['es'][] = 'Lista de';

        $this->messages['en'][] = 'Hour';
        $this->messages['pt'][] = 'Hora';
        $this->messages['es'][] = 'Hora';

        $this->messages['en'][] = 'Output';
        $this->messages['pt'][] = 'Saída';
        $this->messages['es'][] = 'Salida';

        $this->messages['en'][] = 'Generate';
        $this->messages['pt'][] = 'Gerar';
        $this->messages['es'][] = 'Generar';

        $this->messages['en'][] = 'Group of People';
        $this->messages['pt'][] = 'Grupo de Pessoas';
        $this->messages['es'][] = 'Grupo de Personas';

        $this->messages['en'][] = 'Groups of People';
        $this->messages['pt'][] = 'Grupos de Pessoas';
        $this->messages['es'][] = 'Grupos de Personas';

        $this->messages['en'][] = 'No records found';
        $this->messages['pt'][] = 'Nenhum registro foi encontrado';
        $this->messages['es'][] = 'No se encontraron registros';

        $this->messages['en'][] = 'Date from';
        $this->messages['pt'][] = 'Data de ';
        $this->messages['es'][] = 'Fecha de';

        $this->messages['en'][] = 'Date until';
        $this->messages['pt'][] = 'Date até';
        $this->messages['es'][] = 'Fecha asta';

        $this->messages['en'][] = 'Filters Used';
        $this->messages['pt'][] = 'Filtros Utilizados';
        $this->messages['es'][] = 'Filtros Usados';

        $this->messages['en'][] = 'Unit Data';
        $this->messages['pt'][] = 'Dados da Unidade';
        $this->messages['es'][] = 'Datos de la Unidad';

        $this->messages['en'][] = 'Site';
        $this->messages['pt'][] = 'Site';
        $this->messages['es'][] = 'Sitio';

        $this->messages['en'][] = 'Forms';
        $this->messages['pt'][] = 'Formulários';
        $this->messages['es'][] = 'Formularios';

        $this->messages['en'][] = 'Form';
        $this->messages['pt'][] = 'Formulário';
        $this->messages['es'][] = 'Formulario';

        $this->messages['en'][] = 'Content';
        $this->messages['pt'][] = 'Conteúdo';
        $this->messages['es'][] = 'Contenido';

        $this->messages['en'][] = 'Payment Method';
        $this->messages['pt'][] = 'Forma de Pagamento';
        $this->messages['es'][] = 'Forma de Pago';

        $this->messages['en'][] = 'Payment Methods';
        $this->messages['pt'][] = 'Formas de Pagamento';
        $this->messages['es'][] = 'Formas de Pago';

        $this->messages['en'][] = 'Ask for an Account';
        $this->messages['pt'][] = 'Pede Conta';
        $this->messages['es'][] = 'Pedir una Cuenta';

        $this->messages['en'][] = 'Financial';
        $this->messages['pt'][] = 'Financeiro';
        $this->messages['es'][] = 'Ffinanciero';

        $this->messages['en'][] = 'Payment Receiving Method';
        $this->messages['pt'][] = 'Forma de Recebimento';
        $this->messages['es'][] = 'Forma de Recepción';

        $this->messages['en'][] = 'Payment Receiving Methods';
        $this->messages['pt'][] = 'Formas de Recebimento';
        $this->messages['es'][] = 'Formas de Recepción';

        $this->messages['en'][] = 'Course';
        $this->messages['pt'][] = 'Curso';
        $this->messages['es'][] = 'Curso';

        $this->messages['en'][] = 'Courses';
        $this->messages['pt'][] = 'Cursos';
        $this->messages['es'][] = 'Cursos';

        $this->messages['en'][] = 'Teacher';
        $this->messages['pt'][] = 'Professor';
        $this->messages['es'][] = 'Maestro';

        $this->messages['en'][] = 'Payment Type';
        $this->messages['pt'][] = 'Tipo de Pagamento';
        $this->messages['es'][] = 'Tipo de Pago';

        $this->messages['en'][] = 'Payment Types';
        $this->messages['pt'][] = 'Tipos de Pagamento';
        $this->messages['es'][] = 'Tipos de Pago';

        $this->messages['en'][] = 'Revenue';
        $this->messages['pt'][] = 'Receita';
        $this->messages['es'][] = 'ingreso';

        $this->messages['en'][] = 'Revenues';
        $this->messages['pt'][] = 'Receitas';
        $this->messages['es'][] = 'ingreso';

        $this->messages['en'][] = 'Expense';
        $this->messages['pt'][] = 'Despesa';
        $this->messages['es'][] = 'Gasto';

        $this->messages['en'][] = 'Expenses';
        $this->messages['pt'][] = 'Despesas';
        $this->messages['es'][] = 'Gastos';

        $this->messages['en'][] = 'Creditor';
        $this->messages['pt'][] = 'Credor';
        $this->messages['es'][] = 'Acreedor';

        $this->messages['en'][] = 'Debtor';
        $this->messages['pt'][] = 'Devedor';
        $this->messages['es'][] = 'Deudor';

        $this->messages['en'][] = 'Document Number';
        $this->messages['pt'][] = 'Número Documento';
        $this->messages['es'][] = 'Número del Documento';

        $this->messages['en'][] = 'Parcel';
        $this->messages['pt'][] = 'Parcela';
        $this->messages['es'][] = 'Parte';

        $this->messages['en'][] = 'Total Payments';
        $this->messages['pt'][] = 'Total Parcelas';
        $this->messages['es'][] = 'Total de Cuotas';

        $this->messages['en'][] = 'Duo Date';
        $this->messages['pt'][] = 'Vencimento';
        $this->messages['es'][] = 'Vencimento';

        $this->messages['en'][] = 'Value';
        $this->messages['pt'][] = 'Valor';
        $this->messages['es'][] = 'Valor';

        $this->messages['en'][] = 'Discount';
        $this->messages['pt'][] = 'Desconto';
        $this->messages['es'][] = 'Descuento';

        $this->messages['en'][] = 'Fees';
        $this->messages['pt'][] = 'Juros';
        $this->messages['es'][] = 'Honorarios';

        $this->messages['en'][] = 'Penalty';
        $this->messages['pt'][] = 'Multa';
        $this->messages['es'][] = 'Multa';

        $this->messages['en'][] = 'Payday';
        $this->messages['pt'][] = 'Data de Pagamento';
        $this->messages['es'][] = 'Día de Paga';

        $this->messages['en'][] = 'Amount Paid';
        $this->messages['pt'][] = 'Valor Pago';
        $this->messages['es'][] = 'Valor Pago';

        $this->messages['en'][] = 'Receipt Number';
        $this->messages['pt'][] = 'Número Recibo';
        $this->messages['es'][] = 'Número Recibo';

        $this->messages['en'][] = 'Liquidated';
        $this->messages['pt'][] = 'Liquidado';
        $this->messages['es'][] = 'Liquidado';

        foreach ($this->messages as $lang => $messages)
        {
            $this->sourceMessages[$lang] = array_flip( $this->messages[ $lang ] );
        }
    }
    
    /**
     * Returns the singleton instance
     * @return  Instance of self
     */
    public static function getInstance()
    {
        // if there's no instance
        if (empty(self::$instance))
        {
            // creates a new object
            self::$instance = new self;
        }
        // returns the created instance
        return self::$instance;
    }
    
    /**
     * Define the target language
     * @param $lang Target language index
     */
    public static function setLanguage($lang, $global = true)
    {
        $instance = self::getInstance();
        
        if (in_array($lang, array_keys($instance->messages)))
        {
            $instance->lang = $lang;
        }
        
        if ($global)
        {
            AdiantiCoreTranslator::setLanguage( $lang );
            AdiantiTemplateTranslator::setLanguage( $lang );
        }
    }
    
    /**
     * Returns the target language
     * @return Target language index
     */
    public static function getLanguage()
    {
        $instance = self::getInstance();
        return $instance->lang;
    }
    
    /**
     * Translate a word to the target language
     * @param $word     Word to be translated
     * @return          Translated word
     */
    public static function translate($word, $source_language, $param1 = NULL, $param2 = NULL, $param3 = NULL, $param4 = NULL)
    {
        // get the self unique instance
        $instance = self::getInstance();
        // search by the numeric index of the word
        
        if (isset($instance->sourceMessages[$source_language][$word]) and !is_null($instance->sourceMessages[$source_language][$word]))
        {
            $key = $instance->sourceMessages[$source_language][$word];
            
            // get the target language
            $language = self::getLanguage();
            
            // returns the translated word
            $message = $instance->messages[$language][$key];
            
            if (isset($param1))
            {
                $message = str_replace('^1', $param1, $message);
            }
            if (isset($param2))
            {
                $message = str_replace('^2', $param2, $message);
            }
            if (isset($param3))
            {
                $message = str_replace('^3', $param3, $message);
            }
            if (isset($param4))
            {
                $message = str_replace('^4', $param4, $message);
            }
            return $message;
        }
        else
        {
            $word_template = AdiantiTemplateTranslator::translate($word, $source_language, $param1, $param2, $param3, $param4);
            
            if ($word_template)
            {
                return $word_template;
            }
            
            return 'Message not found: '. $word;
        }
    }
    
    /**
     * Translate a template file
     */
    public static function translateTemplate($template)
    {
        // search by translated words
        if(preg_match_all( '!_t\{(.*?)\}!i', $template, $match ) > 0)
        {
            foreach($match[1] as $word)
            {
                $translated = _t($word);
                $template = str_replace('_t{'.$word.'}', $translated, $template);
            }
        }
        
        if(preg_match_all( '!_tf\{(.*?), (.*?)\}!i', $template, $matches ) > 0)
        {
            foreach($matches[0] as $key => $match)
            {
                $raw        = $matches[0][$key];
                $word       = $matches[1][$key];
                $from       = $matches[2][$key];
                $translated = _tf($word, $from);
                $template = str_replace($raw, $translated, $template);
            }
        }
        return $template;
    }
}

/**
 * Facade to translate words from english
 * @param $word  Word to be translated
 * @param $param1 optional ^1
 * @param $param2 optional ^2
 * @param $param3 optional ^3
 * @return Translated word
 */
function _t($msg, $param1 = null, $param2 = null, $param3 = null)
{
    return ApplicationTranslator::translate($msg, 'en', $param1, $param2, $param3);
}

/**
 * Facade to translate words from specified language
 * @param $word  Word to be translated
 * @param $source_language  Source language
 * @param $param1 optional ^1
 * @param $param2 optional ^2
 * @param $param3 optional ^3
 * @return Translated word
 */
function _tf($msg, $source_language = 'en', $param1 = null, $param2 = null, $param3 = null)
{
    return ApplicationTranslator::translate($msg, $source_language, $param1, $param2, $param3);
}
