<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class Referral extends Model
  {

    protected $table    = "referral";
    protected $fillable = [
      'referral_name',
      'referral_code',
      'referral_discount',
      'updated_at',
      'created_at',
    ];

  }
