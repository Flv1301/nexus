<?php

namespace App\Helpers;

class StateFlagHelper
{
    /**
     * Retorna a URL da bandeira do estado baseada na UF
     * 
     * @param string|null $uf
     * @return string|null
     */
    public static function getFlagUrl(?string $uf): ?string
    {
        if (empty($uf)) {
            return null;
        }

        $uf = strtoupper(trim($uf));
        
        // Mapear UFs para nomes dos arquivos das bandeiras (siglas em minúsculo)
        $flagMap = [
            'AC' => 'ac.png',
            'AL' => 'al.png',
            'AP' => 'ap.png',
            'AM' => 'am.png',
            'BA' => 'ba.png',
            'CE' => 'ce.png',
            'DF' => 'df.png',
            'ES' => 'es.png',
            'GO' => 'go.png',
            'MA' => 'ma.png',
            'MT' => 'mt.png',
            'MS' => 'ms.png',
            'MG' => 'mg.png',
            'PA' => 'pa.png',
            'PB' => 'pb.png',
            'PR' => 'pr.png',
            'PE' => 'pe.png',
            'PI' => 'pi.png',
            'RJ' => 'rj.png',
            'RN' => 'rn.png',
            'RS' => 'rs.png',
            'RO' => 'ro.png',
            'RR' => 'rr.png',
            'SC' => 'sc.png',
            'SP' => 'sp.png',
            'SE' => 'se.png',
            'TO' => 'to.png',
        ];

        if (isset($flagMap[$uf])) {
            return '/images/flags/' . $flagMap[$uf];
        }

        return null;
    }

    /**
     * Retorna o HTML da bandeira com classe CSS
     * 
     * @param string|null $uf
     * @param string $title
     * @return string
     */
    public static function getFlagHtml(?string $uf, string $title = ''): string
    {
        if (empty($uf)) {
            return '';
        }

        $uf = strtoupper(trim($uf));
        $flagUrl = self::getFlagUrl($uf);
        $title = $title ?: self::getStateName($uf);
        
        if (!$flagUrl) {
            return sprintf(
                '<span class="state-flag-fallback" title="%s">%s</span>',
                $title,
                $uf
            );
        }

        return sprintf(
            '<img src="%s" alt="Bandeira %s" title="%s" class="state-flag" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'inline-block\';"><span class="state-flag-fallback" style="display:none;" title="%s">%s</span>',
            $flagUrl,
            $uf,
            $title,
            $title,
            $uf
        );
    }

    /**
     * Retorna o nome completo do estado baseado na UF
     * 
     * @param string|null $uf
     * @return string|null
     */
    public static function getStateName(?string $uf): ?string
    {
        if (empty($uf)) {
            return null;
        }

        $uf = strtoupper(trim($uf));
        
        $stateNames = [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espírito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins',
        ];

        return $stateNames[$uf] ?? null;
    }
} 