<?php

namespace PixelApp\Traits\interfacesCommonMethods;

use CRUDServices\FilesOperationsHandlers\FilePathsRetrievingHandler\FileFullPathsHandler;

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

    protected function getNewAttrsArray() : array
    {
        $oldAttrsArray = parent::attributesToArray();
        $this->setFilesFullPathAttrs();
        $ProcessedAttrsArray = parent::toArray();

        /**  Resetting Old Attrs Before Returning The Processed Array */
        $this->fill($oldAttrsArray);

        return $ProcessedAttrsArray;
    }
}
