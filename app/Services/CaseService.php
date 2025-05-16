<?php

namespace App\Services;

use App\FileLayouts\CaseDocumentInterface;
use App\Http\Requests\CaseAttachmentRequest;
use App\Models\Cases\CaseFile;
use App\Models\Cases\Cases;
use App\Models\Cases\CaseSequence;
use App\Models\Departament\Sector;
use App\Models\Departament\Unity;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CaseService
{
    /**
     * @param User|Authenticatable $user
     * @return string
     */
    public static function generateIdentifier(User|Authenticatable $user): string
    {
        $year = date('Y');
        $sequence = CaseSequence::all()->last();
        $number = '0000001';
        if ($sequence && $sequence->year == $year) {
            $number = Str::padLeft($sequence->sequence + 1, 7, 0);
        }
        CaseSequence::create(['sequence' => $number, 'year' => $year]);
        return "{$number}.{$year}.{$user->unity_id}.{$user->sector_id}.{$user->id}";
    }

    /**
     * @param UploadedFile $file
     * @param string $filename
     * @param string $type
     * @param Cases $case
     * @return mixed
     * @throw ModelNotFoundException
     */
    public static function storage(UploadedFile $file, string $filename, string $type, Cases $case): mixed
    {
        $layout = config('file.file_type_layout.' . $type);
        $model = new $layout($file, $filename, $case);
        if ($model instanceof CaseDocumentInterface) {
            return $model->store();
        }
        throw new ModelNotFoundException('Modelo de negócio inexistente!');
    }

    /**
     * @param CaseAttachmentRequest $request
     * @param $id
     * @return array
     * @throws \Exception
     */
    public static function attachment(CaseAttachmentRequest $request, $id): array
    {
        try {
            $user = Auth::user();
            $case = Cases::findOrFail($id);
            $ids = [];

            if ($request->hasFile('files')) {
                $type = $request->input('file_type');
                $name = $request->filled('name') ? Str::upper(Str::ascii($request->name)) : null;
                $files = $request->file('files');

                foreach ($files as $file) {
                    $filename = self::fileName($case, $file);
                    $attachment = self::storage($file, $filename, $type, $case);

                    if ($attachment) {
                        $file = CaseFile::create([
                            'case_id' => $case->id,
                            'user_id' => $user->id,
                            'unity_id' => $user->unity_id,
                            'sector_id' => $user->sector_id,
                            'file_type' => config('file.file_type.' . $type),
                            'file_layout' => config('file.file_type_layout.' . $type),
                            'file_alias' => config('file.file_alias.' . $type),
                            'file_id' => $attachment->id,
                            'name' => $name,
                        ]);
                        $ids[] = $file->id;
                    }
                }
            }
            return $ids;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            if (isset($attachment->id)) {
                $attachment->delete();
            }

            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @param $case
     * @param UploadedFile $file
     * @return string
     */
    private static function fileName($case, UploadedFile $file): string
    {
        $filename = Str::slug($case->identifier) . '-' . time() . '-';
        $filename .= in_array($file->extension(), config('file.except_name'))
            ? time() : Str::beforeLast($file->getClientOriginalName(), '.');
        $filename .= '.' . $file->extension();
        return $filename;
    }

    /**
     * @param Authenticatable|User $user
     * @return Collection
     */
    public static function getCaseUserIds(Authenticatable|User $user): Collection
    {
        $casesIds = collect();
        $caseUsersIds = $user->cases->modelKeys();
        $casesIds = $casesIds->concat($caseUsersIds);
        $sharedCasesUsersIds = $user->caseSharing->modelKeys();
        $casesIds = $casesIds->concat($sharedCasesUsersIds);
        $sharedCasesUnitysIds = Unity::find($user->unity_id)->caseSharing->modelKeys();
        $casesIds = $casesIds->concat($sharedCasesUnitysIds);
        $sharedSectorsCasesIds = Sector::find($user->sector_id)->caseSharing->modelKeys();
        $casesIds = $casesIds->concat($sharedSectorsCasesIds);

        return $casesIds->unique();
    }

}
