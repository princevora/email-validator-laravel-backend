<?php

namespace App\Classes;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DnsHandler
{
    /**
     * @var string
     */
    protected string $domain;

    /**
     * @var array
     */
    protected array $mx;

    /**
     * @var array
     */
    protected array $mx_hosts = [];

    /**
     * @var array
     */
    protected array $ipv6 = [];
    
    /**
     * @var array
     */
    protected array $ipv4 = [];

    protected bool $disposable = false;

    /**
     * @return static
     */
    public function init()
    {
        return $this;
    }

    public function initAll()
    {
        $this->findIsDisposable();

        // get mx hosts
        $this->findMxHosts();

        // get ipv6
        $this->findMx6();

        //get ipv4
        $this->findMx4();

        // send response.
        return $this->sendData();
    }

    protected function findIsDisposable()
    {
        // get file path.
        $path = Storage::path('public/list.txt');

        // get domains as array
        $domains = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $this->disposable = in_array($this->domain, $domains);

        return $this->disposable;
    }

    /**
     * @return array
     */
    protected function findMxHosts(): ?array
    {
        // Set the mx
        $this->mx = @$this->getDnsRecord(DNS_MX);
        
        if (!empty($this->mx)) {
            // use loop to find hosts.

            foreach ($this->mx as $value) {

                // find the host
                if($value['target']) {
                    // Get the target and set it as a key in mxhosts and its priority as value
                    $this->mx_hosts[$value['target']] = $value['pri'];
                }
            }
        }

        return $this->mx_hosts;
    }

    /**
     * @return array
     */
    protected function findMx6(): ?array
    {
        $this->mx = @$this->getDnsRecord(DNS_AAAA);

        if(!empty($this->mx)){
            // run foreach loop to get ipv6 recors.

            foreach ($this->mx as $value) {
                // set the ipv6 ips.
                if($value['ipv6']) $this->ipv6[] = $value['ipv6'];
            }
        }

        return $this->ipv6;
    }

    /**
     * @return array
     */
    protected function findMx4(): ?array 
    {
        $this->mx = @$this->getDnsRecord(DNS_A);

        if(!empty($this->mx)){
            foreach ($this->mx as $value) {
                if($value['ip']) $this->ipv4[] = $value['ip']; 
            }
        }

        return $this->ipv4;
    }

    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    private function sendData()
    {
        return response()->json([
            'disposable'  => $this->disposable,
            'domain'      => $this->domain,
            'mx_hosts'    => array_keys($this->mx_hosts),
            'mx_ip'       => [
                'ipv4'    => $this->ipv4,
                'ipv6'    => $this->ipv6,
            ],
            'mx_priority' => $this->mx_hosts,
        ]);
    }

    /**
     * @param int $type
     * @return array|bool
     */
    private function getDnsRecord(int $type)
    {
        return @dns_get_record($this->domain, $type);
    }
}
