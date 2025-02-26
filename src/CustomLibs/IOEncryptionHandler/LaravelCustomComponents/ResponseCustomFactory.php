<?php

namespace PixelApp\CustomLibs\IOEncryptionHandler\LaravelCustomComponents;
 
use Illuminate\Routing\ResponseFactory;
use PixelApp\CustomLibs\IOEncryptionHandler\ResponseOutputEncrypter;

class ResponseCustomFactory extends ResponseFactory
{
    protected array $mainDataArray = [];
    protected ?ResponseOutputEncrypter $outputEncrypter = null;
    public static bool $outputResponseEncryptingStatus = true;
    protected static ?array  $customEncryptableDataPropNames = null;

    public static function disableOutputEncryption(): void
    {
        static::$outputResponseEncryptingStatus = false;
    }
    public static function enableOutputEncryption(): void
    {
        static::$outputResponseEncryptingStatus = true;
    }

    public static function useCustomEcryptableDataPropNames(array $propNames = []) : void
    {
        static::$customEncryptableDataPropNames = $propNames;
    }
    protected function getResponseEncryptingHeaders(array $headers = []): array
    {
        return array_merge($headers, $this->outputEncrypter->getEncryptingHeaders());
    }

    protected function getDataInternalKey() : ?string
    {
        return config("io-encryption-handler.response-data-array-internal-key");
    }
    protected function replaceInternalKeyWrappedDataArray(array $encryptedInternalArray = []) : void
    {
        if($internalKey = $this->getDataInternalKey())
        {
            $this->mainDataArray[$internalKey] = $encryptedInternalArray;
            return ;
        }
        $this->mainDataArray = $encryptedInternalArray;
    }

    protected function returnBackProcessedData(): array
    {
        return $this->outputEncrypter->returnBackProcessedData();
    }
    protected function replaceEncryptedData() : void
    {
        $encryptedInternalArray =  $this->returnBackProcessedData();
        $this->replaceInternalKeyWrappedDataArray( $encryptedInternalArray );
    }
    protected function getInternalKeyWrappedDataArray() : array
    {
        $internalKey = $this->getDataInternalKey();
        return  $this->mainDataArray[ $internalKey ] ?? $this->mainDataArray;
    }
    protected function encryptData() : void
    {
        $encryptingDataArray = $this->getInternalKeyWrappedDataArray();
        $this->outputEncrypter->setDataToEncryption($encryptingDataArray)->encryptResponseOutput();
    }
    protected function encryptResponseOutput(array $data): array
    {
        $this->setMainDataArray($data);
        $this->encryptData();
        $this->replaceEncryptedData();
        return $this->mainDataArray;

    }
    protected function passOutputResponseEncryptingStatus(): self
    {
        $this->outputEncrypter::$encryptResponseOutputs = $this::$outputResponseEncryptingStatus;
        return $this;
    }

    protected function passCustomEncryptableDataPropNames(): self
    {
        if ($this::$customEncryptableDataPropNames)
        {
            $this->outputEncrypter->useCustomEcryptableDataPropNames( $this::$customEncryptableDataPropNames );
        }
        return $this;
    }

    protected function initResponseOutputEncrypter(): ResponseOutputEncrypter
    {
        if (!$this->outputEncrypter) {
            $this->outputEncrypter = new ResponseOutputEncrypter();
        }
        return $this->outputEncrypter;
    }
    /**
     * @param array $mainDataArray
     * @return $this
     */
    public function setMainDataArray(array $mainDataArray  ): self
    {
        $this->mainDataArray = $mainDataArray;
        return $this;
    }

    public function json($data = [], $status = 200, array $headers = [], $options = 0)
    {
        if(!empty($data))
        {
            $this->initResponseOutputEncrypter();
            $this->passCustomEncryptableDataPropNames();
            $this->passOutputResponseEncryptingStatus();

            $data = $this->encryptResponseOutput($data);
            $headers = $this->getResponseEncryptingHeaders($headers);
        }
        return parent::json($data, $status, $headers, $options);
    }
}
