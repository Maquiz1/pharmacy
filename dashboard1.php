<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage = null;
$pageError = null;
$errorMessage = null;
$noE = 0;
$noC = 0;
$noD = 0;
$numRec = 10;
$users = $override->getData('user');
$today = date('Y-m-d');
$todayPlus30 = date('Y-m-d', strtotime($today . ' + 30 days'));
// $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 1 days'));
// print_r($todayMinus30);
if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        $validate = new validate();
        if (Input::get('update_stock_guide')) {
            $validate = $validate->check($_POST, array(
                'amount' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $total_quantity = 0;
                if (Input::get('amount') > 0) {
                    $total_quantity = Input::get('quantity_db') + Input::get('amount');
                    // print_r($total_quantity);
                    try {
                        $user->updateRecord('batch_description', array(
                            'quantity' => $total_quantity,
                        ), Input::get('id'));

                        $user->createRecord('batch_description_records', array(
                            'quantity' => Input::get('quantity'),
                            'batch_description_id' => Input::get('batch'),
                            // 'location_id' => Input::get('id'),
                            'assigned' => Input::get('assigned'),
                            'notify_amount' => Input::get('notify_amount'),
                            'status' => Input::get('status'),
                            'staff_id' => $user->data()->id,
                            'use_group' => Input::get('use_group'),
                            'create_on' => date('Y-m-d'),
                            'use_case' => Input::get('use_case'),
                            'added' => Input::get('amount'),
                            // 'study_id' => Input::get('study_id'),

                        ));

                        $successMessage = 'Stock guied Successful Updated';
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else {
                    $errorMessage = 'Amount added Must Be Greater Than 0';
                }
            } else {
                $pageError = $validate->errors();
            }
        }
    }
} else {
    Redirect::to('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title> Dashboard |Kingani - Inventory</title>
    <?php include "head.php"; ?>
</head>

<body>
    <div class="wrapper">

        <?php include 'topbar.php' ?>
        <?php include 'menu.php' ?>
        <div class="content">

            <div class="breadLine">
                <ul class="breadcrumb">
                    <li><a href="#">Dashboard</a> <span class="divider"></span></li>
                </ul>
                <?php include 'pageInfo.php' ?>
            </div>
            <div class="workplace">

                <div class="row">
                    <div class="col-sm-2">
                        <div class="wBlock light-blue clearfix">
                            <a href="info.php?id=17">
                                <div class="dSpace">
                                    <h3>All Devices / Medicines</h3>
                                    <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                                        <!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190-->
                                    </span>
                                    <span class="number">
                                        <span class="number"><?= $override->getCount('batch', 'status', 1) ?></span>
                                </div>
                            </a>
                        </div>
                    </div>              
                    
                    <div class="col-sm-2">
                        <div class="wBlock light-blue clearfix">
                            <a href="info.php?id=17">
                                <div class="dSpace">
                                    <h3>All Devices / Medicines</h3>
                                    <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                                        <!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190-->
                                    </span>
                                    <span class="number">
                                        <span class="number"><?= $override->getCount('batch', 'status', 1) ?></span>
                                </div>
                            </a>
                        </div>
                    </div>         

                </div>

                <div class="dr"><span></span></div>
                <div class="row">
                    <div class="col-md-12">
                        <?php if ($errorMessage) { ?>
                            <div class="alert alert-danger">
                                <h4>Error!</h4>
                                <?= $errorMessage ?>
                            </div>
                        <?php } elseif ($pageError) { ?>
                            <div class="alert alert-danger">
                                <h4>Error!</h4>
                                <?php foreach ($pageError as $error) {
                                    echo $error . ' , ';
                                } ?>
                            </div>
                        <?php } elseif ($successMessage) { ?>
                            <div class="alert alert-success">
                                <h4>Success!</h4>
                                <?= $successMessage ?>
                            </div>
                        <?php } ?>
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Inventory Stock Description</h1>
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
                    </div>
                </div>
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="dashboard.php?page=<?php if (($_GET['page'] - 1) > 0) {
                                                        echo $_GET['page'] - 1;
                                                    } else {
                                                        echo 1;
                                                    } ?>" class="btn btn-default">
                            < </a>
                                <?php for ($i = 1; $i <= $pages; $i++) { ?>
                                    <a href="dashboard.php?page=<?= $_GET['id'] ?>&page=<?= $i ?>" class="btn btn-default <?php if ($i == $_GET['page']) {
                                                                                                                                echo 'active';
                                                                                                                            } ?>"><?= $i ?></a>
                                <?php } ?>
                                <a href="dashboard.php?page=<?php if (($_GET['page'] + 1) <= $pages) {
                                                                echo $_GET['page'] + 1;
                                                            } else {
                                                                echo $i - 1;
                                                            } ?>" class="btn btn-default"> > </a>
                    </div>
                </div>
                <div class="row">

                </div>

                <div class="dr"><span></span></div>
            </div>

        </div>
    </div>
    <script>
        <?php if ($user->data()->pswd == 0) { ?>
            $(window).on('load', function() {
                $("#change_password_n").modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');
            });
        <?php } ?>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>