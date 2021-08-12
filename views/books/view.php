<?php

use yii\helpers\Html;
use app\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\Books */

$this->title = 'Library';

\yii\web\YiiAsset::register($this);
?>
<div class="books-view">

    <h1><?= Html::encode($model->book_name) ?></h1>

   <pre><?=$model->description?></pre>


   <?=Alert::widget()?>

   <?php if($model->isAvailable) { ?>
        <form action="/rental/rent?id=<?=$model->id?>" method="POST">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />

            <button type="submit">Rent</button>
        </form>
    <?php } else { ?>
        <h4>Sorry, this book is currently rented out to another user.</h4>
    <?php } ?>
    
</div>
