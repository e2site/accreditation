<?php
/**
 * Отображение для create:
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
    Yii::t('AccreditationModule.accreditation', 'Добавление'),
];

$this->pageTitle = Yii::t('AccreditationModule.accreditation', 'Группы аккредитации - добавление');

$this->menu = [
    ['icon' => 'fa fa-fw fa-list-alt', 'label' => Yii::t('AccreditationModule.accreditation', 'Управление Группами аккредитации'), 'url' => ['/accreditation/accreditationGroupBackend/index']],
    ['icon' => 'fa fa-fw fa-plus-square', 'label' => Yii::t('AccreditationModule.accreditation', 'Добавить Группу аккредитации'), 'url' => ['/accreditation/accreditationGroupBackend/create']],
];
?>
<div class="page-header">
    <h1>
        <?=  Yii::t('AccreditationModule.accreditation', 'Группы аккредитации'); ?>
        <small><?=  Yii::t('AccreditationModule.accreditation', 'добавление'); ?></small>
    </h1>
</div>

<?=  $this->renderPartial('_form', ['model' => $model]); ?>