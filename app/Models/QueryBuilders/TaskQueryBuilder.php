<?php

namespace App\Models\QueryBuilders;

use App\Core\Models\QueryBuilders\CoreQueryBuilder;
use Illuminate\Support\Facades\Auth;

/**
 * Class TaskQueryBuilder
 */
class TaskQueryBuilder extends CoreQueryBuilder
{

    /**
     * @param int|null $userId
     * @return TaskQueryBuilder
     */
    public function byUser(?int $userId = null) : TaskQueryBuilder
    {
        return $this->where('user_id', $userId ?: Auth::user()?->id);
    }

    public function datatableListSubQuery() : TaskQueryBuilder
    {
        return $this->byUser();
    }
}
