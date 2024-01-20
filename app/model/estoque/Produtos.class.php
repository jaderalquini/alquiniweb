<?php
/**
 * Produtos Active Record
 * @author  <your-name-here>
 */
class Produtos extends TRecord
{
    const TABLENAME = 'produtos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $grupo_produto;
    private $fabricante;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('grupo_produto_id');
        parent::addAttribute('descricao');
        parent::addAttribute('referencia');
        parent::addAttribute('dtentrada');
        parent::addAttribute('dtsaida');
        parent::addAttribute('preco_custo');
        parent::addAttribute('preco_venda');
        parent::addAttribute('preco_alocacao');
        parent::addAttribute('estoque_minimo');
        parent::addAttribute('fabricante_id');
        parent::addAttribute('controlar_locacoes');
        parent::addAttribute('ativo');
        parent::addAttribute('manequim');
    }
    
    /**
     * Method set_grupo_produto
     * Sample of usage: $produtos->grupo_produto = $object;
     * @param $object Instance of GruposProdutos
     */
    public function set_grupo_produto(GruposProdutos $object)
    {
        $this->grupo_produto = $object;
        $this->grupo_produto_id = $object->id;
    }
    
    /**
     * Method get_grupo_produto
     * Sample of usage: $produtos->grupo_produto->attribute;
     * @returns GruposProdutos instance
     */
    public function get_grupo_produto()
    {
        // loads the associated object
        if (empty($this->grupo_produto))
            $this->grupo_produto = new GruposProdutos($this->grupo_produto_id);
    
        // returns the associated object
        return $this->grupo_produto;
    }
    
    /**
     * Method set_fabricante
     * Sample of usage: $produtos->fabricante = $object;
     * @param $object Instance of Fabricantes
     */
    public function set_fabricante(Fabricantes $object)
    {
        $this->fabricante = $object;
        $this->fabricante_id = $object->id;
    }
    
    /**
     * Method get_fabricante
     * Sample of usage: $produtos->fabricante->attribute;
     * @returns Fabricantes instance
     */
    public function get_fabricante()
    {
        // loads the associated object
        if (empty($this->fabricante))
            $this->fabricante = new Fabricantes($this->fabricante_id);
    
        // returns the associated object
        return $this->fabricante;
    }
    
    public static function onImportData()
    {
        try
        {
            $unit_id = TSession::getValue('userunitid');
            switch ($unit_id)
            {
                case 6:
                    /*TTransaction::open(TSession::getValue('unit_database'));
                    $conn = TTransaction::get();
                    
                    $sql = "delete from produtos";
                    $conn-> query($sql);

                    TTransaction::close();*/
                    
                    $target_folder = 'tmp/TXT/';
                    $target_file   = $target_folder . 'Produtos.TXT';
                    
                    $i = 1;
                    $linhas = file($target_file);
                    foreach ($linhas as $linha) 
                    {
                        if ($i == 1)
                        {
                            TTransaction::open(TSession::getValue('unit_database'));
                            $produto = new Produtos();
                            $produto->id = (int) $linha;
                        }
                        else if ($i == 2)
                        {
                            $produto->grupo_produto_id = (int) $linha;
                        }
                        else if ($i == 3)
                        {
                            $produto->descricao = trim($linha);
                        }
                        else if ($i == 4)
                        {
                            $produto->referencia = (int) $linha;
                        }
                        else if ($i == 5)
                        {
                            if (strpos($linha, "/") !== false) {
                                $dtentrada = explode("/", $linha);
                                $produto->dtentrada = trim($dtentrada[2]) . "-" . $dtentrada[1] . "-" . $dtentrada[0];
                            }
                        }
                        else if ($i == 6)
                        {
                            $produto->preco_custo = (float) $linha;
                        }
                        else if ($i == 7)
                        {
                            $produto->preco_venda = (float) $linha;
                        }
                        else if ($i == 8)
                        {
                            if (strpos($linha, "/") !== false) {
                                $dtsaida = explode("/", $linha);
                                $produto->dtsaida = trim($dtsaida[2]) . "-" . $dtsaida[1] . "-" . $dtsaida[0];
                            }
                        }
                        else if ($i == 9)
                        {
                            $produto->preco_alocacao = (float) $linha;
                        }
                        else if ($i == 10)
                        {
                            $produto->estoque_minimo = (int) $linha;
                        }
                        else if ($i == 11)
                        {
                            if (!empty(trim($linha))) {
                                $repository = new TRepository('Fabricantes');
                                $critaria = new TCriteria;
                                $critaria->add(new TFilter('nome', '=', trim($linha)));
                                $objects = $repository->load($critaria);
                                
                                if ($objects)
                                {
                                    foreach ($objects as $object)
                                    {
                                        $produto->fabricante_id = (int) $object->id;
                                    }
                                } 
                                else  
                                {
                                    $fabricate = new Fabricantes();
                                    $fabricate->nome = trim($linha);
                                    $fabricate->store();
                                    
                                    $produto->fabricante_id = (int) $fabricate->id; 
                                }
                            } else {
                                $produto->fabricante_id = 0;
                            }
                        }
                        else if ($i == 12)
                        {
                            $produto->controlar_locacoes = trim($linha);
                        }
                        else if ($i == 15)
                        {
                            $produto->manequim = (int) $linha;
                        }
                        else if ($i == 16)
                        {
                            $produto->ativo = trim($linha);
                            $produto->store();
                            $i = 0;
                            TTransaction::close();
                        }
                        $i++;
                    }
                    
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
