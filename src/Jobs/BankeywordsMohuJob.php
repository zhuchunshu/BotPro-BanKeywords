<?php
namespace App\Plugins\Bankeywords\src\Jobs;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Plugins\Bankeywords\src\Models\BankeywordsCi;
use App\Plugins\Bankeywords\src\Message\MohuController;

class BankeywordsMohuJob implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 590;

    public $data;

    public $value;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$value)
    {
        $this->data = $data;
        $this->value = $value;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $all = BankeywordsCi::where('type','模糊')->get();
        foreach ($all as $value) {
            $contains = Str::of($this->data->message)->contains($value->content);
            if($contains){
                $c = $value->event;
                $this->$c($value);
            }
        }
    }

    public function 撤回($value){
        sendMsg([
            'message_id' => $this->data->message_id
        ],'delete_msg');
        if($value->events){
            $c = $value->events;
            sendMsg([
                'message_id' => $this->data->message_id
            ],'delete_msg');
            $this->$c($value);
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
