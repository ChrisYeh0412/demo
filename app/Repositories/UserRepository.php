<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Redis;

class UserRepository {
	/**
	 * 取得列表
	 *
	 * @param array $queryData 關鍵字
	 * @return mixed
	 */
	public function getUserList($queryData=[]) {
		$query = User::select('id', 'permission_id', 'name', 'email', 'created_at')->orderBy($queryData['order'][0], $queryData['order'][1]);

		if (isset($queryData['keyword']) && $queryData['keyword']) {
			$query->where('name', 'LIKE', '%'.$queryData['keyword'].'%')
				  ->orWhere('email', 'LIKE', '%'.$queryData['keyword'].'%');
		}

		$result = $query->offset($queryData['start'])->limit($queryData['limit'])->get()->toArray();
		return $result;
	}
	/**
	 * 取得總數
	 *
	 * @param array $queryData 關鍵字
	 * @return mixed
	 */
	public function getUserTotal($queryData=[]) {
		$query = User::select('id');

		if (isset($queryData['keyword']) && $queryData['keyword']) {
			$query->where('name', 'LIKE', '%'.$queryData['keyword'].'%')
				  ->orWhere('email', 'LIKE', '%'.$queryData['keyword'].'%');
		}

		$result = $query->count();
		return $result;
	}

	/**
	 * 儲存資料
	 *
	 * @param array $data 資料
	 * @return mixed
	 */
	public function addUserData($data=[]) {
		$result = User::create($data);
		$listKey = 'LIST:User';
		$hashKey = 'HASH:User:';
		if (Redis::exists($listKey)) {
			Redis::rpush($listKey, $result->id);
			Redis::hmset($hashKey.$result->id, ['id' => $result->id, 'name' => $data['name']]);
		}
		return $result;
	}

	/**
	 * 更新資料
	 *
	 * @param int $id 資料庫流水號
	 * @param array $data 資料
	 * @return mixed
	 */
	public function updateUserData($id, $data=[]) {
		$result = User::where('id', $id)->update($data);
		$listKey = 'LIST:User';
		$hashKey = 'HASH:User:';
		if (Redis::exists($listKey) && Redis::exists($hashKey.$id) && isset($data['name'])) {
			Redis::hmset($hashKey.$id, ['id' => $id, 'name' => $data['name']]);
		}
		return $result;
	}

	/**
	 * 刪除資料
	 *
	 * @param int $id 資料庫流水號
	 * @return mixed
	 */
	public function deleteUserData($id) {
		$result = User::find($id)->delete();
		$listKey = 'LIST:User';
		$hashKey = 'HASH:User:';
		if (Redis::exists($listKey)) {
			Redis::del($listKey);
		}
		return $result;
	}

	/**
	 * 取出單筆資料
	 *
	 * @param int $id 資料庫流水號
	 * @return mixed
	 */
	public function getUserData($id) {
		$result = User::where('id', $id)->first();
		$result = is_object($result) ? $result->toArray() : [];
		return $result;
	}

	/**
	 * 檢查帳號是否重複
	 *
	 * @param string $email 資料
	 * @param int $id 資料庫流水號
	 * @return mixed
	 */
	public function checkUserEmailIsExist($email='', $id=0) {
		$query = User::withTrashed()->where('email', $email);

		if (isset($id) && $id != 0) {
			$query->where('id', '!=', $id);
		}

		$result = $query->first();
		$result = is_object($result) ? $result->toArray() : [];
		return $result;
	}

	public function getUsersPermissionData($id) {
		$result = User::select('permissions.contents')
					  ->leftJoin('permissions', 'users.permission_id', '=', 'permissions.id')
					  ->where('users.id', $id)
					  ->first();

		$result = is_object($result) ? $result->toArray() : [];
		return $result;
	}

	/**
	 * 取得 id/name 做 key/value
	 *
	 * @return mixed
	 */
	public function getUserKeyValueList() {
		$listKey = 'LIST:User';
		$hashKey = 'HASH:User:';

		if (empty(Redis::exists($listKey))) {
			$result = User::select('id', 'name')->orderBy('id', 'ASC')->get()->toArray();

			foreach ($result as $value) {
				Redis::rpush($listKey, $value['id']);
				Redis::hmset($hashKey.$value['id'], $value);
			}
			return $result;
		}

		$redisData = Redis::lrange($listKey, 0, -1);
		$result = [];
		foreach ($redisData as $value) {
			$result[] = Redis::hgetall($hashKey.$value);
		}

		return $result;
	}

}
