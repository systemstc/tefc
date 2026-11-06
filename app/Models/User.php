<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'status', 'tefc_centre', 'parent_id', 'designation', 'phone'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if the user has a specific role.
     * Treats 'exporter' and 'seller' as equivalent for seamless backward compatibility.
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            if ($role === 'exporter' || $role === 'seller') {
                return $this->roles->contains(fn($r) => in_array($r->slug, ['exporter', 'seller']));
            }
            return $this->roles->contains('slug', $role);
        }

        return !! $role->intersect($this->roles)->count();
    }

    /**
     * Check if the user has a specific permission via roles.
     */
    public function hasPermission($permission)
    {
        return $this->roles->map->permissions->flatten()->unique('id')->contains('slug', $permission);
    }

    public function parentExporter()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function operators()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function isOperator(): bool
    {
        return !empty($this->parent_id) || $this->hasRole('operator');
    }

    public function isMainExporter(): bool
    {
        return !$this->isOperator() && ($this->hasRole('exporter') || $this->hasRole('seller'));
    }

    public function getCompanyUser(): User
    {
        if ($this->isOperator() && $this->parentExporter) {
            return $this->parentExporter;
        }
        return $this;
    }

    public function exporterProfile()
    {
        return $this->hasOne(ExporterProfile::class);
    }

    public function getEffectiveExporterProfileAttribute()
    {
        return $this->getCompanyUser()->exporterProfile;
    }

    public function buyerProfile()
    {
        return $this->hasOne(BuyerProfile::class);
    }

    public function savedSellers()
    {
        return $this->belongsToMany(User::class, 'saved_sellers', 'buyer_id', 'seller_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    public function createdProducts()
    {
        return $this->hasMany(Product::class, 'created_by_user_id');
    }
}
