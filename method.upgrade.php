<?php
#-------------------------------------------------------------------------
# Module: Paiements
# Version: 0.2
# Method: Upgrade
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2008 by Ted Kulp (wishy@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
# The module's homepage is: http://dev.cmsmadesimple.org/projects/skeleton/
#
#-------------------------------------------------------------------------

/**
 * For separated methods, you'll always want to start with the following
 * line which check to make sure that method was called from the module
 * API, and that everything's safe to continue:
*/ 
if (!isset($gCms)) exit;

$db = $this->GetDb();			/* @var $db ADOConnection */
$dict = NewDataDictionary($db); 	/* @var $dict ADODB_DataDict */

$now = trim($db->DBTimeStamp(time()), "'");
$current_version = $oldversion;
switch($oldversion)
{
  // we are now 1.0 and want to upgrade to latest
 
	
case "0.1beta" : 	
	
	{
		
		//$dict = NewDataDictionary( $db );

		// table schema description
			
			$sqlarray = $dict->DropColumnSql( cms_db_prefix()."module_paiements_reglements", "date_created");
			$dict->ExecuteSQLArray($sqlarray);
		
	//	$dict = NewDataDictionary( $db );
		
		
			$sqlarray = $dict->AddColumnSql( cms_db_prefix()."module_paiements_reglements", "date_created D");
			$dict->ExecuteSQLArray($sqlarray);

	
		
	}
		
}


// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('upgraded', $this->GetVersion()));

//note: module api handles sending generic event of module upgraded here
?>