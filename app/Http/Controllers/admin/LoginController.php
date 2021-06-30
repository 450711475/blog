<?php

namespace App\Http\Controllers\admin;

use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Org\code;
use Gregwar\Captcha\CaptchaBuilder;

class LoginController extends Controller
{
    //登录页面
    public function login(){
        return view('admin/login');
    }
    //验证码
//    public  function code() {
//          老办法
////        $code = new code();
////        return $code->outImage();
//        //创建生成验证码的对象
////        $builder = new CaptchaBuilder();
////        return $builder->output();
//    }
    public function captcha($tmp) {
        $phrase = new PhraseBuilder();
        //设置验证码位数
        $code = $phrase->build(6);
        //生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder($code, $phrase);
        //设置背景颜色
        $builder->setBackgroundColor(220,210,230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        //可以设置图片宽高字体
        $builder->build($width = 100, $height = 40, $font = null);
        // 获取验证码的内容
        $phrase = $builder->getPhrase();
        // 把内容存入session
        \Session::flash('code',$phrase);
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }
}
