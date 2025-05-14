<?php

namespace App\Http\Controllers;

use App\Helpers\DataFormatHelper;
use App\Http\Controllers\Gi2\Gi2Controller;
use App\Mail\LetterMail;
use App\Models\Gi2;
use App\Models\LetterCompany;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;

class LetterController extends Controller
{
    public function index()
    {
        return view("qualify.letter.index");
    }

    public function generate(Request $request)
    {

        $wyh = $request->input("whatyouhave");
        $id = $request->input("id");
        $companys = $request->input("companysIds");


        foreach ($companys as $company) {
            $ObjCompany = LetterCompany::find($company);

            $data = [

                'date' => date('d/m/Y'),
                'companyName' => $ObjCompany->nome,
                'wyh' => $wyh,
                'id' => $id
            ];
            $namepdf = "fileteste" . md5(time()) . ".pdf";

            $pdf = PDF::loadView('qualify.letter.templateletter.registrationdata', $data)->setOptions(['defaultFont' => 'serif', 'isRemoteEnabled' => 'true']);
            Storage::put("public\\letters\\" . $namepdf, $pdf->output());
            // $pdf->save(storage_path("public\\letters\\".$namepdf.".pdf"));

            //Storage::put('storage/public/letters/anexo.pdf', $pdf->output());
            //$email = new LetterMail($ObjCompany->email,$pdf->output());
            $email = new LetterMail($ObjCompany->email, $namepdf);
            // $email->attachData($pdf->output(), "anexo.pdf");

            Mail::to($email->mailCompany)->queue($email);
        }

    }

    public function createPDF($companys, $wyh, $id)
    {
        // retreive all records from db

        // share data to view
        $data = [

            'date' => date('d/m/Y'),
            'company' => $companys,
            'wyh' => $wyh,
            'id' => $id
        ];

        $pdf = PDF::loadView('qualify.letter.templateletter.registrationdata', $data);

        return $pdf->download('teste.pdf');
    }

    public function createReportOperator(Request $request)
    {
        $data = new DataFormatHelper();
        $data = $data->getHelper();
        $id = Gi2Controller::filterIntersectionReport($request->input('nomeOperadora'), $request->input('nomeOperacao'));


        // retreive all records from db

        // share data to view
        $data = [

            'data' => $data,
            'companyName' => $request->input('nomeOperadora'),
            'wyh' => 'IMEI',
            'id' => $id
        ];

        $pdf = PDF::loadView('qualify.letter.templateletter.registrationdata', $data);

        return $pdf->download('teste.pdf');
    }

    public function generateLetter(Request $request)
    {
        $data = new DataFormatHelper();
        $datacalendar = $data->getHelper();

        $wyh = $request->input("whatyouhave");
        $id = $request->input("id");
        $companys = $request->input("companysIds");
        $number = $request->input("number");
        $content = $request->input("content");

        $pdfs = [];

        foreach ($companys as $company) {
            $ObjCompany = LetterCompany::find($company);

            $data = [
                'data' => $datacalendar,
                'companyName' => $ObjCompany->nome,
                'address' => $ObjCompany->endereco,
                'wyh' => $wyh,
                'id' => $id,
                'number' => $number,
                'content' => $content,
            ];

            $namepdf = "oficio" . md5(time()) . ".pdf";

            // Gere o PDF e faça o download diretamente
            $pdf = PDF::loadView('qualify.letter.templateletter.registrationdatageneral', $data);
            return $pdf->download($namepdf);


        }
    }

public function sendGenerateLetter(Request $request) {
    $data = new DataFormatHelper();
    $datacalendar = $data->getHelper();

    $wyh = $request->input("whatyouhave");
    $id = $request->input("id");
    $companys = $request->input("companysIds");
    $number = $request->input("number");
    $content = $request->input("content");

    $pdfs = [];

    foreach ($companys as $company) {
        $ObjCompany = LetterCompany::find($company);

        $data = [
            'data' => $datacalendar,
            'companyName' => $ObjCompany->nome,
            'wyh' => $wyh,
            'id' => $id,
            'number' => $number,
            'content' => $content,
        ];

        $namepdf = "fileteste" . md5(time()) . ".pdf";

        // Gere o PDF e faça o download diretamente
        $pdf = PDF::loadView('qualify.letter.templateletter.registrationdatageneral', $data);
        Storage::put("public\\letters\\".$namepdf,$pdf->output());
        // $pdf->save(storage_path("public\\letters\\".$namepdf.".pdf"));

        //Storage::put('storage/public/letters/anexo.pdf', $pdf->output());
        //$email = new LetterMail($ObjCompany->email,$pdf->output());
        $email = new LetterMail($ObjCompany->email, $namepdf);
        // $email->attachData($pdf->output(), "anexo.pdf");

        Mail::to($email->mailCompany)->queue($email);
    }

}

    public function gerar($data){
        $pdf = PDF::loadView('qualify.letter.templateletter.registrationdatageneral', $data);


       return $pdf->download('teste.pdf');
    }
}
