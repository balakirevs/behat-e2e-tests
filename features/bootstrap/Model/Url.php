<?php

require_once __DIR__.'/../../../vendor/autoload.php';
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class Url extends Page {

    private $protocol;
    private $subdomain;
    private $domainName;
    private $domainType;

    public function __construct($protocol, $subdomain, $domainName, $domainType) {
        $this->protocol = $protocol;
        $this->subdomain = $subdomain;
        $this->domainName = $domainName;
        $this->domainType = $domainType;
    }

    public function getProtocol()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
                    || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol;
    }

    public function setSubDomain($subdomain)
    {
        $this->subdomain = $subdomain;
        return $this;
    }

    public function getSubDomain()
    {
        return $this->subdomain;
    }

    public function setDomainName($domainName)
    {
        $this->domainName = $domainName;
        return $domainName;
    }

    public function getDomainName()
    {
        return $this->domainName;
    }

    public function setDomainType($domainType)
    {
        $this->domainType = $domainType;
        return $this;
    }

    public function getDomainType()
    {
        return $this->domainType;
    }

    public function setUpUrl($subDomain = null, $domainName, $domainType)
    {
        $protocol = $this->getProtocol();
        $this->setDomainName($domainName);
        $this->setDomainType($domainType);
        $base_url = $protocol . $subDomain . $domainName . $domainType;
        if (!$subDomain || $subDomain == null) {
            $url = substr_replace($base_url, '', 8, 1);
           return $url;
        }
        return $base_url;
    }
}