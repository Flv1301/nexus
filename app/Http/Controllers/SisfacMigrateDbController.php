<?php

namespace App\Http\Controllers;

use App\Models\Data\Email;
use App\Models\Data\Image;
use App\Models\Data\Telephone;
use App\Models\Person\Person;
use App\Models\Sisfac\Sisfac;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SisfacMigrateDbController extends Controller
{
    public function migrate()
    {
        return response()->json(['message' => 'TENTOU MAIS NÃO CONSEGUIU!']);
        try {
            Sisfac::distinct()->chunk(100, function ($pessoas) {
                foreach ($pessoas as $pessoa) {
                    $new = new Person();
                    $new->name = $pessoa->nome;
                    $new->observation = $pessoa->observacao;
                    if (!empty($pessoa->documento)) {
                        $dados = $this->doc($pessoa->documento);
                        foreach ($dados as $dado) {
                            if (strlen(preg_replace("/\D/", "", $dado)) == 11 and $pessoa->id != 7140) {
                                $new->cpf = $dado;
                            } elseif ($pessoa->id == 7140) {
                                $new->cpf = $dados[8];
                                $new->rg = $dados[0] . ' ' . $dados[2] . ' ' . $dados[3] . ' ' . $dados[4];
                                $new->observation .= "\n\n" . $dados[5] . ' ' . $dados[6];
                            } else {
                                $new->rg .= $dado;
                            }
                        }
                    }

                    $person = !empty($new->cpf) ? Person::where('cpf', '=', $new->cpf)->count() : 0;

                    if ($person > 0) {
                        logger(' CPF-' . $new->cpf);
                        $new->observation .= "\n\n" . 'CPF: ' . $new->cpf . 'CADASTRO DUPLICADO';
                        unset($new->cpf);
                    }

                    $new->birth_date = $pessoa->dt_nascimento;
                    $new->mother = $pessoa->mae;
                    $new->father = $pessoa->pai;
                    $new->nickname = $pessoa->alcunha;
                    $new->orcrim = $pessoa->orcrim;
                    $new->active_orcrim = true;
                    $new->orcrim_occupation_area = $pessoa->area_atuacao;
                    $new->orcrim_office = $pessoa->cargo;
                    $new->detainee_registration = $pessoa->infopen;
                    $new->dead = $pessoa->status == 0;
                    $new->user_id = 2;
                    $new->created_at = $pessoa->created_at;
                    $new->updated_at = $pessoa->updated_at;
                    $new->warrant = $pessoa->mandado == 'S';
                    $new->save();
                    if ($pessoa->telefone) {
                        $telefones = $this->tel($pessoa->telefone);

                        foreach ($telefones as $telefone) {
                            if (strlen($telefone) == 11) {
                                $ddd = substr($telefone, 0, 2);
                                $numero = substr($telefone, 2);
                            } else {
                                $numero = $telefone;
                            }
                            $tel = new Telephone();
                            $tel->ddd = $ddd ?? null;
                            $tel->telephone = $numero;
                            $tel->save();
                            $new->telephones()->attach($tel);
                        }
                    }

                    if ($pessoa->imei) {
                        $imeis = preg_replace('/[^0-9]/', '', $pessoa->imei);
                        $imeis = str_split($imeis, 15);

                        foreach ($imeis as $imei) {
                            $tel = new Telephone();
                            $tel->imei = $imei;
                            $tel->save();
                            $new->telephones()->attach($tel);
                        }
                    }

                    if ($pessoa->email and Str::of($pessoa->email)->contains('@')) {
                        $email = new Email();
                        $email->email = $pessoa->email;
                        $email->save();
                        $new->emails()->attach($email);
                    }

                    $images = DB::connection('pgsql2')->table('sisfac.pessoa_foto')->where('fk_pessoa', $pessoa->id)->get();

                    if ($images) {
                        foreach ($images as $image) {
                            $blob = $image->foto;
                            if (get_resource_type($blob) === 'stream') {
                                $blob = stream_get_contents($blob);
                            }
                            $finfo = finfo_open(FILEINFO_MIME_TYPE);
                            $mime_type = finfo_buffer($finfo, $blob);
                            finfo_close($finfo);
                            $extensao = strtolower(substr(strrchr($mime_type, '/'), 1));

                            $filename = Str::slug(Str::random() . '-' . $pessoa->nome) . '.' . $extensao;
                            $path = 'images/' . $filename;
                            if (Storage::put($path, $blob)) {
                                $newimage = new Image();
                                $newimage->description = $filename;
                                $newimage->path = $path;
                                $newimage->created_at = $image->created_at;
                                $newimage->updated_at = $image->updated_at;
                                $newimage->save();
                                $new->images()->attach($newimage);
                            }
                        }
                    }
                    unset($pessoa);
                    unset($new);
                    unset($images);
                }
            });
            return response()->json(['message' => 'Migração concluída com sucesso.']);
        } catch (\Exception $e) {
            logger($e->getMessage());
            return response()->json(['message' => 'Ocorreu um erro durante a migração.'], 500);
        }
    }

    private function doc($dado)
    {
        //logger('DOC - ' . $dado);
        $nova_string = preg_replace('/\d{3}[\.-]?\d{3}[\.-]?\d{3}[\.-]?\d{2}/', '$0', $dado);
        $nova_string = str_replace(array('.', '-'), '', $nova_string);
        $nova_string = str_replace(array('/', '|', ';'), ' ', $nova_string);
        $nova_string = str_replace(array('CPF:', 'cpf;', 'CPF', 'cpf', 'C´F:', 'CFP', 'cfp'), '', $nova_string);
        $string = preg_replace("/[^[:alnum:]]/u", " ", $nova_string);
        $array = explode(" ", $string);
        return $array;
    }

    private function tel($numero)
    {
        //logger('TEL - ' . $numero);
        $string = preg_replace("/[\(\)\-\+\s]/", "", $numero);
        $string = str_replace("/", ",", $string);
        $string = str_replace(' ', '', $string);
        return explode(',', $string);
    }

}
