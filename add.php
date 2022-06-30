<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
$validate = new validate();
$successMessage = null;
$pageError = null;
$errorMessage = null;
if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('add_user')) {
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
                'username' => array(
                    'required' => true,
                    'unique' => 'user'
                ),
                'phone_number' => array(
                    'required' => true,
                    'unique' => 'user'
                ),
                'email_address' => array(
                    'unique' => 'user'
                ),
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
                    $user->createRecord('user', array(
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'username' => Input::get('username'),
                        'position' => Input::get('position'),
                        'phone_number' => Input::get('phone_number'),
                        'password' => Hash::make($password, $salt),
                        'salt' => $salt,
                        'create_on' => date('Y-m-d'),
                        'last_login' => '',
                        'status' => 1,
                        'power' => 0,
                        'email_address' => Input::get('email_address'),
                        'accessLevel' => $accessLevel,
                        'user_id' => $user->data()->id,
                        'count' => 0,
                        'pswd' => 0,
                    ));

                    $staff_id = $override->lastRow('user', 'id')[0];

                    foreach (Input::get('sites') as $site) {
                        $user->createRecord('staff_sites', array(
                            'staff_id' => $staff_id['id'],
                            'site_id' => $site,
                        ));
                    }

                    foreach (Input::get('study') as $site) {
                        $user->createRecord('staff_study', array(
                            'staff_id' => $staff_id['id'],
                            'study_id' => $site,
                        ));
                    }

                    $successMessage = 'Account Created Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_position')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('position', array(
                        'name' => Input::get('name'),
                    ));
                    $successMessage = 'Position Successful Added';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_study')) {
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
                    $user->createRecord('study', array(
                        'name' => Input::get('name'),
                        'pi_id' => Input::get('pi'),
                        'co_id' => Input::get('coordinator'),
                        'start_date' => Input::get('start_date'),
                        'end_date' => Input::get('end_date'),
                        'details' => Input::get('details'),
                        'date_created' => date('Y-m-d'),
                        'status' => 1,
                        'staff_id' => $user->data()->id,
                    ));

                    $study_id = $override->lastRow('study', 'id')[0];

                    foreach (Input::get('sites') as $site) {
                        $user->createRecord('study_sites', array(
                            'study_id' => $study_id['id'],
                            'site_id' => $site,
                        ));
                    }

                    $successMessage = 'Study Successful Added';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_client')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'file_id' => array(
                    'required' => true,
                    'unique' => 'clients',
                ),
                'study_id' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('clients', array(
                        'study_id' => Input::get('study_id'),
                        'file_id' => Input::get('file_id'),
                        'create_on' => date('Y-m-d'),
                        'status' => 1,
                        'staff_id' => $user->data()->id
                    ));

                    $successMessage = 'Client Added Successful';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_batch')) {
            if (Input::get('complete_batch')) {
                $validate = new validate();
                $validate = $validate->check($_POST, array(
                    'name' => array(
                        'required' => true,
                    ),
                    'brand_name' => array(
                        'required' => true,
                    ),
                    'batch_no' => array(
                        'required' => true,
                    ),
                    'maintainance_type' => array(
                        'required' => true,
                    ),
                    'study' => array(
                        'required' => true,
                    ),
                    'amount' => array(
                        'required' => true,
                    ),
                    'notify_amount' => array(
                        'required' => true,
                    ),
                    'manufactured_date' => array(
                        'required' => true,
                    ),
                    'expire_date' => array(
                        'required' => true,
                    ),
                    // 'batch' => array(
                    //     'required' => true,
                    // ),
                    'category' => array(
                        'required' => true,
                    ),
                    'use_group' => array(
                        'required' => true,
                    ),
                    'use_case' => array(
                        'required' => true,
                    ),
                    // 'location' => array(
                    //     'required' => true,
                    // ),
                ));
                if ($validate->passed()) {
                    $sii = 0;
                    $q = 0;
                    foreach (Input::get('location') as $sid) {
                        $q = $q + Input::get('quantity')[$sii];
                        $sii++;
                    }

                    // $sumLoctn = $override->getSumD1('batch_guide_records', 'quantity', 'batch_description_id', $bDiscription['id'])[0]['SUM(quantity)'];
                    if ($q <= Input::get('amount')) {
                        if ($q == Input::get('notify_amount')) {
                            try {
                                $user->createRecord('batch', array(
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
                                    'dsc_status' => 1,
                                    'staff_id' => $user->data()->id,
                                    'maintainance_type' => Input::get('maintainance_type'),
                                ));


                                $user->createRecord('batch_description', array(
                                    'batch_id' => $override->lastRow('batch', 'id')[0]['id'],
                                    'name' => Input::get('brand_name'),
                                    'cat_id' => Input::get('category'),
                                    'quantity' => Input::get('amount'),
                                    'notify_amount' => Input::get('notify_amount'),
                                    'create_on' => date('Y-m-d'),
                                    'staff_id' => $user->data()->id,
                                    'status' => 1,
                                    'use_group' => Input::get('use_group'),
                                    'use_case' => Input::get('use_case'),
                                    'maintainance_type' => Input::get('maintainance_type'),
                                    'maintainance_status' => Input::get('maintainance_status'),
                                ));

                                $si = 0;
                                foreach (Input::get('location') as $sid) {
                                    $q = Input::get('quantity')[$si];
                                    $location = $override->get('location', 'id', $sid['id'])[0];
                                    $batch_id = $override->lastRow('batch', 'id')[0]['id'];
                                    $batch_desc_id = $override->lastRow('batch_description', 'id')[0]['id'];
                                    $use_group = $override->lastRow('use_group', 'id')[0]['id'];
                                    $user->createRecord('batch_guide_records', array(
                                        'batch_id' => $batch_id,
                                        'batch_description_id' => $batch_desc_id,
                                        'quantity' => $q,
                                        'group_id' => $use_group,
                                        'use_case_id' => Input::get('use_case'),
                                        'location_id' => $location['id'],
                                        'create_on' => date('Y-m-d'),
                                        'staff_id' => $user->data()->id,
                                    ));
                                    // $dr_stock = $override->get('batch_description', 'id', $batch_desc_id)[0];
                                    // $new_stock = $dr_stock['quantity'] - $q;
                                    // $user->updateRecord('batch_description', array(
                                    //     'quantity' => $new_stock,
                                    // ), $dr_stock['id']);
                                    $si++;
                                }

                                $user->createRecord('check_records', array(
                                    'batch_desc_id' => $override->lastRow('batch_description', 'id')[0]['id'],
                                    'last_check_date' => Input::get('last_check_date'),
                                    'next_check_date' => Input::get('next_check_date'),
                                    'create_on' => date('Y-m-d'),
                                    'staff_id' => $user->data()->id,
                                    'status' => Input::get('maintainance_status'),
                                    'check_type' => Input::get('maintainance_type'),
                                ));


                                $successMessage = 'Device / Medicine Added Successful';
                            } catch (Exception $e) {
                                die($e->getMessage());
                            }
                        } else {
                            $errorMessage = 'Amount entered not correct amount, please re - check each location!';
                        }
                    } else {
                        $errorMessage = 'Amount entered exceeded the Current Available amount, Please enter the correct amount';
                    }
                } else {
                    $pageError = $validate->errors();
                }
            }
        } elseif (Input::get('add_site')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('sites', array(
                        'name' => Input::get('name'),
                    ));
                    $successMessage = 'Site Successful Added';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_drug_cat')) {
            $validate = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('drug_cat', array(
                        'name' => Input::get('name'),
                    ));
                    $successMessage = 'Drug Category Successful Added';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('assign_stock')) {
            $validate = $validate->check($_POST, array(
                'batch' => array(
                    'required' => true,
                ),
                'study' => array(
                    'required' => true,
                ),
                'staff' => array(
                    'required' => true,
                ),
                'site' => array(
                    'required' => true,
                ),
                'quantity' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $checkData = $override->selectData('assigned_stock', 'staff_id', Input::get('staff'), 'batch_id', Input::get('batch'), 'study_id', Input::get('study'))[0];
                    $assignStock = $override->get('batch_description', 'id', Input::get('drug'))[0];
                    $newAssigned = $assignStock['assigned'] + Input::get('quantity');
                    $newQty = $checkData['quantity'] +  Input::get('quantity');
                    $current_quantity = $assignStock['quantity'] - Input::get('quantity');
                    // print_r($newQty);
                    // if ($newAssigned <= $assignStock['quantity']) {
                    if (Input::get('quantity') <= $assignStock['quantity']) {
                        if ($checkData) {
                            $user->updateRecord('assigned_stock', array(
                                'quantity' => $newQty,
                                'status' => 1,
                            ), $checkData['id']);
                            $user->updateRecord('batch_description', array('assigned' => $newAssigned), Input::get('drug'));
                            $user->updateRecord('batch_description', array('quantity' => $current_quantity), Input::get('batch'));
                        } else {
                            $user->createRecord('assigned_stock', array(
                                'batch_id' => Input::get('batch'),
                                'study_id' => Input::get('study'),
                                'drug_id' => Input::get('drug'),
                                'staff_id' => Input::get('staff'),
                                'site_id' => Input::get('site'),
                                'quantity' => Input::get('quantity'),
                                'notes' => Input::get('notes'),
                                'admin_id' => $user->data()->id,
                                'status' => 1,
                            ));

                            $user->updateRecord('batch_description', array('assigned' => $newAssigned), Input::get('drug'));
                        }
                        $user->createRecord('assigned_stock_rec', array(
                            'batch_id' => Input::get('batch'),
                            'study_id' => Input::get('study'),
                            'drug_id' => Input::get('drug'),
                            'staff_id' => Input::get('staff'),
                            'site_id' => Input::get('site'),
                            'quantity' => Input::get('quantity'),
                            'notes' => Input::get('notes'),
                            'create_on' => date('Y-m-d'),
                            'admin_id' => $user->data()->id,
                        ));

                        $successMessage = 'Stock Assigned Successful';
                    } else {
                        $errorMessage = 'Insufficient Amount on Stock';
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_use_group')) {
            $validate = $validate->check($_POST, array(
                'use_group' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('use_group', array(
                        'name' => Input::get('use_group')
                    ));
                    $successMessage = 'Group Successful Added';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_use_case')) {
            $validate = $validate->check($_POST, array(
                'use_case' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('use_case', array(
                        'name' => Input::get('use_case')
                    ));
                    $successMessage = 'Use Case Successful Added';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_location')) {
            $validate = $validate->check($_POST, array(
                'location' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('location', array(
                        'name' => Input::get('location')
                    ));
                    $successMessage = 'Group Location Successful Added';
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_maintainance_type')) {
            $validate = $validate->check($_POST, array(
                'maintainance_type' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->createRecord('maintainance_type', array(
                        'name' => Input::get('maintainance_type')
                    ));
                    $successMessage = 'Maintainance Type Successful Added';
                } catch (Exception $e) {
                    die($e->getMessage());
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
    <title> Pharmacy </title>
    <?php include "head.php"; ?>
</head>

<body>
    <div class="wrapper">

        <?php include 'topbar.php' ?>
        <?php include 'menu.php' ?>
        <div class="content">


            <div class="breadLine">

                <ul class="breadcrumb">
                    <li><a href="#">Simple Admin</a> <span class="divider">></span></li>
                    <li class="active">Add Info</li>
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
                    <?php if ($_GET['id'] == 1 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add User</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">First Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="firstname" id="firstname" />
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Last Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="lastname" id="lastname" />
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Username:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="username" id="username" />
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-5">Select Study:</div>
                                        <div class="col-md-7">
                                            <select name="study[]" id="s2_2" style="width: 100%;" multiple="multiple" required>
                                                <option value="">choose a study...</option>
                                                <?php foreach ($override->getData('study') as $study) { ?>
                                                    <option value="<?= $study['id'] ?>"><?= $study['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-5">Select sites:</div>
                                        <div class="col-md-7">
                                            <select name="sites[]" id="s2_1" style="width: 100%;" multiple="multiple" required>
                                                <option value="">choose a site...</option>
                                                <?php foreach ($override->getData('sites') as $site) { ?>
                                                    <option value="<?= $site['id'] ?>"><?= $site['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Position</div>
                                        <div class="col-md-9">
                                            <select name="position" style="width: 100%;" required>
                                                <option value="">Select position</option>
                                                <?php foreach ($override->getData('position') as $position) { ?>
                                                    <option value="<?= $position['id'] ?>"><?= $position['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Phone Number:</div>
                                        <div class="col-md-9"><input value="" class="" type="text" name="phone_number" id="phone" required /> <span>Example: 0700 000 111</span></div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">E-mail Address:</div>
                                        <div class="col-md-9"><input value="" class="validate[required,custom[email]]" type="text" name="email_address" id="email" /> <span>Example: someone@nowhere.com</span></div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_user" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 2 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Position</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="name" id="name" />
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_position" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 3 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Study</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Name: </div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="name" id="name" required />
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">PI</div>
                                        <div class="col-md-9">
                                            <select name="pi" style="width: 100%;" required>
                                                <option value="">Select staff</option>
                                                <?php foreach ($override->getData('user') as $staff) { ?>
                                                    <option value="<?= $staff['id'] ?>"><?= $staff['firstname'] . ' ' . $staff['lastname'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Coordinator</div>
                                        <div class="col-md-9">
                                            <select name="coordinator" style="width: 100%;" required>
                                                <option value="">Select staff</option>
                                                <?php foreach ($override->getData('user') as $staff) { ?>
                                                    <option value="<?= $staff['id'] ?>"><?= $staff['firstname'] . ' ' . $staff['lastname'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-5">Select sites:</div>
                                        <div class="col-md-7">
                                            <select name="sites[]" id="s2_2" style="width: 100%;" multiple="multiple" required>
                                                <option value="">choose a site...</option>
                                                <?php foreach ($override->getData('sites') as $site) { ?>
                                                    <option value="<?= $site['id'] ?>"><?= $site['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Start Date:</div>
                                        <div class="col-md-9"><input type="date" name="start_date" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">End Date:</div>
                                        <div class="col-md-9"><input type="date" name="end_date" required /> </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Study details:</div>
                                        <div class="col-md-9"><textarea name="details" rows="4" required></textarea></div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_study" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>
                        </div>
                    <?php } elseif ($_GET['id'] == 4 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Stock Batch</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <?php if (!Input::get('location') && !Input::get('location_1')) { ?>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Group:</label>
                                                        <select name="use_group" style="width: 100%;" required>
                                                            <option value="">Select Group</option>
                                                            <?php foreach ($override->getData('use_group') as $dCat) { ?>
                                                                <option value="<?= $dCat['id'] ?>"><?= $dCat['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <!-- select -->
                                                <div class="row-form clearfix">
                                                    <div class="form-group">
                                                        <label>Use Case:</label>
                                                        <select name="use_case" style="width: 100%;" required>
                                                            <option value="">Select Use Case</option>
                                                            <?php foreach ($override->getData('use_case') as $dCat) { ?>
                                                                <option value="<?= $dCat['id'] ?>"><?= $dCat['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Generic Name::</label>
                                                        <input value="" class="validate[required]" type="text" name="name" id="name" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Brand Name:</label>
                                                        <input value="" class="validate[required]" type="text" name="brand_name" id="brand_name" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Batch No:</label>
                                                        <input value="" class="validate[required]" type="text" name="batch_no" id="name" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Study:</label>
                                                        <select name="study" style="width: 100%;" required>
                                                            <option value="">Select study</option>
                                                            <?php foreach ($override->getData('study') as $study) { ?>
                                                                <option value="<?= $study['id'] ?>"><?= $study['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Maintainance Type:</label>
                                                        <select name="maintainance_type" style="width: 100%;" required>
                                                            <option value="">Select Type</option>
                                                            <?php foreach ($override->getData('maintainance_type') as $study) { ?>
                                                                <option value="<?= $study['id'] ?>"><?= $study['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Maintainance Status:</label>
                                                        <select name="maintainance_status" style="width: 100%;" required>
                                                            <option value="">Select Type</option>
                                                            <?php foreach ($override->getData('maintainance_status') as $study) { ?>
                                                                <option value="<?= $study['id'] ?>"><?= $study['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Last Check Date:</label>
                                                        <div class="col-md-9"><input type="date" name="last_check_date" required /> <span></span></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Next Check Date:</label>
                                                        <div class="col-md-9"><input type="date" name="next_check_date" required /> <span></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Current Quantity:</label>
                                                        <input value="" class="validate[required]" type="text" name="amount" id="name" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Re-Stock Level:</label>
                                                        <input value="" class="validate[required]" type="text" name="notify_amount" id="name" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Forms:</label>
                                                        <select name="category" style="width: 100%;" required>
                                                            <option value="">Select Form</option>
                                                            <?php foreach ($override->getData('drug_cat') as $dCat) { ?>
                                                                <option value="<?= $dCat['id'] ?>"><?= $dCat['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Item Location:</label>
                                                        <select name="location[]" id="s2_2" style="width: 100%;" multiple="multiple" required>
                                                            <option value="">Select Use Case Location...</option>
                                                            <?php foreach ($override->getData('location') as $drinks) {
                                                                // $brand = $override->get('drink_brand', 'id', $drinks['brand_id'])[0]; 
                                                            ?>
                                                                <option value="<?= $drinks['id'] ?>"><?= $drinks['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Manufacturer:</label>
                                                        <div class="col-md-9"><input type="text" name="manufacturer" id="manufacturer" /></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Manufactured Date:</label>
                                                        <div class="col-md-9"><input type="date" name="manufactured_date" required /> <span>Example: 2012-01-01</span></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Check / Expire Date:</label>
                                                        <div class="col-md-9"><input type="date" name="expire_date" required /> <span>Example: 2012-01-0</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="row-form clearfix">
                                                    <div class="col-md-3">Details: </div>
                                                    <div class="col-md-9">
                                                        <textarea class="" name="details" id="details" rows="4"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="footer tar">
                                            <input type="submit" name="add_batch" value="Submit" class="btn btn-default">
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <?php if (Input::get('location')) { ?>
                                        <label> Complete Stock Guide:
                                        </label>
                                        <div class="col-md-2"><strong>Current Amount Is<?php echo ' '; ?><?= Input::get('amount') ?> : </strong></div>
                                        <?php
                                        $f = 0;
                                        foreach (Input::get('location') as $lctn) {
                                            $location = $override->get('location', 'id', $lctn['id'])[0];
                                            // $name = $override->get('batch_description', 'location', $location['id'])[0];
                                            // print_r($name);
                                        ?>
                                            <div class="row-form clearfix">
                                                <div class="col-md-2"><strong><?= $location['name'] ?> : </strong></div>
                                                <input type="hidden" name="location[<?= $f ?>]" value="<?= $lctn ?>">
                                                <input type="hidden" name="use_group" value="<?= Input::get('use_group') ?>">
                                                <input type="hidden" name="use_case" value="<?= Input::get('use_case') ?>">
                                                <input type="hidden" name="name" value="<?= Input::get('name') ?>">
                                                <input type="hidden" name="brand_name" value="<?= Input::get('brand_name') ?>">
                                                <input type="hidden" name="batch_no" value="<?= Input::get('batch_no') ?>">
                                                <input type="hidden" name="study" value="<?= Input::get('study') ?>">
                                                <input type="hidden" name="amount" value="<?= Input::get('amount') ?>">
                                                <input type="hidden" name="notify_amount" value="<?= Input::get('notify_amount') ?>">
                                                <input type="hidden" name="category" value="<?= Input::get('category') ?>">
                                                <input type="hidden" name="location_1[<?= $f ?>]" value="<?= $lctn ?>">
                                                <input type="hidden" name="manufacturer" value="<?= Input::get('manufacturer') ?>">
                                                <input type="hidden" name="manufactured_date" value="<?= Input::get('manufactured_date') ?>">
                                                <input type="hidden" name="last_check_date" value="<?= Input::get('last_check_date') ?>">
                                                <input type="hidden" name="next_check_date" value="<?= Input::get('next_check_date') ?>">
                                                <input type="hidden" name="maintainance_type" value="<?= Input::get('maintainance_type') ?>">
                                                <input type="hidden" name="maintainance_status" value="<?= Input::get('maintainance_status') ?>">
                                                <input type="hidden" name="expire_date" value="<?= Input::get('expire_date') ?>">
                                                <input type="hidden" name="details" value="<?= Input::get('details') ?>">
                                                <div class="col-md-3"><input value="" class="validate[required]" type="number" name="quantity[]" id="quantity" /> <span></span></div>
                                            </div>
                                        <?php $f++;
                                        } ?>
                                        <div class="footer tar">
                                            <input type="hidden" name="complete_batch" value="1">
                                            <input type="hidden" name="total_cost" value="<?= $total ?>">
                                            <input type="submit" name="add_batch" value="Submit" class="btn btn-default">
                                        </div>
                                    <?php } ?>



                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 5 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Site</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="name" id="name" />
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_site" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 6 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Drug Category</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="name" id="name" />
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_drug_cat" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 7 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Batch Descriptions</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Batch</div>
                                        <div class="col-md-9">
                                            <select name="batch" style="width: 100%;" required>
                                                <option value="">Select Batch</option>
                                                <?php foreach ($override->get('batch', 'status', 1) as $batch) { ?>
                                                    <option value="<?= $batch['id'] ?>"><?= $batch['name'] . ' ' . $batch['batch_no'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Name:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="name" id="name" />
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Group:</div>
                                        <div class="col-md-9">
                                            <select name="batch" style="width: 100%;" required>
                                                <option value="">Select Group</option>
                                                <?php foreach ($override->getData('use_group') as $batch) { ?>
                                                    <option value="<?= $batch['id'] ?>"><?= $batch['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Category</div>
                                        <div class="col-md-9">
                                            <select name="category" style="width: 100%;" required>
                                                <option value="">Select Category</option>
                                                <?php foreach ($override->getData('drug_cat') as $dCat) { ?>
                                                    <option value="<?= $dCat['id'] ?>"><?= $dCat['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Groups</div>
                                        <div class="col-md-9">
                                            <select name="use_group" style="width: 100%;" required>
                                                <option value="">Select Group</option>
                                                <?php foreach ($override->getData('use_group') as $dCat) { ?>
                                                    <option value="<?= $dCat['id'] ?>"><?= $dCat['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Use Case</div>
                                        <div class="col-md-9">
                                            <select name="use_case" style="width: 100%;" required>
                                                <option value="">Select Use Case</option>
                                                <?php foreach ($override->getData('use_case') as $dCat) { ?>
                                                    <option value="<?= $dCat['id'] ?>"><?= $dCat['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Use Case Location</div>
                                        <div class="col-md-9">
                                            <select name="location" style="width: 100%;" required>
                                                <option value="">Select Use Case Location</option>
                                                <?php foreach ($override->getData('location') as $dCat) { ?>
                                                    <option value="<?= $dCat['id'] ?>"><?= $dCat['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Stoke Guide:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="number" name="stock_guide" id="stock_guide" />
                                        </div>
                                    </div>


                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Quantity:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="number" name="quantity" id="name" />
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Notification Amount: </div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="text" name="notify_amount" id="name" required />
                                        </div>
                                    </div>



                                    <div class="footer tar">
                                        <input type="submit" name="add_batch_desc" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 8) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Dispense Medicne / Device</h1>
                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Study</div>
                                        <div class="col-md-9">
                                            <select name="study" style="width: 100%;" id="study" required>
                                                <option value="">Select Study</option>
                                                <?php foreach ($override->get('study', 'status', 1) as $study) { ?>
                                                    <option value="<?= $study['id'] ?>"><?= $study['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Generic Name</div>
                                        <div id="ld_batch">
                                            <span><img src="img/loaders/loader.gif" id="wait_ds1" title="loader.gif" /></span>
                                        </div>
                                        <div class="col-md-9">
                                            <select name="batch" style="width: 100%;" id="batch" required>
                                                <option value="">Select name</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="desc">
                                        <div class="row-form clearfix">
                                            <div class="col-md-3">Staff</div>
                                            <div id="ld_staff">
                                                <span><img src="img/loaders/loader.gif" id="wait_ds1" title="loader.gif" /></span>
                                            </div>
                                            <div class="col-md-9">
                                                <select name="staff" style="width: 100%;" id="s2_1" required>
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Quantity:</div>
                                        <div class="col-md-9">
                                            <input value="" class="validate[required]" type="number" name="quantity" id="name" />
                                        </div>
                                    </div>

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Notes</div>
                                        <div class="col-md-9">
                                            <textarea name="notes" rows="4"></textarea>
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="assign_stock" value="Submit" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 9 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Group</h1>
                            </div>
                            <div class="block-fluid">
                                <form method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Name:</div>
                                        <div class="col-md-9">
                                            <input class="form-control" type="text" name="use_group" id="use_group" />
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_use_group" value="Add Use Group" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 10 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Use Case</h1>
                            </div>
                            <div class="block-fluid">
                                <form method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Name:</div>
                                        <div class="col-md-9">
                                            <input class="form-control" type="text" name="use_case" id="use_case" />
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_use_case" value="Add Use Case" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 11 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Groups Location</h1>
                            </div>
                            <div class="block-fluid">
                                <form method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Name:</div>
                                        <div class="col-md-9">
                                            <input class="form-control" type="text" name="location" id="location" />
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_location" value="Add Group Location" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } elseif ($_GET['id'] == 12 && $user->data()->position == 1) { ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Add Maintainance Type</h1>
                            </div>
                            <div class="block-fluid">
                                <form method="post">

                                    <div class="row-form clearfix">
                                        <div class="col-md-3">Name Type:</div>
                                        <div class="col-md-9">
                                            <input class="form-control" type="text" name="maintainance_type" id="maintainance_type" />
                                        </div>
                                    </div>

                                    <div class="footer tar">
                                        <input type="submit" name="add_maintainance_type" value="Add Maintainance Type" class="btn btn-default">
                                    </div>

                                </form>
                            </div>

                        </div>
                    <?php } ?>
                    <div class="dr"><span></span></div>
                </div>

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
        $(document).ready(function() {
            $('#fl_wait').hide();
            $('#wait_ds').hide();
            $('#ld_staff').hide();
            $('#ld_batch').hide();
            $('#ld_stf').hide();
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
            $('#study').change(function() {
                var getUid = $(this).val();
                $('#ld_batch').show();
                $.ajax({
                    url: "process.php?cnt=a_study",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#batch').html(data);
                        $('#ld_batch').hide();
                    }
                });

            });
            $('#batch').change(function() {
                var getUid = $(this).val();
                $('#ld_staff').show();
                $.ajax({
                    url: "process.php?cnt=a_batch",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#desc').html(data);
                        $('#ld_staff').hide();
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
            $('#a_cc').change(function() {
                var getUid = $(this).val();
                $('#wait').show();
                $.ajax({
                    url: "process.php?cnt=payAc",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#cus_acc').html(data);
                        $('#wait').hide();
                    }
                });

            });
            $('#study_id').change(function() {
                var getUid = $(this).val();
                $('#fl_wait').show();
                $.ajax({
                    url: "process.php?cnt=study",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#s2_2').html(data);
                        $('#fl_wait').hide();
                    }
                });

            });

            $('#study_id').change(function() {
                var getUid = $(this).val();
                $('#fl_wait').show();
                $.ajax({
                    url: "process.php?cnt=study",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#s2_2').html(data);
                        $('#fl_wait').hide();
                    }
                });

            });
        });
    </script>
</body>

</html>