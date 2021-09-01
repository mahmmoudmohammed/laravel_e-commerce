<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 *models
 * @OA\Schema(
 *     required={"name","description","price","in_stock","price_after"},
 * @OA\Xml(name="Products"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="description", type="string", readOnly="true",description="Products description", example="Main Characteristics: ......"),
 *     @OA\Property(property="in_stock", type="integer", readOnly="true", example="111"),
 *     @OA\Property(property="price", type="double", readOnly="true", example="111.00"),
 *     @OA\Property(property="price_after", type="double", readOnly="true", example="111.05"),
 *     @OA\Property(property="has_offer", type="boolean", readOnly="true", example="false"),
 * )
 */

class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ["name", "description", "in_stock", "has_offer", "price", "price_after"];
    protected $hidden = [ 'id', 'created_at', 'updated_at', 'deleted_at'];
    use HasFactory;

    public function orders()
    {
        return $this->belongsToMany(Order::class,'order_product')->attach(['quantity,price']);
    }
}
