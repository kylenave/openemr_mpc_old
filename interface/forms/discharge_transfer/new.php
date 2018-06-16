<?php
/**
 *
 * Copyright (C) 2012-2013 Naina Mohamed <naina@capminds.com> CapMinds Technologies
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Naina Mohamed <naina@capminds.com> modified by Kyle Nave <kyle@atlasrevenue.com>
 * @link    http://www.open-emr.org
 */
//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/forms.inc");
require_once("$srcdir/options.inc.php");

formHeader("Form:Discharge Transfer");

$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
$formid = 0 + (isset($_GET['id']) ? $_GET['id'] : '');
$transferData = $formid ? formFetch("form_discharge_transfer", $formid) : array();

if (is_numeric($pid)) 
{
	$result = getPatientData($pid, "*");
	$_patient_name=text(($result['fname'])." ".($result['lname']));
	$_dob=text($result['DOB']);	


	$insuranceData = getInsuranceData($pid);
	$_policy_number=text($insuranceData['policy_number']);

	$patient_encounters = getEncounters($pid);
	$_encounter_count = text(count($patient_encounters));
	$_first_encounter = $patient_encounters[0]['encounter'];
	$_last_encounter = end($patient_encounters)['encounter'];

	$_start_date = text(getEncounterDateByEncounter($_first_encounter)['date']);
	$_end_date = text(getEncounterDateByEncounter($_last_encounter)['date']);

	$_provider = getProviderInfo(getProviderIdOfEncounter($_last_encounter))[0];
	$_provider_name = text(($_provider['fname'])." ".($_provider['lname']));
}
?>

<html>
<head>
<?php html_header_show();?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js">  </script>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">  </head>

<body class="body_top">
<p align="center">
<span class="forms-title">
	<?php echo xlt('Discharge / Transition Summary'); ?>
</span>

<?php
echo "
	<form method='post' name='my_form' " .
	"action='$rootdir/forms/discharge_transfer/save.php?id=" . attr($formid) ."'>
	\n";
?>

<table align="center" style="text-align: left; width: 70%;" border="0"
	cellpadding="2" cellspacing="2">
	<tbody>
	<tr>
		<td style="text-align: center;"><input checked="checked"
			name="DischargeCheck" value="1" type="checkbox">Discharge</td>
		<td style="text-align: center;">&nbsp;<input
			name="TransitionCheck" value="2" type="checkbox">
			Transition</td>
	</tr>
	</tbody>
</table>

</p>
</br>
	<!-- Start here for formatting form -->
	<table  align="center" border="0">
		<tr>

			<td class="forms">
				<b><?php echo xlt('Client Name' ); ?>:</b>
				<label class="forms-data">

					<?php echo $_patient_name; ?>
				</label>

				<input type="hidden" name="client_name" value="<?php echo attr($_patient_name);?>">
			</td>

			<td class="forms"> <b><?php echo xlt('DOB'); ?> : </b>

				<label class="forms-data">
					<?php echo $_dob; ?>
				</label>
				<input type="hidden" name="DOB" value="<?php echo attr($_dob);?>">
			</td>
		</tr>
		<tr>

                        <td class="forms">
                                <b><?php echo xlt('Policy #' ); ?>:</b>
                                <label class="forms-data">
                                        <?php echo $_policy_number; ?>
                                </label>

                                <input type="hidden" name="policy_number" value="<?php echo attr($_policy_number);?>">
                        </td>

                        <td class="forms">
                                <b><?php echo xlt('Provider Name' ); ?>:</b>
                                <label class="forms-data">
                                        <?php echo $_provider_name; ?>
                                </label>

                                <input type="hidden" name="provider_name" value="<?php echo attr($_provider_name);?>">
                        </td>

		</tr>

		<tr>
			<td class="forms">
				<b><?php echo xlt('Start Date' ); ?>:</b>
				<label class="forms-data">

					<?php echo $_start_date; ?>
				</label>

				<input type="hidden" name="start_date" value="<?php echo attr($_start_date);?>">
			</td>

			<td class="forms"> 
				<b><?php echo xlt('End Date'); ?> :</b>

				<label class="forms-data">
					<?php echo $_end_date; ?>
				</label>
				<input type="hidden" name="end_date" value="<?php echo attr($_end_date);?>">
			</td>
		</tr>

		<tr>
			<td align="left" colspan="3" style="padding-bottom:7px;">  </td>
		</tr>

		<tr>
			<td align="left" class="forms">
<b>
<?php echo xlt('Reason for Admission'); ?>:
</b>
			</td>
			<td colspan="3">
				<textarea name="reason_for_admission" rows="3" cols="60" wrap="virtual name">
<?php echo text($transferData{"reason_for_admission"});?>
</textarea>
			</td>
		</tr>

		<tr>
			<td align="left" colspan="3" style="padding-bottom:7px;">  </td>
		</tr>

		<tr>
			<td align="left" class="forms">
				<b>
<?php echo xlt('Reason for Discharge/Transition'); ?>:
				</b>
			</td>
			<td colspan="3">
				<textarea name="reason_for_discharge" rows="3" cols="60" wrap="virtual name">
<?php echo text($transferData{"reason_for_discharge"});?>
</textarea>
			</td>
		</tr>

		<tr>
			<td align="left" colspan="3" style="padding-bottom:7px;">  </td>
		</tr>

		<tr>

			<td align="left" class="forms">
				<b>
				<?php echo xlt('New Provider Name'); ?>: <br>
				<?php echo "(".xlt('Agency if Applicable').")"; ?>
				:
				</b>
			</td>

			<td class="forms">
				<input type="text" name="transfer_to" id="transfer_to" size="60"
value="<?php echo text($transferData{"transfer_to"});?>">
</td>

		</tr>

		<tr>
			<td align="left" colspan="3" style="padding-bottom:7px;"></td>
		</tr>

		<tr>
			<td align="left" class="forms">
				<b>
				<?php echo xlt('Overall Status Towards Goal')."(0 - 10)"; ?>:
				</b>
			</td>
			<td class="forms">
<input type="text" name="progress" id="progress" size="2" 
value="<?php echo text($transferData{"progress"});?>">
</td>
		</tr>

		<tr>
			<td align="left" colspan="3" style="padding-bottom:7px;">  </td>
		</tr>

		<tr>
			<td align="left" class="forms">
				<b>
					<?php echo xlt('Comments on Progress'); ?>
					:
				</b>
			</td>
			<td colspan="3">
				<textarea name="comment_on_progress" rows="3" cols="60" wrap="virtual name">
<?php echo text($transferData{"comment_on_progress"});?>
</textarea>
			</td>
		</tr>
		<tr>
			<td align="left" colspan="3" style="padding-bottom:7px;">
			</td>
		</tr>
		<tr>
			<td align="left" class="forms">
				<b>
					<?php echo xlt('Areas of Concern'); ?>:
				</b>
			</td>
			<td colspan="3">
				<textarea name="areas_of_concern" rows="3" cols="60" wrap="virtual name">
<?php echo text($transferData{"areas_of_concern"});?>
</textarea>
			</td>
		</tr>

		<tr>
			<td align="left" class="forms">
				<b>
				<?php echo xlt('Family Level of Participation')." (0 - 10)"; ?>:
				</b>
			</td>
			<td class="forms">
				<input type="text" name="family_participation" id="family_participation" size="2" 
value="<?php echo text($transferData{"family_participation"});?>">
</td>
		</tr>

		<tr>
			<td align="left" colspan="3" style="padding-bottom:7px;">  </td>
		</tr>

		<tr>
			<td align="left" colspan="3" style="padding-bottom:14px;">
			</td>
		</tr>
		<tr>
			<td align="left" class="forms">
				<b>
					<?php echo xlt('Areas of Growth'); ?>:
				</b>
			</td>

			<td colspan="3">
				<textarea name="family_areas_of_growth" rows="3" cols="60" wrap="virtual name">
<?php echo text($transferData{"family_areas_of_growth"});?>
</textarea>
			</td>
		</tr>

		<tr>
			<td align="left" colspan="3" style="padding-bottom:7px;"></td>
		</tr>

		<tr>
			<td align="left" class="forms" >
<b>
Placement at Time of Discharge:
</b>
</td>
			<td align="left" colspan="3">
<select name="Placement">
<option value="1" selected="selected">Home of
Natural Parent w/Services</option>
<option value="2">Home of Natural Parent w/out Services</option>
<option value="3">Home of Relative</option>
<option value="4">Therapeutic Foster Home</option>
<option value="5">Hospital</option>
<option value="6">PTRF</option>
<option value="7">DJJ</option>
<option>Other</option>
</select>

			</td>
		</tr>

		<tr>
			<td align="left" colspan="3" style="padding-bottom:40px;"></td>
		</tr>
	</table>

<p align="center" id="signature" style="visibility:hidden">
Signature:_______________________________________ Date:______________
</p>
<p align="center">
				<input type='submit' id="submitbutton"  value='<?php echo xlt('Save');?>' class="button-css">
				&nbsp;
				<input type='button' id="printpagebutton"  value="Print" onclick="printpage()" class="button-css">
				&nbsp;
				<input type='button' id="cancelbutton" class="button-css" value='<?php echo xlt('Cancel');?>' 
				onclick="top.restoreSession();location='<?php echo "$rootdir/patient_file/encounter/$returnurl" ?>'" />
</P>
</form>

<script type="text/javascript">

function printpage() {
	//Get the print button and put it into a variable
	var printButton = document.getElementById("printpagebutton");
	var cancelButton = document.getElementById("submitbutton");
	var submitButton = document.getElementById("cancelbutton");
	var menubar = document.getElementById("encountermenu");
	var signature = document.getElementById("signature");
	//Set the print button visibility to 'hidden' 
	printButton.style.visibility = 'hidden';
	submitButton.style.visibility = 'hidden';
	cancelButton.style.visibility = 'hidden';
	menubar.style.visibility = 'hidden';
	signature.style.visibility = 'visible';
	//Print the page content
	window.print()
		//Set the print button to 'visible' again 
		//[Delete this line if you want it to stay hidden after printing]
		printButton.style.visibility = 'visible';
		submitButton.style.visibility = 'visible';
		cancelButton.style.visibility = 'visible';
		menubar.style.visibility = 'visible';
		signature.style.visibility = 'hidden';
}
</script>
<?php
formFooter();
?>

