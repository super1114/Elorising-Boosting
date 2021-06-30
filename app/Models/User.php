<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class User extends Model
  {

    protected $table    = "user";
    protected $fillable = [
      'username',
      'email',
      'password',
      'skype',
      'discord',
      'paypal',
      'opgg',
      'procentage',
      'lol_summonerName',
      'lol_username',
      'lol_password',
      'lol_server',
      'lol_summonerId',
      'lol_accountId',
      'lol_latestGameId',
      'user_type',
      'created_at',
      'updated_at',
    ];

  }
