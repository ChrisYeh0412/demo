<?php
namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    public function getList($queryData=[]) {
        return app(UserRepository::class)->getList($queryData);
    }

    public function getData($id) {
        return app(UserRepository::class)->getData($id);
    }

    public function addData($data=[]) {
        return app(UserRepository::class)->addData($data);
    }

    public function updateData($data=[]) {
        return app(UserRepository::class)->updateData($data);
    }

    public function deleteData($id) {
        return app(UserRepository::class)->deleteData($id);
    }

    public function checkExistEmail($email) {
        return app(UserRepository::class)->checkExistEmail($email);
    }

    public function getKeyValueList() {
        return app(UserRepository::class)->getKeyValueList();
    }
}
