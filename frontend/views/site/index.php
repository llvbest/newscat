<?php
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <p><a class="btn btn-lg btn-success" href="<?=Url::to(['/news']);?>">News catalog for rubric</a></p>
    </div>

</div>
