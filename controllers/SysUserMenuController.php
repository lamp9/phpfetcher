<?php
namespace app\controllers;

use Yii;
use app\logic\sys\SysMenu;
use app\logic\BaseController;
class SysUserMenuController extends BaseController
{
    public function actionIndex(){
        $model = self::findModel('new', $this->model);
        return $this->renderPartial('index', ['model' => $model, 'data' => SysMenu::getList()]);
    }

    public function actionOne(){
        $id = intval($_POST['id']);
        echo json_encode(SysMenu::getSingle($id));
    }

    public function actionCreate(){
        SysMenu::add(Yii::$app->request->post());
    }

    public function actionUpdate($id){
        SysMenu::update(Yii::$app->request->post());
    }

    public function actionDelete(){
        $id = intval($_POST['id']);
        SysMenu::delete($id);
    }
}
