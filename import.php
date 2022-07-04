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
        if (Input::get('submit')) {
            // Allowed mime types
            $fileMimes = array(
                'text/x-comma-separated-values',
                'text/comma-separated-values',
                'application/octet-stream',
                'application/vnd.ms-excel',
                'application/x-csv',
                'text/x-csv',
                'text/csv',
                'application/csv',
                'application/excel',
                'application/vnd.msexcel',
                'text/plain'
            );

            // Validate whether selected file is a CSV file
            if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)) {

                // print_r($_FILES);
                // Open uploaded CSV file with read-only mode
                $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

                // Skip the first line
                fgetcsv($csvFile);
                fgetcsv($csvFile);


                // Parse data from CSV file line by line
                // Parse data from CSV file line by line
                while (($getData = fgetcsv($csvFile, 10000, ",")) !== FALSE) {

                    try {

                        // If user already exists in the database with the same email
                        // $query = "SELECT id FROM users WHERE email = '" . $getData[1] . "'";

                        // $check = mysqli_query($conn, $query);

                        // if ($check->num_rows > 0) {
                        //     mysqli_query($conn, "UPDATE users SET name = '" . $name . "', phone = '" . $phone . "', status = '" . $status . "', created_at = NOW() WHERE email = '" . $email . "'");
                        // } else {
                        //     mysqli_query($conn, "INSERT INTO users (name, email, phone, created_at, updated_at, status) VALUES ('" . $name . "', '" . $email . "', '" . $phone . "', NOW(), NOW(), '" . $status . "')");
                        // }


                        // Get row data
                        $generic_name = $getData[0];
                        $brand_name = $getData[1];
                        $amount = $getData[2];
                        $cat_id = $getData[5];
                        $batch_no = $getData[6];
                        $maintainance_type = $getData[7];
                        $next_check_date = $getData[9];
                        $expire_date = $getData[8];
                        $last_check_date = $getData[9];
                        $notify_amount = $getData[10];
                        $stoke_guide = $getData[11];
                        $study_id = $getData[19];
                        $status = $getData[20];
                        $staff_id = $getData[21];
                        $dsc_status = $getData[22];
                        $details = $getData[23];
                        $use_group = $getData[24];
                        $use_case = $getData[25];
                        $maintainance_status = $getData[26];
                        $form_og = $getData[30];
                        $concentration_unit = $getData[31];

                        // $assigned = $amount - $notify_amount;

                        $date = explode('/', $getData[8]);
                        if (strlen($date[0]) != '2') {
                            $date[0] = '0' . $date[0];
                        } else {
                            $date[0] = $date[0];
                        }

                        if (strlen($date[1]) != '2') {
                            $date[1] = '0' . $date[1];
                        } else {
                            $date[1] = $date[1];
                        }
                        $expire_date = $date[2] . '-' . $date[0] . '-' . $date[1];


                        $last_date = explode('/', $getData[9]);
                        if (strlen($last_date[0]) != '2') {
                            $last_date[0] = '0' . $last_date[0];
                        } else {
                            $last_date[0] = $last_date[0];
                        }

                        if (strlen($last_date[1]) != '2') {
                            $last_date[1] = '0' . $last_date[1];
                        } else {
                            $last_date[1] = $last_date[1];
                        }

                        $last_check_date = $last_date[2] . '-' . $last_date[0] . '-' . $last_date[1];

                        $user->createRecord('batch', array(
                            'name' => $generic_name,
                            'study_id' => $study_id,
                            'batch_no' => $batch_no,
                            'amount' => $amount,
                            'notify_amount' => $notify_amount,
                            // 'manufacturer' => $name,
                            // 'manufactured_date' => $name,
                            'expire_date' => $expire_date,
                            'create_on' => date('Y-m-d'),
                            'details' => $details,
                            'status' => $status,
                            'dsc_status' => $dsc_status,
                            'staff_id' => $staff_id,
                            'maintainance_type' => $maintainance_type,
                        ));


                        $user->createRecord('batch_description', array(
                            'batch_id' => $override->lastRow('batch', 'id')[0]['id'],
                            'name' => $brand_name,
                            'cat_id' => $cat_id,
                            'quantity' => $amount,
                            'assigned' => 2,
                            'notify_amount' => $notify_amount,
                            'status' => $status,
                            'create_on' => date('Y-m-d'),
                            'staff_id' => $staff_id,
                            'use_group' => $use_group,
                            'use_case' => $use_case,
                            'stock_guide' => $stoke_guide,
                            'last_check_date' => $last_check_date,
                            'next_check_date' => '',
                            'maintainance_status' => $maintainance_status,
                            'maintainance_type' => $maintainance_type,
                            'last_status' => $maintainance_status,
                            'form_og' => $form_og,
                            'concentration_unit' => $concentration_unit,

                        ));

                        // $date = str_replace('/', '-', $getData[8]);
                        // $date2 = date('Y-m-d', strtotime($date));



                        // print_r($date2);



                        // if ($getData[12] || $getData[13] || $getData[14] || $getData[15] || $getData[16] || $getData[17]) {
                        // $array = array();
                        // $array = ['icu' => $getData[12], 'emkit' => $getData[13], 'embuffer' => $getData[14], 'amkit' => $getData[15], 'examrum' => $getData[16], 'ctmrum' => $getData[17]];
                        $array = ['1' => $getData[12], '2' => $getData[13], '3' => $getData[14], '4' => $getData[15], '5' => $getData[16], '6' => $getData[17]];

                        // $location = $override->get('location', 'id', 1)[0]['id'];
                        $key = array_keys($array);
                        $i = 1;

                        foreach ($array as $sid) {
                            // print_r($key);
                            // if ($getData[12]) {
                            //     $location = 1;
                            // }if($getData[13]){
                            //     $location = 2;
                            // }if($getData[14]){
                            //     $location = 3;
                            // }if($getData[15]){
                            //     $location = 4;
                            // }if($getData[16]){
                            //     $location = 5;
                            // }if($getData[17]){
                            //     $location = 6;
                            // }
                            $user->createRecord('batch_guide_records', array(
                                'batch_id' => $override->lastRow('batch', 'id')[0]['id'],
                                'batch_description_id' => $override->lastRow('batch_description', 'id')[0]['id'],
                                'quantity' => $sid,
                                'group_id' => $use_group,
                                'use_case_id' => $use_case,
                                'location_id' => $i,
                                'create_on' => date('Y-m-d'),
                                'staff_id' => $staff_id,
                            ));

                            $i++;
                        }
                        $successMessage = 'Your Data Uploaded successfully';
                    } catch (Exception $e) {
                        $e->getMessage();
                    }
                }

                // Close opened CSV file
                fclose($csvFile);
                // header("Location: index.php");
            } else {
                $errorMessage = "Please select valid file";
            }












            // $attachment_file = Input::get('pic');
            // if (!empty($_FILES['image']["error"])) {
            // $attach_file = $_FILES['image']['type'];
            // if ($attach_file == "image/jpeg" || $attach_file == "image/jpg" || $attach_file == "image/png" || $attach_file == "image/gif" || $attach_file == "image/ico") {
            //     $folderName = 'img/project/';
            //     $attachment_file = $folderName . basename($_FILES['image']['name']);
            //     if (move_uploaded_file($_FILES['image']["tmp_name"], $attachment_file)) {
            //         $file = true;
            //     } else { {
            //             $errorM1 = true;
            //             $errorMessage = 'Your Image Not Uploaded ,';
            //         }
            //     }
            // } else {
            //     $errorM1 = true;
            //     $errorMessage = 'None supported file format';
            // } //not supported format
            // if ($errorM1 == false) {
            //     try {
            //         $user->updateRecord('images', array(
            //             'location' => $attachment_file,
            //             'status' => 1
            //         ), Input::get('id'));
            //         $successMessage = 'Your Image Uploaded successfully';
            //     } catch (Exception $e) {
            //         $e->getMessage();
            //     }
            // }
            // } else {
            //     $errorMessage = 'You have not select any image to upload';
            // }
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

                                        foreach ($override->get4('batch_description', 'status', 1) as $bDiscription) {
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

                    <?php } elseif ($_GET['id'] == 2) { ?>
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
                                <!-- <table cellpadding="0" cellspacing="0" width="100%" class="table"> -->
                                <tbody>
                                    <form enctype="multipart/form-data" method="post">
                                        <div class="input-row">
                                            <label class="col-md-4 control-label">Choose a CSV file</label>
                                            <input type="file" name="file" id="file" accept=".csv">
                                            <br />
                                            <br />
                                            <input type="submit" name="submit" value="Upload" class="btn btn-primary">
                                            <br />
                                        </div>
                                    </form>

                                    <!-- <form method="POST" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">ADD IMAGES</h4>
                                        </div>
                                        <div class="modal-body clearfix">
                                            <div class="controls">
                                                <div class="form-row">
                                                    <div class="col-md-2">Image:</div>
                                                    <div class="col-md-10">
                                                        <div class="input-group file"> -->
                                    <!-- <input type="text" class="form-control" value="" /> -->
                                    <!-- <input type="file" name="image" required /> -->
                                    <!-- <span class="input-group-btn">
                                                                    <button class="btn" type="button">Browse</button>
                                                                </span> -->
                                    <!-- </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="pull-right col-md-3">
                                                <input type="submit" name="edit_image" value="ADD" class="btn btn-success btn-clean">
                                            </div>
                                            <div class="pull-right col-md-2">
                                                <button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </form> -->
                                </tbody>
                                <!-- </table> -->
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