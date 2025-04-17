<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Exceptions\RequestException;
use App\Traits\Uuids;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class User extends Authenticatable
{
    use Uuids;

    public static array $permissions = [
        'categories',
        'messages',
        'users',
        'products',
        'logs',
        'disputes',
        'tickets',
        'vendorpurchase',
        'purchases'
    ];
    public static array $permissionsLong = [
        'categories' =>'Categories',
        'messages' => 'Messages',
        'users' => 'Users',
        'products' => 'Products',
        'logs' => 'Logs',
        'disputes' => 'Disputes',
        'tickets' => 'Tickets',
        'vendorpurchase' => 'Vendor Purchases',
        'purchases' => 'Purchases'
    ];

    public $incrementing = false;


    protected $fillable = [
        'name',
        'email',
        'password',
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
    private mixed $pgp_key;
    private bool $login_2fa;

    public static function findByUsername(string $username): User
    {
        $user = self::where('username',$username)->first();
        if ($user == null){
            throw new NotFoundHttpException('User not found!');
        }
        return $user;
    }

    public function setRememberToken($value)
    {
    }

    public function hasPGP(): bool
    {
        return $this->pgp_key != null;
    }

    public function pgpKeys(): HasMany
    {
        return $this -> hasMany(PGPKey::class,'user_id','id');
    }

    public function set2fa($turn)
    {
        if ($turn == true && $this->pgp_key == null)
            throw  new RequestException("To turn on the Two Factor Authetication you will need to add PGP key first!");
        else{
            //set the login 2fa
            $this->login_2fa = $turn == true;
            $this->save();
        }
    }

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

    public function referredBy(): HasOne
    {
        return $this->hasOne(User::class,'id','referred_by');
    }

    public function hasReferredBy(): bool
    {
        return $this -> referredBy() != null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setAddress($address,$coin = 'btc'): void
    {
        $newAddress = new Address();
        $newAddress->address = $address;
        $newAddress -> user_id = $this -> id;
        $newAddress -> coin = $coin;
        $newAddress -> save();

    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class,'user_id','id');
    }

    /**
     * @throws RequestException
     */
    public function coinAddress($coin)
    {
        if(!in_array($coin, array_keys(config('coins.coin_list'))))
            throw new RequestException('Purchase completion attempt unsuccessful, coin not suported by marketpalce');

        $usersAddress = $this->addresses()->where('coin', $coin)->orderByDesc('created_at')->first();
        if(is_null($usersAddress) && $coin == 'btcm')
            throw new RequestException('User ' . $this -> username . ' doesn\'t have a valid public key for making multisig address!');
        if(is_null($usersAddress))
            throw new RequestException('User ' . $this -> username . ' doesn\'t have a valid address for sending funds! If this is user who referred you please notify him!');
        return $usersAddress;
    }

    /**
     * @throws RequestException
     */
    public function numberOfAddresses($coin): int
    {
        if(!in_array($coin, array_keys(config('coins.coin_list'))))
            throw new RequestException('There is no coin under that name!');

        return $this -> addresses() -> where('coin', $coin) -> count();
    }
}
