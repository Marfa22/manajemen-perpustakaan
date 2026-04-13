<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_MENU_ADMIN = 'menu_admin';

    public const ACCESS_DOCUMENTS = 'documents';
    public const ACCESS_INVENTORY = 'inventory';
    public const ACCESS_FOUND_ITEMS = 'found_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'permissions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'permissions' => 'array',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function accessLabels(): array
    {
        return [
            self::ACCESS_DOCUMENTS => 'Dokumen',
            self::ACCESS_INVENTORY => 'Barang Kantor (Inventory)',
            self::ACCESS_FOUND_ITEMS => 'Barang Temuan',
        ];
    }

    public static function superAdminRole(): string
    {
        return self::ROLE_SUPER_ADMIN;
    }

    public static function menuAdminRole(): string
    {
        return self::ROLE_MENU_ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function hasAccess(string $access): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($access, $this->normalizedPermissions(), true);
    }

    /**
     * @return list<string>
     */
    public function normalizedPermissions(): array
    {
        $allowedAccesses = array_keys(self::accessLabels());

        return collect($this->permissions ?? [])
            ->map(function ($permission) {
                return (string) $permission;
            })
            ->filter(function ($permission) use ($allowedAccesses) {
                return in_array($permission, $allowedAccesses, true);
            })
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @param array<int, mixed> $permissions
     */
    public function syncPermissions(array $permissions): void
    {
        $this->permissions = collect($permissions)
            ->map(function ($permission) {
                return (string) $permission;
            })
            ->filter(function ($permission) {
                return array_key_exists($permission, self::accessLabels());
            })
            ->unique()
            ->values()
            ->all();
    }
}
