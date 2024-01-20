<?php
/**
 * GruposProdutos Active Record
 * @author  <your-name-here>
 */
class GruposProdutos extends TRecord
{
    const TABLENAME = 'grupos_produtos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('perc_margem');
        parent::addAttribute('preco_venda');
        parent::addAttribute('preco_aluguel');
    }
    
    public static function onImportData()
    {
        try
        {
            $unit_id = TSession::getValue('userunitid');
            switch ($unit_id)
            {
                case 6:
                    TTransaction::open(TSession::getValue('unit_database'));
                    $conn = TTransaction::get();
                    
                    $sql = "delete from grupos_produtos";
                    $conn-> query($sql);
                    
                    $target_folder = 'tmp/TXT/';
                    $target_file   = $target_folder . 'GruposProdutos.TXT';
                    
                    $i = 1;
                    $linhas = file($target_file);
                    foreach ($linhas as $linha) 
                    {
                        if ($i == 1)
                        {
                            $grupo = new GruposProdutos();
                            $grupo->id = (int) $linha;
                        } 
                        else if ($i == 2)
                        {
                            $grupo->descricao = trim($linha);
                        } 
                        else if ($i == 3)
                        {
                            $grupo->perc_margem = (float) $linha;
                        }
                        else if ($i == 4)
                        {
                            $grupo->preco_venda = (float) $linha;
                        }
                        else if ($i == 5)
                        {
                            $grupo->preco_aluguel = (float) $linha;
                            $grupo->store();
                            $i = 0;
                        }
                        $i++;
                    }
                    
                    TTransaction::close();
                    break;
                    
                default:
                    break;
            }
            
            new TMessage('info', 'Importação efetuada com sucesso!');
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
