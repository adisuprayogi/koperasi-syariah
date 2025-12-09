<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'first_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'first_login' => 'boolean',
        'role' => 'string',
    ];

    /**
     * Get the pengurus associated with the user.
     */
    public function pengurus()
    {
        return $this->hasOne(Pengurus::class);
    }

    /**
     * Get the anggota associated with the user.
     */
    public function anggota()
    {
        return $this->hasOne(Anggota::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is pengurus
     */
    public function isPengurus()
    {
        return $this->role === 'pengurus';
    }

    /**
     * Check if user is anggota
     */
    public function isAnggota()
    {
        return $this->role === 'anggota';
    }

    /**
     * Scope untuk admin users
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope untuk pengurus users
     */
    public function scopePengurus($query)
    {
        return $query->where('role', 'pengurus');
    }

    /**
     * Scope untuk anggota users
     */
    public function scopeAnggota($query)
    {
        return $query->where('role', 'anggota');
    }

    /**
     * Scope untuk users yang belum login pertama kali
     */
    public function scopeFirstLogin($query)
    {
        return $query->where('first_login', true);
    }

    /**
     * Get role label
     */
    public function getRoleLabelAttribute()
    {
        $roles = [
            'admin' => 'Admin',
            'pengurus' => 'Pengurus',
            'anggota' => 'Anggota'
        ];

        return $roles[$this->role] ?? $this->role;
    }

    /**
     * Get related data based on role
     */
    public function getRelatedData()
    {
        if ($this->isPengurus()) {
            return $this->pengurus;
        } elseif ($this->isAnggota()) {
            return $this->anggota;
        }

        return null;
    }
}
