<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Courier extends Model
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
        'email',
        'phone',
        'description',
        'demo_url',
        'live_url',
        'credentials',
        'supported_product_types',
        'current_shipments',
        'max_shipments',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'supported_product_types' => 'array',
        'credentials' => 'array',
    ];

    /**
     * Get all of the shipments for the Courier
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    /**
     * The cities that belong to the Courier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class);
    }
}