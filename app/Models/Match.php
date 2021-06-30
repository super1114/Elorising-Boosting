<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class Match extends Model
  {

    protected $table    = "match";
    protected $fillable = [
      'orderId',
      'summonerId',
      'accountId',
      'gameId',
      'win',
      'kda',
      'minion_kills',
      'damage_dealt',
      'gold_earned',
      'double_kills',
      'triple_kills',
      'qudra_kills',
      'penta_kills',
      'created_at',
      'updated_at',
    ];

  }
