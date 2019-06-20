<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transactions extends Model
{
     use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table;
    
    protected $fillable = [
        'user_id','order_desc','orderId','paymentCardId','fullPaymentResponse','paymentStatus','amount'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
            parent::__construct($attributes);
            $this->table = 'transactions';
    }
}
