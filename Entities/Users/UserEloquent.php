<?php namespace App\Entities\Users;

use Illuminate\Database\Eloquent\Model;


/**
 * UserEloquent
 *
 * @author       Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class UserEloquent extends Model
{

    protected $primaryKey = 'ID';
    protected $table      = 'users';

    public $timestamps = false;

    protected $fillable = [
        'user_login',
        'user_pass',
        'user_nicename',
        'user_email',
        'user_url',
        'user_registered',
        'user_activation_key',
        'user_status',
        'display_name',
        'remember_token'
    ];


    protected $dates = ['user_registered'];


    public function history()
    {
        return $this->hasMany('App\Entities\Users\UserHistoryEloquent', 'user_id', 'ID');
    }

    public function metas()
    {
        return $this->hasMany('App\Entities\Users\UserMetaEloquent', 'user_id', 'ID');
    }

}