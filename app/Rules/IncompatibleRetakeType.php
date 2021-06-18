<?php

namespace App\Rules;

use App\Models\LegacyEvaluationRuleGradeYear;
use App\Models\LegacySchoolClass;
use Illuminate\Contracts\Validation\Rule;
use RegraAvaliacao_Model_TipoRecuperacaoParalela;

class IncompatibleRetakeType implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $schoolClass = $value[0]['turma_id'];
        $schoolClass = LegacySchoolClass::find($schoolClass);
        $grades = array_column($value, 'serie_id');

        $retakeType = LegacyEvaluationRuleGradeYear::query()
            ->whereIn('serie_id', $grades)
            ->where('ano_letivo', $schoolClass->ano)
            ->with('evaluationRule')
            ->get()
            ->map(function ($model) {
                return $model->evaluationRule->tipo_recuperacao_paralela;
            })
            ->toArray();

        // Ignora regra que não usa recuperação
        $retakeType = array_diff($retakeType, [RegraAvaliacao_Model_TipoRecuperacaoParalela::NAO_USAR]);

        return count(array_unique($retakeType)) <= 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'As séries selecionadas devem possuir o mesmo tipo de recuperação.';
    }
}
