<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     title="HYDRA - PCPA",
 *     version="1.0.0",
 *     description="**Polícia Civil do Estado do Pará** - API desenvolvida pelo Ciber Lab para facilitar a integração de sistemas internos da Polícia Civil do Estado do Pará. Esta API permite o acesso seguro a informações críticas, devendo ser usada de acordo com as políticas de segurança e privacidade estabelecidas pela organização.",
 *     termsOfService="https://policiacivil.pa.gov.br/termos-de-uso",
 *     @OA\Contact(
 *         name="Equipe HYDRA - Ciber Lab",
 *         email="pc.hydra@policiacivil.pa.gov.br"
 *     ),
 *     @OA\License(
 *         name="Proprietary",
 *         url="https://hydra.pc.pa.gov.br/licenca"
 *     )
 * )
 *
 * @OA\Server(
 *     url="https://hydra.pc.pa.gov.br/api",
 *     description="Servidor principal"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Use o token JWT para autenticar as requisições. O token deve ser enviado no header `Authorization` no formato `Bearer {token}`."
 * )
 *
 * @OA\Tag(
 *     name="Autenticação",
 *     description="Endpoints relacionados à autenticação de usuários"
 * )
 *
 * @OA\Tag(
 *     name="Consulta de Registros Civis",
 *     description="Endpoints para consulta de registros civis do Estado do Pará"
 * )
 *
 * @OA\Tag(
 *     name="Consulta de Ocorrências SISP",
 *     description="Endpoints para consulta de ocorrências policiais no Estado do Pará"
 * )
 */

class ApiDocumentation
{

}
