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

        throw new NotFoundException('type.notFound');
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

        throw new NotFoundException('type.notFound');
    }

    /**
     * @return Type
     */
    public function create(array $input) :Type
    {
        DB::beginTransaction();

        $type = Type::where('name', $input['name'])
            ->lockForUpdate()
            ->first();

        if (!is_null($type)) {
            DB::rollback();
            throw new NotFoundException('type.sameNameExist');

        } else {      
            $type = new Type;
            $type->name = $input['name'];
            $type->en = $input['en'];
            $type->description = $input['description'];
            $type->save();

            DB::commit();
       	}

        return $type;
    }

    /**
     * @return Collection
     */
    public function update(int $id, array $input) :bool
    {
        DB::beginTransaction();

        $type = Type::where('name', $input['name'])
            ->lockForUpdate()
            ->first();

        if (!is_null($type) && $type->id !== $id) {
            DB::rollback();
            throw new NotFoundException('type.sameNameExist');
        } else {
        
        $type = Type::find($id);

        if (is_null($type)) {
            DB::rollback();
            throw new NotFoundException('type.notFound');
        } else {

            $type->name = $input['name'];
            $type->en = $input['en'];
            $type->description = $input['description'];
            $type->save();
            DB::commit();
        }}
        
        return true;
    }

    /**
     * @return bool
     */
    public function delete(int $id) :bool
    {
        DB::beginTransaction();

        $type = Type::find($id);

        if ($type->plans()->lockForUpdate()->count() > 0) {
            DB::rollback();
            throw new NotFoundException('type.hasPlans');

        } else {

            $type->delete();
            DB::commit();
        }

        return true;
    }
}
