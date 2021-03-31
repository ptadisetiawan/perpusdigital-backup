<?php

/* @var $this \yii\web\View */
/* @var $content string */

use guestbook\assetsb\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\PendaftaranAsset;

use common\models\LocationLibrary;

use common\widgets\Alert;
use yii\helpers\Url;

$asset = AppAsset::register($this);
$baseurl = $asset->baseUrl;
$subtitle="";

$IDLibrary = Yii::$app->request->cookies->getValue('location_detail')['ID'];
$alamat = ($IDLibrary) ? LocationLibrary::find()->select(['Address'])->where(['ID'=>$IDLibrary])->asArray()->one(): array();


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - Perpusnas RI</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

     <link rel="shortcut icon" type='image/x-icon' href="<?=Yii::$app->urlManager->createUrl('../uploaded_files/aplikasi/favicon.png'); ?>">

    <?php $this->head() ?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>


<body onload="startTime()" class="skin-blue layout-top-nav">
    <div class="wrapper">
        <!-- Header web -->
        <header class="main-header">
            <!-- navbar -->
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <div class="title">
                            <div class="image"><img src="<?= Yii::$app->urlManager->createUrl('../uploaded_files/aplikasi/logo_perpusnas_2015.png') ?>" class="img-logo" height="65" width="70"></div>
                            <div class="text">
                                <h3 style="margin-top: 20px;"><?= Html::encode($this->title) ?></h3>
                                <div class="clear"></div>
                                <div class="time"><?= Yii::$app->config->get('NamaPerpustakaan') ?></div><br/>
                                <div class="clear"></div>
                                <div class="timeddr" style="margin-bottom: 10px"><?= $alamat['Address'] ?></div>
                                <div class="clear"></div>
                            </div>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                                <i class="fa fa-bars"></i>
                            </button>
                            <div class="clear">
                            </div>
                        </div>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-right" id="navbar-collapse">
                        <span class="pull-right" style="color:#fff; margin-right:5px; margin-top:5px" id="clocktime" ></span><br>
                        <!-- <ul class="nav navbar-nav">
                            <li><a href="#">Keranjang (0)</a></li>
                            <li><a href="#">Login</a></li>
                            <li>
                                <a href="#">Registrasi </a>

                            </li>
                        </ul> -->

                    </div><!-- /.navbar-collapse -->
                    <!-- Navbar Right Menu -->
                    <!-- /.navbar-custom-menu -->
                </div><!-- /.container-fluid -->
            </nav>
            <!-- /navbar -->
        </header>

        <section class="content-search">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <center>
                            <strong>
                                <?= $this->params['subTitle']; ?>
                            </strong>
                        </center>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12" style="padding:0 -15px">
                        <!-- Custom Tabs -->
                        <!-- nav-tabs-custom -->
                    </div><!-- /.col -->
                </div>

            </div>
        </section>
        <!-- Full Width Column -->

        <?php $this->beginBody() ?>
        <div class="content-wrapper" style="min-height: 507px;">
            <div class="container">
                <!-- Content Header (Page header) -->

                <!-- Main content -->
                <section class="content">
                    <?= Alert::widget() ?>
                    <div class="box box-default">
                        <?= $content ?>
                    </div>
                </section><!-- /.content -->
            </div><!-- /.container -->
        </div><!-- /.content-wrapper -->

        <?php $this->endBody() ?>
    </div><!-- ./wrapper -->
</body>
</html>
<?php $this->endPage() ?>

<script>
    //alert(Date());
	function startTime()
	{   var today=new Date();
		var weekday=new Array(7);
		var weekday=["Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu"];
		var monthname=new Array(12);
		var monthname=["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
		var dayname=weekday[today.getDay()];
		var day=today.getDate();
		var month=monthname[today.getMonth()];
		var year=today.getFullYear();
		var h=today.getHours();
		var m=today.getMinutes();
		var s=today.getSeconds();
		h=checkTime(h);
		m=checkTime(m);
		s=checkTime(s);
		document.getElementById('clocktime').innerHTML=dayname+", "+day+" "+month+" "+year+", "+h+":"+m+":"+s;
		t=setTimeout(function(){startTime()},500);
	}
	// function checkTime to add a zero in front of numbers < 10
	function checkTime(i)
	{   if(i<10){i="0"+i;}
		return i;
	}
</script>
