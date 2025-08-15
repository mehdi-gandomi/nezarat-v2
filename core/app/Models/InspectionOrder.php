<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InspectionOrder extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable=[
        'user_id',
        'claim_date_start',
        'claim_date_end',
        'claim_no',
        'claim_file',
        'status'
    ];

    protected $casts = [
        'claim_date_start' => 'date',
        'claim_date_end' => 'date',
    ];

    /**
     * Get the active inspection order
     */
    public static function getActiveOrder()
    {
        return static::where('user_id', auth('backpack')->id())
                    ->where('status', 'active')
                    ->where('claim_date_end', '>=', now()->toDateString())
                    ->first();
    }

    /**
     * Check if there's an active inspection order
     */
    public static function hasActiveOrder()
    {
        return static::getActiveOrder() !== null;
    }

    /**
     * Get active inspection order accessor
     */
    public function getIsActiveAttribute()
    {
        return $this->status === 'active' &&
               $this->claim_date_end >= now()->toDateString();
    }

    /**
     * Get status text attribute
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'فعال' : 'غیرفعال';
    }

    /**
     * Get can edit attribute
     */
    public function getCanEditAttribute()
    {
        return $this->claim_date_end >= now()->toDateString();
    }

    /**
     * Get the user that owns the inspection order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot method to handle automatic status updates
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Set the user_id to the logged-in user
            $model->user_id = auth('backpack')->id();

            // Check if there's already an active order for this user
            if (static::hasActiveOrder()) {
                throw new \Exception('یک دستور بازرسی فعال وجود دارد. لطفاً ابتدا آن را غیرفعال کنید.');
            }
        });

                static::updating(function ($model) {
            // If this order is being set to active, check if another active order exists for this user
            if ($model->isDirty('status') && $model->status === 'active') {
                $existingActive = static::where('id', '!=', $model->id)
                                      ->where('user_id', auth('backpack')->id())
                                      ->where('status', 'active')
                                      ->where('claim_date_end', '>=', now()->toDateString())
                                      ->first();

                if ($existingActive) {
                    throw new \Exception('یک دستور بازرسی فعال دیگر وجود دارد. لطفاً ابتدا آن را غیرفعال کنید.');
                }
            }
        });
    }
}
