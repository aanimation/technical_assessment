<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Models\VerificationLog;

class GeneralApiException extends Exception
{
    protected string $result;
    protected string $name;
    protected string $customMessage;

    public function __construct(array $data)
    {
        parent::__construct();
        $this->result = $data['result'];
        $this->name = $data['name'];
        $this->customMessage = $data['message'];
    }

    public function report()
    {
        VerificationLog::create([
            'result' => $this->result,
            'message' => "{$this->name} : {$this->customMessage}",
        ]);
    }

    public function render($request)
    {
        return new JsonResponse([
            'data' => [
                'issuer' => $this->name,
                'result' => $this->result,
            ]
        ], 200);
    }    
}
