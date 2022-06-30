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
                                        <span class="number"><?= $override->getCount1('batch', 'status', 1) ?></span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-2">

                        <div class="wBlock blue clearfix">
                            <a href="data.php?id=2">
                                <div class="dSpace">
                                    <h3>30 Days Before Expiration Date</h3>
                                    <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                                        <!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190-->
                                    </span>
                                    <span class="number">
                                        <span class="number"><?= $override->getCount2('batch', 'expire_date', $todayPlus30) ?></span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="wBlock green clearfix">
                            <a href="data.php?id=3">
                                <div class="dSpace">
                                    <h3>30 Days Before Ckecks</h3>
                                    <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                                        <!--5,10,15,20,23,21,25,20,15,10,25,20,10-->
                                    </span>
                                    <span class="number"><?= $override->getCount2('batch', 'expire_date', $todayPlus30) ?></span>
                                </div>
                            </a>
                        </div>

                    </div>

                    <div class="col-sm-2">
                        <a href="data.php?id=4">
                            <div class="wBlock gray clearfix">
                                <div class="dSpace">
                                    <h3>Unchecked Devices</h3>
                                    <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                                        <!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190-->
                                    </span>
                                    <span class="number"><?= $override->getCount1('batch', 'expire_date', $today) ?></span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-2">
                        <a href="data.php?id=6">
                            <div class="wBlock yellow clearfix">
                                <div class="dSpace">
                                    <h3>Running Low </h3>
                                    <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                                        <!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190-->
                                    </span>
                                    <span class="number"><?= $override->getCount3('batch_description') ?></span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-2">
                        <div class="wBlock red clearfix">
                            <a href="data.php?id=1">
                                <div class="dSpace">
                                    <h3>Expired Medicine</h3>
                                    <span class="mChartBar" sparkType="bar" sparkBarColor="white">
                                        <!--130,190,260,230,290,400,340,360,390-->
                                    </span>
                                    <span class="number"><?= $override->getCount1('batch', 'expire_date', $today) ?></span>
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
                        <div class="block-fluid">
                            <!-- <thead>
                                    <tr>
                                        <th width="15%">From</th>
                                        <th width="15%">Drugs</th>
                                        <th width="15%">Quantity</th>
                                        <th width="15%">Date</th>
                                        <th width="25%">Manage</th>
                                    </tr>
                                </thead> -->

                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                    <tr>
                                        <th width="10%">Generic Name</th>
                                        <th width="5%"> Group</th>
                                        <th width="5%"> Use Case</th>
                                        <th width="5%">Current Quantity</th>
                                        <!-- <th width="5%">Current Used</th> -->
                                        <th width="5%">Re-stock Level</th>
                                        <th width="5%"> ICU</th>
                                        <th width="5%"> EmKit</th>
                                        <th width="5%"> EmBuffer</th>
                                        <th width="5%"> AmbKit</th>
                                        <th width="5%"> CTM Room</th>
                                        <th width="5%"> Exam Room</th>
                                        <th width="5%"> Pharmacy</th>
                                        <th width="5%">Status</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $amnt = 0;
                                    $pagNum = $override->getCount('batch', 'status', 1);
                                    $pages = ceil($pagNum / $numRec);
                                    if (!$_GET['page'] || $_GET['page'] == 1) {
                                        $page = 0;
                                    } else {
                                        $page = ($_GET['page'] * $numRec) - $numRec;
                                    }

                                    foreach ($override->getData('batch_description') as $bDiscription) {
                                        $useGroup = $override->get('use_group', 'id', $bDiscription['use_group'])[0]['name'];
                                        $useCase = $override->get('use_case', 'id', $bDiscription['use_case'])[0]['name'];
                                        $icu = ($override->getNews('batch_guide_records', 'batch_description_id', $bDiscription['id'], 'location_id', 1)[0]['quantity']);
                                        $EmKit = $override->getNews('batch_guide_records', 'batch_description_id', $bDiscription['id'], 'location_id', 2)[0]['quantity'];
                                        $EmBuffer = $override->getNews('batch_guide_records', 'batch_description_id', $bDiscription['id'], 'location_id', 3)[0]['quantity'];
                                        $AmKit = $override->getNews('batch_guide_records', 'batch_description_id', $bDiscription['id'], 'location_id', 4)[0]['quantity'];
                                        $CTM = $override->getNews('batch_guide_records', 'batch_description_id', $bDiscription['id'], 'location_id', 5)[0]['quantity'];
                                        $Exam = $override->getNews('batch_guide_records', 'batch_description_id', $bDiscription['id'], 'location_id', 6)[0]['quantity'];
                                        $Pharmacy = $override->getNews('batch_guide_records', 'batch_description_id', $bDiscription['id'], 'location_id', 7)[0]['quantity'];
                                        $sumLoctn = $override->getSumD1('batch_guide_records', 'quantity', 'batch_description_id', $bDiscription['id'])[0]['SUM(quantity)'];
                                        // $study = $override->get('study', 'id', $bDiscription['use_case'])[0]['name'];
                                    ?>
                                        <tr>
                                            <td><?= $bDiscription['name'] ?></td>
                                            <td><?= $useGroup ?></td>
                                            <td><?= $useCase ?></td>
                                            <td><?= $bDiscription['quantity'] ?></td>
                                            <td><?= $bDiscription['notify_amount'] ?></td>
                                            <td><?php if ($icu) {
                                                ?>
                                                    <a href="#" role="button" class="btn btn-info"><?= $icu; ?></a>
                                                <?php
                                                } else {
                                                    echo 'N/A';
                                                } ?>
                                            </td>
                                            <td><?php if ($EmKit) {
                                                ?>
                                                    <a href="#" role="button" class="btn btn-info"><?= $EmKit; ?></a>
                                                <?php
                                                } else {
                                                    echo 'N/A';
                                                } ?>
                                            </td>
                                            <td><?php if ($EmBuffer) {
                                                ?>
                                                    <a href="#" role="button" class="btn btn-info"><?= $EmBuffer; ?></a>
                                                <?php
                                                } else {
                                                    echo 'N/A';
                                                } ?>
                                            </td>
                                            <td><?php if ($AmKit) {
                                                ?>
                                                    <a href="#" role="button" class="btn btn-info"><?= $AmKit; ?></a>
                                                <?php
                                                } else {
                                                    echo 'N/A';
                                                } ?>
                                            </td>
                                            <td><?php if ($CTM) {
                                                ?>
                                                    <a href="#" role="button" class="btn btn-info"><?= $CTM; ?></a>
                                                <?php
                                                } else {
                                                    echo 'N/A';
                                                } ?>
                                            </td>
                                            <td><?php if ($Exam) {
                                                ?>
                                                    <a href="#" role="button" class="btn btn-info"><?= $Exam; ?></a>
                                                <?php
                                                } else {
                                                    echo 'N/A';
                                                } ?>
                                            </td>
                                            <td><?php if ($Pharmacy) { ?>
                                                    <a href="#" role="button" class="btn btn-info"><?= $Pharmacy; ?></a>
                                                <?php
                                                } else {
                                                    echo 'N/A';
                                                } ?>
                                            </td>
                                            <td>
                                                <?php if ($bDiscription['quantity'] <= $bDiscription['notify_amount'] && $bDiscription['quantity'] > 0) { ?>
                                                    <a href="#" role="button" class="btn btn-warning btn-sm">Running Low</a>
                                                <?php } elseif ($bDiscription['quantity'] == 0) { ?>
                                                    <a href="#" role="button" class="btn btn-danger">Out of Stock</a>
                                                <?php } else { ?>
                                                    <a href="#" role="button" class="btn btn-success">Sufficient</a>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="data.php?id=7&did=<?= $bDiscription['id'] ?>" class="btn btn-info">View</a>
                                                <a href="#edit_stock_guide_id<?= $bDiscription['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Update</a>
                                                <a href="#delete<?= $bDiscription['id'] ?>" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="edit_stock_guide_id<?= $bDiscription['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="post">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                            <h4>Update Stock Info</h4>
                                                        </div>
                                                        <div class="modal-body modal-body-np">
                                                            <div class="row">
                                                                <div class="block-fluid">
                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Generic Name</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?= $override->get('batch', 'id', $bDiscription['batch_id'])[0]['name'] ?>" type="text" id="name" disabled />
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Brand Name:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?= $bDiscription['name'] ?>" class="validate[required]" type="text" name="name" id="name" disabled />
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Current Quantity:</div>
                                                                        <div class="col-md-9">
                                                                            <input value="<?= $bDiscription['quantity'] ?>" class="validate[required]" type="number" name="quantity" id="name" disabled />
                                                                        </div>
                                                                    </div>

                                                                    <div class="row-form clearfix">
                                                                        <div class="col-md-3">Quantity to Add:</div>
                                                                        <div class="col-md-9">
                                                                            <input value=" " class="validate[required]" type="number" name="amount" id="amount" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dr"><span></span></div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="batch" value="<?= $bDiscription['batch_id'] ?>">
                                                            <input type="hidden" name="id" value="<?= $bDiscription['id'] ?>">
                                                            <input type="hidden" name="quantity" value="<?= $bDiscription['quantity'] ?>">
                                                            <input type="hidden" name="assigned" value="<?= $bDiscription['assigned'] ?>">
                                                            <input type="hidden" name="notify_amount" value="<?= $bDiscription['notify_amount'] ?>">	
                                                            <input type="hidden" name="status" value="<?= $bDiscription['maintainance_status'] ?>">                                                          
                                                            <input type="hidden" name="use_group" value="<?= $bDiscription['use_group'] ?>">
                                                            <input type="hidden" name="use_case" value="<?= $bDiscription['use_case'] ?>">
                                                            <input type="hidden" name="quantity_db" value="<?= $bDiscription['quantity'] ?>">	
                                                            <input type="submit" name="update_stock_guide" value="Save updates" class="btn btn-warning">
                                                            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="delete<?= $batchDesc['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="post">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                            <h4>Delete Product</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <strong style="font-weight: bold;color: red">
                                                                <p>Are you sure you want to delete this Product</p>
                                                            </strong>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" value="<?= $batchDesc['id'] ?>">
                                                            <input type="submit" name="delete_file" value="Delete" class="btn btn-danger">
                                                            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </tbody>
                            </table>
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