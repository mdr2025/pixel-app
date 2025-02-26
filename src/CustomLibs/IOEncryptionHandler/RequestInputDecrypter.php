<?php

namespace PixelApp\CustomLibs\IOEncryptionHandler;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class RequestInputDecrypter
{

    protected Request $request;

    public function __construct(?Request $request = null)
    {
        $this->setRequest($request);
    }

    /**
     * @param Request|null $request
     * @return void
     */
    public function setRequest(?Request $request = null): void
    {
        if (!$request) {
            $request = request();
        }
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param mixed $valueToParse
     * @return mixed
     *
     * Convert json encoded values to associative array or returns the same value without processing on json decoding failing
     */
    protected function parseJsonOnNeed(mixed $valueToParse) : mixed
    {
        return json_decode($valueToParse , true ) ?? $valueToParse ;
    }
    protected function overrideRequestPropValue(string $propName, mixed $propDecryptedValue): void
    {
        $propDecryptedValue = $this->parseJsonOnNeed($propDecryptedValue);
        $this->request->merge([$propName => $propDecryptedValue]);
    }
    protected function decryptValue(string $value): mixed
    {
        try {
            return Crypt::decrypt($value, false);
        } catch (DecryptException $exception) {
            return null;
        }
    }
    protected function getRequestPropValue(string $propName): mixed
    {
        return $this->request->input($propName);
    }

    protected function decryptRequestEncryptedProp(string $propName): void
    {
        $propRequestValue = $this->getRequestPropValue($propName);
        if ($propRequestValue && is_string($propRequestValue)) {

            $decryptedValue = $this->decryptValue($propRequestValue);
            $this->overrideRequestPropValue($propName, $decryptedValue);

        }
    }
    protected function filterEncryptedDataPropNamesArray(array $EncryptedDataPropNames): array
    {
        return array_filter($EncryptedDataPropNames, function ($propName) {
            return !empty($propName) && is_string($propName);
        });
    }
    protected function getDefaultEncryptableDataPropNames(): array
    {
        return config("io-encryption-handler.global-encryptable-prop-names", []);
    }
    protected function getRequestEncryptedPropsHeader(): ?array
    {
        if ($headerValue = $this->request->header("Encrypted-Props")) {
            return array_map(function ($propName) {
                return trim($propName);
            },  explode(",", $headerValue));
        }
        return null;
    }
    protected function getEncryptedDataPropNames(): array
    {
        $propNames = $this->getRequestEncryptedPropsHeader() ?? $this->getDefaultEncryptableDataPropNames();
        return $this->filterEncryptedDataPropNamesArray($propNames);
    }
    protected function decryptRequestData(): void
    {
        foreach ($this->getEncryptedDataPropNames() as $propName) {
            $this->decryptRequestEncryptedProp($propName);
        }
    }
    protected function hasEncryptedData(): bool
    {
        return (bool) $this->request->header("Has-Encrypted-Data", false);
    }
    public function decryptRequestInputs(): void
    {
        if ($this->hasEncryptedData()) {
            $this->decryptRequestData();
        }
    }
}
