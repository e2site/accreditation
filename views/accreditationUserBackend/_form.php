<?php
/**
 * Отображение для _form:
 *
 *   @category YupeView
 *   @package  yupe
 *   @author   Yupe Team <team@yupe.ru>
 *   @license  https://github.com/yupe/yupe/blob/master/LICENSE BSD
 *   @link     http://yupe.ru
 *
 *   @var $model AccreditationUser
 *   @var $form TbActiveForm
 *   @var $this AccreditationUserController
 **/
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm', [
        'id'                     => 'accreditation-user-form',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'htmlOptions'            => ['class' => 'well', 'enctype' => 'multipart/form-data'],
    ]
);

$mainAssets = Yii::app()->getAssetManager()->publish(
    Yii::getPathOfAlias('application.modules.accreditation.views.assets')
);

Yii::app()->getClientScript()->registerCssFile($mainAssets . '/cropper/cropper.css');
Yii::app()->getClientScript()->registerScriptFile($mainAssets . '/cropper/cropper.js');
Yii::app()->getClientScript()->registerScriptFile($mainAssets . '/js/webcam.js');
?>

<div class="alert alert-info">
    <?=  Yii::t('AccreditationModule.accreditation', 'Поля, отмеченные'); ?>
    <span class="required">*</span>
    <?=  Yii::t('AccreditationModule.accreditation', 'обязательны.'); ?>
</div>

<?=  $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-sm-7">
            <?=  $form->textFieldGroup($model, 'firstname', [
                'widgetOptions' => [
                    'htmlOptions' => [
                        'class' => 'popover-help',
                        'data-original-title' => $model->getAttributeLabel('firstname'),
                        'data-content' => $model->getAttributeDescription('firstname')
                    ]
                ]
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-7">
            <?=  $form->textFieldGroup($model, 'lastname', [
                'widgetOptions' => [
                    'htmlOptions' => [
                        'class' => 'popover-help',
                        'data-original-title' => $model->getAttributeLabel('lastname'),
                        'data-content' => $model->getAttributeDescription('lastname')
                    ]
                ]
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-7">
            <?=  $form->textFieldGroup($model, 'surname', [
                'widgetOptions' => [
                    'htmlOptions' => [
                        'class' => 'popover-help',
                        'data-original-title' => $model->getAttributeLabel('surname'),
                        'data-content' => $model->getAttributeDescription('surname')
                    ]
                ]
            ]); ?>
        </div>
    </div>

            <?=  $form->hiddenField($model, 'barcode');       ?>

    <div class="row">
        <div class="col-sm-7">
            <?=  $form->dropDownListGroup($model, 'group_id', [
                    'widgetOptions' => [
                        'data' => AccreditationGroup::getGroupDataList(true)
                    ]
                ]); ?>
        </div>
    </div>
    <div class='row'>
        <div class="col-sm-7">
            <div id="imgPano">
                <?php
                echo CHtml::image(
                    !$model->getIsNewRecord() && $model->photo ? $model->getImageUrl() : '#',
                    $model->firstname,
                    [
                        'class' => 'preview-image img-responsive',
                        'style' => !$model->getIsNewRecord() && $model->photo ? '' : 'display:none',
                    ]
                ); ?>
            </div>

            <?php if (!$model->getIsNewRecord() && $model->photo): ?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="delete-file"> <?= Yii::t('YupeModule.yupe', 'Delete the file') ?>
                    </label>
                </div>
            <?php endif; ?>

            <?= $form->fileFieldGroup(
                $model,
                'photo',
                [
                    'widgetOptions' => [
                        'htmlOptions' => [
                            'onchange' => 'readURL(this);',
                            'style' => 'background-color: inherit;',
                        ],
                    ],
                ]
            ); ?>
            <button class="btn btn-primary btn-lg" type="button" data-toggle="modal" data-target="#photoeditor">
                Фото редактор
            </button>
        </div>
    </div>



    <div class="row">
        <div class="col-sm-7">
            <?=  $form->textAreaGroup($model, 'comment', [
            'widgetOptions' => [
                'htmlOptions' => [
                    'class' => 'popover-help',
                    'rows' => 6,
                    'cols' => 50,
                    'data-original-title' => $model->getAttributeLabel('comment'),
                    'data-content' => $model->getAttributeDescription('comment')
                ]
            ]]); ?>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-4">
            <?= $form->dropDownListGroup(
                $model,
                'status',
                [
                    'widgetOptions' => [
                        'data'        => $model->getStatusList(),
                        'htmlOptions' => [
                            'class'               => 'popover-help',
                            'data-original-title' => $model->getAttributeLabel('status'),
                            'data-content'        => $model->getAttributeDescription('status'),
                        ],
                    ],
                ]
            ); ?>
        </div>
    </div>

    <?php if(!$model->getIsNewRecord()):?>
        <div class="row">
            <div class="col-sm-4">
                <p>
                <a class="btn btn-warning" target="_blank" href="<?= $model->getPrintUrl();?>">Печать</a>
                    <?php if( $model->issued != AccreditationUser::IS_SUED):?>
                        <a class="btn btn-success " id="getAccreditation" href="#getAccreditation" data-id="<?=$model->id;?>">Выдать</a>
                    <?php else: ?>
                        <span class="label label-success">Аккредитация выдана</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    <?php endif;?>


    <?php $this->widget(
        'bootstrap.widgets.TbButton', [
            'buttonType' => 'submit',
            'context'    => 'primary',
            'label'      => Yii::t('AccreditationModule.accreditation', 'Сохранить Аккредитацию и продолжить'),
        ]
    ); ?>
    <?php $this->widget(
        'bootstrap.widgets.TbButton', [
            'buttonType' => 'submit',
            'htmlOptions'=> ['name' => 'submit-type', 'value' => 'index'],
            'label'      => Yii::t('AccreditationModule.accreditation', 'Сохранить Аккредитацию и закрыть'),
        ]
    ); ?>
<textarea id="imgdata" name="AccreditationUser[webcam]" style="display: none;"></textarea>


<?php $this->endWidget(); ?>
<!-- Modal -->
<div class="modal fade" id="photoeditor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 850px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Фото редактор</h4>
                <button type="button" id="retakePhotoBtn" class="btn btn-info">Переделать фото</button>
                <button id="snap" type="button" class="btn btn-warning">Сделать фото</button>
                <div>
                <label for="fileLoad" style="float: left">Загрузить из файла: </label><input  id="fileLoad" name="fileLoad" type="file" />
                </div>
            </div>
            <div class="modal-body">

                <video id="v" width="800" height="600" autoplay></video>


                <canvas id="c" width="800" height="600"></canvas>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary"  id="upload" data-dismiss="modal">Сохранить фото</button>
            </div>
        </div>
    </div>
</div>


<?php
Yii::app()->getClientScript()->registerScript('webcam',"
    $('#getAccreditation').click(function(){
        id = $(this).data('id');
        $.ajax({
            url: '".AccreditationUser::model()->getIssuedUrl()."',
            type: \"GET\",
            data:{
                id: id
            },
            dataType: \"json\",
            success: function (data) {
                if(data==1) {
                    $('#getAccreditation').hide();
                }
            }
        });
    });


        var cropper=null;
		var w = new WebCam({
			canvas:document.getElementById(\"c\"),
			video:document.getElementById(\"v\"),
			inputId: document.getElementById('fileLoad'),
			btnTakePhoto: document.getElementById(\"snap\"),
			btnReTakePhoto:document.getElementById(\"retakePhotoBtn\"),
			fncTakePhoto: function(){
				if(cropper != null) {
						cropper.destroy();
						cropper = null;
				}
				cropper = new Cropper(document.getElementById(\"c\"),
					{
						aspectRatio: 3 / 4
					}
				);
			},
			fncReTakePhoto: function(){
				if(cropper!=null) {
					cropper.destroy();
					cropper = null;
				}
			}
		});

		$('#upload').click(function(){
			$('#imgPano').html(cropper.getCroppedCanvas());
			cropper.destroy();
			$('#imgPano canvas').attr('id','tmpCanvas');
			$('#imgdata').val(document.getElementById(\"tmpCanvas\").toDataURL(\"image/png\"));
		});

",CClientScript::POS_LOAD);

?>
<script>

</script>
