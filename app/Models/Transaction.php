<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Listing;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'listing_id',
        'start_date',
        'end_date',
        'price_per_day',
        'total_day',
        'fee',
        'total_price',
        'status',
    ] ;

    public function setListingIdAttribute($value){
        $listing        = Listing::find($value);
        $total_day      = Carbon::createFromDate($this->attributes["start_date"])->diffInDays($this->attributes["end_date"]) + 1;
        $total_price    = $listing->price_per_day * $total_day;
        $fee            = $total_price * 0.1;

        $this->attributes['listing_id'] = $value;
        $this->attributes['price_per_day'] = $listing->price_per_day;
        $this->attributes['total_day'] = $total_day;
        $this->attributes['fee'] = $fee;
        $this->attributes['total_price'] = $total_price + $fee;
    }

    /**
     * Get the user that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the listing that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}
