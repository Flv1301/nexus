<?php

namespace App\Models;

use App\Models\Sisp\BopRel;
use App\Models\Sisp\BopRelImei;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Gi2 extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $connection = 'pgsql';

    /**
     * @var string
     */
    protected $table = "gi2";
    /**
     * @var string[]
     */
    protected $fillable = [
        "date_time",
        "imsi",
        "imei",
        "random_mac",
        "sv",
        "alvo",
        "domain",
        "grupo",
        "conteudo",
        "guti",
        "msisdn",
        "tipo",
        "anexos",
        "modelo",
        "operador",
        "rede",
        "pais",
        "transmission_operator",
        "transmission_network",
        "equivalent networks",
        "operacao",
        "local",
        "ran",
        "ostatnic",
        "distancia",
        "notas",
        "juntar_contagem",
        "tmsi",
        "a51",
        "a52",
        "a53",
        "nivel_rx",
        "gps_indicator",
        "longitude",
        "latitude",
        "altitude",
        "arfcn",
        "banda",
        "loc_tel_long",
        "loc_tel_lat",
        "loc_tel_alt",
        "loc_tel_exa",
        "loc_tel_pais",
        "loc_tel_reg",
        "loc_tel_cidade",
        "rua",
        "hora_ini",
        "hora_fim",
        "duracao",
        "criador",
        "destinatario",
        "dtmf",
        "sms_sender",
        "sms_receiver",
        "sms_send_time",
        "sms_received_timee",
        "sms_status",
        "falso",
        "sillence_call_type",
        "potencia",
        "intervalo",
        "description"
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return HasOne
     */
    public function bopImei(): HasOne
    {
        return $this->hasOne(BopRelImei::class, 'imei', 'imei');
    }

    public static function getNumberImeis()
    {
//       $query = Gi2::query("select count(*) from gi2.gi2");
//       dd($query);
//        return $query[0]->count;
        return Gi2::query()->count();
    }

    public static function getNumberImeisInGi2()
    {
        $query = DB::select(
            "SELECT count(*) from gi2 inner join sisp.boprel_imei ON sisp.boprel_imei.imei = gi2.gi2.imei"
        );
//        //$query = ("SELECT count(*) from gi2.gi2 inner join sisp.boprel_imei ON sisp.boprel_imei.imei = gi2.gi2.imei");
//       dd($query);
        return $query[0]->count;
        return 0;
    }

    public static function getNameOperations(){
        return Gi2::distinct()->pluck('operacao')->toArray();
    }

    public static function getNameOperators(){
        return Gi2::distinct()->pluck('operador')->toArray();
    }

    public static function treatmentHeader($arraygi2): array
    {
        $headerSample = '"Silent call type","Potência","Intervalo de tempo","Description"';
        $newHeader = array();
        foreach ($arraygi2 as $value) {
            $tempHeader = match ($value) {
                'Date time' => 'date_time',
                'Random MAC' => 'random_mac',
                'Conteúdo' => 'conteudo',
                'País' => 'pais',
                'Transmission Operator' => 'transmission_operator',
                'Transmission Network' => 'transmission_network',
                'Equivalent networks' => 'equivalent_networks',
                'Operação' => 'operacao',
                'Ostatni LAC/TAC' => 'ostatinic',
                'Distância' => 'distancia',
                'Juntar contagem' => 'juntar_contagem',
                'A5/1' => 'a51',
                'A5/2' => 'a52',
                'A5/3' => 'a53',
                'Nível Rx ' => 'nivel_rx',
                'GPS indicator' => 'gps_indicator',
                'Localização do telefone - longitude' => 'loc_tel_long',
                'Localização do telefone - latitude' => 'loc_tel_lat',
                'Localização do telefone - altitude' => 'loc_tel_alt',
                'Localização do telefone - exatidão' => 'loc_tel_exa',
                'Localização do telefone - país' => 'loc_tel_pais',
                'Localização do telefone - região' => 'loc_tel_reg',
                'Localização do telefone - cidade' => 'loc_tel_cidade',
                'Localização do telefone - rua' => 'loc_tel_rua',
                'Hora de início' => 'hora_ini',
                'Hora de fim' => 'hora_fim',
                'Duração' => 'duracao',
                'Destinatário' => 'destinatario',
                'SMS sender' => 'sms_sender',
                'SMS receiver' => 'sms_receiver',
                'SMS send time' => 'sms_send_time',
                'SMS receive time' => 'sms_received_timee',
                'SMS status' => 'sms_status',
                'Silent call type' => 'sillence_call_type',
                'Potência' => 'potencia',
                'Intervalo de tempo' => 'intervalo',
                default => $value
            };

            array_push($newHeader, strtolower($tempHeader));
        }
        return $newHeader;
    }

    public static function tratamentoDeDistancia($distancia)
    {
        if ($distancia === null) {
            return 500;
        } else {
            $valor = explode('-', $distancia)[1];
            $valor = trim($valor);
            $valor = str_replace('m', '', $valor);
            return intval($valor);
        }
    }

}
