<?php

namespace App\Classes;

class DnsHandler
{
    /**
     * @var string
     */
    protected string $domain;

    /**
     * @param string $domain
     */
    public function __construct(string $domain)
    {
        $this->domain = $domain;
    }
}
