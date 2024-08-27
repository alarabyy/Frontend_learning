<?php 
namespace API\Core;

class JWT {
    public function encode(array $payload)
    {

        $header = json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]);

        $header = $this->base64URLEncode($header);
        $payload = json_encode($payload);
        $payload = $this->base64URLEncode($payload);

        $signature = hash_hmac("sha256", $header . "." . $payload, $GLOBALS['config']['Secret_Key'], true);
        $signature = $this->base64URLEncode($signature);
        return $header . "." . $payload . "." . $signature;
    }
    public function decode(string $token)
    {
        if (
            preg_match(
                "/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/",
                $token,
                $matches
            ) !== 1
        ) {

            throw new InvalidArgumentException("invalid token format");
        }

        $signature = hash_hmac(
            "sha256",
            $matches["header"] . "." . $matches["payload"],
            $GLOBALS['config']['Secret_Key'],
            true
        );

        $signature_from_token = $this->base64URLDecode($matches["signature"]);

        if (!hash_equals($signature, $signature_from_token)) {

            // throw new Exception("signature doesn't match");
            throw new InvalidSignatureException;
        }

        $payload = json_decode($this->base64URLDecode($matches["payload"]), true);

        return $payload;
    }
    public function AuthenticateJWTToken($token)
    {
        if (!preg_match("/^Bearer\s+(.*)$/", $token, $matches)) {
            return false;
        }

        $data = $this->decode($matches[1]);
        return $data;
    }
    private function base64URLEncode(string $text)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($text));
    }
    private function base64URLDecode(string $text): string
    {
        return base64_decode(
            str_replace(
                ["-", "_"],
                ["+", "/"],
                $text
            )
        );
    }
}