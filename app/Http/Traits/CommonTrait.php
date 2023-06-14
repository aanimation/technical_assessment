<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\Http;
use Exception;
use App\Exceptions\GeneralApiException;

trait CommonTrait
{
    public function verifyContent($content): string
    {
        $data = $content->data;

        $identityHash = $this->getIdentity($data);
        $recipientHash = $this->getRecipient($data);
        $issuerHash = $this->getIssuer($data);
        
        $merged = array_merge($identityHash, $recipientHash, $issuerHash);
        sort($merged);

        // TODO: Not tested yet about `Don’t worry about whitespace when you hash the array in PHP.`
        $customArrayToString = '["' . implode('","', $merged) . '"]';

        $targetHash = hash('sha256', $customArrayToString);
        $this->signatureCheck($content, $targetHash);

        return $content->data->issuer->name;
    }

    protected function signatureCheck($content, $targetHash): bool
    {
        try {
            $signature = $content->signature;
            $signatureTargetHash = $signature->targetHash;

            $issuerName = $content->data->issuer->name;
        } catch (Exception $exception) {
            $this->throwException('invalid_signature', $exception->getMessage(), $issuerName ?? '');
        }

        return $targetHash === $signatureTargetHash;
    }

    protected function getIdentity($data): array
    {
        try {
            $id = $data->id;
            $name = $data->name;

            $issuerName = $data->issuer->name;
        } catch (Exception $exception) {
            $this->throwException('invalid_identity', $exception->getMessage(), $issuerName ?? '');
        }

        $format['id'] = $id;
        $format['name'] = $name;
        return $this->getDataHashes($format);
    }

    protected function getRecipient($data): array
    {
        try {
            $recipient = $data->recipient;

            $name = $recipient->name;
            $email = $recipient->email;

            $issuerName = $data->issuer->name;
        } catch (Exception $exception) {
            $this->throwException('invalid_recipient', $exception->getMessage(), $issuerName ?? '');
        }

        $format['recipient.name'] = $name;
        $format['recipient.email'] = $email;
        return $this->getDataHashes($format);
    }

    protected function getIssuer($data): array
    {
        try {
            $issuer = $data->issuer;

            $name = $issuer->name;
            $identityProof = $issuer->identityProof;

            $identityProofType = $identityProof->type;
            $identityProofKey = $identityProof->key;
            $identityProofLocation = $identityProof->location;

            $issued = $data->issued;

            // Google DNS lookup
            $url = "https://dns.google/resolve?name={$identityProofLocation}&type=TXT";
            $response = Http::get($url);
            $body = $response->body();
            $isVerified = str_contains($body, $identityProofKey);

            if (!$isVerified) {
                $message = 'Property of identityProof.key not verified';
                $this->throwException('invalid_issuer', $message, $name);
            }

        } catch (Exception $exception) {
            $this->throwException('invalid_issuer', $exception->getMessage(), $name ?? '');
        }

        $format['issuer.name'] = $name;
        $format['issuer.identityProof.type'] = $identityProofType;
        $format['issuer.identityProof.key'] = $identityProofKey;
        $format['issuer.identityProof.location'] = $identityProofLocation;
        $format['issued'] = $issued;
        
        return $this->getDataHashes($format);
    }

    protected function throwException(string $result, ?string $message, ?string $name): void
    {
        throw new GeneralApiException([
            'result' => $result,
            'message' => $message,
            'name' => $name,
        ]);
    }

    private function getDataHashes(array $data): array
    {
        $hashes = [];

        foreach ($data as $key => $value) {
            $hashes[] = hash('sha256', json_encode([$key => $value]));
        }

        return $hashes;
    }
}