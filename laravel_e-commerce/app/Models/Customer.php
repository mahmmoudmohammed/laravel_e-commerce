<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

/**
 *
 * @OA\Schema(
 * required={"name","email","password"},
 * @OA\Xml(name="Customer"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="email", type="string", readOnly="true", format="email", description="Admin unique email address", example="Admin@gmail.com"),
 *     @OA\Property(property="name", type="string", readOnly="true", example="Admin"),
 *     @OA\Property(property="password", type="string", readOnly="true", format="password",example="password12345"),
 * )
 */
class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'Oauth_token',
        'fcm_token',
    ];

    protected $guard_name = 'customer';

    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
