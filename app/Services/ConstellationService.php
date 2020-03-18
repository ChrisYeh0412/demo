<?php
namespace App\Services;

use App\Repositories\ConstellationRepository;

class ConstellationService
{
    public function getList($queryData=[]) {
        return app(ConstellationRepository::class)->getList($queryData);
    }

    public function getData($id) {
        return app(ConstellationRepository::class)->getData($id);
    }

    public function addData($data=[]) {
        return app(ConstellationRepository::class)->addData($data);
    }

    public function updateData($data=[]) {
        return app(ConstellationRepository::class)->updateData($data);
    }

    public function deleteData($id) {
        return app(ConstellationRepository::class)->deleteData($id);
    }

    public function getKeyValueList() {
        return app(ConstellationRepository::class)->getKeyValueList();
    }

    public function getId($name) {
        return app(ConstellationRepository::class)->getId($name);
    }
}
