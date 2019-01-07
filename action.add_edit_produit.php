<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Paiements use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
$edit = 0; //variable pour savoir s'il s'agit d'un ajout ou d'une modification
if(isset($params['record_id']) && $params['record_id'] !="")
{
		$record_id = $params['record_id'];
		$edit = 1;
		$query = "SELECT id,licence,categorie, ref_action,module, nom, tarif, actif, statut, date_created FROM ".cms_db_prefix()."module_paiements_produits WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($record_id));
		$compt = 0;
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			$compt++;
			$id = $row['id'];
			$ref_action = $row['ref_action'];
			$categorie = $row['categorie'];
			$nom = $row['nom'];
			$tarif = $row['tarif'];
			$date_created = $row['date_created'];
		}
}
	//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_edit_produit', $returnid ) );

	if($edit == 1)
	{
		$smarty->assign('record_id',$this->CreateInputHidden($id,'record_id',$record_id));
		
	}
		

	
	$smarty->assign('categorie',
			$this->CreateInputDropdown($id,'categorie',(isset($categorie)?$categorie:"")));
	$smarty->assign('nom',
			$this->CreateInputText($id,'nom',(isset($nom)?$nom:""),50,200));
	$smarty->assign('tarif',
			$this->CreateInputText($id,'tarif',(isset($tarif)?$tarif:""),50,200));			
	$smarty->assign('date_created',
			$this->CreateInputDate($id,'date_created',(isset($date_created)?$date_created:"")));		
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
echo $this->ProcessTemplate('add_edit_produit.tpl');

#
# EOF
#
?>