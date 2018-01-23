Author's Note:
--------------
BEFORE USING ANY CODE IN THESE SCRIPTS, READ THROUGH ALL FILES THOROUGHLY, UNDERSTAND WHAT THE SCRIPTS ARE DOING AND TEST THEIR BEHAVIOR IN AN ISOLATED ENVIRONMENT.  RESEARCH ANY POTENTIAL BUGS IN THE VERSION OF THE SOFTWARE YOU ARE USING THESE SCRIPTS WITH AND UNDERSTAND THAT FEATURE SETS OFTEN CHANGE FROM VERSION TO VERSION OF ANY PLATFORM WHICH MAY DEPRECATE CERTAIN PARTS OF THIS CODE.  ANY INDIVIDUAL CHARGED WITH RESPONSIBILITY IN THE MANAGEMENT OF A SYSTEM RUNS THE RISK OF CAUSING SERVICE DISRUPTIONS AND/OR DATA LOSS WHEN THEY MAKE ANY CHANGES AND SHOULD TAKE THIS DUTY SERIOUSLY AND ALWAYS USE CAUTION.  THIS CODE IS PROVIDED WITHOUT ANY WARRANTY WHATSOEVER AND IS INTENDED FOR EDUCATIONAL PURPOSES.  

Cisco Unified Communications Manager ITL Eraser
=======================================================
These scripts were written to address certain challenges in managing Cisco phones in a Cisco Unified Communications (CUCM) environment.  

The remoteAdmin.php script uses a combination of methods to authenticate to Cisco Unified Communications Manager (CUCM) and demonstrates;

* Authentication to CUCM
* Querying the CUCM for phone information
* Executing commands to Cisco VoIP phones

These are accomplished with PHP, cURL, and Cisco AXL API.

The remoteAdmin.php script was originally written with the intent of automating the deletion of ITL certificates on Cisco phones to accommodate a phone system migration from one cluster to another.  When option 150 on a Cisco voice vlan has been changed from one TFTP server to another, the ITL certificate on the phone must be deleted and the phone reset in order for it to be able to join the new system.

There are two application user accounts that need to be configured in order for the script to work;

* applicationAccountUsername
  * Needs "Standard AXL API", "Standard CCM Admin Users", "Standard CCMADMIN Read Only", and "Standard SERVICEABILITY Read Only" roles.  This account is used to pull IP address information from CUCM and for any other CUCM related tasks in the script.
* phoneControlUsername
  * Needs "Standard CCM End Users" and "Standard CCMUser Administration" roles.  All phones that are to be controlled must be associated with this account.

The method used to query CUCM, pull realtime stats (like IP addresses from phones), and executing commands to remote phones can have many applications beyond deleting certificates.  Many tasks that would typically require close proximity to the phone by a technician can be done from anywhere on the network, which of course can greatly reduce the time needed to solve any user phone issues.

Tested on:
----------
* Debian 7
* PHP 5 (with cURL)
* CUCM 10.5
* Cisco AXL Toolkit (specifically, the AXLAPI.wsdl file)
* An application user account on CUCM with the following privileges
  * Standard AXL API access
  * Standard CCM Admin Users
  * Standard CCMADMIN Read Only
  * Standard Serviceability Read Only
