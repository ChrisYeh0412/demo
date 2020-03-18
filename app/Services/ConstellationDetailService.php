<?php
namespace App\Services;

use App\Repositories\ConstellationDetailRepository;

class ConstellationDetailService
{
    public function getList($queryData=[]) {
        return app(ConstellationDetailRepository::class)->getList($queryData);
    }

    public function getData($id) {
        return app(ConstellationDetailRepository::class)->getData($id);
    }

    public function addData($data=[]) {
        return app(ConstellationDetailRepository::class)->addData($data);
    }

    public function updateData($data=[]) {
        return app(ConstellationDetailRepository::class)->updateData($data);
    }

    public function deleteData($id) {
        return app(ConstellationDetailRepository::class)->deleteData($id);
    }

    public function getKeyValueList() {
        return app(ConstellationDetailRepository::class)->getKeyValueList();
    }
}
