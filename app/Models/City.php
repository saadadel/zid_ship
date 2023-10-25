<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class City extends Model
{
    use HasFactory;

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'country_code'
    ];

    /**
     * The couriers that belong to the City
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function couriers(): BelongsToMany
    {
        return $this->belongsToMany(Courier::class);
    }
}
