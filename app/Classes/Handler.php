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

    private DnsHandler $dns;

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request, DnsHandler $dns)
    {
        $this->request = $request;
        $this->dns = $dns;
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

        if(!$this->isValidDomain() && !$this->isValidEmail() && !$this->isValidUrl()) {
            return response()->json([
                'message' => 'The provided input is not valid Email, domain or url'
            ], 409);
        }

        return $this->extract();
    }

    private function extract()
    {
        // Validate if the input is email
        if($this->isValidEmail()) {
            // Get the domain from the email
            list(, $domain) = explode('@', $this->input);

            // Set the dns' s domain
            $this->dns->domain = $domain;
        } else if ($this->isValidUrl()) {
            // Parse the url
            $url = @parse_url($this->input);

            // get the host
            $this->dns->domain = $url['host'] ?? 'google.com';
        } else $this->dns->domain = $this->input;

        return $this->dns->initAll();
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
