<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class Order extends Model
  {

    protected $table    = "order";
    protected $fillable = [
      'price',
      'currentRank',
      'currentDivision',
      'desiredRank',
      'desiredDivision',
      'specific_spellposition',
      'flash_posistion',
      'favorite_champs',
      'priority_lane_1',
      'priority_1_champions',
      'priority_lane_2',
      'priority_2_champions',
      'priority_lane_3',
      'priority_3_champions',
      'priority_completion',
      'coching',
      'offlinemode',
      'account_warranty',
      'order_done',
      'order_type',
      'order_taken',
      'ordered_by',
      'order_taken_by',
      'orderID',
      'created_at',
      'updated_at',
    ];

    public function getBoosterPaypal($id) {
      $booster = User::find($id);
      return $booster->paypal;
    }

    public function getBoosterProcentage($id) {
      $booster = User::find($id);
      return $booster->procentage;
    }


    public function getWinLoss($id) {
      $wins = Match::where('orderId', '=', $id)->where('win', '=', true)->count();
      $loss = Match::where('orderId', '=', $id)->where('win', '=', false)->count();
      return $wins . "/" . $loss;
    }

  }
