<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class Checkout extends Model
  {

    protected $table    = "checkout";
    protected $fillable = [
      'userId',
      'price',
      'payment',
      'paypal',
      'updated_at',
      'created_at',
    ];

    public function getBoosterOpgg($id) {
      $booster = User::find($id);
      return $booster->opgg;
    }

    public function getBoosterUsername($id) {
      $booster = User::find($id);
      return $booster->username;
    }

    public function getBoosterPaypal($id) {
      $booster = User::find($id);
      return $booster->username;
    }

  }
