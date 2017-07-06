<?php

namespace melkov\tools\modules\translate\controllers;

use melkov\tools\CurrentUser;
use melkov\tools\models\LanguageSource;
use melkov\tools\models\LanguageTranslate;
use yii\web\Controller;
use melkov\tools\models\search\LanguageSource as LanguageSourceSearch;
use yii\web\HttpException;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new LanguageSourceSearch();
        $dataProvider = $searchModel->search($_GET);
        return $this->render("index", [
            "searchModel" => $searchModel,
            "dataProvider" => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = LanguageSource::find()->where(["id" => $id])->one();
        if (!$model) {
            throw new HttpException(404, \Yii::t("translate", "Source model with id {id} not found", ["id" => $id]));
        }

        if (isset($_POST["LanguageTranslate"]) && is_array($_POST["LanguageTranslate"])) {
            foreach ($_POST["LanguageTranslate"] as $lang => $value) {
                $translation = LanguageTranslate::find()->where(["id" => $model->id, "language" => $lang])->one();
                if (!$translation) {
                    $translation = new LanguageTranslate(["id" => $model->id, "language" => $lang]);
                }
                $translation->translation = $value;
                $translation->save();
            }
            CurrentUser::setFlashSuccess(\Yii::t("translate", "Translation successfully saved"));
            return $this->redirect(["/translate/default/view", "id" => $model->id]);
        }

        return $this->render("view", [
            "model" => $model
        ]);
    }
}