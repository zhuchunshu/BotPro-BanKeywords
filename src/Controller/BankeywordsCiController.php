<?php

namespace App\Plugins\Bankeywords\src\Controller;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Admin;
use Illuminate\Http\Response;
use Dcat\Admin\Layout\Content;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Plugins\Bankeywords\src\Repositories\BankeywordsCi;

class BankeywordsCiController extends Controller
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new BankeywordsCi(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('content');
            $grid->column('type');
            $grid->column('event');

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id): Show
    {
        return Show::make($id, new BankeywordsCi(), function (Show $show) {
            $show->field('id');
            $show->field('content');
            $show->field('type');
            $show->field('event');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        return Form::make(new BankeywordsCi(), function (Form $form) {
            $form->display('id');
            $form->textarea('content', '敏感词')->required();
            $form->select('type', '触发条件')->options(['精确' => '精确触发', '模糊' => '模糊触发'])->required();
            $form->select('event', '触发事件')
            ->options(['撤回' => '撤回', '禁言' => '禁言','踢出' => '踢出','踢出拉黑' => '踢出并拉黑'])
            ->when('禁言',function(Form $form){
                $form->number('ban_time', '禁言时长(分钟)')->required()->max('43200')->min(1);
            })
            ->when('撤回',function(Form $form){
                $form->select('events','撤回并且')
                ->options(['禁言' => '禁言', '踢出' => '踢出', '踢出拉黑' => '踢出并拉黑'])
                ->when('禁言',function(Form $form){
                    $form->number('ban_time', '禁言时长(分钟)')->required()->max('43200')->min(1);
                });
            })
            ->required();
        });
    }

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title;

    /**
     * Set description for following 4 action pages.
     *
     * @var array
     */
    protected $description = [
        //        'index'  => 'Index',
        //        'show'   => 'Show',
        //        'edit'   => 'Edit',
        //        'create' => 'Create',
    ];

    /**
     * Get content title.
     *
     * @return string
     */
    protected function title(): string
    {
        return $this->title ?: admin_trans_label();
    }

    /**
     * Get description for following 4 action pages.
     *
     * @return array
     */
    protected function description(): array
    {
        return $this->description;
    }

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description($this->description()['index'] ?? trans('admin.list'))
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function show($id, Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description($this->description()['show'] ?? trans('admin.show'))
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description($this->description()['edit'] ?? trans('admin.edit'))
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content): Content
    {
        return $content
            ->title($this->title())
            ->description($this->description()['create'] ?? trans('admin.create'))
            ->body($this->form());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return JsonResponse|RedirectResponse|Response
     */
    public function update(int $id)
    {
        return $this->form()->update($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->form()->store();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->form()->destroy($id);
    }
}
