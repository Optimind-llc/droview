<?php

namespace App\Repositories\Backend\Place;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Storage;
use Image;
//Models
use App\Models\Access\User;
use App\Models\Ticket;
use App\Models\Pin;
use App\Models\Flight\Flight;
use App\Models\Flight\Plan;
use App\Models\Flight\Type;
use App\Models\Flight\Place;
use App\Models\Flight\Img;
//Exceptions
use App\Exceptions\NotFoundException;
use App\Http\Requests\Api\Backend\Flight\PlaceRequest;

/**
 * Class EloquentPlaceRepository
 * @package App\Repositories\Flight
 */
class EloquentPlaceRepository implements PlaceContract
{
    /**
     * @return Place
     */
    public function findOrThrowException(int $id) :Place
    {
    	$place = Place::find($id);

        if (!is_null($place)) {
            return $place;
        }

        throw new NotFoundException('plan.notFound');
    }

    /**
     * @return Collection
     */
    public function all() :Collection
    {
    	$place = Place::all();

        if (!is_null($place)) {
            return $place;
        }

        throw new NotFoundException('plan.notFound');
    }

    /**
     * @return Place
     */
    public function store(array $input, $file = null) :Place
    {
        DB::beginTransaction();

        $place = new Place;
        $place->name = $input['name'];
        $place->description = $input['description'];

        if (isset($file)) {
            $img = new Img;
            $img->data = file_get_contents($file);
            $img->save();

            $place->img_id = $img->id;

            $place->save();
            DB::commit();

        } else {
            DB::commit();
        }

        return $place;
    }

    /**
     * @return bool
     */
    public function update(int $id, array $input, $file = null) :bool
    {
    	$place = $this->findOrThrowException($id);

    	if (isset($file)) {
            $img = new Img;
            $img->data = file_get_contents($file);
            $img->save();

            $place->img_id = $img->id;
    	}

        $place->name = $input['name'];
        $place->description = $input['description'];

        if ($place->save()) {
            return true;
        }

        throw new NotFoundException('places.update.faile');
    }

    /**
     * @return bool
     */
    public function delete(int $id) :bool
    {
        DB::beginTransaction();

        $place = Place::find($id);

        if ($place->plans()->lockForUpdate()->count() > 0) {
            DB::rollback();
            throw new NotFoundException('place.hasPlans');

        } else {

            $place->delete();
            DB::commit();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function putPicture(int $id, UploadedFile $file) :bool
    {
    	$path = '/place/' . $id . '.jpg';
    	$realPath = file_get_contents($file->getRealPath());

        if (Storage::put($path, $realPath)) {
            return true;
        }

        throw new NotFoundException('places.putPicture.faile');
    }

    /**
     * @return string
     */
    public function getPicture(int $id) :string
    {
        $place = Place::find($id);

        if (!is_null($place)) {
            throw new NotFoundException('plan.notFound');
        }
        
        $contents = Storage::get('/place/' . $place->path);
        return $contents;
    }
}
