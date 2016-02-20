<?php

namespace App\Repositories\Backend\Type;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
//Models
use App\Models\Access\User;
use App\Models\Ticket;
use App\Models\Pin;
use App\Models\Flight\Flight;
use App\Models\Flight\Plan;
use App\Models\Flight\Type;
use App\Models\Flight\Place;
//Exceptions
use App\Exceptions\NotFoundException;

/**
 * Class EloquentTypeRepository
 * @package App\Repositories\Flight
 */
class EloquentTypeRepository implements TypeContract
{
    /**
     * @return Type
     */
    public function findOrThrowException(int $id) :Type
    {
    	$type = Type::find($id);

        if (!is_null($type)) {
            return $type;
        }

        throw new NotFoundException('plan.notFound');
    }

    /**
     * @return Collection
     */
    public function all() :Collection
    {
    	$type = Type::all();

        if (!is_null($type)) {
            return $type;
        }

        throw new NotFoundException('plan.notFound');
    }

    /**
     * @return Type
     */
    public function create(array $input) :Type
    {
        $type = new Type;
        $type->name = $input['name'];
        $type->en = $input['en'];
        $type->description = $input['description'];

    	if ($type->save()) {
            return $type;
       	}

       	throw new NotFoundException('types.create.faile');
    }

    /**
     * @return Collection
     */
    public function update(int $id, array $input) :bool
    {
        $type = $this->findOrThrowException($id);
        $type->name = $input['name'];
        $type->en = $input['en'];
        $type->description = $input['description'];

        if ($type->save()) {
            return true;
        }

        throw new NotFoundException('types.update.faile');
    }

    /**
     * @return bool
     */
    public function delete(int $id) :bool
    {
        $type = $this->findOrThrowException($id);

        if ($type->delete()) {
            return true;
        }

        throw new NotFoundException('plans.delete.faile');
    }
}
