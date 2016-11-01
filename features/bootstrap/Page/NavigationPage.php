<?php

namespace Page;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class NavigationPage extends Page
{

    public function getProtocol()
    {
        $protocol = ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? "https://" : "http://");
        return $protocol;
    }

    public function setSubDomain($subDomain)
    {
        $this->subDomain = $subDomain;
        return $this;
    }

    public function setDomainName($domainName)
    {
        $this->domainName = $domainName;
        return $domainName;
    }

    public function setDomainType($domainType)
    {
        $this->domainType = $domainType;
        return $this;
    }

    public function setUpUrl($subDomain = null, $domainName, $domainType)
    {
        $protocol = $this->getProtocol();
        $this->setDomainName($domainName);
        $this->setDomainType($domainType);
        $base_url = $protocol . $subDomain . '.' . $domainName . '.' . $domainType;
        if (!$subDomain || $subDomain == null) {
            $pos = strpos($base_url, '.');
            if ($pos !== false) {
                $url = substr_replace($base_url, '', $pos, strlen('.'));
                return $url;
            }
        }
        return $base_url;
    }
}