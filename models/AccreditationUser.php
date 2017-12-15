<?php
Yii::import('application.modules.accreditation.models.AccreditationGroup');

/**
 * This is the model class for table "{{accreditation_user}}".
 *
 * The followings are the available columns in table '{{accreditation_user}}':
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $surname
 * @property string $barcode
 * @property integer $group_id
 * @property string $photo
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property integer $create_date
 * @property integer $update_date
 * @property string $comment
 * @property integer $is_print
 * @property integer $issued
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property AccreditationGroup $group
 */
class AccreditationUser extends yupe\models\YModel
{
	const IS_PRINT = 1;
	const ISNT_PRINT = 0;

	const ACTIVE = 1;
	const NO_ACTIVE = 0;

	const IS_SUED = 1;
	const ISNT_SUED = 0;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{accreditation_user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('firstname, lastname, surname, barcode', 'required'),
			array('group_id, create_user_id, update_user_id, create_date, update_date, is_print, issued, status', 'numerical', 'integerOnly'=>true),
			array('firstname, lastname, surname, photo', 'length', 'max'=>250),
			array('barcode', 'length', 'max'=>64),
			array('comment', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, firstname, lastname, surname, barcode, group_id, photo, create_user_id, update_user_id, create_date, update_date, comment, is_print, issued, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'group' => array(self::BELONGS_TO, 'AccreditationGroup', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'firstname' => 'Имя',
			'lastname' => 'Фамилия',
			'surname' => 'Отчество',
			'barcode' => 'Barcode',
			'group_id' => 'Категория',
			'photo' => 'Фото',
			'create_user_id' => 'Create User',
			'update_user_id' => 'Update User',
			'create_date' => 'Create Date',
			'update_date' => 'Update Date',
			'comment' => 'Комментарий',
			'is_print' => 'Напечатан',
			'issued' => 'Выдан',
			'status' => 'Статус',
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
			'CTimestampBehavior' => [
				'class' => 'zii.behaviors.CTimestampBehavior',
				'setUpdateOnCreate' => true,
				'createAttribute' => 'create_date',
				'updateAttribute' => 'update_date',
			],

			'photoUpload' => [
				'class' => 'yupe\components\behaviors\ImageUploadBehavior',
				'attributeName' => 'photo',
				'minSize' => $module->minSize,
				'maxSize' => $module->maxSize,
				'types' => $module->allowedExtensions,
				'uploadPath' => $module->uploadPhotoPath,
				'resizeOptions' => [
					'quality' => [
						'jpeg_quality' => 100,
						'png_compression_level' => 3,
					],
				]
			],
		];
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
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('barcode',$this->barcode,true);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_user_id',$this->update_user_id);
		$criteria->compare('create_date',$this->create_date);
		$criteria->compare('update_date',$this->update_date);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('is_print',$this->is_print);
		$criteria->compare('issued',$this->issued);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return bool
	 */
	public function beforeSave()
	{

		$this->update_user_id = Yii::app()->user->getId();
		$this->savePhoto();
		if ($this->getIsNewRecord()) {
			$this->create_user_id = $this->update_user_id;
		}

		return parent::beforeSave();
	}

	public function savePhoto(){
		if(isset($_POST['AccreditationUser']['webcam']) && (strlen($_POST['AccreditationUser']['webcam'])>0) ) {
			$fileName = md5(time()).'.png';

			/**
			 * @var AccreditationModule $module
			 */
			$module = Yii::app()->getModule('accreditation');

			if( file_exists($module->getUploadPhotoPath().DIRECTORY_SEPARATOR.$this->photo)) {
				@unlink($module->getUploadPhotoPath().DIRECTORY_SEPARATOR.$this->photo);
			}
			try{
				$this->photo = $fileName;
				$rawData =$_POST['AccreditationUser']['webcam'];
				$filterData = explode(',',$rawData);
				$unencoded = base64_decode($filterData[1]);
				$fp = fopen($module->getUploadPhotoPath().DIRECTORY_SEPARATOR.$this->photo,'w');
				fwrite($fp,$unencoded);
				fclose($fp);
			} catch(Exception $err) {
				throw new CHttpException(500,$err->getMessage());
			}

		}




	}

	public function beforeValidate(){
		if ($this->getIsNewRecord()) {
			if(($this->surname==null) || strlen($this->surname)==0) {
				$this->surname = '-';
			}
			$this->barcode = $this->makeBarcode();
		}
		return parent::beforeValidate();
	}


	public function makeBarcode(){
		$barcode = date('Nymdv').rand(10,99).date('hm');
		return $barcode;
	}

	public function getStatusList(){
		return [
			self::NO_ACTIVE => 'Отключен',
			self::ACTIVE => 'Активный'
		];
	}

	public function getPrintStatus() {
		return [
			self::ISNT_PRINT =>'Не напечатан',
			self::IS_PRINT =>'Напечатан'
		];
	}

	public function getIssuedStatus(){
		return [
			self::ISNT_SUED =>'Не выдан',
			self::IS_SUED =>'Выдан'
		];
	}

	public function getPrintUrl(){
		return Yii::app()->createUrl('/accreditation/accreditationUserBackend/print',['id'=>$this->id]);
	}

	public function getIssuedUrl(){
		return Yii::app()->createUrl('/accreditation/accreditationUserBackend/issued');
	}

	public function getPrintLink(){
		return CHtml::link('Печать',$this->getPrintUrl(),['class'=>'label label-info printclk','data-id'=>$this->id,'target'=>'_blank']);
	}


	public function getIssuedLink(){
		return CHtml::link('Выдать','#',['class'=>'label label-danger issuedclk','data-id'=>$this->id]);
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccreditationUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
