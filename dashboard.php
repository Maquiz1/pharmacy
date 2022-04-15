<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage=null;$pageError=null;$errorMessage=null;$noE=0;$noC=0;$noD=0;
$users = $override->getData('user');
if($user->isLoggedIn()) {
    if(Input::exists('post')){

    }
}else{
    Redirect::to('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title> Dashboard | Pharmacy </title>
    <?php include "head.php";?>
</head>
<body>
<div class="wrapper">

    <?php include 'topbar.php'?>
    <?php include 'menu.php'?>
    <div class="content">


        <div class="breadLine">

            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a> <span class="divider">></span></li>
            </ul>
            <?php include 'pageInfo.php'?>
        </div>

        <div class="workplace">

            <div class="row">

                <div class="col-md-3">

                    <div class="wBlock red clearfix">
                        <div class="dSpace">
                            <h3>Studies</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--130,190,260,230,290,400,340,360,390--></span>
                            <span class="number"><?=$override->getNo('study')?></span>
                        </div>
                    </div>

                </div>

                <div class="col-md-3">

                    <div class="wBlock green clearfix">
                        <div class="dSpace">
                            <h3>Staff</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--5,10,15,20,23,21,25,20,15,10,25,20,10--></span>
                            <span class="number"><?=$override->getNo('user')?></span>
                        </div>
                    </div>

                </div>

                <div class="col-md-3">

                    <div class="wBlock blue clearfix">
                        <div class="dSpace">
                            <h3>Client</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>
                            <span class="number"><?=$override->getNo('clients')?></span>
                        </div>

                    </div>

                </div>

                <div class="col-md-3">
                    <div class="wBlock yellow clearfix">
                        <div class="dSpace">
                            <h3>Free Files</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>
                            <span class="number"><?=$override->getCount('study_files','status',0)?></span>
                        </div>
                    </div>

                </div>

            </div>

            <div class="dr"><span></span></div>
            <div class="row">
                <div class="col-md-12">
                    <?php if($errorMessage){?>
                        <div class="alert alert-danger">
                            <h4>Error!</h4>
                            <?=$errorMessage?>
                        </div>
                    <?php }elseif($pageError){?>
                        <div class="alert alert-danger">
                            <h4>Error!</h4>
                            <?php foreach($pageError as $error){echo $error.' , ';}?>
                        </div>
                    <?php }elseif($successMessage){?>
                        <div class="alert alert-success">
                            <h4>Success!</h4>
                            <?=$successMessage?>
                        </div>
                    <?php }?>
                    <div class="head clearfix">
                        <div class="isw-grid"></div>
                        <h1>Drug Transfer</h1>
                        <ul class="buttons">
                            <li><a href="#" class="isw-download"></a></li>
                            <li><a href="#" class="isw-attachment"></a></li>
                            <li>
                                <a href="#" class="isw-settings"></a>
                                <ul class="dd-list">
                                    <li><a href="#"><span class="isw-plus"></span> New document</a></li>
                                    <li><a href="#"><span class="isw-edit"></span> Edit</a></li>
                                    <li><a href="#"><span class="isw-delete"></span> Delete</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="block-fluid">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table">
                            <thead>
                            <tr>
                                <th width="15%">From</th>
                                <th width="15%">To</th>
                                <th width="15%">Drugs</th>
                                <th width="15%">Quantity</th>
                                <th width="15%">Date</th>
                                <th width="25%">Manage</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="dr"><span></span></div>

            <div class="row">

            </div>

            <div class="dr"><span></span></div>
        </div>

    </div>
</div>
<script>
    <?php if($user->data()->pswd == 0){?>
    $(window).on('load',function(){
        $("#change_password_n").modal({
            backdrop: 'static',
            keyboard: false
        },'show');
    });
    <?php }?>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
</body>

</html>
