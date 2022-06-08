<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property string $organizeid 
 * @property string $parentid 
 * @property string $organizename 
 * @property string $shortname 
 * @property string $organizemail 
 * @property string $organizecode 
 * @property int $status 
 */
class Organize extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organize';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'status' => 'integer'];
}