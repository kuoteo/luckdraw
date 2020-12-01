<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AwardLogModel;
use App\UserModel;
use App\AwardModel;
use function foo\func;

class IndexController extends Controller
{
    public function login(Request $request){
        if($request->isMethod('Post')){
            $requestInfo = $request->all();
            //判断用户名密码是否为空
            if (empty($requestInfo['user_name']) || empty($requestInfo['password'])) {
                echo '<script>alert("用户名或密码不能为空");</script>';
                return view('index/login');
            }

            //查找是否有对应用户名密码
            $userInfo = UserModel::where('user_name',$requestInfo['user_name'])
                ->select('password','uid')
                ->first();

            if (empty($userInfo)) {
                echo '<script>alert("用户名不存在！");</script>';
                return view('index/login');
            }

            //核对密码是否匹配
            if ($requestInfo['password'] == $userInfo['password']) {
                session(['user_name' => $requestInfo['user_name'],
                    'uid' => $userInfo['uid']]);
                echo '<script>location.href="/index"</script>';
            }
        }
        return view('index/login');
    }


    public function index(Request $request){
        $sessionInfo = $request->session()->all();
        if (empty($sessionInfo['user_name'])) {
            echo '<script>alert("请先登录！");location.href="/index/login"</script>';
        }
        //查询剩余机会
        $chance = UserModel::where('uid',$sessionInfo['uid'])->first();

        //查找用户自己的中奖记录
        $awardLogInfo = AwardLogModel::where('uid',$sessionInfo['uid'])->get();

        return view('index/index', [
            'user_name' => $sessionInfo['user_name'],
            'chance' => $chance['chance'],
            'self_info' => $awardLogInfo,
        ]);
    }
    public function start(Request $request)
    {

        $sessionInfo = $request->session()->all();

        //判断今日次数是否足够，不够则无法抽奖
        $chance = UserModel::where('uid',$sessionInfo['uid'])
            ->select('chance')
            ->first();
        if ($chance['chance'] == 0) {
            return '今日抽奖次数已用完';
        }
        //更新剩余次数
        UserModel::where('uid',$sessionInfo['uid'])
            ->update(['chance' => $chance['chance']-1]);

        $num = rand(1,1000);
        $one = 1000 * (0.10);
        $two = 1000 * (0.05);
        $three = 1000 * (0.002);


        $award = new AwardModel();
        $awardLog = new AwardLogModel();

        if ($num >= 1 && $num <= $one) { // 落在一等奖的范围内
            //查找中奖信息表看是否已经中过该奖品，如果中了就不再发奖
            $awardLogInfo = $awardLog->where('awid',1)
                ->where('uid',$sessionInfo['uid'])
                ->first();
            if (!empty($awardLogInfo)) {
                return '你已经中了吹风机';
            }

            //查看库存，如果已经抽完则返回如下
            $upInfo = $award->updateAwardNum(1);
            if (!$upInfo) {
                return '吹风机已被抽完';
            }

            //$awardLog->saveAwardLog($sessionInfo['uid'],$sessionInfo['user_name'],1);
            return  '吹风机已被抽完';

        } else if ($num >= ($one + 1) && $num <= ($one + $two)) { // 落在二等奖的范围内
            //查找中奖信息表看是否已经中过该奖品，如果中了就不再发奖
            $awardLogInfo = $awardLog->where('awid',2)
                ->where('uid',$sessionInfo['uid'])
                ->first();
            if (!empty($awardLogInfo)) {
                return '你已经中了小米手环';
            }

            //查看库存，如果已经抽完则返回如下
            $upInfo = $award->updateAwardNum(2);
            if (!$upInfo) {
                return '小米手环已被抽完';
            }

            //$awardLog->saveAwardLog($sessionInfo['uid'],$sessionInfo['user_name'],2);
            return  '恭喜你中了小米手环吹风机';

        } else if ($num >= ($one + $two + 1) && $num <= ($one + $two + $three)) { // 落在三等奖的范围内
            //查找中奖信息表看是否已经中过该奖品，如果中了就不再发奖
            $awardLogInfo = $awardLog->where('awid',3)
                ->where('uid',$sessionInfo['uid'])
                ->first();
            if (!empty($awardLogInfo)) {
                return '你已经中了iphone';
            }

            //查看库存，如果已经抽完则返回如下
            $upInfo = $award->updateAwardNum(3);
            if (!$upInfo) {
                return 'iphone已被抽完';
            }

            //$awardLog->saveAwardLog($sessionInfo['uid'],$sessionInfo['user_name'],3);
            return  '恭喜你中了iphone';

        } else {
            return '谢谢惠顾！';
        }

    }


    //更新库存
    public function updateAwardNum($id){
        //先查询库存是否为0了
        $awardInfo = $this->where('awid',$id)
            ->select('award_name','award_num')
            ->first()
            ->toArray();
        if ($awardInfo['award_num'] == 0) {
            return false;
        }

        // 利用事务机制来更新库存
        DB::beginTransaction();
        try {
            $this::where('awid',$id)->update(['award_num' => $awardInfo['award_num'] - 1]);
            //提交事务
            DB::commit();
        } catch (Exception $e) {
            //回滚
            DB::rollBack();
        }
        return $awardInfo;
    }
    //记录中奖信息库存
    public function saveAwardLog($uid,$userName,$id){
        //获得奖品信息
        $awardInfo = AwardModel::where('awid',$id)->first()->toArray();

        //保存至中奖记录表
        $input = [
            'uid' => $uid,
            'user_name' => $userName,
            'awid' => $id,
            'award_name' => $awardInfo['award_name']
        ];

        $this::create($input);
    }

}


