<?php

namespace admin\users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Kyslik\ColumnSortable\Sortable;

class UserRole extends Model
{
    use HasFactory, Sortable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'status',
    ];

    protected $sortable = [
        'name',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user_role) {
            if (empty($user_role->slug)) {
                $user_role->slug = Str::slug($user_role->name, '_');
            }
        });

        static::updating(function ($user_role) {
            if ($user_role->isDirty('name')) {
                $user_role->slug = Str::slug($user_role->name, '_');
            }
        });
    }

    public function scopeFilter($query, $name)
    {
        if ($name) {
            return $query->where('name', 'like', '%' . $name . '%');
        }
        return $query;
    }
    /**
     * filter by status
     */
    public function scopeFilterByStatus($query, $status)
    {
        if (!is_null($status)) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }
}
