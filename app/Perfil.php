<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Persona extends Model{

        protected $fillable = [
            'descripcion', 'nombre_perfil',
        ];

        protected $table = 'perfil';
        
        protected $primaryKey = 'id_perfil';

        public function personas(){

            return $this->hasMany('App\Persona');

        }
    }

?>