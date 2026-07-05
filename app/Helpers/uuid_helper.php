<?php

/**
 * Gera um UUID versão 4 (aleatório) conforme RFC 4122.
 *
 * Utiliza random_bytes() do PHP 7+ para garantir entropia criptográfica.
 * Nenhuma dependência externa necessária.
 *
 * @return string UUID v4 no formato xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
 */
function uuid(): string
{
    $data = random_bytes(16);

    // Define o version number (4) nos 4 bits mais significativos do byte 7 (time_hi_and_version)
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);

    // Define a variant (RFC 4122) nos 2 bits mais significativos do byte 9 (clock_seq_hi_and_reserved)
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Formata como UUID: 8-4-4-4-12
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
