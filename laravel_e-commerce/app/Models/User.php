<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

    /**
     *
     * @OA\Schema(
     * required={"name","email","password"},
     * @OA\Xml(name="User"),
     *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
     *     @OA\Property(property="email", type="string", readOnly="true", format="email", description="Admin unique email address", example="Admin@gmail.com"),
     *     @OA\Property(property="name", type="string", readOnly="true", example="Admin"),
     *     @OA\Property(property="contact", type="string", readOnly="true", example="01234567890"),
     *     @OA\Property(property="password", type="string", readOnly="true", format="password",example="password12345"),
     * )
     */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'contact'
    ];
    protected $guard_name = 'web';

    protected $hidden = [
        'password',
        'remember_token',
        "created_at",
        "updated_at",
        "deleted_at",
        "id"
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
