<?php

namespace PixelApp\Services\CoreServices;

use CRUDServices\CRUDServiceTypes\DeletingServices\DeletingService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BulkDestroyService extends DeletingService
{
    /** @var int[] */
    protected array $ids = [];
    protected array $models = [];

    /**
     * @return string
     */
    abstract protected function getModelClass(): string;

    /**
     * @param array $ids
     *
     * @return array
     */
    protected function filterRequestIdsValue(array $ids = []): array
    {
        return array_filter($ids, 'is_numeric');
    }

    /**
     * @return void
     */
    protected function getRequestIdsValue(): void
    {
        $ids = request()->input('ids') ?? [];
        $this->ids = $this->filterRequestIdsValue($ids);
    }


    /**
     * @param mixed Collection
     */
    public function __construct(Collection | Model |Null $modelsToDelete = null)
    {
        if (!$modelsToDelete) {
            //get the model class
            $modelClass = $this->getModelClass();
            //get the request ids
            $this->getRequestIdsValue();
            //get models instances from ids arrays
            $modelsToDelete = app($modelClass)->whereIn('id', $this->ids)->get();
            $this->doSomeActionsForCollectionBeforeDelete($modelsToDelete);
        }
        //initiate parent and delete records
        parent::__construct($modelsToDelete);
    }

    /**
     * @param Collection|array $modelsToDelete
     * 
     * @return void
     */
    public function doSomeActionsForCollectionBeforeDelete(Collection|array $modelsToDelete): void {}
}
