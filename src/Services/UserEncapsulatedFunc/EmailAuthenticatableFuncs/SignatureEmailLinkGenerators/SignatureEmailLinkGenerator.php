<?php

namespace PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\SignatureEmailLinkGenerators;

use PixelApp\Interfaces\EmailAuthenticatable;
use Exception;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use Stancl\Tenancy\Database\Models\Domain;

class SignatureEmailLinkGenerator
{
    protected ?string $token = null;
    protected ?string $domain = null;
    protected ?string $frontendRootPath = null;
    protected ?string $frontendURI = null;

    protected EmailAuthenticatable $EmailAuthenticatable;

    public function __construct(EmailAuthenticatable $EmailAuthenticatable)
    {
        $this->setEmailAuthenticatable($EmailAuthenticatable);
    }
    /**
     * @param EmailAuthenticatable $EmailAuthenticatable
     * @return $this
     */
    public function setEmailAuthenticatable(EmailAuthenticatable $EmailAuthenticatable): self
    {
        $this->EmailAuthenticatable = $EmailAuthenticatable;
        return $this;
    }

    /**
     * @param string|Domain|null $domain
     * @return $this
     */
    public function setCompanyDomain(string | Domain | null $domain): self
    {
        if($domain instanceof Domain)
        {
            $domain = $domain->domain;
        }
        $this->domain = $domain;
        return $this;
    }
    /**
     * @param string|null $frontendURI
     * @return $this
     */
    public function setFrontendURI(?string $frontendURI): self
    {
        $this->frontendURI = $frontendURI;
        return $this;
    }

    /**
     * @param string|null $frontendRootPath
     * @return $this
     */
    public function setFrontendRootPath(?string $frontendRootPath):self
    {
        $this->frontendRootPath = $frontendRootPath;
        return $this;
    }

    protected function getAuthenticatableEmail()  :string
    {
        return $this->EmailAuthenticatable->{ $this->EmailAuthenticatable->getEmailColumnName() };
    }

    public function generateToken() : string
    {
        return $this->token = md5(rand(0, 9) . $this->getAuthenticatableEmail() . time());
    }

    /**
     * @return string
     */
    public function getGeneratedToken(): string
    {
        return $this->token ?? $this->generateToken();
    }
    protected function getFrontendRootDefaultPath()  :?string
    {
        return env("FRONTEND_APP_URL");
    }
    protected function prepareFrontendRootPathValue()  :void
    {
        if(!$this->frontendRootPath)
        {
            $this->frontendRootPath = $this->getFrontendRootDefaultPath();
        }
    }
    protected function wrapFrontendRootPath(string $link) : string
    {
        $this->prepareFrontendRootPathValue();
        if($this->frontendRootPath)
        {
            $link =  $this->frontendRootPath  . "/" . ltrim($link , "/") ;
        }
        return $link;
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getFrontendURIValue()  :string
    {
        return $this->frontendURI ??
               throw new Exception("Failed to generate link frontendURI value is not set !");
    }

    protected function mustHandleDomainQueryStringParam() : bool
    {
        return PixelTenancyManager::mustHandleDomainQueryStringParam();
    }

    /**
     * @return void
     */
    protected function prepareDomainValue(): void
    {
        if (!$this->domain && $this->mustHandleDomainQueryStringParam())
        {
            $this->domain = tenant('domain') ; // a value or null 
        } 
    }


    /**
     * @throws Exception
     */
    protected function appendDomainQueryStringParam(array $queryParams = []) : array
    {
        $this->prepareDomainValue();

        if( $this->domain)
        {
            $queryParams["domain"] =  $this->domain  ;
        }
        return $queryParams;
    }
    protected function appendTokenQueryStringParam(array $queryParams = []) : array
    {
        $queryParams["token"] = $this->getGeneratedToken();
        return $queryParams;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function generateLink(): string
    {
        $queryStringParams = $this->appendTokenQueryStringParam();
        $queryStringParams = $this->appendDomainQueryStringParam($queryStringParams);
        $link = $this->getfrontendURIValue() . "?" . http_build_query($queryStringParams);
        return $this->wrapFrontendRootPath($link) ;
    }

}
