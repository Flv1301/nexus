<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Enums;

enum Databases: string
{
    case SISP = 'sisp';
    case HYDRA = 'hydralb';
    case DPA = 'dpa';
    case SRH = 'srh';
    case SEAP = 'seap';
    case GALTON = 'galton';
    case EQUATORIAL = 'equatorial';
}
