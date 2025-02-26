<?php

namespace PixelApp\CustomLibs\IOEncryptionHandler;

class IOEncryptionHandler
{
    protected static ?ResponseOutputEncrypter $outputEncrypter = null;
    protected static function initRequestInputDecrypter(): RequestInputDecrypter
    {
        return new RequestInputDecrypter();
    }
    public static function decryptRequestInputs(): void
    {
        static::initRequestInputDecrypter()->decryptRequestInputs();
    }
    protected static function initResponseOutputEncrypter(): ResponseOutputEncrypter
    {
        if (!static::$outputEncrypter) {
            static::$outputEncrypter = new ResponseOutputEncrypter();
        }
        return static::$outputEncrypter;
    }
    public static function encryptResponseOutput(array $data , ?array $customEncryptablePropNames = null): void
    {
        $responseOutputEncrypter = static::initResponseOutputEncrypter()->setDataToEncryption($data);
        if($customEncryptablePropNames)
        {
            $responseOutputEncrypter->useCustomEcryptableDataPropNames( $customEncryptablePropNames );
        }

        $responseOutputEncrypter->encryptResponseOutput();
    }
    public static function getResponseEncryptingHeaders(): array
    {
        return static::$outputEncrypter->getEncryptingHeaders();
    }
    public static function returnBackProcessedData(): array
    {
        return static::$outputEncrypter->returnBackProcessedData();
    }
}
