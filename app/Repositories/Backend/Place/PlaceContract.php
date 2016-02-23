<?php

namespace App\Repositories\Backend\Place;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Models\Flight\Place;

/**
 * Interface PlaceContract
 * @package App\Repositories\Flight
 */
interface PlaceContract
{
    /**
     * @return Place
     */
    public function findOrThrowException(int $id) :Place;

    /**
     * @return Collection
     */
    public function all() :Collection;

    /**
     * @return Place
     */
    public function store(array $input, $file = null) :Place;

    /**
     * @return bool
     */
    public function update(int $id, array $input, $file) :bool;

    /**
     * @return bool
     */
    public function delete(int $id) :bool;

    /**
     * @return bool
     */
    public function putPicture(int $id, UploadedFile $file) :bool;

    /**
     * @return string
     */
    public function getPicture(int $id) :string;
}
