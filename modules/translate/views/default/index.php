<?php

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var melkov\tools\models\search\LanguageSource $searchModel
 */

use melkov\tools\models\LanguageSource;
use yii\grid\GridView;

$this->title = Yii::t("translate", "Translate Module");
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-default color-palette-box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t("translate", "Translation Source List") ?></h3>
    </div>
    <div class="box-body">
        <?php \yii\widgets\Pjax::begin(['id' => 'pjax-main', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("yo")}']]) ?>

        <?php
        $columns = [


            [
                "attribute" => "category",
                "filter" => \melkov\tools\helpers\ArrayHelper::map(LanguageSource::find()->select("category")->groupBy("category")->orderBy("category")->all(), "category", "category")
            ],
            [
                "attribute" => "message",
                "format" => "raw",
                "value" => function ($model) {
                    return \yii\bootstrap\Html::a($model->message, \yii\helpers\Url::to(["/translate/default/view", "id" => $model->id]));
                }
            ],
        ];
        foreach (\melkov\tools\models\Lang::find()->all() as $lang) {
            $columns[] = [
                "header" => Yii::t("translate", "Message") . " " . $lang->name,
                "value" => function ($model) use ($lang) {
                    /** @var LanguageSource $model */
                    return $model->getTranslateForLang($lang->local);
                }
            ];
        }
        ?>



        <div class="table-responsive">
            <?= GridView::widget([
                'layout' => '{summary}{pager}{items}{pager}',
                'dataProvider' => $dataProvider,
                'pager' => [
                    'class' => yii\widgets\LinkPager::className(),
                    'firstPageLabel' => Yii::t('app', 'First'),
                    'lastPageLabel' => Yii::t('app', 'Last')],
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'headerRowOptions' => ['class' => 'x'],
                'columns' => $columns
            ]); ?>
        </div>



        <?php \yii\widgets\Pjax::end() ?>
    </div>
    <!-- /.box-body -->
</div>