<?php

namespace PixelApp\CustomLibs\IOEncryptionHandler;

use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;


class ResponseOutputEncrypter
{
    protected array $dataToEncryption = [];
    protected bool $dataChanged = false;
    protected ?array $encryptableDataPropNames = null;
    public static bool $encryptResponseOutputs = true;
    public function __construct(array $dataToEncryption = [])
    {
        $this->setDataToEncryption($dataToEncryption);
    }

    public static function disableOutputEncryption(): void
    {
        static::$encryptResponseOutputs = false;
    }
    public static function enableOutputEncryption(): void
    {
        static::$encryptResponseOutputs = true;
    }

    public function useCustomEcryptableDataPropNames(array $propNames = []): self
    {
        $this->encryptableDataPropNames = $propNames;
        return $this;
    }

    protected function filterEncryptableDataPropNamesArray(array $EncryptedDataPropNames): array
    {
        return array_filter($EncryptedDataPropNames, function ($propName) {
            return !empty($propName) && is_string($propName);
        });
    }

    protected function setEncryptableDataPropNames(): void
    {
        $propNames = config("io-encryption-handler.global-encryptable-prop-names", []);

        $this->encryptableDataPropNames =  $this->filterEncryptableDataPropNamesArray($propNames);
    }
    protected function getEncryptableDataPropNames(): array
    {
        if (!$this->encryptableDataPropNames) {
            $this->setEncryptableDataPropNames();
        }
        return $this->encryptableDataPropNames;
    }

    /**
     * @param array $dataToEncryption
     * @return $this
     */
    public function setDataToEncryption(array $dataToEncryption): self
    {
        $this->dataToEncryption = $dataToEncryption;
        return $this;
    }

    protected function dataChanged(): void
    {
        $this->dataChanged = true;
    }
    protected function encryptValue($value): string
    {
        return  Crypt::encrypt($value , false);
    }

    protected function convertToJson($value) : bool | string
    {
        return json_encode($value , JSON_PRETTY_PRINT) ;
    }
    protected function getPropEncryptableValue(string $propName): mixed
    {
        if (!array_key_exists($propName,  $this->dataToEncryption)) {
            return null;
        }

        $value = $this->dataToEncryption[$propName];

        if ($value instanceof Jsonable)
        {
            return $value->toJson();
        }

        if (is_object($value) && method_exists($value, "toArray"))
        {
            $value =  $value->toArray(request());
        }

        return $this->convertToJson($value) ?: null;
    }
    protected function encryptEncryptableProp(string $propName): void
    {
        if ($value = $this->getPropEncryptableValue($propName)) {
            $this->dataToEncryption[$propName] = $this->encryptValue($value);
            $this->dataChanged();
        }
    }
    protected function encryptEncryptableDataProps(): void
    {
        foreach ($this->getEncryptableDataPropNames() as $propName) {
            $this->encryptEncryptableProp($propName);
        }
    }

    public function encryptResponseOutput(): void
    {
        if ($this::$encryptResponseOutputs) {
            $this->encryptEncryptableDataProps();
        }
    }

    public function returnBackProcessedData(): array
    {
        return $this->dataToEncryption; // return the same array after processing is done (if there is need)
    }
    public function getEncryptingHeaders(): array
    {
        if (
            $this->dataChanged &&
            !empty($encryptableDataPropNames = $this->getEncryptableDataPropNames())
        ) {
            $headers = join(",", $encryptableDataPropNames);
            return  ["Has-Encrypted-Data" => 1, "Encrypted-Props" => $headers];
        }

        return [];
    }
}
