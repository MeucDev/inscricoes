<?php

namespace App;

use Exception;
use crocodicstudio\crudbooster\helpers\CRUDBooster;

class TesteEmail
{
    public static function enviarEmail($dados, $slug){
        $data = ['email'=>$dados->email];

        try{
            CRUDBooster::sendEmail(['to'=>$dados->email,'data'=>$data,'template'=>$slug,'attachments'=>[]]);
        }   
        catch(Exception $e){
            print_r("Erro ao enviar email: " . $e->getMessage());
        }

    }
}