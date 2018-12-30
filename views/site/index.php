<?php

/* @var $this yii\web\View */

$this->title = '后台首页';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?php echo Yii::$app->user->identity->name;?>，欢迎您!</h1>

        <p class="lead"></p>        
    </div>
</div>
