<?php

namespace App\DTOs;

use Carbon\Carbon;
use App\Enums\PaymentTypeEnum;
use App\Enums\ProductTypeEnum;
use App\Enums\ShipmentStatusEnum;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\In;
use WendellAdriel\ValidatedDTO\ValidatedDTO;
use WendellAdriel\ValidatedDTO\Attributes\Cast;
use WendellAdriel\ValidatedDTO\Casting\EnumCast;
use WendellAdriel\ValidatedDTO\Casting\CarbonCast;
use WendellAdriel\ValidatedDTO\Concerns\EmptyCasts;

class ShipmentDTO extends ValidatedDTO
{
    public ?int $courier_id;

    public ?string $courier_selection_method;

    public ?string $consignor_name;

    public ?string $consignor_email;

    public ?string $consignor_phone;

    public string $consignee_name;

    public string $consignee_email;

    public string $consignee_phone;

    public Carbon $due_date;

    public int $pickup_address_id;

    public int $delivery_address_id;

    public float $length;

    public float $width;

    public float $height;

    public string $dimensions_unit;

    public float $weight;

    public string $weight_unit;

    public int $num_of_pieces;

    public string $products_type;

    public string $payment_type;

    public float $max_coast;

    public ?string $description;

    public string $origin_country_code;

    public ?ShipmentStatusEnum $status;


    public ?Carbon $actual_delivery_date;

    public ?float $coast;

    public ?string $shipment_num;

    protected function rules(): array
    {
        return [
            'courier_id'     => ['sometimes', 'exists:couriers,id'],
            'courier_selection_method' => ['required_without:courier_id', 'in:fast,cheap'],
            'consignee_name'    => ['required', 'string'],
            'consignee_email'    => ['required', 'email'],
            'consignee_phone'    => ['required', 'string', 'between:9,14'],
            'due_date'    => ['sometimes', 'date', 'after:today'],
            'pickup_address_id'    => ['required', 'exists:addresses,id'],
            'delivery_address_id'    => ['required', 'exists:addresses,id'],
            'length'    => ['required', 'decimal:0,2'],
            'width'    => ['required', 'decimal:0,2'],
            'height'    => ['required', 'decimal:0,2'],
            'dimensions_unit'    => ['sometimes', 'in:cm,m'],
            'weight'    => ['required', 'decimal:0,2'],
            'weight_unit'    => ['sometimes', 'in:kg,g'],
            'num_of_pieces'    => ['required', 'integer', 'max:100'],
            'products_type'    => ['required', 'string', new Enum(ProductTypeEnum::class)],
            'payment_type'    => ['required', new Enum(PaymentTypeEnum::class)],
            'max_coast'    => ['sometimes', 'decimal:0,2'],
            'description'    => ['nullable', 'string', 'max:500'],
            'origin_country_code'    => ['required', 'string', 'max:2']
        ];
    }


    protected function casts(): array
    {
        return [
            'due_date' => new CarbonCast(),
            'actual_delivery_date' => new CarbonCast()
        ];
    }

    protected function defaults(): array
    {
        return [
            'status' => ShipmentStatusEnum::PENDING,
            'dimensions_unit' => 'cm',
            'weight_unit' => 'g'
        ];
    }
}
