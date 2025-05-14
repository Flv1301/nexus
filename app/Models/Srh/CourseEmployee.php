<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Models\Srh;

/**
 * Description of CouseEmployee
 *
 * @author 55919
 */
class CourseEmployee extends \Illuminate\Database\Eloquent\Model{
    //put your code here
    protected $connection = "srh";
    protected $table = "srh_cursos_servidor";

    public static function getCourseByMat($mat){
        return CourseEmployee::query()->where('matricula', '=',$mat)->orderBy('ano_conclusao')->get();
    }
}
