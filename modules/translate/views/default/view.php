<?php

/**
 * @var yii\web\View $this
 * @var melkov\tools\models\LanguageSource $model
 */

use melkov\tools\models\LanguageSource;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = Yii::t("translate", "Update translation");
$this->params['breadcrumbs'][] = ["label" => Yii::t("translate", "Translate Module"), "url" => \yii\helpers\Url::to(["/translate"])];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-default color-palette-box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $model->message ?></h3>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
                'id' => 'Translation',
                'layout' => 'horizontal',
                'enableClientValidation' => true,
                'errorSummaryCssClass' => 'error-summary alert alert-error'
            ]
        );
        ?>

        <?php echo $form->errorSummary($model); ?>

        <?php foreach (\melkov\tools\models\Lang::find()->all() as $lang) : ?>
            <div class="form-group field-languagesource-translation">
                <label class="control-label col-sm-3"><?= Yii::t("translate", "Message") . " " . $lang->name ?></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="LanguageTranslate[<?= $lang->local ?>]" value="<?= $model->getTranslateForLang($lang->local) ?>" maxlength="255">
                    <div class="help-block help-block-error "></div>
                </div>

            </div>
        <?php endforeach; ?>


        <?= Html::submitButton(
            '<span class="glyphicon glyphicon-check"></span> ' .
            ($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')),
            [
                'id' => 'save-' . $model->formName(),
                'class' => 'btn btn-success'
            ]
        );
        ?>

        <?php ActiveForm::end(); ?>
    </div>
    <!-- /.box-body -->
</div>