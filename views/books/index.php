<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BooksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Library';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="books-index">

    <h1><?= Html::encode('All Books') ?></h1>

    <?= Yii::$app->user->isGuest?'': $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
      //  'filterModel' => $searchModel,
        'columns' => [
       
            ['attribute'=>'book_name',
              'format' => 'Raw', 
             'value' => function ($model) {
                       return Yii::$app->user->isGuest?$model->book_name:(Html::a($model->book_name, ['/books/' . $model->id]));
             }
                
             ],

            'isbn',

           
        ],
    ]); ?>


</div>
