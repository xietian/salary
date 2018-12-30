<?php 
use yii\helpers\Html;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => '销售人员管理', 'url' => ['/users/index']],
                    ['label' => '渠道管理', 'url' => ['/channel/index']],
                    ['label' => '销售订单', 'url' => ['/channel-sales-order/index']],
                    ['label' => '出货记录', 'url' => ['/channel-outbound-order/index']],
                    ['label' => '微信菜单', 'url' => ['/wxmenu/index']],
                    ['label' => '客户反馈', 'url' => ['/feedback/index']],
                    ['label' => 'FAQ', 'url' => ['/faq/index']],
                    Yii::$app->user->isGuest ? (
                    ['label' => '登录', 'url' => ['/site/login']]
                    ) : (
                            ['label' => '退出', 'url' => ['/site/logout'],'options'=>['class' => 'navbar-form']]
                        // '<li>'
                        // . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                        // . Html::submitButton(
                        //     '退出 (' . Yii::$app->user->identity->name . ')',
                        //     ['class' => 'btn btn-link']
                        // )
                        // . Html::endForm()
                        // . '</li>'
                    )
                    //['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],                   
                ],
            ]
        ) ?>

    </section>

</aside>
