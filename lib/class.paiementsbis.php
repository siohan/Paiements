<?php 
//namespace paiements;
#CMS - CMS Made Simple
#(c)2004 by Ted Kulp (wishy@users.sf.net)
#This project's homepage is: http://www.cmsmadesimple.org
//namespace asso_paiements;

class paiementsbis
{
  function __construct() {}



##
##
function details_paiement($ref_action)
{
	$db = cmsms()->GetDb();
	$query = "SELECT id,licence,categorie, ref_action,module, nom, tarif, actif, statut, date_created FROM ".cms_db_prefix()."module_paiements_produits WHERE ref_action = ?";
	$dbresult = $db->Execute($query, array($ref_action));
	if($dbresult)
	{
		if($dbresult->RecordCount()>0)
		{
			
			$details = array();
			while ($dbresult && $row = $dbresult->FetchRow())
			{
				$details['id'] = $row['id'];
				$details['ref_action'] = $row['ref_action'];
				$details['categorie'] = $row['categorie'];
				$details['nom'] = $row['nom'];
				$details['tarif'] = $row['tarif'];
				$details['date_created'] = $row['date_created'];
			}
			return $details;
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
function add_paiement($licence,$ref_action,$module,$nom,$tarif)
{
	global $gCms;
		//$ping = cms_utils::get_module('paiements'); 
	$db = cmsms()->GetDb();
	
	$query = "INSERT INTO ".cms_db_prefix()."module_paiements_produits (licence,ref_action, date_created, module,nom, tarif) VALUES ( ?, ?, ?, ?, ?, ?)";
	$dbresult = $db->Execute($query, array($licence,$ref_action,time(),$module,$nom, $tarif));
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
	$query = "SELECT tarif FROM ".cms_db_prefix()."module_paiements_produits WHERE ref_action = ?";
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
//vérifie si une facture est payée
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
		$var = is_null($reglements);
		//var_dump($var);
		if(TRUE === is_null($reglements))
		{
			return false;
		}
		else
		{
			if(bccomp($tarif,$reglements,2) == 0)//(string)$tarif === (string)$reglements)
			{
				return true;
			}
			else
			{
				return false;
			}
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
	$query = "SELECT SUM(tarif) AS montant FROM ".cms_db_prefix()."module_paiements_produits WHERE module = 'Cotisations' AND licence = ?";
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
	$query = "SELECT ref_action FROM ".cms_db_prefix()."module_paiements_produits WHERE module = 'Cotisations' AND licence = ?";
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
			else
			{
				return true;
			}
		}
		
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
	$query = "DELETE FROM ".cms_db_prefix()."module_paiements_produits WHERE ref_action = ?";
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
function liste_categories_produits()
{
	global $gCms;
	$db = cmsms()->GetDb();
	$query = "SELECT categorie FROM ".cms_db_prefix()."module_paiements_cat_produits WHERE actif = 1 ORDER BY ordre ASC";
	$dbresult = $db->Execute($query);
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			$categories = $row['categorie'];
		}
		
	}
	else
	{
		return false;
	}	
}
//cette fonction calcule le restant dû de chaque adhérent
function restant_du_total($ref_action)
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
//solde le reglement total d'une ref_action
function add_reglement_total($ref_action, $montant)
{
	$db = cmsms()->GetDb();
	$query = "INSERT INTO ".cms_db_prefix()."module_paiements_reglements (ref_action,date_created, montant_paiement) VALUES (?, ?, ?)";
	$dbresult = $db->Execute($query, array($ref_action,time(), $montant));
	if(!$dbresult)
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