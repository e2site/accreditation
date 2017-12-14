<?php
/**
* Класс AccreditationGroupController:
*
*   @category Yupe\yupe\components\controllers\BackController
*   @package  yupe
*   @author   Yupe Team <team@yupe.ru>
*   @license  https://github.com/yupe/yupe/blob/master/LICENSE BSD
*   @link     http://yupe.ru
**/
class AccreditationGroupBackendController extends \yupe\components\controllers\BackController
{
    /**
    * Отображает Группу аккредитации по указанному идентификатору
    *
    * @param integer $id Идинтификатор Группу аккредитации для отображения
    *
    * @return void
    */
    public function actionView($id)
    {
        $this->render('view', ['model' => $this->loadModel($id)]);
    }
    
    /**
    * Создает новую модель Группу аккредитации.
    * Если создание прошло успешно - перенаправляет на просмотр.
    *
    * @return void
    */
    public function actionCreate()
    {
        $model = new AccreditationGroup;

        if (Yii::app()->getRequest()->getPost('AccreditationGroup') !== null) {
            $model->setAttributes(Yii::app()->getRequest()->getPost('AccreditationGroup'));
        
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
    
    /**
    * Редактирование Группу аккредитации.
    *
    * @param integer $id Идинтификатор Группу аккредитации для редактирования
    *
    * @return void
    */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (Yii::app()->getRequest()->getPost('AccreditationGroup') !== null) {
            $model->setAttributes(Yii::app()->getRequest()->getPost('AccreditationGroup'));

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
    * Удаляет модель Группу аккредитации из базы.
    * Если удаление прошло успешно - возвращется в index
    *
    * @param integer $id идентификатор Группу аккредитации, который нужно удалить
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
    * Управление Группами аккредитации.
    *
    * @return void
    */
    public function actionIndex()
    {
        $model = new AccreditationGroup('search');
        $model->unsetAttributes(); // clear any default values
        if (Yii::app()->getRequest()->getParam('AccreditationGroup') !== null)
            $model->setAttributes(Yii::app()->getRequest()->getParam('AccreditationGroup'));
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
        $model = AccreditationGroup::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('AccreditationModule.accreditation', 'Запрошенная страница не найдена.'));

        return $model;
    }
}
