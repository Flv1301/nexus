<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 31/01/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CaseAttachmentRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        if (!Gate::allows('caso')) {
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $allowedFileTypes = [
            'txt', 'csv', 'htm', 'html', 'odt', 'ods',
            'mp3', 'wav', 'ogg', 'm4a',
            'jpg', 'jpeg', 'png', 'gif', 'bmp',
            'doc', 'docx', 'xls', 'xlsx',
            'zip', 'rar', '7z',
            'pdf',
            'mp4', 'avi', 'mov', 'mkv'
        ];

        return [
            'file_type' => Rule::requiredIf(!empty($this->file_type)),
            'files' => Rule::requiredIf($this->hasFile('files')),
            'files.*' => [
                'file',
                'mimes:' . implode(',', $allowedFileTypes)
            ]
        ];
    }
}
