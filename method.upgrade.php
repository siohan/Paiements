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
	case "0.1.2" : 
	{
		# Mails templates
		$fn = cms_join_path(dirname(__FILE__),'templates','orig_relance_email.tpl');
		if( file_exists( $fn ) )
		{
			$template = file_get_contents( $fn );
			$this->SetTemplate('relance_email',$template);
		}
		# Les préférences 

		$this->SetPreference('admin_email', 'root@localhost.com');
		$this->SetPreference('sujet_relance_email','[T2T] Relance Facture...');
	}
	case "0.1.3" :
	{
		// table schema description
		$flds = "
			id I(11) AUTO KEY,
			nom_moyen C(25),
			description C(125),
			ordre I(11),
			actif I(1) DEFAULT 1";
			$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_paiements_moyens", $flds);
			$dict->ExecuteSQLArray($sqlarray);			
		//
		//on insère qqs valeurs ?
		//
		//On change de nom à la table
		$dict = NewDataDictionary($db);
		$oldTableName = cms_db_prefix()."module_paiements_paiements";
		$newTableName = cms_db_prefix()."module_paiements_produits";
		$sqlarray = $dict->RenameTableSql($oldTableName, $newTableName);
		$dict->ExecuteSQLArray($sqlarray);
		//on rajoute un champ à cette nouvelle table
		$sqlarray = $dict->AddColumnSql( cms_db_prefix()."module_paiements_produits", "categorie I(3)");
		$dict->ExecuteSQLArray($sqlarray); 
		
		//on créé une nouvelle table
		// table schema description
		$flds = "
			id I(11) AUTO KEY,
			licence I(11),
			ref_action C(15),
			categorie I(3),
			module C(100),
			date_created D,
			nom C(150),
			tarif N(6.2) DEFAULT 0.00,
			actif I(1) DEFAULT 1,
			statut I(1)";
			$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_paiements_charges", $flds);
			$dict->ExecuteSQLArray($sqlarray);
			
			//on créé une nouvelle table
		// table schema description
		$flds = "
			id I(11) AUTO KEY,
			libelle C(255),
			actif I(1),
			ordre I(10)";
			$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_paiements_cat_produits", $flds);
			$dict->ExecuteSQLArray($sqlarray);
			//
		$flds = "
		id I(11) AUTO KEY,
		libelle C(255),
		actif I(1),
		ordre I(10)";
		$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_paiements_cat_depenses", $flds);
		$dict->ExecuteSQLArray($sqlarray);
		//Une nouvelle permission
		$this->CreatePermission('Paiements add', 'Paiements : Ajouter un paiement');
		
	}
	case "0.1.4" :
	{
		//
		//on remplace les licences par le genid dans la table produits
		$query = "SELECT adh.genid, be.licence FROM ".cms_db_prefix()."module_adherents_adherents AS adh, ".cms_db_prefix()."module_paiements_produits AS be WHERE adh.licence = be.licence";
		$dbresult = $db->Execute($query);
		if($dbresult)
		{
			while($row = $dbresult->FetchRow())
			{
				$genid = $row['genid'];
				$query2 = "UPDATE ".cms_db_prefix()."module_paiements_produits SET licence = ? WHERE licence = ?";
				$dbresult2 = $db->Execute($query2, array($genid, $row['licence']));
			
			}
		}
		
		//pareil dans la table charges
		//on remplace les licences par le genid dans la table produits
		$query = "SELECT adh.genid, be.licence FROM ".cms_db_prefix()."module_adherents_adherents AS adh, ".cms_db_prefix()."module_paiements_charges AS be WHERE adh.licence = be.licence";
		$dbresult = $db->Execute($query);
		if($dbresult)
		{
			while($row = $dbresult->FetchRow())
			{
				$genid = $row['genid'];
				$query2 = "UPDATE ".cms_db_prefix()."module_paiements_charges SET licence = ? WHERE licence = ?";
				$dbresult2 = $db->Execute($query2, array($genid, $row['licence']));
			
			}
		}
	}
		
}


// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('upgraded', $this->GetVersion()));

//note: module api handles sending generic event of module upgraded here
?>