<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 11/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Models\Views;

use App\Helpers\Str;
use App\Models\Cases\Cases;
use App\Models\Data\Telephone;
use App\Models\VCard\VCardPhone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ViewCaseFileWhatsappCall extends Model
{
    /** @var string */
    protected $table = 'case_file_whatsapp_calls_view';

    /**
     * @return HasOne
     */
    public function cases(): HasOne
    {
        return $this->hasOne(Cases::class, 'id', 'case_id');
    }

    /**
     * @return mixed
     */
    public function vcard(): mixed
    {
        $telephones = array_filter([$this->sender, $this->from, $this->to, $this->call_creator], 'strlen');
        return VCardPhone::whereIn('number', $telephones);
    }


    public function telephone(string $telephone)
    {
        $telephone = Str::parsePhoneNumberBr($telephone);
        $telephone = array_filter($telephone, 'strlen');
        if ($telephone) {
            return Telephone::where('telephone', $telephone['number'])
                ->when($telephone['ddd'], fn($query, $ddd) => $query->where('ddd', $ddd));
        }
        return collect();
    }
}
