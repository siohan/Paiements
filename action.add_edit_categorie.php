<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Paiements use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
$edit = 0; //variable pour savoir s'il s'agit d'un ajout ou d'une modification
if(isset($params['objet']) && $params['objet'] != '')
{
	$objet = $params['objet'];
}
else
{
	//on redirige car cet élément est obligatoire
	$this->SetMessage('recette ou dépense ?');
	if($objet =='recette')
	{
		$this->RedirectToAdminTab('produits');
	}
	else
	{
		$this->RedirectToAdminTab('charges');
	}
}
if(isset($params['record_id']) && $params['record_id'] !="")
{
		$record_id = $params['record_id'];
		$edit = 1;
		if($objet == 'recette')
		{
			$query = "SELECT id,libelle, actif, ordre FROM ".cms_db_prefix()."module_paiements_cat_produits WHERE ref_action = ?";
		}
		else
		{
			$query = "SELECT id,libelle, actif, ordre FROM ".cms_db_prefix()."module_paiements_cat_charges WHERE ref_action = ?";
		}
		$dbresult = $db->Execute($query, array($record_id));
		$compt = 0;
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			$compt++;
			$id = $row['id'];
			$libelle = $row['libelle'];
			$actif = $row['actif'];
			$ordre = $row['ordre'];
		}
}
	//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_edit_categorie', $returnid ) );

	if($edit == 1)
	{
		$smarty->assign('record_id',$this->CreateInputHidden($id,'record_id',$record_id));
		
	}
		
	$smarty->assign('objet', $objet);
	
	$smarty->assign('categorie',
			$this->CreateInputText($id,'categorie',(isset($categorie)?$categorie:""), 15, 150));
	$smarty->assign('libelle',
			$this->CreateInputText($id,'libelle',(isset($libelle)?$libelle:""),50,200));
	$smarty->assign('actif',
			$this->CreateInputText($id,'actif',(isset($actif)?$actif:""),50,200));			
	$smarty->assign('ordre',
			$this->CreateInputText($id,'ordre',(isset($ordre)?$ordre:""),50,200));			
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
echo $this->ProcessTemplate('add_edit_categorie.tpl');

#
# EOF
#
?>