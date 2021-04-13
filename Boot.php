<?php
namespace App\Plugins\Bankeywords;

use App\Plugins\Bankeywords\src\Controller\BankeywordsCiController;
use Dcat\Admin\Admin;
use Dcat\Admin\Layout\Menu;
use Illuminate\Support\Facades\Route;
use App\Plugins\Bankeywords\src\Controller\IndexController;
use App\Plugins\Bankeywords\src\database\CreateBanKeywordsCiTable;

class Boot {

    public function handle(){
        $this->menu();
        $this->route();
        $this->SqlMigrate();
    }

    // 数据库迁移
    public function SqlMigrate(){
        (new CreateBanKeywordsCiTable())->up();
    }

    // 注册路由
    public function route(){
        Route::group([
            'prefix'     => config('admin.route.prefix')."/bankeywords",
            'middleware' => config('admin.route.middleware'),
        ], function () {
            Route::get('/', [IndexController::class,'show']); // 插件信息
            Route::get('/ci', [BankeywordsCiController::class,'index']);
            Route::get('/ci/create', [BankeywordsCiController::class,'create']); // 新增
            Route::post('/ci', [BankeywordsCiController::class,'store']); // 保存
            Route::get('/ci/{id}/edit', [BankeywordsCiController::class,'edit']); // 编辑
            Route::get('/ci/{id}', [BankeywordsCiController::class,'show']); // 显示
            Route::put('/ci/{id}', [BankeywordsCiController::class,'update']); // 更新
            Route::delete('/ci/{id}', [BankeywordsCiController::class,'destroy']); //删除
        });
    }

    // 注册菜单
    public function menu(){
        Admin::menu(function (Menu $menu) {
            $menu->add([
                [
                    'id'            => 100, // 此id只要保证当前的数组中是唯一的即可
                    'title'         => '关键词过滤',
                    'icon'          => 'feather icon-message-square',
                    // 'uri'           => 'fuduji',
                    'parent_id'     => 0,
                    'permission_id' => 'administrator', // 与权限绑定
                    'roles'         => 'administrator', // 与角色绑定
                ],
                [
                    'id'            => 101, // 此id只要保证当前的数组中是唯一的即可
                    'title'         => '插件信息',
                    'icon'          => '',
                    'uri'           => 'bankeywords',
                    'parent_id'     => 100,
                    'permission_id' => 'administrator', // 与权限绑定
                    'roles'         => 'administrator', // 与角色绑定
                ],
                [
                    'id'            => 102, // 此id只要保证当前的数组中是唯一的即可
                    'title'         => '敏感词管理',
                    'icon'          => '',
                    'uri'           => 'bankeywords/ci',
                    'parent_id'     => 100,
                    'permission_id' => 'administrator', // 与权限绑定
                    'roles'         => 'administrator', // 与角色绑定
                ],
            ]);
        });
    }

}
