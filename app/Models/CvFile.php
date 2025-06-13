<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class CvFile extends Model
  {
      protected $fillable = ['user_id', 'file_name', 'file_url', 'file_type'];

      public function user()
      {
          return $this->belongsTo(User::class);
      }
  }