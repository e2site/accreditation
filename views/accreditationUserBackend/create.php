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
    Yii::t('AccreditationModule.accreditation', 'Аккредитаций') => ['/accreditation/accreditationUserBackend/index'],
    Yii::t('AccreditationModule.accreditation', 'Добавление'),
];

$this->pageTitle = Yii::t('AccreditationModule.accreditation', 'Аккредитаций - добавление');

$this->menu = [
    ['icon' => 'fa fa-fw fa-list-alt', 'label' => Yii::t('AccreditationModule.accreditation', 'Управление Аккредитациями'), 'url' => ['/accreditation/accreditationUserBackend/index']],
    ['icon' => 'fa fa-fw fa-plus-square', 'label' => Yii::t('AccreditationModule.accreditation', 'Добавить Аккредитацию'), 'url' => ['/accreditation/accreditationUserBackend/create']],
];
?>
<div class="page-header">
    <h1>
        <?=  Yii::t('AccreditationModule.accreditation', 'Аккредитаций'); ?>
        <small><?=  Yii::t('AccreditationModule.accreditation', 'добавление'); ?></small>
    </h1>
</div>

<?=  $this->renderPartial('_form', ['model' => $model]); ?>