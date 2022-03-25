<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
if($_GET['cnt'] == 'region'){
    $districts=$override->get('district','region_id',$_GET['getUid']);?>
<option value="">Select District</option>
    <?php foreach ($districts as $district){?>
        <option value="<?=$district['id']?>"><?=$district['name']?></option>
<?php }}elseif ($_GET['cnt'] == 'district'){
    $wards=$override->get('ward','district_id',$_GET['getUid']);?>
    <option value="">Select Ward</option>
<?php foreach ($wards as $ward){?>
    <option value="<?=$ward['id']?>"><?=$ward['name']?></option>
<?php }}elseif ($_GET['cnt'] == 'download'){ $user->exportData('citizen', 'citizen_data');?>

<?php }elseif ($_GET['cnt'] == 'study'){
    $sts=$override->get('study_files','study_id',$_GET['getUid'])?>
    <option value="">Select File</option>
    <?php foreach ($sts as $st){?>
        <option value="<?=$st['id']?>"><?=$st['name']?></option>
<?php }}elseif ($_GET['cnt'] == 'a_study'){
    $batches=$override->get('batch', 'id', $_GET['getUid']) ?>
    <option value="">Select Batch</option>
    <?php foreach ($batches as $batch){?>
        <option value="<?=$batch['id']?>"><?=$batch['name']?></option>
<?php }}elseif ($_GET['cnt'] == 'a_batch'){?>
    <option value="">Select Staff</option>
    <?php
    $a_batch=$override->get('batch', 'id', $_GET['getUid'])[0];
    $a_study_staff=$override->get('staff_study', 'study_id', $a_batch['study_id'])[0];
    foreach ($a_study_staff as $staff){$stf=$override->get('user','id',$staff['staff_id'])[0]?>
        <option value="<?=$stf['id']?>"><?=$stf['firstname'].' '.$stf['lastname']?></option>
<?php }}?>
