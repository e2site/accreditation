<?php
/**
 * Отображение для view:
 *
 *   @category YupeView
 *   @package  yupe
 *   @author   Yupe Team <team@yupe.ru>
 *   @license  https://github.com/yupe/yupe/blob/master/LICENSE BSD
 *   @link     http://yupe.ru
 **/
$this->breadcrumbs = [
    $this->getModule()->getCategory() => [],
    Yii::t('AccreditationModule.accreditation', 'Группы аккредитации') => ['/accreditation/accreditationGroupBackend/index'],
    $model->name,
];

$this->pageTitle = Yii::t('AccreditationModule.accreditation', 'Группы аккредитации - просмотр');

$this->menu = [
    ['icon' => 'fa fa-fw fa-list-alt', 'label' => Yii::t('AccreditationModule.accreditation', 'Управление Группами аккредитации'), 'url' => ['/accreditation/accreditationGroupBackend/index']],
    ['icon' => 'fa fa-fw fa-plus-square', 'label' => Yii::t('AccreditationModule.accreditation', 'Добавить Группу аккредитации'), 'url' => ['/accreditation/accreditationGroupBackend/create']],
    ['label' => Yii::t('AccreditationModule.accreditation', 'Группа аккредитации') . ' «' . mb_substr($model->id, 0, 32) . '»'],
    ['icon' => 'fa fa-fw fa-pencil', 'label' => Yii::t('AccreditationModule.accreditation', 'Редактирование Группу аккредитации'), 'url' => [
        '/accreditation/accreditationGroupBackend/update',
        'id' => $model->id
    ]],
    ['icon' => 'fa fa-fw fa-eye', 'label' => Yii::t('AccreditationModule.accreditation', 'Просмотреть Группу аккредитации'), 'url' => [
        '/accreditation/accreditationGroupBackend/view',
        'id' => $model->id
    ]],
    ['icon' => 'fa fa-fw fa-trash-o', 'label' => Yii::t('AccreditationModule.accreditation', 'Удалить Группу аккредитации'), 'url' => '#', 'linkOptions' => [
        'submit' => ['/accreditation/accreditationGroupBackend/delete', 'id' => $model->id],
        'confirm' => Yii::t('AccreditationModule.accreditation', 'Вы уверены, что хотите удалить Группу аккредитации?'),
        'csrf' => true,
    ]],
];
?>
<div class="page-header">
    <h1>
        <?=  Yii::t('AccreditationModule.accreditation', 'Просмотр') . ' ' . Yii::t('AccreditationModule.accreditation', 'Группу аккредитации'); ?>        <br/>
        <small>&laquo;<?=  $model->name; ?>&raquo;</small>
    </h1>
</div>

<?php $this->widget('bootstrap.widgets.TbDetailView', [
    'data'       => $model,
    'attributes' => [
        'id',
        'name',
        'description',
        'template',
        'is_barcode',
        'status',
    ],
]); ?>
