<?php


namespace App\Org;


class code
{
    protected $number;//验证码内字符个数
    protected $codeType;//验证码样式
    protected $width;//图像宽
    protected $height;//图像高
    protected $code;//验证码
    protected $image;//图像资源

    /**
     * Code constructor.
     * @param int $number
     * @param int $codeType
     * @param int $width
     * @param int $height
     */
    public function __construct($number=5, $codeType=2, $width=100, $height=40)
    {
        $this->number = $number;
        $this->codeType = $codeType;
        $this->width = $width;
        $this->height = $height;
        $this->code = $this->createCode();
    }

    /**
     * 销毁资源
     */
    public function __destruct()
    {
        imagedestroy($this->image);
    }

    /**
     * 外部调用code时触发
     * @param $name
     * @return bool
     */
    public function __get($name)
    {
        if ('code' == $name) {
            return $this->$name;
        } else {
            return false;
        }
    }

    /**
     * 生成code
     */
    protected function createCode()
    {
        switch ($this->codeType) {
            case 0:
                $code = $this->getNum();
                break;
            case 1:
                $code = $this->getChar();
                break;
            case 2:
                $code = $this->getNumChar();
                break;
            default:
                die('样式不对');
        }
        return $code;
    }

    /**
     * 数字验证码
     * @return string
     */
    protected function getNum()
    {
        $str = join('', range(0,9));
        return substr(str_shuffle($str), 0, $this->number);
    }

    /**
     * 字符验证码
     * @return string
     */
    protected function getChar()
    {
        $str = join('', range('a', 'z'));
        $str = $str . strtoupper($str);
        return substr(str_shuffle($str), 0, $this->number);
    }

    /**
     * 字符和数字混合验证码
     * @return string
     */
    protected function getNumChar()
    {
        $num = join('', range(0, 9));
        $str = join('', range('a', 'z'));
        $str_big = strtoupper($str);
        $numChar = $num . $str . $str_big;
        return substr(str_shuffle($numChar), 0, $this->number);
    }

    /**
     * 生成图像
     */
    protected function createImage()
    {
        $this->image = imagecreatetruecolor($this->width, $this->height);
    }

    /**
     * 填充背景色
     */
    protected function fillColor()
    {
        imagefill($this->image, 0, 0, $this->lightColor());
    }

    /**
     * 浅颜色
     * @return int
     */
    protected function lightColor()
    {
        return imagecolorallocate($this->image, mt_rand(170, 255), mt_rand(170, 255), mt_rand(170, 255));
    }

    /**
     * 深颜色
     * @return int
     */
    protected function darkColor()
    {
        return imagecolorallocate($this->image, mt_rand(0, 120), mt_rand(0, 120), mt_rand(0, 120));
    }

    /**
     * 添加验证码字符
     */
    protected function drawChar()
    {
        $width = ceil($this->width/$this->number);
        for ($i = 0; $i < $this->number; $i++) {
            $x = mt_rand($i * ($width - 5), ($i + 1) * ($width - 5));
            $y = mt_rand(0, $this->height - 15);
            imagechar($this->image, 5, $x, $y, $this->code[$i], $this->darkColor());
        }
    }

    /**
     * 添加干扰点
     */
    protected function drawDisturb()
    {
        for ($i= 0; $i < 100; $i++) {
            imagesetpixel($this->image, mt_rand(0, $this->width), mt_rand(0, $this->height), $this->darkColor());
        }
    }

    /**
     * 添加干扰线
     */
    protected function drawArc()
    {
        for ($i = 0; $i < $this->number - 3; $i++) {
            imagearc($this->image, mt_rand(5, $this->width), mt_rand(5, $this->height), mt_rand(5, $this->width), mt_rand(5, $this->height),mt_rand(0, 70), mt_rand(300, 360), $this->darkColor());
        }
    }

    /**
     * 输出显示
     */
    protected function show()
    {
        header('Content-Type:image/png');
        imagepng($this->image);
    }

    /**
     * 外部image
     */
    public function outImage()
    {
        $this->createImage();//创建画布
        $this->fillColor();//填充背景色
        $this->drawChar();//添加验证字符
        $this->drawDisturb();//添加干扰点
        $this->drawArc();//添加干扰线
        $this->show();//输出
    }
}