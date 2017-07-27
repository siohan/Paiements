<?php
#-------------------------------------------------------------------------
# Module: Paiements
# Version: 0.1, Claude SIOHAN Agi webconseil
# Method: Install
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


/** 
 * After this, the code is identical to the code that would otherwise be
 * wrapped in the Install() method in the module body.
 */

$db = $gCms->GetDb();

// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	licence I(11),
	ref_action C(15),
	module C(100),
	date_created D,
	nom C(150),
	tarif N(6.2) DEFAULT 0.00,
	actif I(1) DEFAULT 1,
	statut I(1)";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_paiements_paiements", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//
// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	ref_action C(15),
	date_created T,
	montant_paiement N(6.2) DEFAULT 0.00,
	moyen_paiement C(15),
	statut I(1) DEFAULT 0,
	commentaires C(255)";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_paiements_reglements", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//

//Permissions
$this->CreatePermission('Paiements use', 'Paiements : utiliser le module');



// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('installed', $this->GetVersion()) );

	
	      
?>