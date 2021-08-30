<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

/**
 *
 * @OA\Schema(
 * required={"status","shipping","total"},
 * @OA\Xml(name="Order"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="status", type="integer", readOnly="true", description=""),
 * @OA\Property(property="shipping", type="douple", readOnly="true", description=""),
 * @OA\Property(property="total", type="douple", readOnly="true", description=""),
 * )
 */

class Order extends Model
{
    use HasFactory;
    protected $fillable = [ "status","shipping","total"];
    protected $hidden = [ 'id', 'created_at', 'updated_at', 'deleted_at'];

    use HasFactory;

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product');
    }
}
