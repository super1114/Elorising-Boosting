<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class Api extends Model
  {

    protected $table    = "apikey";
    protected $fillable = [
      'name',
      'key',
      'updated_at',
      'created_at',
    ];

  }
