<?php

namespace App\Services;

use App\Models\Person\Person;
use App\Models\User;
use App\Models\VCard\VCard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use JeroenDesloovere\VCard\VCardParser;

class VCardService
{
    /** @var VCardParser */
    private VCardParser $vCard;
    /** @var UploadedFile */
    private UploadedFile $file;
    /** @var Person */
    private Person $person;
    /** @var Authenticatable|User|null */
    protected User|Authenticatable|null $user;

    /**
     * @param Person $person
     * @param UploadedFile $file
     * @param string $pathVCard
     */
    public function __construct(Person $person, UploadedFile $file, string $pathVCard)
    {
        $this->person = $person;
        $this->vCard = VCardParser::parseFromFile(Storage::disk('tmp')->path($pathVCard));
        $this->file = $file;
        $this->user = Auth::user();
    }

    /**
     * @return bool
     */
    public function store(): bool
    {
        try {
            $cards = $this->vCard->getCards();
            $uploadfile = UploadFileService::getForFile($this->file);

            if (!$cards) {
                return false;
            }

            if ($uploadfile->count()) {
                $uploadfile->delete();
            }

            $uploadfile = UploadFileService::store($this->file);

            foreach ($cards as $value) {
                $value->person_id = $this->person->id;
                $value->uploadfile_id = $uploadfile->id;
                $value->user_id = $this->user->id;
                $value->unity_id = $this->user->unity_id;
                $value->sector_id = $this->user->sector_id;
                $vcard = VCard::create((array)$value);

                foreach ($value->phone as $phonesArray) {
                    foreach ($phonesArray as $phone) {
                        $vcard->phones()->create(['number' => $phone]);
                    }
                }
            }

            return true;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }
    }
}
