<?php
// Script de debug para testar busca por telefone
// Execute: php test-debug-search.php

echo "🔍 Debug da Busca por Telefone\n";
echo "==============================\n\n";

// Simular dados de entrada
$phoneInput = "(91) 98069-6190";
$phoneClean = preg_replace('/\D/', '', $phoneInput);

echo "📞 Entrada: '$phoneInput'\n";
echo "📞 Normalizado: '$phoneClean'\n\n";

// Estratégias de busca SQL
echo "🔍 Estratégias de busca que serão testadas:\n\n";

echo "1. CONCAT(ddd, telephone) LIKE '%$phoneClean%'\n";
echo "   → Busca por número completo concatenado\n\n";

echo "2. telephone LIKE '%$phoneClean%'\n";
echo "   → Busca apenas pelo campo telefone\n\n";

if (strlen($phoneClean) === 11) {
    $ddd = substr($phoneClean, 0, 2);
    $numero = substr($phoneClean, 2);
    echo "3. ddd = '$ddd' AND telephone LIKE '%$numero%'\n";
    echo "   → Busca separada por DDD='$ddd' e telefone='$numero'\n\n";
}

// Queries SQL completas que serão executadas
echo "📋 Query SQL completa para base NEXUS:\n";
echo "=====================================\n";
echo "SELECT persons.id, persons.name, persons.cpf, persons.mother, persons.father,\n";
echo "       to_char(birth_date::date, 'dd/mm/yyyy') as birth_date\n";
echo "FROM persons\n";
echo "WHERE active_orcrim = false\n";
echo "  AND EXISTS (\n";
echo "    SELECT 1\n";
echo "    FROM person_telephones\n";
echo "    JOIN telephones ON person_telephones.telephone_id = telephones.id\n";
echo "    WHERE person_telephones.person_id = persons.id\n";
echo "      AND (\n";
echo "        CONCAT(telephones.ddd, telephones.telephone) LIKE '%$phoneClean%'\n";
echo "        OR telephones.telephone LIKE '%$phoneClean%'\n";

if (strlen($phoneClean) === 11) {
    $ddd = substr($phoneClean, 0, 2);
    $numero = substr($phoneClean, 2);
    echo "        OR (telephones.ddd = '$ddd' AND telephones.telephone LIKE '%$numero%')\n";
}

echo "      )\n";
echo "  )\n";
echo "LIMIT 50;\n\n";

echo "📋 Query SQL completa para base FACCIONADO:\n";
echo "==========================================\n";
echo "SELECT persons.id, persons.name, persons.cpf, persons.mother, persons.father,\n";
echo "       to_char(birth_date::date, 'dd/mm/yyyy') as birth_date\n";
echo "FROM persons\n";
echo "WHERE active_orcrim = true\n";
echo "  AND EXISTS (\n";
echo "    SELECT 1\n";
echo "    FROM person_telephones\n";
echo "    JOIN telephones ON person_telephones.telephone_id = telephones.id\n";
echo "    WHERE person_telephones.person_id = persons.id\n";
echo "      AND (\n";
echo "        CONCAT(telephones.ddd, telephones.telephone) LIKE '%$phoneClean%'\n";
echo "        OR telephones.telephone LIKE '%$phoneClean%'\n";

if (strlen($phoneClean) === 11) {
    $ddd = substr($phoneClean, 0, 2);
    $numero = substr($phoneClean, 2);
    echo "        OR (telephones.ddd = '$ddd' AND telephones.telephone LIKE '%$numero%')\n";
}

echo "      )\n";
echo "  )\n";
echo "LIMIT 50;\n\n";

echo "🎯 Para testar, você pode:\n";
echo "1. Executar essas queries diretamente no banco\n";
echo "2. Verificar se existe a pessoa com DDD='91' e telefone='980696190'\n";
echo "3. Verificar se a pessoa tem active_orcrim = false (Nexus) ou true (Faccionado)\n";
echo "4. Limpar cache da aplicação: php artisan cache:clear\n\n";

echo "🔧 Commands úteis:\n";
echo "- Verificar dados: SELECT * FROM telephones WHERE ddd='91' AND telephone LIKE '%98069%';\n";
echo "- Verificar pessoa: SELECT id, name, active_orcrim FROM persons WHERE id IN (SELECT person_id FROM person_telephones WHERE telephone_id IN (SELECT id FROM telephones WHERE ddd='91'));\n";
echo "- Limpar cache: php artisan cache:clear\n";
?> 