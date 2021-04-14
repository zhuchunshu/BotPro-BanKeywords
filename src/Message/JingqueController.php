<?php
namespace App\Plugins\Bankeywords\src\Message;

use App\Plugins\Bankeywords\src\Models\BankeywordsCi;

class JingqueController {

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
            if(BankeywordsCi::where([['content',$data->message],['type','精确']])->count()){
                // 匹配到违禁词
                $all = BankeywordsCi::where([['content',$data->message],['type','精确']])->get();
                foreach ($all as $value) {
                    $c = $value->event;
                    if(method_exists(new JingqueController(),$c)){
                        $this->$c($value);
                    }else{
                        sendMsg([
                            'group_id' => $this->data->group_id,
                            'message' => "未注册方法: 「{$value->event}」"
                        ], "send_group_msg");
                    }
                }
            }
        }
    }

    public function 撤回($value){
        sendMsg([
            'message_id' => $this->data->message_id
        ],'delete_msg');
        if($value->events){
            $c = $value->events;
            if(method_exists(new JingqueController(),$c)){
                sendMsg([
                    'message_id' => $this->data->message_id
                ],'delete_msg');
                $this->$c($value);
            }else{
                sendMsg([
                    'message_id' => $this->data->message_id
                ],'delete_msg');
                $this->$c($value);
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'message' => "未注册方法: 「{$value->events}」"
                ], "send_group_msg");
            }
        }
    }

    public function 禁言($value){
        if($value->ban_time){
            sendMsg([
                'group_id' => $this->data->group_id,
                'user_id' => $this->data->user_id,
                'duration' => $value->ban_time*60
            ],'set_group_ban');
        }
        if($value->ban_time2){
            sendMsg([
                'group_id' => $this->data->group_id,
                'user_id' => $this->data->user_id,
                'duration' => $value->ban_time2*60
            ],'set_group_ban');
        }
    }

    public function 踢出(){
        sendMsg([
            'group_id' => $this->data->group_id,
            'user_id' => $this->data->user_id,
        ],'set_group_kick');
    }
    public function 踢出拉黑(){
        sendMsg([
            'group_id' => $this->data->group_id,
            'user_id' => $this->data->user_id,
            'reject_add_request' => true
        ],'set_group_kick');
    }
}