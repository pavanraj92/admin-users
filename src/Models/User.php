<?php

namespace admin\users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Kyslik\ColumnSortable\Sortable;

class User extends Model
{
    use HasFactory, Notifiable, SoftDeletes, Sortable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'role_id',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'status'
    ];

    protected $sortable = [
        'name',
        'email',
        'status',
        'created_at',
    ];


    public function scopeFilter($query, $name)
    {
        if ($name) {
            return $query->where(function ($q) use ($name) {
                // full name filter
                $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", '%' . $name . '%')
                    ->orWhere('email', 'like', '%' . $name . '%')
                    ->orWhere('mobile', 'like', '%' . $name . '%')
                    ->orWhere('first_name', 'like', '%' . $name . '%')
                    ->orWhere('last_name', 'like', '%' . $name . '%');
            });
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

    public function getFullNameAttribute()
    {
        $first = trim($this->first_name ?? '');
        $last = trim($this->last_name ?? '');
        return trim("{$first} {$last}");
    }

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }
}
