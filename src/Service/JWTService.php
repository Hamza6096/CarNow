<?php

declare(strict_types=1);

namespace App\Service;

use DateTimeImmutable;

class JWTService
{
    //Générer le token

    /**
     * Génération du JWT token
     * @param array $header
     * @param array $payload
     * @param string $secret
     * @param int $validity
     * @return string
     */
    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string
    {
        if($validity > 0 ){
            $now = new DateTimeImmutable();
            $exp = $now->getTimestamp() + $validity;

            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $exp;
        }


        //Encoder en base64
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        //Nettoyer les valeurs encodées (retrais des '+', '/' et '=')
        $base64Header = str_replace(['+','/','='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+','/','='], ['-', '_', ''], $base64Payload);

        //Génerer la signature
        $secret = base64_encode($secret);

        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);

        $base64Signature = base64_encode($signature);

        $base64Signature = str_replace(['+','/','='], ['-', '_', ''], $base64Signature);

        //Créer le token
        $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;

        return $jwt;
    }

    //Vérifier que le token est valide
    public function isValid(string $token):bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token
        ) === 1;
    }

    //Récuperer le Header
    public function getHeader(string $token):array
    {
        //démonter le token
        $array = explode('.', $token);
        //On décode le payload
        $header = json_decode(base64_decode($array[0]), true);
        return $header;
    }

    //Récuperer le Payload
    public function getPayload(string $token):array
    {
        //démonter le token
        $array = explode('.', $token);
        //On décode le payload
        $payload = json_decode(base64_decode($array[1]), true);
        return $payload;
    }

    //Vérifier si le token a expirer
    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);
        $now = new DateTimeImmutable();

        return $payload['exp'] < $now->getTimestamp();
    }

    //Vérifier la signature du token
    public function check(string $token, string $secret)
    {
        //Récuperer le header et loader
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        //Regénerer le token pour vérifier la signature
        $verifToken = $this->generate($header, $payload, $secret,0);

        return $token === $verifToken;
    }
}