<?php

if(!isset($gCms)) exit;
//on vérifie les permissions
if(!$this->CheckPermission('Paiements use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$db =& $this->GetDb();
global $themeObject;
$aujourdhui = date('Y-m-d');

$paiements_ops = new paiementsbis();
if(isset($params['obj']) && $params['obj'] != '')
{
	$obj = $params['obj'];
}

switch($obj)
{
	case "delete_reglement":
		if(isset($params['ref_action']) && $params['ref_action'] != '')
		{
			$ref_action = $params['ref_action'];
		}
		if(isset($params['record_id']) && $params['record_id'] != '')
		{
				$record_id = $params['record_id'];
		}
		$delete_reglement = $paiements_ops->delete_reglement($ref_action, $record_id);
		if(TRUE === $delete_reglement)
		{
			$this->SetMessage('Règlement supprimé');
		}
		else
		{
			$this->SetMessage('Un problème est survenu !');
		}
		$this->Redirect($id, 'view_reglements', $returnid, array("record_id"=>$ref_action));
		
	break;
	
	
	
}

//$this->RedirectToAdminTab('adherents');

?>