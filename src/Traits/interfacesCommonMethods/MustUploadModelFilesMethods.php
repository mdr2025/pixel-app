<?php

namespace PixelApp\Traits\interfacesCommonMethods;

use CRUDServices\FilesOperationsHandlers\FilePathsRetrievingHandler\FileFullPathsHandler;
use CRUDServices\Interfaces\MustUploadModelFiles;

trait MustUploadModelFilesMethods
{

    /**
     * @return void
     */
    public function setFilesFullPathAttrs() : void
    {
        /** @var FileFullPathsHandler $filePathsRetrievingHandler */
        $filePathsRetrievingHandler = FileFullPathsHandler::singleton();
        $filePathsRetrievingHandler->ModelFilesPathHandling($this);
    } 
    
    public function setFileFullPathAttr(string $attrName) : void
    {
        /** @var FileFullPathsHandler $filePathsRetrievingHandler */
        $filePathsRetrievingHandler = FileFullPathsHandler::singleton();
        $filePathsRetrievingHandler->ModelFilesPathHandling($this , [$attrName]);
    } 


    protected function getPathCompletedAttrValue(string $attrName) : string|array|null
    {
        //keep the old value of file path prop to fill it into moddel attrs again
        //using parent's getAttribute method to avoid runTime cached relation keys checking 
        //because we have already checked that the prop is already a file path prop by isItUploadedFilePropName method
        // even if getPathCompletedAttrValue method is called without calling isItUploadedFilePropName
        // the model will behave with the attrName as a file path prop 
        $attrOldValue = parent::getAttribute($attrName);

        //handling file path
        $this->setFileFullPathAttr($attrName);

        //getting new value after file path handling
        $attrNewValue = parent::getAttribute($attrName);
        
        
        //refilling the old value into model attrs
        $this->setAttribute($attrName , $attrOldValue);


        return $attrNewValue;
    }

    protected function isItUploadedFilePropName(string $attrName) : bool
    {
        return FileFullPathsHandler::MustUploadModelFiles($this) && FileFullPathsHandler::isItUploadedFilePropName($this , $attrName);
    }

    public function getFileFullPathAttrValue(string $attrName): string|array|null
    { 
        if ($this->isItUploadedFilePropName($attrName))
        {
            return $this->getPathCompletedAttrValue($attrName);
        }
        
        //just for development env.
        dd($attrName . " is not  file path prop or model doesn't implement MustUploadModelFiles interface !");
    }

    protected function getPathCompletedAttrsArray() : array
    {
        $oldAttrsArray = parent::attributesToArray();
        $this->setFilesFullPathAttrs();
        $ProcessedAttrsArray = parent::toArray();

        /**  Resetting Old Attrs Before Returning The Processed Array */
        $this->fill($oldAttrsArray);

        return $ProcessedAttrsArray;
    }

    public function getDocumentStoragePath(string $fileName) : string
    {
        /** @var MustUploadModelFiles $this */
        return $this->getDocumentsStorageFolderName() . "/" . $fileName;
    }
}
