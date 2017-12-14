<?php
/**
* Класс AccreditationUserController:
*
*   @category Yupe\yupe\components\controllers\BackController
*   @package  yupe
*   @author   Yupe Team <team@yupe.ru>
*   @license  https://github.com/yupe/yupe/blob/master/LICENSE BSD
*   @link     http://yupe.ru
**/
class AccreditationUserBackendController extends \yupe\components\controllers\BackController
{
    /**
    * Отображает Аккредитацию по указанному идентификатору
    *
    * @param integer $id Идинтификатор Аккредитацию для отображения
    *
    * @return void
    */
    public function actionView($id)
    {
        $this->render('view', ['model' => $this->loadModel($id)]);
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'inline' => [
                'class' => 'yupe\components\actions\YInLineEditAction',
                'model' => 'AccreditationUser',
                'validAttributes' => [
                    'is_print',
                    'issued',
                    'status'
                ],
            ],
        ];
    }


    /**
    * Создает новую модель Аккредитацию.
    * Если создание прошло успешно - перенаправляет на просмотр.
    *
    * @return void
    */
    public function actionCreate()
    {
        $model = new AccreditationUser;

        if (Yii::app()->getRequest()->getPost('AccreditationUser') !== null) {
            $model->setAttributes(Yii::app()->getRequest()->getPost('AccreditationUser'));
        
            if ($model->save()) {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('AccreditationModule.accreditation', 'Запись добавлена!')
                );

                $this->redirect(
                    (array)Yii::app()->getRequest()->getPost(
                        'submit-type',
                        [
                            'update',
                            'id' => $model->id
                        ]
                    )
                );
            }
        }
        $this->render('create', ['model' => $model]);
    }

    public function actionPrint($id){
        $id = (int)$id;
        $user = AccreditationUser::model()->findByPk($id);
        if(!$user) throw new CHttpException(404,'Аккредитация не найдена');

        try{
            $group = $user->group;
            $pdf = new PDFGenerator($user,$group);
            $user->is_print = AccreditationUser::IS_PRINT;
            $user->save();
            $pdf->generatePdf();

        } catch(Exception $err) {
            throw new CHttpException(500,$err->getMessage());
        }


    }

    public function actionIssued($id) {
        /**
         * @var AccreditationUser $user
         */
        $user = AccreditationUser::model()->findByPk((int)$id);
        $result = 0;
        if($user){
            $user->issued = AccreditationUser::IS_SUED;
            $user->save();
            $result = 1;
        }
        echo CJSON::encode($result);
    }
    
    /**
    * Редактирование Аккредитацию.
    *
    * @param integer $id Идинтификатор Аккредитацию для редактирования
    *
    * @return void
    */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (Yii::app()->getRequest()->getPost('AccreditationUser') !== null) {
            $model->setAttributes(Yii::app()->getRequest()->getPost('AccreditationUser'));

            if ($model->save()) {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('AccreditationModule.accreditation', 'Запись обновлена!')
                );

                $this->redirect(
                    (array)Yii::app()->getRequest()->getPost(
                        'submit-type',
                        [
                            'update',
                            'id' => $model->id
                        ]
                    )
                );
            }
        }
        $this->render('update', ['model' => $model]);
    }
    
    /**
    * Удаляет модель Аккредитацию из базы.
    * Если удаление прошло успешно - возвращется в index
    *
    * @param integer $id идентификатор Аккредитацию, который нужно удалить
    *
    * @return void
    */
    public function actionDelete($id)
    {
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            // поддерживаем удаление только из POST-запроса
            $this->loadModel($id)->delete();

            Yii::app()->user->setFlash(
                yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                Yii::t('AccreditationModule.accreditation', 'Запись удалена!')
            );

            // если это AJAX запрос ( кликнули удаление в админском grid view), мы не должны никуда редиректить
            if (!Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->redirect(Yii::app()->getRequest()->getPost('returnUrl', ['index']));
            }
        } else
            throw new CHttpException(400, Yii::t('AccreditationModule.accreditation', 'Неверный запрос. Пожалуйста, больше не повторяйте такие запросы'));
    }
    
    /**
    * Управление Аккредитациями.
    *
    * @return void
    */
    public function actionIndex()
    {
        $model = new AccreditationUser('search');
        $model->unsetAttributes(); // clear any default values
        if (Yii::app()->getRequest()->getParam('AccreditationUser') !== null)
            $model->setAttributes(Yii::app()->getRequest()->getParam('AccreditationUser'));
        $this->render('index', ['model' => $model]);
    }
    
    /**
    * Возвращает модель по указанному идентификатору
    * Если модель не будет найдена - возникнет HTTP-исключение.
    *
    * @param integer идентификатор нужной модели
    *
    * @return void
    */
    public function loadModel($id)
    {
        $model = AccreditationUser::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('AccreditationModule.accreditation', 'Запрошенная страница не найдена.'));

        return $model;
    }
}
