<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentCards extends Model
{
     use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table;
    
    protected $fillable = [
        'user_id', 'cardId', 'fingerPrint', 'isPrimary','last4','brand'
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
            $this->table = 'paymentCards';
    }
}
