<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use yii\bootstrap4\Html;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        .header ul {
            display:block;
            padding:0;
        }
        li{
            display:block;
            float:left;
            padding: 8px
        }
        .header li a.active{
            color: white;
        }
        .clearfix::before,
        .clearfix::after {
            content: " ";
            display: table;
        }

        .clearfix::after {
            clear: both;
        }

        .clearfix {
            *zoom: 1;
            /* Für den IE6 und IE7 */

        }
    </style>
</head>
<body >
<?php $this->beginBody() ?>

<div class="header">
        <div class="pure-menu pure-menu-horizontal">
            <ul class="pure-menu-list clearfix">
                <li><a href="/books" class="pure-menu-link ">All Books</a></li>
                <?=(Yii::$app->user->isGuest)?'':'<li><a href="/my-rentals" class="pure-menu-link">My Rentals</a></li>'
                                    
                                    ?>
                                    <li><?=(Yii::$app->user->isGuest)?'<a href="/login" class="pure-menu-link">Login</a>':'<a href="/logout" class="pure-menu-link">Logout</a>'
                                    
                                    ?></li>
                            </ul>
        </div>
    </div>
    <div class="container">
        
   
        <?= $content ?>
        </div>




<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
