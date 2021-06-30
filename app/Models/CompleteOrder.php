<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class CompleteOrder extends Model
  {

    protected $table    = "complete_order";
    protected $fillable = [
      'orderId',
      'price',
      'procentage',
      'order_type',
      'start_rank',
      'end_rank',
      'win_loss',
      'booster_paypal',
      'boosterId',
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

  }
