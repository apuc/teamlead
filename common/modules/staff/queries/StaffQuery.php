<?php

namespace common\modules\staff\queries;

use common\modules\roles\records\Role;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\modules\staff\records\Staff]].
 *
 * @see \common\modules\staff\records\Staff
 */
class StaffQuery extends \yii\db\ActiveQuery
{

    public function teamlead()
    {
        return $this->joinWith(['role' => function(ActiveQuery $query) {
            return $query
                ->andWhere(['=', 'LCASE('.Role::tableName().'.name)', 'тимлид']);
        }]);
    }

    public function worker()
    {
        return $this->joinWith(['role' => function(ActiveQuery $query) {
            return $query
                ->andWhere(['=', 'LCASE('.Role::tableName().'.name)', 'сотрудник']);
        }]);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\staff\records\Staff[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\staff\records\Staff|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
