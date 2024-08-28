<?php

namespace App\Classes;

class DnsHandler
{
    /**
     * @var string
     */
    protected string $domain;

    /**
     * @var array
     */
    protected array $mx_host;
    public function run()
    {
        // Set the mx
        $this->mx = dns_get_record($this->domain, DNS_MX);
        if ($this->mx) {
            dd($this->mx);
        }
    }
}
