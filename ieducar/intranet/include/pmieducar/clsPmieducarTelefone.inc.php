<?php

require_once 'include/pmieducar/geral.inc.php';

class clsPmieducarTelefone
{
    public $ref_cod_pessoa_educ;
    public $tipo;
    public $ddd;
    public $fone;

    /**
     * Armazena o total de resultados obtidos na ultima chamada ao metodo lista
     *
     * @var int
     */
    public $_total;

    /**
     * Nome do schema
     *
     * @var string
     */
    public $_schema;

    /**
     * Nome da tabela
     *
     * @var string
     */
    public $_tabela;

    /**
     * Lista separada por virgula, com os campos que devem ser selecionados na proxima chamado ao metodo lista
     *
     * @var string
     */
    public $_campos_lista;

    /**
     * Lista com todos os campos da tabela separados por virgula, padrao para selecao no metodo lista
     *
     * @var string
     */
    public $_todos_campos;

    /**
     * Valor que define a quantidade de registros a ser retornada pelo metodo lista
     *
     * @var int
     */
    public $_limite_quantidade;

    /**
     * Define o valor de offset no retorno dos registros no metodo lista
     *
     * @var int
     */
    public $_limite_offset;

    /**
     * Define o campo padrao para ser usado como padrao de ordenacao no metodo lista
     *
     * @var string
     */
    public $_campo_order_by;

    public function __construct($ref_cod_pessoa_educ = null, $tipo = null, $ddd = null, $fone = null)
    {
        $db = new clsBanco();
        $this->_schema = 'pmieducar.';
        $this->_tabela = "{$this->_schema}telefone";

        $this->_campos_lista = $this->_todos_campos = 'ref_cod_pessoa_educ, tipo, ddd, fone';

        if (is_numeric($ref_cod_pessoa_educ)) {
            if (class_exists('clsPmieducarPessoaEduc')) {
                $tmp_obj = new clsPmieducarPessoaEduc($ref_cod_pessoa_educ);
                if (method_exists($tmp_obj, 'existe')) {
                    if ($tmp_obj->existe()) {
                        $this->ref_cod_pessoa_educ = $ref_cod_pessoa_educ;
                    }
                } elseif (method_exists($tmp_obj, 'detalhe')) {
                    if ($tmp_obj->detalhe()) {
                        $this->ref_cod_pessoa_educ = $ref_cod_pessoa_educ;
                    }
                }
            } else {
                if ($db->CampoUnico("SELECT 1 FROM pmieducar.pessoa_educ WHERE cod_pessoa_educ = '{$ref_cod_pessoa_educ}'")) {
                    $this->ref_cod_pessoa_educ = $ref_cod_pessoa_educ;
                }
            }
        }

        if (is_numeric($tipo)) {
            $this->tipo = $tipo;
        }
        if (is_numeric($ddd)) {
            $this->ddd = $ddd;
        }
        if (is_numeric($fone)) {
            $this->fone = $fone;
        }
    }

    /**
     * Cria um novo registro
     *
     * @return bool
     */
    public function cadastra()
    {
        if (is_numeric($this->ref_cod_pessoa_educ) && is_numeric($this->tipo) && is_numeric($this->ddd) && is_numeric($this->fone)) {
            $db = new clsBanco();

            $campos = '';
            $valores = '';
            $gruda = '';

            if (is_numeric($this->ref_cod_pessoa_educ)) {
                $campos .= "{$gruda}ref_cod_pessoa_educ";
                $valores .= "{$gruda}'{$this->ref_cod_pessoa_educ}'";
                $gruda = ', ';
            }
            if (is_numeric($this->tipo)) {
                $campos .= "{$gruda}tipo";
                $valores .= "{$gruda}'{$this->tipo}'";
                $gruda = ', ';
            }
            if (is_numeric($this->ddd)) {
                $campos .= "{$gruda}ddd";
                $valores .= "{$gruda}'{$this->ddd}'";
                $gruda = ', ';
            }
            if (is_numeric($this->fone)) {
                $campos .= "{$gruda}fone";
                $valores .= "{$gruda}'{$this->fone}'";
                $gruda = ', ';
            }

            $db->Consulta("INSERT INTO {$this->_tabela} ( $campos ) VALUES( $valores )");

            return true;
        }

        return false;
    }

    /**
     * Edita os dados de um registro
     *
     * @return bool
     */
    public function edita()
    {
        if (is_numeric($this->ref_cod_pessoa_educ) && is_numeric($this->tipo)) {
            $db = new clsBanco();
            $set = '';

            if (is_numeric($this->ddd)) {
                $set .= "{$gruda}ddd = '{$this->ddd}'";
                $gruda = ', ';
            }
            if (is_numeric($this->fone)) {
                $set .= "{$gruda}fone = '{$this->fone}'";
                $gruda = ', ';
            }

            if ($set) {
                $db->Consulta("UPDATE {$this->_tabela} SET $set WHERE ref_cod_pessoa_educ = '{$this->ref_cod_pessoa_educ}' AND tipo = '{$this->tipo}'");

                return true;
            }
        }

        return false;
    }

    /**
     * Retorna uma lista filtrados de acordo com os parametros
     *
     * @return array
     */
    public function lista($int_ref_cod_pessoa_educ = null, $int_tipo = null, $int_ddd = null, $int_fone = null)
    {
        $sql = "SELECT {$this->_campos_lista} FROM {$this->_tabela}";
        $filtros = '';

        $whereAnd = ' WHERE ';

        if (is_numeric($int_ref_cod_pessoa_educ)) {
            $filtros .= "{$whereAnd} ref_cod_pessoa_educ = '{$int_ref_cod_pessoa_educ}'";
            $whereAnd = ' AND ';
        }
        if (is_numeric($int_tipo)) {
            $filtros .= "{$whereAnd} tipo = '{$int_tipo}'";
            $whereAnd = ' AND ';
        }
        if (is_numeric($int_ddd)) {
            $filtros .= "{$whereAnd} ddd = '{$int_ddd}'";
            $whereAnd = ' AND ';
        }
        if (is_numeric($int_fone)) {
            $filtros .= "{$whereAnd} fone = '{$int_fone}'";
            $whereAnd = ' AND ';
        }

        $db = new clsBanco();
        $countCampos = count(explode(',', $this->_campos_lista));
        $resultado = [];

        $sql .= $filtros . $this->getOrderby() . $this->getLimite();

        $this->_total = $db->CampoUnico("SELECT COUNT(0) FROM {$this->_tabela} {$filtros}");

        $db->Consulta($sql);

        if ($countCampos > 1) {
            while ($db->ProximoRegistro()) {
                $tupla = $db->Tupla();

                $tupla['_total'] = $this->_total;
                $resultado[] = $tupla;
            }
        } else {
            while ($db->ProximoRegistro()) {
                $tupla = $db->Tupla();
                $resultado[] = $tupla[$this->_campos_lista];
            }
        }
        if (count($resultado)) {
            return $resultado;
        }

        return false;
    }

    /**
     * Retorna um array com os dados de um registro
     *
     * @return array
     */
    public function detalhe()
    {
        if (is_numeric($this->ref_cod_pessoa_educ) && is_numeric($this->tipo)) {
            $db = new clsBanco();
            $db->Consulta("SELECT {$this->_todos_campos} FROM {$this->_tabela} WHERE ref_cod_pessoa_educ = '{$this->ref_cod_pessoa_educ}' AND tipo = '{$this->tipo}'");
            $db->ProximoRegistro();

            return $db->Tupla();
        }

        return false;
    }

    /**
     * Retorna um array com os dados de um registro
     *
     * @return array
     */
    public function existe()
    {
        if (is_numeric($this->ref_cod_pessoa_educ) && is_numeric($this->tipo)) {
            $db = new clsBanco();
            $db->Consulta("SELECT 1 FROM {$this->_tabela} WHERE ref_cod_pessoa_educ = '{$this->ref_cod_pessoa_educ}' AND tipo = '{$this->tipo}'");
            $db->ProximoRegistro();

            return $db->Tupla();
        }

        return false;
    }

    /**
     * Exclui um registro
     *
     * @return bool
     */
    public function excluir()
    {
        if (is_numeric($this->ref_cod_pessoa_educ) && is_numeric($this->tipo)) {
        }

        return false;
    }

    /**
     * Define quais campos da tabela serao selecionados na invocacao do metodo lista
     *
     * @return null
     */
    public function setCamposLista($str_campos)
    {
        $this->_campos_lista = $str_campos;
    }

    /**
     * Define que o metodo Lista devera retornoar todos os campos da tabela
     *
     * @return null
     */
    public function resetCamposLista()
    {
        $this->_campos_lista = $this->_todos_campos;
    }

    /**
     * Define limites de retorno para o metodo lista
     *
     * @return null
     */
    public function setLimite($intLimiteQtd, $intLimiteOffset = null)
    {
        $this->_limite_quantidade = $intLimiteQtd;
        $this->_limite_offset = $intLimiteOffset;
    }

    /**
     * Retorna a string com o trecho da query resposavel pelo Limite de registros
     *
     * @return string
     */
    public function getLimite()
    {
        if (is_numeric($this->_limite_quantidade)) {
            $retorno = " LIMIT {$this->_limite_quantidade}";
            if (is_numeric($this->_limite_offset)) {
                $retorno .= " OFFSET {$this->_limite_offset} ";
            }

            return $retorno;
        }

        return '';
    }

    /**
     * Define campo para ser utilizado como ordenacao no metolo lista
     *
     * @return null
     */
    public function setOrderby($strNomeCampo)
    {
        if (is_string($strNomeCampo) && $strNomeCampo) {
            $this->_campo_order_by = $strNomeCampo;
        }
    }

    /**
     * Retorna a string com o trecho da query resposavel pela Ordenacao dos registros
     *
     * @return string
     */
    public function getOrderby()
    {
        if (is_string($this->_campo_order_by)) {
            return " ORDER BY {$this->_campo_order_by} ";
        }

        return '';
    }
}
