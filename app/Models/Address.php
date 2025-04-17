<?php

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use Uuids;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    public static function label($coinName): string
    {
        if ($coinName == 'btcm')
            return 'btc pubkey';
        return $coinName;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function getCoinAttribute($coin): string
    {
        if ($coin =='btcm')
            return 'btc pubkey';
        return $coin;
    }

    public function getAddedAgoAttribute(): string
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}
