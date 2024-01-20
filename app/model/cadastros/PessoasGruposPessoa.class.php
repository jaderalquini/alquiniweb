<?php
/**
 * PessoasGruposPessoa Active Record
 * @author  <your-name-here>
 */
class PessoasGruposPessoa extends TRecord
{
    const TABLENAME = 'pessoas_grupos_pessoa';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $pessoa;
    private $grupo_pessoa;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id');
        parent::addAttribute('grupo_pessoa_id');
    }
    
    /**
     * Method set_pessoa
     * Sample of usage: $representantes->pessoa = $object;
     * @param $object Instance of Pessoas
     */
    public function set_pessoa(Pessoas $object)
    {
        $this->pessoa = $object;
        $this->pessoa_id = $object->id;
    }
    
    /**
     * Method get_pessoa
     * Sample of usage: $representantes->pessoa->attribute;
     * @returns Pessoas instance
     */
    public function get_pessoa()
    {
        // loads the associated object
        if (empty($this->pessoa))
            $this->pessoa = new Pessoas($this->pessoa_id);
    
        // returns the associated object
        return $this->pessoa;
    }
    
    /**
     * Method set_grupo_pessoa
     * Sample of usage: $pessoas_grupos->grupo_pessoa = $object;
     * @param $object Instance of GruposPessoa
     */
    public function set_grupo_pessoa(GruposPessoa $object)
    {
        $this->grupo_pessoa = $object;
        $this->grupo_pessoa_id = $object->id;
    }
    
    /**
     * Method get_grupo_pessoa
     * Sample of usage: $pessoas_grupos->grupo_pessoa->attribute;
     * @returns GruposPessoa instance
     */
    public function get_grupo_pessoa()
    {
        // loads the associated object
        if (empty($this->grupo_pessoa))
            $this->grupo_pessoa = new GruposPessoa($this->grupo_pessoa_id);
    
        // returns the associated object
        return $this->grupo_pessoa;
    }
}
