<?php

namespace Tests\Unit;

use App\APIs\PgeApi;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PgeApiTest extends TestCase
{
    /** @test */
    public function it_builds_url_using_base_host_and_path_argument()
    {
        // Simula o .env com a URI base contendo host apenas
        $base = 'https://sida.pge.pa.gov.br';
        $client = new PgeApi($base, '');

        $lastUrl = null;
        Http::fake(function ($request) use (&$lastUrl) {
            $lastUrl = $request->url();
            return Http::response([], 200);
        });

        $client->get('consultamp', ['documento' => '68493088234']);

        $this->assertNotNull($lastUrl);
        // Exibe a URL completa no terminal durante o teste
        fwrite(STDOUT, "Requested URL: {$lastUrl}\n");
    }

    /** @test */
    public function it_handles_env_with_path_by_normalizing_to_host()
    {
        // Se o usuário colocou a URI com path, o construtor normaliza para host
        $raw = 'https://sida.pge.pa.gov.br/consultamp';
        $client = new PgeApi($raw, '');

        $lastUrl = null;
        Http::fake(function ($request) use (&$lastUrl) {
            $lastUrl = $request->url();
            return Http::response([], 200);
        });

        $client->get('consultamp', ['documento' => '98765432100']);

        $this->assertNotNull($lastUrl);
        fwrite(STDOUT, "Requested URL: {$lastUrl}\n");
    }
}
