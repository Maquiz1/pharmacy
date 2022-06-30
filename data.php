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


if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        $validate = new validate();
        if (Input::get('edit_position')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('position', array(
                        'name' => Input::get('name'),
                    ), Input::get('id'));
                    $successMessage = 'Position Successful Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('edit_staff')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'firstname' => array(
                    'required' => true,
                ),
                'lastname' => array(
                    'required' => true,
                ),
                'position' => array(
                    'required' => true,
                ),
                'phone_number' => array(
                    'required' => true,
                ),
                'email_address' => array(),
            ));
            if ($validate->passed()) {
                $salt = $random->get_rand_alphanumeric(32);
                $password = '12345678';
                switch (Input::get('position')) {
                    case 1:
                        $accessLevel = 1;
                        break;
                    case 2:
                        $accessLevel = 2;
                        break;
                    case 3:
                        $accessLevel = 3;
                        break;
                }
                try {
                    //                    $staffSites=$override->get('staff_sites', 'staff_id', Input::get('id'));
                    //                    $staffStudy=$override->get('staff_study', 'staff_id', Input::get('id'));
                    $user->updateRecord('user', array(
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'position' => Input::get('position'),
                        'phone_number' => Input::get('phone_number'),
                        'email_address' => Input::get('email_address'),
                        'accessLevel' => $accessLevel,
                    ), Input::get('id'));

                    //                    if($staffSites){
                    //                        $currentSite=array();
                    //                        foreach ($staffSites as $staffSite){
                    //                            array_push($currentSite, $staffSite['site_id']);
                    ////                            $user->deleteRecord('staff_sites','id',$staffSite['id']);
                    //                        }
                    //                        $changeSites = array_diff($currentSite,Input::get('sites'));
                    //                        if($changeSites){
                    //                            foreach ($changeSites as $changeSite){
                    //                                if(in_array($changeSite, $currentSite)){
                    ////                                    $user->deleteRecord('staff_sites','id',$changeSite);
                    //                                    print_r($changeSite);echo ' To delete, ';
                    //                                }else{
                    ////                                    $user->createRecord('staff_sites', array(
                    ////                                        'staff_id' => Input::get('id'),
                    ////                                        'site_id' => $changeSite,
                    ////                                    ));
                    //                                    print_r('New Site ,');
                    //                                }
                    //                            }
                    //                        }
                    //                    }
                    //                    foreach (Input::get('sites') as $site){
                    //                        $user->createRecord('staff_sites', array(
                    //                            'staff_id' => Input::get('id'),
                    //                            'site_id' => $site,
                    //                        ));
                    //                    }

                    $successMessage = 'Account Updated Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('reset_pass')) {
            $salt = $random->get_rand_alphanumeric(32);
            $password = '12345678';
            $user->updateRecord('user', array(
                'password' => Hash::make($password, $salt),
                'salt' => $salt,
            ), Input::get('id'));
            $successMessage = 'Password Reset Successful';
        } elseif (Input::get('delete_staff')) {
            $user->updateRecord('user', array(
                'status' => 0,
            ), Input::get('id'));
            $successMessage = 'User Deleted Successful';
        } elseif (Input::get('edit_batch')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
                'batch_no' => array(
                    'required' => true,
                ),
                'study' => array(
                    'required' => true,
                ),
                'amount' => array(
                    'required' => true,
                ),
                'manufactured_date' => array(
                    'required' => true,
                ),
                'expire_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('batch', array(
                        'name' => Input::get('name'),
                        'study_id' => Input::get('study'),
                        'batch_no' => Input::get('batch_no'),
                        'amount' => Input::get('amount'),
                        'notify_amount' => Input::get('notify_amount'),
                        'manufacturer' => Input::get('manufacturer'),
                        'manufactured_date' => Input::get('manufactured_date'),
                        'expire_date' => Input::get('expire_date'),
                        'create_on' => date('Y-m-d'),
                        'details' => Input::get('details'),
                        'status' => 1,
                        'staff_id' => $user->data()->id
                    ), Input::get('id'));

                    $successMessage = 'Batch Updated Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('delete_batch')) {
            $user->updateRecord('batch', array(
                'status' => 0,
            ), Input::get('id'));
            $successMessage = 'Batch Deleted Successful';
        } elseif (Input::get('edit_site')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('sites', array(
                        'name' => Input::get('name'),
                    ), Input::get('id'));
                    $successMessage = 'Site Successful Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('edit_study')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
                'pi' => array(
                    'required' => true,
                ),
                'coordinator' => array(
                    'required' => true,
                ),
                'start_date' => array(
                    'required' => true,
                ),
                'end_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('study', array(
                        'name' => Input::get('name'),
                        'pi_id' => Input::get('pi'),
                        'co_id' => Input::get('coordinator'),
                        'start_date' => Input::get('start_date'),
                        'end_date' => Input::get('end_date'),
                        'details' => Input::get('details'),
                    ), Input::get('id'));

                    $successMessage = 'Study Successful Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('edit_drug_cat')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('drug_cat', array(
                        'name' => Input::get('name'),
                    ), Input::get('id'));
                    $successMessage = 'Drug Category Successful Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('edit_batch_desc')) {
            $validate = $validate->check($_POST, array(
                'batch' => array(
                    'required' => true,
                ),
                'name' => array(
                    'required' => true,
                ),
                'category' => array(
                    'required' => true,
                ),
                'quantity' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $descSum = 0;
                $bSum = 0;
                $dSum = 0;
                $descSum = $override->getSumD1('batch_description', 'quantity', 'batch_id', Input::get('batch'));
                $bSum = $override->get('batch', 'id', Input::get('batch'))[0];
                $dSum = $descSum[0]['SUM(quantity)'] + Input::get('quantity');
                if ($dSum <= $bSum['amount']) {
                    try {
                        $user->updateRecord('batch_description', array(
                            'name' => Input::get('name'),
                            'cat_id' => Input::get('category'),
                            'quantity' => Input::get('quantity'),
                            'notify_amount' => Input::get('notify_amount'),
                        ), Input::get('id'));
                        $successMessage = 'Batch Description Successful Updated';
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else {
                    $errorMessage = 'Exceeded Batch Amount, Please cross check and try again';
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('edit_group')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('use_group', array(
                        'name' => Input::get('name'),
                    ), Input::get('id'));
                    $successMessage = 'Group Successful Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('edit_use_case')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('use_case', array(
                        'name' => Input::get('name'),
                    ), Input::get('id'));
                    $successMessage = 'Use Case Successful Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('edit_location')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('location', array(
                        'name' => Input::get('name'),
                    ), Input::get('id'));
                    $successMessage = 'Location Successful Updated';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('update_check')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'last_check_date' => array(
                    'required' => true,
                ),
                'next_check_date' => array(
                    'required' => true,
                ),
                'maintainance_status' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                if (Input::get('next_check_date') >= date('Y-m-d')) {
                    if (Input::get('next_check_date') < Input::get('next_check_date_db')) {
                        if (Input::get('last_check_date') <= date('Y-m-d')) {
                            if (Input::get('last_check_date') > Input::get('last_check_date_db')) {
                                try {
                                    $user->createRecord('check_records', array(
                                        'batch_desc_id' => Input::get('batch_desc_id'),
                                        'last_check_date' => Input::get('last_check_date'),
                                        'next_check_date' => Input::get('next_check_date'),
                                        'create_on' => date('Y-m-d H:m:s'),
                                        'staff_id' => $user->data()->id,
                                        'status' => Input::get('maintainance_status'),
                                        'check_type' => Input::get('maintainance_type'),
                                    ));

                                    $successMessage = 'Check Status Updated Successful';
                                } catch (Exception $e) {
                                    die($e->getMessage());
                                }
                            } else {
                                $errorMessage = 'Last Date not correct';
                            }
                        } else {
                            $errorMessage = 'Last Date can not be of Future';
                        }
                    } else {
                        $errorMessage = 'Next Date not correct';
                    }
                } else {
                    $errorMessage = 'Next Date can not be of Past';
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
    <title> Info | Pharmacy </title>
    <?php include "head.php"; ?>
</head>

<body>
    <div class="wrapper">

        <?php include 'topbar.php' ?>
        <?php include 'menu.php' ?>
        <div class="content">


            <div class="breadLine">

                <ul class="breadcrumb">
                    <li><a href="#">Info</a> <span class="divider">></span></li>
                </ul>
                <?php include 'pageInfo.php' ?>
            </div>

            <div class="workplace">
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

                <div class="row">

                    <?php if ($_GET['id'] == 1) { ?>
                        <div class="col-md-12">
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Expired Medicine</h1>
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
                                            <th><input type="checkbox" name="checkall" /></th>
                                            <th width="20%">Generic Name</th>
                                            <th width="10%">Batch No</th>
                                            <th width="10%">Drug Category</th>
                                            <th width="10%">Currently Quantity</th>
                                            <th width="10%">Remained</th>
                                            <th width="10%">Expire Date</th>
                                            <th width="15%">Status</th>
                                            <th width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $amnt = 0;
                                        foreach ($override->getLessThanDate('batch', 'expire_date', $today) as $batchDesc) {
                                            $batch_no = $override->get('batch', 'id', $batchDesc['id'])[0];
                                            $dCat = $override->get('drug_cat', 'id', $batchDesc['id'])[0];
                                            $amnt = $batchDesc['quantity'] - $batchDesc['assigned'];
                                            $used = $override->get('batch_description', 'batch_id', $batchDesc['id'])[0];
                                            // number_format($batchDesc['amount'] - $notifyAmount['assigned'])                                            
                                            // print_r($used);
                                        ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkbox" /></td>
                                                <td><?= $batchDesc['name'] ?></td>
                                                <td><?= $batchDesc['batch_no'] ?></td>
                                                <td><?= $dCat['name'] ?></td>
                                                <td><?= $batchDesc['amount'] ?></td>
                                                <td><?= $used['assigned'] ?></td>
                                                <td><?= $batchDesc['expire_date'] ?></td>
                                                <td>
                                                    <?php if ($batchDesc['expire_date'] <= $today) { ?>
                                                        <a href="#" role="button" class="btn btn-danger" data-toggle="modal">Expired</a>
                                                    <?php } elseif ($batchDesc['expire_date'] > 0) { ?>
                                                        <a href="#" role="button" class="btn btn-warning" data-toggle="modal">Not Expired</a>
                                                    <?php } else { ?>
                                                        <a href="#" role="button" class="btn btn-success" data-toggle="modal">Un - Checked</a>
                                                    <?php } ?>
                                                </td>

                                                <td>
                                                    <a href="#study<?= $batchDesc['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">Edit</a>
                                                    <a href="#delete<?= $batchDesc['id'] ?>" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
                                                </td>

                                            </tr>
                                            <div class="modal fade" id="study<?= $batchDesc['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form method="post">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                <h4>Edit Batch Description Info</h4>
                                                            </div>
                                                            <div class="modal-body modal-body-np">
                                                                <div class="row">
                                                                    <div class="block-fluid">
                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Batch</div>
                                                                            <div class="col-md-9">
                                                                                <input value="<?= $override->get('batch', 'id', $batchDesc['batch_id'])[0]['name'] ?>" type="text" id="name" disabled />
                                                                            </div>
                                                                        </div>

                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Name:</div>
                                                                            <div class="col-md-9">
                                                                                <input value="<?= $batchDesc['name'] ?>" class="validate[required]" type="text" name="name" id="name" />
                                                                            </div>
                                                                        </div>

                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Category</div>
                                                                            <div class="col-md-9">
                                                                                <select name="category" style="width: 100%;" required>
                                                                                    <option value="<?= $batchDesc['cat_id'] ?>"><?= $override->get('drug_cat', 'id', $batchDesc['cat_id'])[0]['name'] ?></option>
                                                                                    <?php foreach ($override->getData('drug_cat') as $dCat) { ?>
                                                                                        <option value="<?= $dCat['id'] ?>"><?= $dCat['name'] ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Quantity:</div>
                                                                            <div class="col-md-9">
                                                                                <input value="<?= $batchDesc['quantity'] ?>" class="validate[required]" type="number" name="quantity" id="name" />
                                                                            </div>
                                                                        </div>

                                                                        <div class="row-form clearfix">
                                                                            <div class="col-md-3">Notification Amount: </div>
                                                                            <div class="col-md-9">
                                                                                <input value="<?= $batchDesc['notify_amount'] ?>" class="validate[required]" type="text" name="notify_amount" required />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="dr"><span></span></div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="batch" value="<?= $batchDesc['batch_id'] ?>">
                                                                <input type="hidden" name="id" value="<?= $batchDesc['id'] ?>">
                                                                <input type="submit" name="edit_batch_desc" value="Save updates" class="btn btn-warning">
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
                    <?php } elseif ($_GET['id'] == 2) { ?>
                        <div class="col-md-12">
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>List of medicine 30 days Before Expiration Date</h1>
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
                                            <th><input type="checkbox" name="checkall" /></th>
                                            <th width="25%">Generic Name</th>
                                            <th width="25%">Study</th>
                                            <th width="10%">Amount</th>
                                            <th width="10%">Expire Date</th>
                                            <th width="25%">Manage</th>
                                        </tr>
                                    </thead>
                                    <tbody>



                                        <?php


                                        // print_r($todayPlus30);
                                        foreach ($override->getLessThanDate30('batch', 'expire_date', $todayPlus30) as $list) {
                                            $study = $override->get('study', 'id', $list['study_id'])[0]['name'];
                                            // print_r($study);
                                        ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkbox" /></td>
                                                <td> <a href="info.php?id=7&bt=<?= $list['id'] ?>"><?= $list['name'] ?></a></td>
                                                <td><?= $study ?></td>
                                                <td><?= $list['amount'] ?></td>
                                                <td><?= $list['expire_date'] ?></td>
                                                <td>
                                                    <a href="info.php?id=7&bt=<?= $lis['id'] ?>" class="btn btn-default">View</a>
                                                </td>

                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 3) { ?>
                        <div class="col-md-12">
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Batch Description</h1>
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
                                            <th><input type="checkbox" name="checkall" /></th>
                                            <th width="35%">Generic Name</th>
                                            <th width="15%">Batch No</th>
                                            <th width="10%">Drug Category</th>
                                            <th width="10%">Current Quantity</th>
                                            <th width="10%">Last Check Date</th>
                                            <th width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($override->getLessThanDate('batch_description', 'next_check_date', $today) as $batchDesc) {
                                            $batch_no = $override->get('batch', 'id', $batchDesc['batch_id'])[0];
                                            $dCat = $override->get('drug_cat', 'id', $batchDesc['cat_id'])[0] ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkbox" /></td>
                                                <td><a href="info.php?id=8&dsc=<?= $batchDesc['id'] ?>"><?= $batchDesc['name'] ?></a></td>
                                                <td><?= $batch_no['batch_no'] ?></td>
                                                <td><?= $dCat['name'] ?></td>
                                                <td> <?= $batchDesc['quantity'] ?></td>
                                                <td> <?= $batchDesc['next_check_date'] ?></td>
                                                <td>
                                                    <a href="info.php?id=8&dsc=<?= $batchDesc['id'] ?>" class="btn btn-info">Details</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 4) { ?>
                        <div class="col-md-12">
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Product Assignment</h1>
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
                                            <th width="15%">Generic Name</th>
                                            <th width="25%">Study</th>
                                            <th width="10%">Quantity</th>
                                            <th width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($override->getLessThanDate('batch_description', 'next_check_date', $todayPlus30) as $batchDesc) {
                                            $study = $override->get('study', 'id', $batchDesc['id'])[0];
                                            $staff = $override->get('user', 'id', $batchDesc['id'])[0];
                                            $drug = $override->get('batch_description', 'id', $_GET['dsc'])[0];
                                        ?>
                                            <tr>
                                                <td><?= $batchDesc['name'] ?></a></td>
                                                <td><?= $study['name'] ?></td>
                                                <td><?= $batchDesc['quantity'] ?></td>
                                                <td>
                                                    <a href="info.php?id=9&dsc=<?= $_GET['dsc'] ?>" class="btn btn-default">Assigned History</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 9) { ?>
                        <div class="col-md-12">
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Product Assignment History</h1>
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
                                            <th><input type="checkbox" name="checkall" /></th>
                                            <th width="15%">Staff Name</th>
                                            <th width="25%">Study</th>
                                            <th width="25%">Drug</th>
                                            <th width="10%">Quantity</th>
                                            <th width="25%">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($override->get('assigned_stock_rec', 'drug_id', $_GET['dsc']) as $batchDesc) {
                                            $study = $override->get('study', 'id', $batchDesc['study_id'])[0];
                                            $staff = $override->get('user', 'id', $batchDesc['staff_id'])[0];
                                            $drug = $override->get('batch_description', 'id', $_GET['dsc'])[0];
                                        ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkbox" /></td>
                                                <td><a href="#"><?= $staff['firstname'] . ' ' . $staff['lastname'] ?></a></td>
                                                <td><?= $study['name'] ?></td>
                                                <td><?= $drug['name'] ?></td>
                                                <td> <?= $batchDesc['quantity'] ?></td>
                                                <td><?= $batchDesc['create_on'] ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 6) { ?>
                        <div class="col-md-12">
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Batch Description</h1>
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

                                        foreach ($override->get4('batch_description') as $bDiscription) {
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

                                            // var_dump($bDiscription);
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
                                                    <a href="info.php?id=16&gid=<?= $bDiscription['id'] ?>" class="btn btn-info">View</a>
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
                            </div>
                        </div>

                    <?php } elseif ($_GET['id'] == 7) { ?>
                        <div class="col-md-12">
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Use Stock Guide Description</h1>
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
                                            <th width="15%">Generic Name</th>
                                            <th width="10%">Added</th>
                                            <th width="10%">Added Date</th>
                                            <th width="10%">Staff</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $amnt = 0;
                                        $pagNum = $override->getCount('batch_description_records', 'batch_description_id', $_GET['did']);
                                        $pages = ceil($pagNum / $numRec);
                                        if (!$_GET['page'] || $_GET['page'] == 1) {
                                            $page = 0;
                                        } else {
                                            $page = ($_GET['page'] * $numRec) - $numRec;
                                        }
                                        foreach ($override->getWithLimit('batch_description_records', 'batch_description_id', $_GET['did'], $page, $numRec) as $batch) {
                                            $staff = $override->get('user', 'id', $batch['staff_id'])[0]['firstname'];
                                            $name = $override->get('batch_description', 'batch_id', $_GET['did'])[0]['name'];
                                        ?>
                                            <tr>
                                                <td><?= $name ?></td>
                                                <td><?= $batch['added'] ?></td>
                                                <td><?= $batch['create_on'] ?></td>
                                                <td><?= $staff ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 8) { ?>
                        <div class="col-md-12">
                            <div class="head clearfix">
                                <div class="isw-grid"></div>
                                <h1>Use Stock Guide Description</h1>
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
                                            <th width="15%">Generic Name</th>
                                            <th width="10%">Last check date</th>
                                            <th width="10%">Next check date</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Date Changed</th>
                                            <th width="10%">Staff</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $amnt = 0;
                                        $pagNum = $override->getCount('check_records', 'batch_desc_id', $_GET['updateId']);
                                        $pages = ceil($pagNum / $numRec);
                                        if (!$_GET['page'] || $_GET['page'] == 1) {
                                            $page = 0;
                                        } else {
                                            $page = ($_GET['page'] * $numRec) - $numRec;
                                        }
                                        foreach ($override->getWithLimit('check_records', 'batch_desc_id', $_GET['updateId'], $page, $numRec) as $batch) {
                                            $staff = $override->get('user', 'id', $batch['staff_id'])[0]['firstname'];
                                            $status = $override->get('maintainance_status', 'id', $batch['status'])[0]['name'];
                                            $name = $override->get('batch_description', 'id', $_GET['updateId'])[0]['name'];
                                            $last_check_date = $override->get('batch_description', 'id', $_GET['updateId'])[0]['last_check_date'];
                                            $last_check_date = $override->get('batch_description', 'id', $_GET['updateId'])[0]['next_check_date'];
                                        ?>
                                            <tr>
                                                <td><?= $name ?></td>
                                                <td><?= $batch['last_check_date'] ?></td>
                                                <td><?= $batch['next_check_date'] ?></td>
                                                <td><?= $status ?></td>
                                                <td><?= $batch['create_on'] ?></td>
                                                <td><?= $staff ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="dr"><span></span></div>
            </div>
        </div>
    </div>
</body>
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
    $(document).ready(function() {
        $('#wait_ds').hide();
        $('#region').change(function() {
            var getUid = $(this).val();
            $('#wait_ds').show();
            $.ajax({
                url: "process.php?cnt=region",
                method: "GET",
                data: {
                    getUid: getUid
                },
                success: function(data) {
                    $('#ds_data').html(data);
                    $('#wait_ds').hide();
                }
            });

        });
        $('#wait_wd').hide();
        $('#ds_data').change(function() {
            $('#wait_wd').hide();
            var getUid = $(this).val();
            $.ajax({
                url: "process.php?cnt=district",
                method: "GET",
                data: {
                    getUid: getUid
                },
                success: function(data) {
                    $('#wd_data').html(data);
                    $('#wait_wd').hide();
                }
            });

        });
        $('#download').change(function() {
            var getUid = $(this).val();
            $.ajax({
                url: "process.php?cnt=download",
                method: "GET",
                data: {
                    getUid: getUid
                },
                success: function(data) {

                }
            });

        });
    });
</script>

</html>