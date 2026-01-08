<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetOtp extends Model
{
   // These allow Laravel to fill these columns in the database
    protected $fillable = ['email', 'otp'];
}
