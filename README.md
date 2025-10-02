![Logo](https://gitlab.com/mpe/mpe/-/raw/main/public/images/logo_mppa_transparente.png)

<p align="center">
<a href=""><img src="https://img.shields.io/badge/Vers%C3%A3o-v1.0-green" alt="Versão"></a>
<a href=""><img src="https://img.shields.io/badge/Licen%C3%A7a-Propriet%C3%A1ria-orange" alt="Versão"></a>
</p>

## Sobre o Nexus

O Nexus é um projeto do Ministério Público do Estado do Pará para servir de suporte as investigações.

## Autores

-   IPC Herbety Thiago Maciel
-   IPC Paulo Braga


## Licença

O Nexus é um software proprietário do Ministério Público do Estado do Pará

## Instalação

Instale as dependências.


```composer install```

```npm install```

```npm run dev``` **Use uma das opções** ```dev``` ou ```production```.

Execute a criação das tabelas ou atualize.

```php artisan migrate```

*** 

## Stacks

**Front-end:** AdminLte 3

**Back-end:** PHP ^8.1, Laravel 9

**Banco:** Postgresql-16


### Comunicação com APIs externas

Para comunicar com uma API externa usando URI e api-key, coloque estas variáveis no seu arquivo `.env`:

```
EXTERNAL_PGE_API_URI=https://sida.pge.pa.gov.br
EXTERNAL_PGE_API_KEY=suachaveaqui
```

Use o cliente HTTP reutilizável criado em `app/APIs/PgeApi.php`:

Exemplo rápido no seu código:

```php
use App\APIs\PgeApi;

$client = new PgeApi(); // lê EXTERNAL_PGE_API_URI e EXTERNAL_PGE_API_KEY do .env
$resp = $client->get('consultamp', ['documento' => '12345678901']);
if (isset($resp['error'])) {
	// tratar erro
}
```

O cliente automaticamente envia `X-API-KEY` ou `Authorization: Bearer ...` se a chave começar com "Bearer ".

