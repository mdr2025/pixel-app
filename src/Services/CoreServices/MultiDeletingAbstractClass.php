<?php

namespace PixelApp\Services\CoreServices;

use Illuminate\Support\Facades\DB; 

abstract class MultiDeletingAbstractClass
{
    protected array $data = [];

    abstract protected function modelClass(): string;
    abstract protected function successMessage(): string;
    abstract protected function deletingService($object): void;

    public function __construct()
    {
        $this->data = request()->input('data', []);
    }

    private function getData(): array
    {
        return $this->data;
    }

    private function checkData()
    {
        if (empty($this->getData()) || !is_array($this->getData())) {
            throw new \Exception('Invalid or missing data parameter');
        }
    }

    public function deleteMulti()
    {
        $this->checkData();
        $data = $this->getData();
        DB::beginTransaction();
        try {
            foreach ($data as $id) {
                $modelClass = $this->modelClass();
                $object = $modelClass::find($id);
                if ($object) {
                    $this->deletingService($object);
                } else {
                    throw new \Exception("ID {$id} not found");
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $this->successMessage() . " And What is not deleted is because it is used elsewhere within the system"
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
