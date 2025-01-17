<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StkRequest extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mpesa_callbacks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone',
        'amount',
        'reference',
        'description',
        'MerchantRequestID',
        'CheckoutRequestID',
        'status',
        'MpesaReceiptNumber',
        'ResultDesc',
        'TransactionDate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'TransactionDate' => 'json',
        'status' => 'integer',
    ];

    public function updateCallbackDetails(array $data): bool
    {
        $this->status = $data['status'] ?? $this->status;
        $this->TransactionDate = $data['TransactionDate'] ?? $this->TransactionDate;
        $this->MpesaReceiptNumber = $data['MpesaReceiptNumber'] ?? $this->MpesaReceiptNumber;
        $this->result_desc = $data['result_desc'] ?? $this->result_desc;

        return $this->save();
    }
}
