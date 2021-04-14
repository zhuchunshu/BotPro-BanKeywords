<?php
namespace App\Plugins\Bankeywords\src\Message;

use App\Plugins\Bankeywords\src\Jobs\BankeywordsMohuJob;
use App\Plugins\Bankeywords\src\Models\BankeywordsCi;

class MohuController {

    /**
     * 接收到的数据
     *
     * @var object
     */
    public $data;

    /**
     * 插件信息
     *
     * @var array
     */
    public $value;

    /**
     * 插件注册
     *
     * @param object 接收到的数据 $data
     * @param array 插件信息 $value
     * @return void
     */
    public function register($data, $value)
    {
        $this->data = $data;
        $this->value = $value;
        if($data->sender->role=="member"){
            dispatch(new BankeywordsMohuJob($data,$value));
        }
    }
}