<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Paiements use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;

if(isset($params['ref_action']) && $params['ref_action'] !="")
{
		$ref_action = $params['ref_action'];
}
$OuiNon = array("Oui"=>"Oui", "Non"=>"Non");
	//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_destockage', $returnid ));

	$smarty->assign('ref_action',
				$this->CreateInputHidden($id,'ref_action',$ref_action));
	$smarty->assign('choix',
			$this->CreateInputDropdown($id,'choix',$OuiNon));
			
			
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
echo $this->ProcessTemplate('destockage.tpl');

#
# EOF
#
?>