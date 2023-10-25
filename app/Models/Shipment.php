<?php

namespace App\Models;

use App\Enums\PaymentTypeEnum;
use App\Enums\ProductTypeEnum;
use App\Enums\ShipmentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends Model
{
    use HasFactory;

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "courier_id",
        "consignor_name",
        "consignor_email",
        "consignor_phone",
        "consignee_name",
        "consignee_email",
        "consignee_phone",
        "due_date",
        "actual_delivery_date",
        "pickup_address_id",
        "delivery_address_id",
        "length",
        "width",
        "height",
        "dimensions_unit",
        "weight",
        "weight_unit",
        "num_of_pieces",
        "products_type",
        "payment_type",
        "description",
        "origin_country_code",
        "status",
        "shipment_num",
        "coast",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'products_type' => ProductTypeEnum::class,
        'payment_type' => PaymentTypeEnum::class,
        "status" => ShipmentStatusEnum::class,
        'due_date' => 'datetime',
        'actual_delivery_date' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (Shipment $shipment) {
            $shipment->courier->current_shipments += 1;
            $shipment->courier->save();
        });

        static::updated(function (Shipment $shipment) {
            $shipment->courier->current_shipments += 1;
            $shipment->courier->save();
        });
    }

    /**
     * Get the courier that owns the Shipment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    /**
     * Get the pickupAddress associated with the Shipment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pickupAddress(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'pickup_address_id');
    }

    /**
     * Get the deliveryAddress associated with the Shipment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function deliveryAddress(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'delivery_address_id');
    }
}