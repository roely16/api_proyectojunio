<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Persona extends Model{

        protected $fillable = [
            'nombre', 'apellido',
        ];

        protected $hidden = [
            'password',
        ];

        protected $table = 'persona';

        protected $primaryKey = 'id_persona';

        public function perfil(){

            return $this->belongsTo('App\Perfil');

        }
    }

?>