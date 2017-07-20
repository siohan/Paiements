<?php
#CMS - CMS Made Simple
#(c)2004 by Ted Kulp (wishy@users.sf.net)
#This project's homepage is: http://www.cmsmadesimple.org


class paiementsbis
{
  function __construct() {}


##
##

function add_paiement($licence,$ref_action,$module,$nom,$tarif)
{
	global $gCms;
		//$ping = cms_utils::get_module('paiements'); 
	$db = cmsms()->GetDb();
	
	$query = "INSERT INTO ".cms_db_prefix()."module_paiements_paiements (licence,ref_action, module,nom, tarif) VALUES ( ?, ?, ?, ?, ?)";
	$dbresult = $db->Execute($query, array($licence,$ref_action,$module,$nom, $tarif));
	if($dbresult)
	{
		return TRUE;
	}
	else
	{
		$error = $db->ErrorMsg();
		return $error;
	}
}
function montant_tarif($ref_action)
{
	global $gCms;
	$db = cmsms()->GetDb();
	$query = "SELECT tarif FROM ".cms_db_prefix()."module_paiements_paiements WHERE ref_action = ?";
	$dbresult = $db->Execute($query, array($ref_action));
	if($dbresult && $dbresult->recordCount() >0)
	{
		$row = $dbresult->FetchRow();
		$tarif = $row['tarif'];
		return $tarif;
	}
	else
	{
		return FALSE;
	}
	
}
function is_paid($ref_action)
{
	global $gCms;
	$db = cmsms()->GetDb();
	$tarif = $this->montant_tarif($ref_action);
	$query = "SELECT SUM(montant_paiement) AS reglements FROM ".cms_db_prefix()."module_paiements_reglements WHERE ref_action = ?";
	$dbresult = $db->Execute($query, array($ref_action));
	if($dbresult)
	{
		$row = $dbresult->FetchRow();
		$reglements = $row['reglements'];
		
		if(bccomp($tarif,$reglements,2) == 0)//(string)$tarif === (string)$reglements)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
	
}
function montant_cotis($licence)
{
	$db = cmsms()->GetDb();
	$query = "SELECT SUM(tarif) AS montant FROM ".cms_db_prefix()."module_paiements_paiements WHERE module = 'Cotisations' AND licence = ?";
	$dbresult = $db->Execute($query, array($licence));
	if($dbresult)
	{
		$row = $dbresult->FetchRow();
		$montant = $row['montant'];
		return $montant;
	}
}
function cotis_ref_action($licence)
{
	//cette fonction liste les ref_actions pour déterminer le montant des versements des cotis de chq joueur
	$error = 0;	//on instancie un compteur d'erreurs
	$db = cmsms()->GetDb();
	$query = "SELECT ref_action FROM ".cms_db_prefix()."module_paiements_paiements WHERE module = 'Cotisations' AND licence = ?";
	$dbresult = $db->Execute($query, array($licence));
	if($dbresult && $dbresult->RecordCount()>0)
	{
		//on fait un while
		while($row = $dbresult->FetchRow())
		{
			$cotis_paid = $this->is_paid($row['ref_action']);
			if(FALSE === $cotis_paid)
			{
				$error++;
			}
		}
		return $error;
	}
	
}

function restant_du($ref_action)
{
	global $gCms;
	$db = cmsms()->GetDb();
	$query = "SELECT SUM(montant_paiement) AS reglements FROM ".cms_db_prefix()."module_paiements_reglements WHERE ref_action = ?";
	$dbresult = $db->Execute($query, array($ref_action));
	if($dbresult)
	{
		$row = $dbresult->FetchRow();
		$reglements = $row['reglements'];
		return $reglements;
		
		
	}
	else
	{
		return false;
	}
}
function delete_paiement($ref_action)
{
	global $gCms;
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_paiements_paiements WHERE ref_action = ?";
	$dbresult = $db->Execute($query, array($ref_action));
	if($dbresult)
	{
		return true;		
	}
	else
	{
		return false;
	}
}
function delete_reglements($ref_action)
{
	global $gCms;
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_paiements_reglements WHERE ref_action = ?";
	$dbresult = $db->Execute($query, array($ref_action));
	if($dbresult)
	{
		return true;		
	}
	else
	{
		return false;
	}
}
function delete_reglement($ref_action,$record_id)
{
	global $gCms;
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_paiements_reglements WHERE ref_action = ? AND id = ?";
	$dbresult = $db->Execute($query, array($ref_action,$record_id));
	if($dbresult)
	{
		return true;		
	}
	else
	{
		return false;
	}
}
function nb_reglements($ref_action)
{
	global $gCms;
	$db = cmsms()->GetDb();
	$query = "SELECT count(*) AS nb FROM ".cms_db_prefix()."module_paiements_reglements WHERE ref_action = ?";
	$dbresult = $db->Execute($query, array($ref_action));
	if($dbresult)
	{
		$row = $dbresult->FetchRow();
		$nb = $row['nb'];
		return $nb;
	}
	else
	{
		return false;
	}
}
#
#
#
}//end of class
#
# EOF
#
?>