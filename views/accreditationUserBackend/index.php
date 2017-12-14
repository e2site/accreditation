<?php
/**
 * Отображение для index:
 *
 *   @category YupeView
 *   @package  yupe
 *   @author   Yupe Team <team@yupe.ru>
 *   @license  https://github.com/yupe/yupe/blob/master/LICENSE BSD
 *   @link     http://yupe.ru
 **/
$this->breadcrumbs = [
    $this->getModule()->getCategory() => [],
    Yii::t('AccreditationModule.accreditation', 'Аккредитаций') => ['/accreditation/accreditationUserBackend/index'],
    Yii::t('AccreditationModule.accreditation', 'Управление'),
];

$this->pageTitle = Yii::t('AccreditationModule.accreditation', 'Аккредитаций - управление');

$this->menu = [
    ['icon' => 'fa fa-fw fa-list-alt', 'label' => Yii::t('AccreditationModule.accreditation', 'Управление Аккредитациями'), 'url' => ['/accreditation/accreditationUserBackend/index']],
    ['icon' => 'fa fa-fw fa-plus-square', 'label' => Yii::t('AccreditationModule.accreditation', 'Добавить Аккредитацию'), 'url' => ['/accreditation/accreditationUserBackend/create']],
];
?>
<div class="page-header">
    <h1>
        <?=  Yii::t('AccreditationModule.accreditation', 'Аккредитаций'); ?>
        <small><?=  Yii::t('AccreditationModule.accreditation', 'управление'); ?></small>
    </h1>
</div>

<p>
    <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="collapse" data-target="#search-toggle">
        <i class="fa fa-search">&nbsp;</i>
        <?=  Yii::t('AccreditationModule.accreditation', 'Поиск Аккредитации');?>
        <span class="caret">&nbsp;</span>
    </a>
</p>

<div id="search-toggle" class="collapse out search-form">
        <?php Yii::app()->clientScript->registerScript('search', "
        $('.search-form form').submit(function () {
            $.fn.yiiGridView.update('accreditation-user-grid', {
                data: $(this).serialize()
            });

            return false;
        });
    ");
    $this->renderPartial('_search', ['model' => $model]);
?>
</div>

<br/>

<p> <?=  Yii::t('AccreditationModule.accreditation', 'В данном разделе представлены средства управления Аккредитациями'); ?>
</p>

<?php
 $this->widget(
    'yupe\widgets\CustomGridView',
    [
        'id'           => 'accreditation-user-grid',
        'type'         => 'striped condensed',
        'dataProvider' => $model->search(),
        'filter'       => $model,
        'columns'      => [
            'id',
            'firstname',
            'lastname',
            'surname',
            'barcode',
            [
                'name' => 'group_id',
                'type' => 'raw',
                'value' => '$data->group->name',
                'filter' => AccreditationGroup::getGroupDataList(),
            ],


//            'photo',
//            'create_user_id',
//            'update_user_id',
//            'create_date',
//            'update_date',
//            'comment',
//            'is_print',
            [
                'class'   => 'yupe\widgets\EditableStatusColumn',
                'name'    => 'is_print',
                'url'     => $this->createUrl('/accreditation/accreditationUserBackend/inline'),
                'source'  => $model->getPrintStatus(),
                'options' => [
                    AccreditationUser::IS_PRINT => ['class' => 'label-success'],
                    AccreditationUser::ISNT_PRINT => ['class' => 'label-warning'],
                ],
            ],
            [
                'class'   => 'yupe\widgets\EditableStatusColumn',
                'name'    => 'issued',
                'url'     => $this->createUrl('/accreditation/accreditationUserBackend/inline'),
                'source'  => $model->getIssuedStatus(),
                'options' => [
                    AccreditationUser::IS_SUED => ['class' => 'label-success'],
                    AccreditationUser::ISNT_SUED => ['class' => 'label-warning'],
                ],
            ],
            [
                'class'   => 'yupe\widgets\EditableStatusColumn',
                'name'    => 'status',
                'url'     => $this->createUrl('/accreditation/accreditationUserBackend/inline'),
                'source'  => $model->getStatusList(),
                'options' => [
                    AccreditationUser::ACTIVE => ['class' => 'label-success'],
                    AccreditationUser::NO_ACTIVE => ['class' => 'label-warning'],
                ],
            ],
            'print'=>[
                'header'=>'Печать',
                'type'=>'raw',
                'value'=> '$data->getPrintLink();',
            ],
            'issued'=>[
                'header'=>'Выдать',
                'type'=>'raw',
                'value'=> '$data->getIssuedLink();',
            ],
            [
                'class' => 'yupe\widgets\CustomButtonColumn',

            ],
        ],
    ]
); ?>


<?php
Yii::app()->getClientScript()->registerScript('ajax_print',"
    $('.printclk').click(function(){
        id = $(this).data('id');
        if(id != undefined) {
            $('a[rel*=AccreditationUser_is_print][data-pk*='+id+']').html('<div class=\"label label-success\"><span style=\"border-bottom: 1px dashed;\">Напечатан</span></div>');
        }
    });

    $('.issuedclk').click(function(){
         id = $(this).data('id');
         if(id != undefined) {
            urlIss = '".AccreditationUser::model()->getIssuedUrl()."';

            $.ajax({
            url: urlIss,
            type: \"GET\",
            data:{
             id: id
            },
            dataType: \"json\",
            success: function (data) {
               if(data==1) {
                $('a[rel*=AccreditationUser_issued][data-pk*='+id+']').html('<div class=\"label label-success\"><span style=\"border-bottom: 1px dashed;\">Выдан</span></div>');
               }
            }
        });


        }
    });
",CClientScript::POS_LOAD);

?>