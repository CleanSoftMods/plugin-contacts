<?php namespace WebEd\Plugins\Ecommerce\Addons\Customers\Models;

use WebEd\Plugins\Ecommerce\Addons\Customers\Models\Contracts\CustomerModelContract;
use WebEd\Base\Core\Models\EloquentBase as BaseModel;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Customer extends BaseModel implements CustomerModelContract, AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable;

    use CanResetPassword;

    protected $table = 'customers';

    protected $primaryKey = 'id';

    protected $fillable = [
        'username', 'email', 'password',
        'first_name', 'last_name', 'display_name',
        'sex', 'status', 'phone', 'mobile_phone', 'avatar',
        'birthday', 'description', 'disabled_until',
    ];

    public $timestamps = true;

    /**
     * Hash the password before save to database
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = str_slug($value, '_');
    }
}
