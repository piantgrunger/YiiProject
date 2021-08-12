<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BooksSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="books-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

   <?= $form->field($model, 'q')->textInput(['placeholder'=>"search query",'name'=>'q','style'=>"width:100%"])->label(false) ?>

 

    <div class="form-group">
        <?= Html::submitButton('Search', ['style'=>"width:100%",'class' => 'btn btn-primary btn-block']) ?>
      
    </div>

    <?php ActiveForm::end(); ?>

</div>
