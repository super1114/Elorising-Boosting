<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class Review extends Model
  {

    protected $table    = "review";
    protected $fillable = [
      'orderId',
      'userId',
      'username',
      'review_text',
      'stars',
      'updated_at',
      'created_at',
    ];

    public function getStartRank($id) {
      $order = CompleteOrder::where('orderId', '=', $id)->first();
      return $order->start_rank;
    }

    public function getEndRank($id) {
      $order = CompleteOrder::where('orderId', '=', $id)->first();
      return $order->end_rank;
    }

    public function getPrice($id) {
      $order = CompleteOrder::where('orderId', '=', $id)->first();
      return $order->price;
    }

  }
