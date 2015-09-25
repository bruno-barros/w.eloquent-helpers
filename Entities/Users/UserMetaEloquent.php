<?php  namespace App\Entities\Users;

use Illuminate\Database\Eloquent\Model;


/**
 * UserMetaEloquent
 * 
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright	Copyright (c) 2015 Bruno Barros
 */
class UserMetaEloquent extends Model{

    protected $primaryKey = 'umeta_id';
    
    protected $table = 'usermeta';

    public $timestamps = false;

    protected $fillable = ['user_id',
                           'created_at',
                           'data_documentacao',
                           'prazo_documentacao',
                           'analise_documentacao',
                           'data_pendente',
                           'prazo_pendente',
                           'analise_pendente'];


    protected $dates = ['created_at'];


    public function user()
    {
        return $this->belongsTo('App\Entities\Users\UserEloquent');
    }

}