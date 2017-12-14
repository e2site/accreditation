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
    Yii::t('AccreditationModule.accreditation', 'Аккредитаций') => ['/accreditation/accreditationUserBackend/index'],
    $model->id,
];

$this->pageTitle = Yii::t('AccreditationModule.accreditation', 'Аккредитаций - просмотр');

$this->menu = [
    ['icon' => 'fa fa-fw fa-list-alt', 'label' => Yii::t('AccreditationModule.accreditation', 'Управление Аккредитациями'), 'url' => ['/accreditation/accreditationUserBackend/index']],
    ['icon' => 'fa fa-fw fa-plus-square', 'label' => Yii::t('AccreditationModule.accreditation', 'Добавить Аккредитацию'), 'url' => ['/accreditation/accreditationUserBackend/create']],
    ['label' => Yii::t('AccreditationModule.accreditation', 'Аккредитация') . ' «' . mb_substr($model->id, 0, 32) . '»'],
    ['icon' => 'fa fa-fw fa-pencil', 'label' => Yii::t('AccreditationModule.accreditation', 'Редактирование Аккредитацию'), 'url' => [
        '/accreditation/accreditationUserBackend/update',
        'id' => $model->id
    ]],
    ['icon' => 'fa fa-fw fa-eye', 'label' => Yii::t('AccreditationModule.accreditation', 'Просмотреть Аккредитацию'), 'url' => [
        '/accreditation/accreditationUserBackend/view',
        'id' => $model->id
    ]],
    ['icon' => 'fa fa-fw fa-trash-o', 'label' => Yii::t('AccreditationModule.accreditation', 'Удалить Аккредитацию'), 'url' => '#', 'linkOptions' => [
        'submit' => ['/accreditation/accreditationUserBackend/delete', 'id' => $model->id],
        'confirm' => Yii::t('AccreditationModule.accreditation', 'Вы уверены, что хотите удалить Аккредитацию?'),
        'csrf' => true,
    ]],
];
?>
<div class="page-header">
    <h1>
        <?=  Yii::t('AccreditationModule.accreditation', 'Просмотр') . ' ' . Yii::t('AccreditationModule.accreditation', 'Аккредитацию'); ?>        <br/>
        <small>&laquo;<?=  $model->id; ?>&raquo;</small>
    </h1>
</div>

<?php $this->widget('bootstrap.widgets.TbDetailView', [
    'data'       => $model,
    'attributes' => [
        'id',
        'firstname',
        'lastname',
        'surname',
        'barcode',
        'group_id',
        'photo',
        'create_user_id',
        'update_user_id',
        'create_date',
        'update_date',
        'comment',
        'is_print',
        'issued',
        'status',
    ],
]); ?>
