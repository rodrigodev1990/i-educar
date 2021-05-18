<?php

use iEducar\Legacy\Model;
use iEducar\Modules\School\Model\ExemptionType;

class clsPmieducarTipoDispensa extends Model
{
    public $cod_tipo_dispensa;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_tipo;
    public $descricao;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_instituicao;
    public $tipo;

    public function __construct(
        $cod_tipo_dispensa = null,
        $ref_usuario_exc = null,
        $ref_usuario_cad = null,
        $nm_tipo = null,
        $descricao = null,
        $data_cadastro = null,
        $data_exclusao = null,
        $ativo = null,
        $ref_cod_instituicao = null,
        $tipo = ExemptionType::DISPENSA_COMPONENTES
    ){
        $db = new clsBanco();
        $this->_schema = 'pmieducar.';
        $this->_tabela = "{$this->_schema}tipo_dispensa";

        $this->_todos_campos = 'td.cod_tipo_dispensa, td.ref_usuario_exc, td.ref_usuario_cad, td.nm_tipo, td.descricao, td.data_cadastro, td.data_exclusao, td.ativo, td.ref_cod_instituicao, td.tipo';
        $this->_campos_lista = $this->_todos_campos;

        if (is_numeric($ref_usuario_exc)) {
            $this->ref_usuario_exc = $ref_usuario_exc;
        }
        if (is_numeric($ref_usuario_cad)) {
            $this->ref_usuario_cad = $ref_usuario_cad;
        }

        if (is_numeric($cod_tipo_dispensa)) {
            $this->cod_tipo_dispensa = $cod_tipo_dispensa;
        }
        if (is_string($nm_tipo)) {
            $this->nm_tipo = $nm_tipo;
        }
        if (is_string($descricao)) {
            $this->descricao = $descricao;
        }
        if (is_string($data_cadastro)) {
            $this->data_cadastro = $data_cadastro;
        }
        if (is_string($data_exclusao)) {
            $this->data_exclusao = $data_exclusao;
        }
        if (is_numeric($ativo)) {
            $this->ativo = $ativo;
        }
        if (is_numeric($ref_cod_instituicao)) {
            $this->ref_cod_instituicao = $ref_cod_instituicao;
        }
        $this->tipo = $tipo;
    }

    /**
     * Cria um novo registro
     *
     * @return bool
     */
    public function cadastra()
    {
        if (is_numeric($this->ref_usuario_cad) && is_string($this->nm_tipo) && is_numeric($this->ref_cod_instituicao)) {
            $db = new clsBanco();

            $campos = '';
            $valores = '';
            $gruda = '';

            if (is_numeric($this->ref_usuario_cad)) {
                $campos .= "{$gruda}ref_usuario_cad";
                $valores .= "{$gruda}'{$this->ref_usuario_cad}'";
                $gruda = ', ';
            }
            if (is_string($this->nm_tipo)) {
                $nm_tipo = $db->escapeString($this->nm_tipo);
                $campos .= "{$gruda}nm_tipo";
                $valores .= "{$gruda}'{$nm_tipo}'";
                $gruda = ', ';
            }
            if (is_string($this->descricao)) {
                $descricao = $db->escapeString($this->descricao);
                $campos .= "{$gruda}descricao";
                $valores .= "{$gruda}'{$descricao}'";
                $gruda = ', ';
            }
            $campos .= "{$gruda}data_cadastro";
            $valores .= "{$gruda}NOW()";
            $gruda = ', ';
            $campos .= "{$gruda}ativo";
            $valores .= "{$gruda}'1'";
            $gruda = ', ';
            $campos .= "{$gruda}tipo";
            $valores .= "{$gruda}{$this->tipo}";
            $gruda = ', ';
            if (is_numeric($this->ref_cod_instituicao)) {
                $campos .= "{$gruda}ref_cod_instituicao";
                $valores .= "{$gruda}'{$this->ref_cod_instituicao}'";
                $gruda = ', ';
            }

            $db->Consulta("INSERT INTO {$this->_tabela} ( $campos ) VALUES( $valores )");

            return $db->InsertId("{$this->_tabela}_cod_tipo_dispensa_seq");
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
        if (is_numeric($this->cod_tipo_dispensa) && is_numeric($this->ref_usuario_exc)) {
            $db = new clsBanco();
            $set = '';
            $gruda = '';
            if (is_numeric($this->ref_usuario_exc)) {
                $set .= "{$gruda}ref_usuario_exc = '{$this->ref_usuario_exc}'";
                $gruda = ', ';
            }
            if (is_numeric($this->ref_usuario_cad)) {
                $set .= "{$gruda}ref_usuario_cad = '{$this->ref_usuario_cad}'";
                $gruda = ', ';
            }
            if (is_string($this->nm_tipo)) {
                $nm_tipo = $db->escapeString($this->nm_tipo);
                $set .= "{$gruda}nm_tipo = '{$nm_tipo}'";
                $gruda = ', ';
            }
            if (is_string($this->descricao)) {
                $descricao = $db->escapeString($this->descricao);
                $set .= "{$gruda}descricao = '{$descricao}'";
                $gruda = ', ';
            }
            if (is_string($this->data_cadastro)) {
                $set .= "{$gruda}data_cadastro = '{$this->data_cadastro}'";
                $gruda = ', ';
            }
            $set .= "{$gruda}data_exclusao = NOW()";
            $gruda = ', ';
            $set .= "{$gruda}tipo = {$this->tipo}";
            $gruda = ', ';
            if (is_numeric($this->ativo)) {
                $set .= "{$gruda}ativo = '{$this->ativo}'";
                $gruda = ', ';
            }
            if (is_numeric($this->ref_cod_instituicao)) {
                $set .= "{$gruda}ref_cod_instituicao = '{$this->ref_cod_instituicao}'";
                $gruda = ', ';
            }
            if ($set) {
                $db->Consulta("UPDATE {$this->_tabela} SET $set WHERE cod_tipo_dispensa = '{$this->cod_tipo_dispensa}'");
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
    public function lista(
        $int_cod_tipo_dispensa = null,
        $int_ref_usuario_exc = null,
        $int_ref_usuario_cad = null,
        $str_nm_tipo = null,
        $str_descricao = null,
        $date_data_cadastro_ini = null,
        $date_data_cadastro_fim = null,
        $date_data_exclusao_ini = null,
        $date_data_exclusao_fim = null,
        $int_ativo = null,
        $int_ref_cod_instituicao = null,
        $tipo = null
    ){
        $db = new clsBanco();

        $sql = "SELECT {$this->_campos_lista} FROM {$this->_tabela} td, {$this->_schema}instituicao i";

        $whereAnd = ' AND ';
        $filtros = ' WHERE td.ref_cod_instituicao = i.cod_instituicao ';

        if (is_numeric($int_cod_tipo_dispensa)) {
            $filtros .= "{$whereAnd} td.cod_tipo_dispensa = '{$int_cod_tipo_dispensa}'";
            $whereAnd = ' AND ';
        }
        if (is_numeric($int_ref_usuario_exc)) {
            $filtros .= "{$whereAnd} td.ref_usuario_exc = '{$int_ref_usuario_exc}'";
            $whereAnd = ' AND ';
        }
        if (is_numeric($int_ref_usuario_cad)) {
            $filtros .= "{$whereAnd} td.ref_usuario_cad = '{$int_ref_usuario_cad}'";
            $whereAnd = ' AND ';
        }
        if (is_string($str_nm_tipo)) {
            $nm_tipo = $db->escapeString($str_nm_tipo);
            $filtros .= "{$whereAnd} td.nm_tipo LIKE '%{$nm_tipo}%'";
            $whereAnd = ' AND ';
        }
        if (is_string($str_descricao)) {
            $filtros .= "{$whereAnd} td.descricao LIKE '%{$str_descricao}%'";
            $whereAnd = ' AND ';
        }
        if (is_string($date_data_cadastro_ini)) {
            $filtros .= "{$whereAnd} td.data_cadastro >= '{$date_data_cadastro_ini}'";
            $whereAnd = ' AND ';
        }
        if (is_string($date_data_cadastro_fim)) {
            $filtros .= "{$whereAnd} td.data_cadastro <= '{$date_data_cadastro_fim}'";
            $whereAnd = ' AND ';
        }
        if (is_string($date_data_exclusao_ini)) {
            $filtros .= "{$whereAnd} td.data_exclusao >= '{$date_data_exclusao_ini}'";
            $whereAnd = ' AND ';
        }
        if (is_string($date_data_exclusao_fim)) {
            $filtros .= "{$whereAnd} td.data_exclusao <= '{$date_data_exclusao_fim}'";
            $whereAnd = ' AND ';
        }
        if (is_null($int_ativo) || $int_ativo) {
            $filtros .= "{$whereAnd} td.ativo = '1'";
            $whereAnd = ' AND ';
        } else {
            $filtros .= "{$whereAnd} td.ativo = '0'";
            $whereAnd = ' AND ';
        }
        if (is_numeric($int_ref_cod_instituicao)) {
            $filtros .= "{$whereAnd} i.cod_instituicao = '{$int_ref_cod_instituicao}'";
            $whereAnd = ' AND ';
        }
        if(is_array($tipo)){
            $tipos = implode(',', $tipo);
            $filtros .= "{$whereAnd} td.tipo in ({$tipos})";
            $whereAnd = ' AND ';
        }
        if(is_numeric($tipo)){
            $filtros .= "{$whereAnd} td.tipo = '{$tipo}'";
            $whereAnd = ' AND ';
        }

        $countCampos = count(explode(',', $this->_campos_lista));
        $resultado = [];

        $sql .= $filtros . $this->getOrderby() . $this->getLimite();

        $this->_total = $db->CampoUnico("SELECT COUNT(0) FROM {$this->_tabela} td, {$this->_schema}instituicao i {$filtros}");

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
        if (is_numeric($this->cod_tipo_dispensa)) {
            $db = new clsBanco();
            $db->Consulta("SELECT {$this->_todos_campos} FROM {$this->_tabela} td WHERE td.cod_tipo_dispensa = '{$this->cod_tipo_dispensa}'");
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
        if (is_numeric($this->cod_tipo_dispensa)) {
            $db = new clsBanco();
            $db->Consulta("SELECT 1 FROM {$this->_tabela} WHERE cod_tipo_dispensa = '{$this->cod_tipo_dispensa}'");
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
        if (is_numeric($this->cod_tipo_dispensa) && is_numeric($this->ref_usuario_exc)) {
            $this->ativo = 0;

            return $this->edita();
        }
        return false;
    }
}
