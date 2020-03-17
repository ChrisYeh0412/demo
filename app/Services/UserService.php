<?php
namespace App\Services;

use App\Repositories\UserRepository;

class UserService {
    /**
     * 取得列表
     *
     * @param array $queryData 關鍵字
     * @return mixed
     */
    public function getUserList($queryData=[]) {
        return resolve(UserRepository::class)->getUserList($queryData);
    }

    /**
     * 取得列表
     *
     * @param array $queryData 關鍵字
     * @return mixed
     */
    public function getUserTotal($queryData=[]) {
        return resolve(UserRepository::class)->getUserTotal($queryData);
    }

    /**
     * 取出單筆資料
     *
     * @param int $id 資料庫流水號
     * @return mixed
     */
    public function getUserData($id) {
        return resolve(UserRepository::class)->getUserData($id);
    }

    /**
     * 儲存資料
     *
     * @param array $data 資料
     * @return mixed
     */
    public function addUserData($data=[]) {
        return resolve(UserRepository::class)->addUserData($data);
    }

    /**
     * 儲存資料
     *
     * @param int $id 資料庫流水號
     * @param array $data 資料
     * @return mixed
     */
    public function updateUserData($id, $data=[]) {
        return resolve(UserRepository::class)->updateUserData($id, $data);
    }

    /**
     * 儲存資料
     *
     * @param int $id 資料庫流水號
     * @return mixed
     */
    public function deleteUserData($id) {
        return resolve(UserRepository::class)->deleteUserData($id);
    }

    /**
     * 檢查帳號是否重複
     *
     * @param string $email 資料
     * @param int $id 資料庫流水號
     * @return mixed
     */
    public function checkUserEmailIsExist($email='', $id=0) {
        return resolve(UserRepository::class)->checkUserEmailIsExist($email, $id);
    }

    public function getUserKeyValueList() {
        return resolve(UserRepository::class)->getUserKeyValueList();
    }

    /**
     * 確認資料是否正確
     *
     * @param $request
     */
    public function userDataValidate($request) {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|between:8,20'
        ];
        $messages = [
            'required' => '欄位不能空白',
            'password.confirmed' => '密碼與確認密碼不一至',
            'between' => '密碼長度為 8 到 20 碼',
        ];
        $this->validate($request, $rules, $messages);
    }
}
