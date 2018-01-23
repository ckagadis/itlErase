<?php
//error_reporting(E_ALL);
//ini_set('display_errors', True);
require('header.php');
?>

<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="style1.css">
  <title>Remote Phone ITL Eraser</title>

  <table>
    <tr>
      <td>


        <table width=50% border='0' align='left'>
          <form method="post" action="/remoteAdmin.php"  enctype="multipart/form-data">
            <tr class='fields'>
              <td width = '50' style='white-space: nowrap' align=left><font color=#696969>Enter Device Name(s).
                Separate each Device Name with a space:&nbsp
                <input type="text" size="70" name="data"></td>
                <td width=10></td>
                <td width = "100"><input type="submit" value="Erase Certs and Reset Phones" name="searchresults"></td>

              </tr>
            </form>
          </table>

<?php

//$_POST['data'] is posted from the form above.  The contents are exploded so that they can be added in the querie information below.

if (isset($_POST[data]))
{
  $macString = $_POST[data];
  $macArray = explode(" ", $macString);
}

//print_r($macArray);

// $username and $passwd are used to pull IP and device information from CUCM.
$username = 'applicationAccountUsername';
$passwd = 'applicationAccountPassword';
$context = stream_context_create(array('ssl' => array('verify_peer' => false, 'allow_self_signed' => true)));
$count = 0;

// USED TO CACHE THIS PAGE SO THAT WE CAN POST TOTAL DEVICE COUNT AT THE TOP.
ob_start();

// LIST OF PHONES PLACED INTO ARRAY THAT WILL BE COMMANDED.
//$deviceList = $macArray;

//$_SESSION['deviceList'] = $deviceList;

// FOR EACH DEVICE IN THE ARRAY, PERFORM THE FOLLOWING COMMANDS.
foreach($macArray as $deviceName)
{
  $soapClient = new SoapClient("https://<ipAddress of CUCM server>:8443/realtimeservice/services/RisPort?wsdl", array('stream_context' => $context, 'trace'=>true, 'login' => $username,'password'=> $passwd));
  $devices = $soapClient->SelectCmDevice("", array('SelectBy'=>'Name','Status'=>'Any','SelectItems'=>array('SelectItem[0]'=>array('Item'=>"$deviceName"))));

  //var_dump($devices);

  foreach($devices as $first)
  {
    if( is_array($first->CmNodes) )
    {
      $CmNodes = $first->CmNodes;
      foreach($CmNodes as $second)
      {
        if( is_array($second->CmDevices) )
        {
          $CmDevices = $second->CmDevices;
          foreach( $CmDevices as $dev)
          {
            //THIS SECTION USED CURL TO PUSH XML TO THE PHONES TO RUN COMMANDS.
            $ch = curl_init();
            $userpwd = 'phoneControlUsername:phoneControlPassword';
            $url = 'http://' . $dev->IpAddress . '/CGI/Execute';
            $encode = array("Content-Type: application/x-www-form-urlencoded");

            $field_string_array = array(
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:Settings'/></CiscoIPPhoneExecute>","","",
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:KeyPadStar'/></CiscoIPPhoneExecute>",
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:KeyPadStar'/></CiscoIPPhoneExecute>",
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:KeyPadPound'/></CiscoIPPhoneExecute>",
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:Directories'/></CiscoIPPhoneExecute>",
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:Settings'/></CiscoIPPhoneExecute>",
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:KeyPad4'/></CiscoIPPhoneExecute>",
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:KeyPad5'/></CiscoIPPhoneExecute>",
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:KeyPad2'/></CiscoIPPhoneExecute>",
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:Soft4'/></CiscoIPPhoneExecute>",
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:Soft2'/></CiscoIPPhoneExecute>","","","","","","","","","","","","","","","","","","","","","","","",
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:KeyPadStar'/></CiscoIPPhoneExecute>",
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:KeyPadStar'/></CiscoIPPhoneExecute>",
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:KeyPadPound'/></CiscoIPPhoneExecute>","","","","","",
              "XML=<CiscoIPPhoneExecute><ExecuteItem Priority='0'
              URL='Key:Directories'/></CiscoIPPhoneExecute>",
            );

            for ($i = 0; $i < 45; $i++)
            {
              curl_setopt($ch, CURLOPT_HTTPHEADER, $encode);
              curl_setopt($ch, CURLOPT_USERPWD, $userpwd);
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string_array[$i]);
              curl_exec($ch);
              sleep(1);
            }

            //CLOSES THE CONNECITON TO THE PHONE ON THIS PASS.
            curl_close($ch);

            //PRINTS INFORMATION ABOUT THE PHONE.
            echo "<table border='0' width=100%><tr><td><b>Device Name</b> " . $dev->Name . "<br>";
            echo "<b>Description</b> " . $dev->Description . "<br>";
            echo "<b>Phone IP Address</b> " . $dev->IpAddress . "<br>";
            echo "<b>Directory Numbers</b> " . $dev->DirNumber . "<br></td></tr></table>";
            $count++;
          }
        }
      }
    }
  }
}

$content = ob_get_clean();

if($count != 0)
{
  echo "</td></tr><tr><td>";
  echo "<table border = '0'><tr><td><b>Total Device Count</b> " . $count . "</tr></td>";
  echo "<tr><td>$content</td></tr></table></td></tr></table>";
}

?>
