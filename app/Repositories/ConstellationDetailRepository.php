<?php
namespace App\Repositories;

use App\Constant\Common\ErrorCodeConstant;
use App\Constant\Common\ErrorMessageConstant;
use App\Models\ConstellationDetail;

class ConstellationDetailRepository
{
    public function getList($queryData) {
        $result = [];
        try {
            $query = ConstellationDetail::orderByDesc('id');

            if (isset($queryData['keyword']) && $queryData['keyword']) {
                $query->where('name', 'like', '%'.$queryData['keyword'].'%')
                    ->orWhere('email', 'like', '%'.$queryData['keyword'].'%');
            }

            $result['result'] = 1;
            $result['data'] = $query->paginate($queryData['limit'], ['*'], 'page', $queryData['page']);
            $result['error']['code'] = ErrorCodeConstant::ERROR_CODE_0000;
            $result['error']['message'] = ErrorMessageConstant::ERROR_MESSAGE_0000;
        } catch (\Exception $e) {
            $result['result'] = 0;
            $result['error']['code'] = ErrorCodeConstant::ERROR_CODE_0012;
            $result['error']['message'] = ErrorMessageConstant::ERROR_MESSAGE_0012.ErrorMessageConstant::ERROR_MESSAGE.$e->getMessage();
        }
        return $result;
    }

    public function getData($id) {
        $result = [];
        try {
            $data = ConstellationDetail::find($id);
            if ($data) {
                $result['result'] = 1;
                $result['data'] = $data->toArray();
                $result['error']['code'] = ErrorCodeConstant::ERROR_CODE_0000;
                $result['error']['message'] = ErrorMessageConstant::ERROR_MESSAGE_0000;
            } else {
                $result['result'] = 0;
                $result['error']['code'] = ErrorCodeConstant::ERROR_CODE_0004;
                $result['error']['message'] = ErrorMessageConstant::ERROR_MESSAGE_0004;
            }
        } catch (\Exception $e) {
            $result['result'] = 0;
            $result['error']['code'] = ErrorCodeConstant::ERROR_CODE_0012;
            $result['error']['message'] = ErrorMessageConstant::ERROR_MESSAGE_0012.ErrorMessageConstant::ERROR_MESSAGE.$e->getMessage();
        }
        return $result;
    }

    public function addData($data) {
        $result = [];
        try {
            $result['result'] = 1;
            $result['data'] = ConstellationDetail::create($data);
            $result['error']['code'] = ErrorCodeConstant::ERROR_CODE_0000;
            $result['error']['message'] = ErrorMessageConstant::ERROR_MESSAGE_0000;
        } catch (\Exception $e) {
            $result['result'] = 0;
            $result['error']['code'] = ErrorCodeConstant::ERROR_CODE_0005;
            $result['error']['message'] = ErrorMessageConstant::ERROR_MESSAGE_0005.ErrorMessageConstant::ERROR_MESSAGE.$e->getMessage();
        }
        return $result;
    }

    public function updateData($data) {
        $result = [];
        try {
            if (!$user = ConstellationDetail::find($data['id'])) {
                throw new \Exception(ErrorMessageConstant::ERROR_MESSAGE_0004);
            }

            $result['result'] = 1;
            $result['data'] = $user->update($data);
            $result['error']['code'] = ErrorCodeConstant::ERROR_CODE_0000;
            $result['error']['message'] = ErrorMessageConstant::ERROR_MESSAGE_0000;
        } catch (\Exception $e) {
            $result['result'] = 0;
            $result['error']['code'] = ErrorCodeConstant::ERROR_CODE_0006;
            $result['error']['message'] = ErrorMessageConstant::ERROR_MESSAGE_0006.ErrorMessageConstant::ERROR_MESSAGE.$e->getMessage();
        }
        return $result;
    }

    public function deleteData($id) {
        $result = [];
        try {
            if (!$user = ConstellationDetail::find($id)) {
                throw new \Exception(ErrorMessageConstant::ERROR_MESSAGE_0004);
            }

            $result['result'] = 1;
            $result['data'] = $user->delete();
            $result['error']['code'] = ErrorCodeConstant::ERROR_CODE_0000;
            $result['error']['message'] = ErrorMessageConstant::ERROR_MESSAGE_0000;
            return $result;
        } catch (\Exception $e) {
            $result['result'] = 0;
            $result['error']['code'] = ErrorCodeConstant::ERROR_CODE_0007;
            $result['error']['message'] = ErrorMessageConstant::ERROR_MESSAGE_0007.ErrorMessageConstant::ERROR_MESSAGE.$e->getMessage();
            return $result;
        }
    }

    public function getKeyValueList() {
        $result = [];
        try {
            $collection = collect(ConstellationDetail::select('id', 'name')->get());
            $keyValue = $collection->mapWithKeys(function($value, $key){
                return [$value->id => $value->name];
            })->toArray();

            $result['result'] = 1;
            $result['data'] = $keyValue;
            $result['error']['code'] = ErrorCodeConstant::ERROR_CODE_0000;
            $result['error']['message'] = ErrorMessageConstant::ERROR_MESSAGE_0000;
        } catch (\Exception $e) {
            $result['result'] = 0;
            $result['data'] = [];
            $result['error']['code'] = ErrorCodeConstant::ERROR_CODE_0012;
            $result['error']['message'] = ErrorMessageConstant::ERROR_MESSAGE_0012.ErrorMessageConstant::ERROR_MESSAGE.$e->getMessage();
        }
        return $result;
    }
}
