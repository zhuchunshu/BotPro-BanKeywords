<?php
namespace App\Plugins\Bankeywords\src\Controller;

use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Markdown;

class IndexController {

    public function show(Content $content){
        $content->title('关键词过滤插件');
        $content->header('关键词过滤插件');
        $content->description('关键词过滤插件插件信息');
        $content->body(Card::make(
            Markdown::make(read_file(plugin_path("Bankeywords/README.md")))
        ));
        return $content;
    }

}