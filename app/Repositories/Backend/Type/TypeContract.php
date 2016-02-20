<?php

namespace App\Repositories\Backend\Type;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Flight\Type;

/**
 * Interface TypeContract
 * @package App\Repositories\Flight
 */
interface TypeContract
{
    /**
     * @return Plan
     */
    public function findOrThrowException(int $id) :Type;
    
    /**
     * @return Type
     */
    public function all() :Collection;
}