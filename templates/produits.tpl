<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{*<p><a href="{module_action_url action='add_edit_produit'}">{admin_icon icon='newobject'} Ajouter une recette</a></p>*}
{if $itemcount > 0}

<table cellpadding="0" class="pagetable cms_sortable tablesorter" id="articlelist">
 <thead>
	<tr>
		<th>Date</th>
		<th>Nom</th>
		<th>Description</th>
		<th>Référence interne</th>
		<th>Tarif</th>
		<th>Restant</th>
		<th>Réglé ?</th>
		<th>Action(s)</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->date_created}</td>
	<td> {$entry->joueur}</td>
	<td> {$entry->nom}</td>
	<td>{$entry->ref_action}</td>
	<td>{$entry->tarif}</td>
	<td>{$entry->restant_du}</td>
	<td>{$entry->statut}</td>
	<td>{*$entry->editlink*} {$entry->view_reglement} {$entry->add_reglement} | {$entry->relance} {*$entry->delete*}</td>
  </tr>
{/foreach}
 </tbody>
</table>

{/if}

