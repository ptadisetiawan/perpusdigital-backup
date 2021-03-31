<?php

/* @var $this \yii\web\View */
/* @var $content string */


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use opac\assets_b\AppAsset;
use common\widgets\Alert;
use kartik\datecontrol\DateControl;
use kartik\datetime\DateTimePicker;
use kartik\widgets\DatePicker;
use common\models\Collections;
use common\models\Refferenceitems;
use common\models\Worksheets;
use common\components\OpacHelpers;
use common\models\LocationLibrary;

$indexer = (Yii::$app->config->get('OpacIndexer') == '1') ? 'search/index' : 'pencarian-sederhana';
Url::remember();
AppAsset::register($this);
$homeUrl=Yii::$app->homeUrl;
$base=Yii::$app->controller->route;
$dateNow = new \DateTime("now");
$bahasa = Refferenceitems::find()
    ->where(['Refference_id' => 5])
    ->all();
$bentukKarya = Refferenceitems::find()
    ->where(['Refference_id' => 17])
    ->all();
$targetPembaca = Refferenceitems::find()
    ->where(['Refference_id' => 2])
    ->all();
$Worksheets= OpacHelpers::sortWorksheets(Worksheets::find()->asArray()->all());

$namaruang = Yii::$app->request->cookies->getValue('location_opac_name')['Name'];
$namalokperpus = Yii::$app->request->cookies->getValue('location_detail_opac')['Name'];
$namaperpus = ($namalokperpus && $namaruang) ? $namalokperpus." - ".$namaruang :Yii::$app->config->get('NamaPerpustakaan');
$IDLibrary = Yii::$app->request->cookies->getValue('location_detail')['ID'];
$alamat = Yii::$app->request->cookies->getValue('location_detail_opac')['Address'];

if(!Yii::$app->user->isGuest){
    $noAnggota = \Yii::$app->user->identity->NoAnggota;
    $this->title = 'Perpustakaan Provinsi Bali';
    $booking = Collections::find()
                    ->select([
                        'collections.BookingExpiredDate',
                        'catalogs.Title',
                    ])
                    ->leftJoin('catalogs', '`catalogs`.`ID` = `collections`.`Catalog_id`')
                    ->andWhere('BookingMemberID ="' . $noAnggota.'"')
                    ->andWhere('BookingExpiredDate >  "' . $dateNow->format("Y-m-d H:i:s") . '"')
                    ->all();

}

else {
   
    $booking=null;  
}
    if(sizeof($booking)==0){
        $this->registerJS('
        $(document).ready(
            function() {
                $(\'a.bookmarkShow\').hide();
            }
        );
        ');



    }else{
        $this->registerJS('

        $(document).ready(
            function() {
                $(\'a.bookmarkShow\').show();
                $(\'a.bookmarkShow\').text(\'Keranjang('.sizeof($booking).')\');
            }
        );
        ');

    }

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>Perpustakaan Provinsi Bali</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" type='image/x-icon' href="<?=Yii::$app->urlManager->createUrl('../uploaded_files/aplikasi/favicon.png');?>">

    <?php $this->head() ?>
</head>


<body class="skin-blue layout-top-nav">
    <div class="wrapper" style="background-color: #FFF;">
        <!-- Header web -->
        <header style="color:#FFFFFF">
            <nav style="background-color:#B20909; color:#FFFFFF" class="navbar navbar-default">
                <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar2">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <a style="text-decoration:none; color:#FFFFFF;" class="navbar-brand" href="#">
                    <h3>Perpustakaan Digital</h3>
                    </a>
                </div>
                <div id="navbar2" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a style="text-decoration:none; color:#FFFFFF" class='mr-2' href="<?php echo $homeUrl."bookmark"; ?>"><?= Yii::t('app', 'Tampung')?></a></li>
                        <?php 
                            if (Yii::$app->user->isGuest) {
                            echo"
                                <li><a style='text-decoration:none; color:#FFFFFF' class='mr-2' href=\"javascript:void(0)\"  onclick='tampilLogin()'>Login</a></li>
                                <li><a style='text-decoration:none; color:#FFFFFF' class='mr-2' href=\"../".Url::to('pendaftaran')."\">" .Yii::t('app', 'Registrasi')."</a></li>
                            ";
                            } else {
                            echo"
                            <li><a style='text-decoration:none; color:#FFFFFF' class='mr-2' href=\"".$homeUrl."profile  \">Profile</a></li>
                            <li><a style='text-decoration:none; color:#FFFFFF' class='mr-2' href=\"".$homeUrl."site/logout  \">Logout (" . $noAnggota.")</a></li>
                            ";

                            $_SESSION['__NoAnggota']= $noAnggota;
                            }
                            ?>
                    
                    </ul>
                </div>
                <!--/.nav-collapse -->
                </div>
            </nav>
</header>

<section style="margin-top:10px; margin-bottom:10px" class="container">
    <div class="row">
    <div class="col-md-12">
    <form action="<?php echo $homeUrl; ?><?=$indexer?>" method="GET">
    <input type="hidden" name="action" value="pencarianSederhana"/>
        <div class="form-group mx-sm-3 mb-2">
            <input type="text" class="form-control" name="katakunci" id="KataKunci" placeholder='<?= Yii::t('app', 'Kata Kunci')?>' size="25" >
        </div>
        <div class="form-group mx-sm-3 mb-2">
            <select  class="form-control" name="ruas">
                        <option value="Judul"><?= Yii::t('app', 'Judul')?></option>
                        <option value="Pengarang"><?= Yii::t('app', 'Pengarang')?></option>
                        <option value="Penerbit"><?= Yii::t('app', 'Penerbitan')?></option>
                        <option value="Subyek"><?= Yii::t('app', 'Subyek')?></option>
                        <option value="Nomor Panggil"><?= Yii::t('app', 'Nomor Panggil')?></option>
                        <option value="ISBN">ISBN</option>
                        <option value="ISSN">ISSN</option>                    
                        <option value="ISMN">ISMN</option>
                    <option value="Semua Ruas"><?= Yii::t('app', 'Sembarang')?></option> 
            </select>
        </div>
        <div class="form-group mx-sm-3 mb-2">
             <select class="form-control" name="bahan" onChange="getData(this);" >
                    <?php
                    for ($i=0; $i <sizeof($Worksheets) ; $i++) { 
                    echo"<option value ='".$Worksheets[$i]['ID']."'>".$Worksheets[$i]['Name']."</option> ";
                    }
                    ?>

                    <option value="Semua Jenis Bahan" selected><?= Yii::t('app', 'Semua Bahan')?></option>

                </select>
        </div>
        <input class="btn btn-success mx-sm-3 pull-right" type="submit" value=<?= Yii::t('app', 'Cari')?>  >
        </form>
    </div>
    
</div>
</section>


<!-- Full Width Column -->
<div id="alert"></div>
<div id="usulan"></div>
<?php $this->beginBody() ?>
<div class="content-wrapper" style="min-height: 507px;">
<div class="container">
<!-- Content Header (Page header) -->
<div class="row mt-2">
    <div class="col-sm-12">
        <a class="btn btn-danger mb-2" href="<?php echo $homeUrl."pencarian-lanjut"; ?>"><?= Yii::t('app', 'Pencarian lanjut')?> </a>  
        <a class="btn btn-danger mb-2" href="<?php echo $homeUrl."riwayat-pencarian"; ?>"><?= Yii::t('app', 'Riwayat Pencarian')?> </a>
        <a class="btn btn-info mb-2"  href="javascript:void(0)"  onclick='showBantuan()'><?= Yii::t('app', 'Bantuan')?></a> 
        </div>
</div>
<!-- Main content -->
<section class="content">
    <!--   <?= Alert::widget() ?>  -->
    <div class="box box-default">
        <?= $content ?>
    </div>
</section><!-- /.content -->
</div><!-- /.container -->
</div><!-- /.content-wrapper -->

<?php $this->endBody() ?>
    </div><!-- ./wrapper -->

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
    <!-- <script src="js/shards.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/shards-ui@3.0.0/dist/js/shards.min.js" integrity="sha256-PCFtnjaRohL1L6wEpWGaVacPmSQOoqYC4YDDRKC6RUc=" crossorigin="anonymous"></script> -->
</body>
</html>
<?php $this->endPage() ?>
<?php $today = getdate(); ?>
<?php $lang = Yii::$app->config->get('language'); ?>
<script>

    var d = new Date(Date(<?php echo $today['year'].",".$today['mon'].",".$today['mday'].",".$today['hours'].",".$today['minutes'].",".$today['seconds']; ?>));
    var weekday=new Array(7);
    var weekday=["Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu"];
    var weekday_en=new Array(7);
    var weekday_en=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    var monthname=new Array(12);
    var monthname=["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
    var monthname_en=new Array(12);
    var monthname_en=["","January","February","March","April","May","June","July","August","September","October","November","December"];
    var dayname=weekday[<?= date('w')?>];
    var day=<?= date('d')?>;
    var month=monthname[<?= date('m')?>];
    var year=<?= date('Y')?>;
    if ('<?= $lang ?>'=='en') {
            var dayname=weekday_en[<?= date('w')?>];
            var month=monthname_en[<?= date('m')?>];
        }
    setInterval(function() {
        d.setSeconds(d.getSeconds() + 1);
        $('#clocktime').text((dayname+", "+day+" "+month+" "+year+", "+d.getHours() +':' + d.getMinutes() + ':' + d.getSeconds() ));
    }, 1000);

  function getData(dropdown) {
    var value = dropdown.options[dropdown.selectedIndex].value;
    if(document.getElementById("dariTGL").style.display == "inline"){

    }
    document.getElementById("dariTGL").style.display = "none"
    document.getElementById("sampaiTGL").style.display = "none"
    if (value == '4'){   
      document.getElementById("dariTGL").style.display = "inline"
      document.getElementById("sampaiTGL").style.display = "inline"
    }
  }
    function tampilBooking() {
        $.ajax({
            type: "POST",
            cache: false,
            url: "booking?action=showBookingDetail",
            success: function (response) {
                $("#modalBooking").modal('show');
                $("#BookingShow").html(response);
            }
        });
    }
    function tampilUsulan() {
        $.ajax({
            type: "POST",
            cache: false,
            url: "usulan-koleksi?action=showUsulan",
            success: function (response) {
                $("#modalUsulan").modal('show');
                $("#UsulanShow").html(response);
            }
        });
    }

      function tampilLogin() {
        $.ajax({
            type: "POST",
            cache: false,
            url: "site/login",
            success: function (response) {
                $("#modalLogin").modal('show');
                $("#LoginShow").html(response);
            }
        });

    }

    function cancelBooking(id) {
        $.ajax({
            type: "POST",
            cache: false,
            url: "booking?action=cancelBooking&colID="+id,
            success: function (response) {
                $("#modalBooking").modal('hide');
                $("#alert").html(response);
            }
        });
    }

    function loginAnggota() {
        $.ajax({
            type: "POST",
            cache: false,
            url: "site/loginanggota",
            data : $("#login-anggota").serialize(), 
            success: function (response) {
                console.log(response);
                if (response) {
                  $("#error-login-opac-123-321").html(response);
                }
            }

        });
    }

    function showBantuan() {
        $("#modalBantuan").modal('show');
    }


  var MaxInputs = 50;
  var Dynamic = $("#Dynamic");
  var i = $("#Dynamic div").size() + 1;

  $("#AddInpElem").click( function () {
    if (i <= MaxInputs) {
      $("<div> <div class=\"row\">  <div class=\"col-sm-1\"></div>  <div class=\"col-sm-1\"><div class=\"form-group\"><select name=\"danAtau[]\" class=\"form-control\"><option value=\"and\">dan</option>  <option value=\"or\">atau</option>   <option value=\"selain\">selain</option>      </select></div></div><div class=\"col-sm-3\"><div class=\"form-group\">  <input name=\"katakunci[]\"  type=\"text\" class=\"form-control login-field\"  /></div></div><div class=\"col-sm-2\"><div class=\"form-group\"> <select name=\"jenis[]\" class=\"form-control\"><option>di dalam</option><option >di awal</option><option >di akhir</option> </select></div></div><div class=\"col-sm-3\"><div class=\"form-group\"><select   name=\"tag[]\" class=\"form-control\"> <option value=\"Judul\">Judul</option> <option value=\"Pengarang\">Pengarang</option> <option value=\"Penerbitan\">Penerbitan</option> <option value=\"Edisi\">Edisi</option> <option value=\"Deskripsi Fisik\">Deskripsi Fisik</option> <option value=\"Jenis Konten\">Jenis Konten</option> <option value=\"Jenis Media\">Jenis Media</option> <option value=\"Media Carrier\">Media Carrier</option> <option value=\"Subyek\">Subyek</option> <option value=\"ISBN\">ISBN</option> <option value=\"ISSN\">ISSN</option> <option value=\"ISMN\">ISMN</option> <option value=\"Nomor Panggil\">ISBN</option> <option value=\"Sembarang Ruas\">Sembarang Ruas </option>       </select> </div></div><a href=\"javascript:void(0)\" class=\"RemInpElem\"><span class=\"glyphicon glyphicon-minus-sign\"></span></a> &nbsp; </div> </div>").appendTo(Dynamic);
      i++;
    }
    return false;
  });


$("body").on("click",".RemInpElem", function(){
  if (i > 2) {
    $(this).parent("div").remove();
    i--;
  }
  return false;
}) 


</script>

<div class="modal fade" id="modalBooking" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Booking Detail</h4>
            </div>
            <div class="modal-body">
                <p id="demo"></p>
                <div id="BookingShow">
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" id="PrintButton" class="btn btn-primary"> <span class="glyphicon glyphicon-print"></span>  Print  </a>&nbsp;&nbsp;
                <button type="button" class="btn btn-default" data-dismiss="modal">&nbsp;<?= Yii::t('app', 'Tutup')?>&nbsp;</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUsulan" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Usulan Koleksi')?></h4>
            </div>
            <div class="modal-body">
                <p id="demo"></p>
                <div id="UsulanShow">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Tutup')?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalLogin" role="dialog">
    <div class="modal-dialog" >       
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Login Anggota')?></h4>
                
            </div>
            <div class="modal-body ">
                <p id="demo"></p>
                <div id="LoginShow">




                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Tutup')?></button>
            </div>
        </div>
    </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="modalBantuan" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header justify-content-between">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Bantuan')?></h4>
            </div>
            <div class="modal-body">

                <ul>
                <li> <?= Yii::t('app', 'Pencarian sederhana adalah pencarian koleksi dengan menggunakan hanya satu kriteria pencarian saja.')?> </li>
                <li> <?= Yii::t('app', 'Ketikkan kata kunci pencarian, misalnya : " Sosial kemasyarakatan "')?> </li>
                <li> <?= Yii::t('app', 'Pilih ruas yang dicari, misalnya : " Judul " .')?> </li>
                <li> <?= Yii::t('app', 'Pilih jenis koleksi misalnya  " Monograf(buku) ", atau biarkan pada  pilihan " Semua Jenis Bahan "')?> </li>
                <li> <?= Yii::t('app', 'Klik tombol "Cari" atau tekan tombol Enter pada keyboard')?> </li>
            </ul>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
            </div>

        </div>
        </div>
