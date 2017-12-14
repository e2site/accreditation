<?php

/**
 * This is the model class for table "{{accreditation_group}}".
 *
 * The followings are the available columns in table '{{accreditation_group}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $template
 * @property integer $is_barcode
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property AccreditationUser[] $accreditationUsers
 */
class AccreditationGroup extends yupe\models\YModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{accreditation_group}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name,template', 'required'),
			array('is_barcode, status', 'numerical', 'integerOnly'=>true),
			array('name, template', 'length', 'max'=>250),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, template, is_barcode, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array
	 */
	public function behaviors()
	{
		/**
		 * @var AccreditationModule $module
		 */
		$module = Yii::app()->getModule('accreditation');

		return [
			'templateUpload' => [
				'class' => 'yupe\components\behaviors\FileUploadBehavior',
				'attributeName' => 'template',
				'minSize' => $module->minSize,
				'maxSize' => $module->maxSize,
				'types' => $module->allowedZipExtensions,
				'uploadPath' => $module->uploadZipPath,
			],
		];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'accreditationUsers' => array(self::HAS_MANY, 'AccreditationUser', 'group_id'),
		);
	}


	public function afterSave(){

		/**
		 * @var AccreditationModule $module
		 */
		$module = Yii::app()->getModule('accreditation');

		$pathDir = $module->getUploadTemplatePath().DIRECTORY_SEPARATOR.$this->id;
		if(file_exists($pathDir)){
			$files = glob($pathDir.DIRECTORY_SEPARATOR."*");
			$c = count($files);
			if (count($files) > 0) {
				foreach ($files as $file) {

					if (file_exists($file)) {
						@unlink($file);
					}
				}
			}
		} else{
			@mkdir($pathDir);
			if(!file_exists($pathDir)) throw new CHttpException(500,'Не удалось создать директорию '.$pathDir);
		}
		$zip = new ZipArchive;
		if ($zip->open($module->getUploadTemplatePath().DIRECTORY_SEPARATOR.$this->template) === TRUE) {
			$zip->extractTo($pathDir);
			$zip->close();
		} else {
			throw new CHttpException(500,'Архив не найден');
		}

		return parent::afterSave();
	}


	public static function getGroupDataList($active=false){
		$cr = new CDbCriteria();
		$cr->select ='id,name';
		if($active) {
			$cr->condition = 'status=1';
		}
		$cr->order='name';

		return CHtml::listData(AccreditationGroup::model()->findAll($cr), 'id', 'name');
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Наименование',
			'description' => 'Описание',
			'template' => 'Шаблон',
			'is_barcode' => 'Использовать штрих-код',
			'status' => 'Статус',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('template',$this->template,true);
		$criteria->compare('is_barcode',$this->is_barcode);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccreditationGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function getStatusList(){
		return [
			0=>'Отключен',
			1=>'Активный'
		];
	}

	public function getStatusBarcode(){
		return [
			0=>'Не использовать',
			1=>'Использовать'
		];
	}


}
