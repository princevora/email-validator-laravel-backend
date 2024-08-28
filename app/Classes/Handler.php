<?php

namespace App\Classes;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class Handler extends DnsHandler
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var string
     */
    private ?string $input;

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        // Assign the value.
        $this->input = $this->request->input;

        // Check type of the input
        if(empty($this->input)){
            return response()->json([
                'message' => 'Please provide valid input'
            ], 409);
        }

        if($this->isValidDomain() || $this->isValidEmail() || $this->isValidUrl())
    }

    /**
     * @return bool
     */
    private function isValidUrl(): bool
    {
        return filter_var($this->input, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * @return bool
     */
    private function isValidDomain(): bool
    {
        return filter_var($this->input, FILTER_VALIDATE_DOMAIN) !== false;
    }

    /**
     * @return bool
     */
    private function isValidEmail(): bool
    {
        return filter_var($this->input, FILTER_VALIDATE_EMAIL) !== false;
    }
}
