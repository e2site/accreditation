<?php

/**
 * Created by PhpStorm.
 * User: prosto
 * Date: 12.12.2017
 * Time: 13:13
 */

use yupe\components\WebModule;

class AccreditationModule extends \yupe\components\WebModule
{
    const VERSION='0.1';


    /**
     * @var int
     */
    public $minSize = 0;
    /**
     * @var int
     */
    public $maxSize = 5368709120;


    public $allowedExtensions = 'jpg,jpeg,png,gif';


    public $allowedZipExtensions = 'zip';


    public $mimeTypes = 'image/gif, image/jpeg, image/png';

    public $mimeZipTypes = 'application/zip';



    public $uploadPhotoPath = 'photos';

    public $uploadZipPath = 'templates';

    public $printPath = 'print';

    public $cameraWidth=800;
    public $cameraHeight = 600;



    /**
     * @return string
     */
    public function getUploadPhotoPath()
    {
        return Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . Yii::app()->getModule(
            "yupe"
        )->uploadPath . DIRECTORY_SEPARATOR . $this->uploadPhotoPath;
    }

    /**
     * @return string
     */
    public function getUploadTemplatePath()
    {
        return Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . Yii::app()->getModule(
            "yupe"
        )->uploadPath . DIRECTORY_SEPARATOR . $this->uploadZipPath;
    }

    public function getWebTemplatePath(){
        return Yii::app()->getBaseUrl(true).'/'.Yii::app()->getModule("yupe")->uploadPath.'/'.$this->uploadZipPath.'/';
    }

    /**
     * @return string
     */
    public function getUploadPrintoPath()
    {
        return Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . Yii::app()->getModule(
            "yupe"
        )->uploadPath . DIRECTORY_SEPARATOR . $this->printPath;
    }



    /**
     * @return array
     */
    public function getParamsLabels()
    {
        return [
            'cameraWidth' => 'Ширина камеры',
            'cameraHeight' => 'Высота камеры',

        ];
    }

    /**
     * @return array
     */
    public function getEditableParams()
    {
        return [
            'cameraWidth',
            'cameraHeight',
        ];
    }


    public function getNavigation()
    {
        return [
            ['label' =>'Группы аккредитации'],
            [
                'icon' => 'fa fa-fw fa-list-alt',
                'label' => 'Список',
                'url' => ['/accreditation/accreditationGroupBackend/index'],
            ],
            [
                'icon' => 'fa fa-fw fa-plus-square',
                'label' => 'Создать',
                'url' => ['/accreditation/accreditationGroupBackend/create'],
            ],

            ['label' =>  'Аккредитация'],
            [
                'icon' => 'fa fa-fw fa-list-alt',
                'label' => 'Список',
                'url' => ['/accreditation/accreditationUserBackend/index'],
            ],
            [
                'icon' => 'fa fa-fw fa-plus-square',
                'label' => 'Создать',
                'url' => ['/accreditation/accreditationUserBackend/create'],
            ],

        ];
    }


    public function getVersion (){
        return self::VERSION;
    }

    public function getName(){
        return 'Аккредитация';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Блок аккредитации';
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return  'Filipp Chelyshkov';
    }

    /**
     * @return string
     */
    public function getAdminPageLink()
    {
        return '/accreditation/accreditationUserBackend/index';
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return "fa fa-fw fa-pencil";
    }

    /**
     *
     */
    public function init()
    {
        parent::init();

        $this->setImport(
            [
                'accreditation.models.*',
                'accreditation.components.*',
                'vendor.yiiext.taggable-behavior.*',
            ]
        );
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return 'Аккредитация';
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            'user',
        ];
    }

}