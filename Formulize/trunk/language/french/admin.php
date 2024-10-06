<?php
/*mod Language for admin side, by François T*/

/*mod Language for applications*/
define("_AM_APP_APPLICATION","Regroupement: ");
define("_AM_APP_SETTINGS","Reglages");
define("_AM_APP_FORMS","Formulaires");
define("_AM_APP_FORM","Formulaire: ");
define("_AM_APP_RELATIONSHIPS","Inter Relations");
define("_AM_APP_FORMWITHNOAPP","Formulaire(s) hors d'un regroupement");
define("_AM_APP_RELATIONSHIPS_CREATE","Créer une nouvelle Inter Relation entre des formulaires");
define("_AM_APP_RELATIONSHIPS_DELETE_CONFIRM","Etes vous sur de vouloir effacer cette inter relation et tous ses liens?");
define("_AM_APP_SCREENS","Ecrans");
define("_AM_APP_NEWFORM","Nouveau Formulaire");
define("_AM_APP_USETITLE","Utiliser le titre du formulaire");
define("_AM_APP_NAMEQUESTION","Quel est le nom de ce regroupement?");
define("_AM_APP_DESCQUESTION","Description de ce regroupement:");
define("_AM_APP_FORMSIN","Formulaire(s) dans ce regroupement:");
define("_AM_APP_CONFIGURE","Configurer");
define("_AM_APP_VIEW_DEFAULT_SCREEN","Voir (avec les options du screen par défaut)");
define("_AM_APP_VIEW_OPTIONS_SCREEN","Voir (avec toutes les options du screen activées)");
define("_AM_APP_CLONE_SIMPLY","Cloner");
define("_AM_APP_CLONE_WITHDATA","Cloner avec les données");
define("_AM_APP_LOCKDOWN","Verrouiller");
define("_AM_APP_DELETE_FORM","Effacer");
define("_AM_APP_CREATE_NEW_SCREEN","Créer un nouveau screen");
define("_AM_APP_DEFAULTSCREENS","Screens par défaut:");
define("_AM_APP_MORESCREENS","…plus de screens");

/*mod Language for home*/
define("_AM_HOME_PREF"," Préférences du module");
define("_AM_HOME_NEWDATATABLE"," Créer une nouvelle référence dans la base de données");
define("_AM_HOME_MANAGEAPP","Gérer vos regroupements");
define("_AM_HOME_NEWFORM"," Créer un nouveau formulaire");
define("_AM_HOME_CONFIRMDELETEFORM","Etes vous sur de vouloir effacer ce formulaire?  Toutes les données associées à ce formulaire seront perdues.");
define("_AM_HOME_CONFIRMDELETEAPP","Etes vous sur de vouloir effacer ce regroupement?  Tous les formulaires ne seront pas affectés, mais le regroupement n'apparaitra plus dans la liste.");
define("_AM_HOME_CONFIRMLOCKDOWN","Etes vous sur de vouloir verrouiller ce formulaire?  Vous serez alors incapable de changer quoi que ce soit à ce formulaire et vous devrez agir directement dans la base de données pour le déverrouiller.");
define("_AM_HOME_APP_CONFIG","Configurer ce regroupement ainsi que les inter relations entre les formulaires y figurant");
define("_AM_HOME_APP_DELETE","Effacer ce regroupement");
define("_AM_HOME_APP_DESC","Pour mettre un formulaire dans un regroupement, regardez dans les réglages du formulaire.");
define("_AM_HOME_APP_RELATION","Configurer les inter-relations (frameworks) pour ces formulaires");
define("_AM_HOME_GOBACKTO","Retourner sur ");
define("_AM_HOME_SAVECHANGES","Sauver modifications");
define("_AM_HOME_WARNING_UNSAVED","Vous avez des changements non sauvegardés!");




/*mod Language for elements*/
define("_AM_ELE_CONVERT_ML", "Conversion simple ligne en multi lignes.");
define("_AM_ELE_CONVERT_SL", "Conversion multi lignes en simple ligne.");
define("_AM_ELE_CONVERT_CB", "Conversion en cases à cocher.");
define("_AM_ELE_CONVERT_RB", "Conversion en boutons radios.");
define("_AM_ELE_ADDINGTOFORM","Ajout d'éléments au formulaire");
define("_AM_ELE_MANAGINGELEFORM","Gestion des éléments du formulaire");
define("_AM_ELE_CLICKTOADD","Cliquez sur le nom d'un élément pour l'ajouter");
define("_AM_ELE_CLICKDRAGANDDROP","Cliquez sur un élément et utilisez le glisser-déposer pour réorganiser");
define("_AM_ELE_MLTEXT", "Aire de texte multi-lignes.");
define("_AM_ELE_DROPDORLIST", "(Menus déroulants ou listes)");
define("_AM_ELE_SELECTEXPLAIN","Boîte de sélection (menus déroulants et listes)");
define("_AM_ELE_DATEBOX","Boîte de sélection de la date");
define("_AM_ELE_SUBFORMEXPLAIN", "Sous formulaire (un autre formulaire ayant une relation établie avec celui ci)");
define("_AM_ELE_NAMEANDSETTINGS","Nom & Configuration");
define("_AM_ELE_DISPLAYSETTINGS","Afficher la Configuration");
define("_AM_ELE_LINKSELECTEDABOVE", "Utiliser les champs liés sélectionnés ci-dessus");
define("_AM_ELE_VALUEINLIST", "Utiliser les valeurs affichées dans la liste");
define("_AM_ELE_LINKFIELD_ITSELF", "Utiliser le champ lié lui même (ordre alphabétique)");
define("_AM_CONVERT_RB_CB", "Convertir ce bouton radio en cases à cocher?");
define("_AM_CONVERT_CB_RB", "Convertir cette case à cocher en bouton radio?");

/*mod Language for form*/
define("_FORM_DELETE_TEXT_CONFIRM", "Êtes vous sur de vouloir effacer ce formulaire? Toutes les données y étant associées seront perdues.");
define("_AM_FORM_CREATE","Créer un nouveau formulaire");
define("_AM_FORM_CREATE_EXPLAIN","Pour ranger le formulaire dans un regroupement (catégorie), jetez un oeil à l'onglet Réglages lorsque vous configurez ce formulaire.");
define("_AM_FORM_SCREEN","Ecran: ");
define("_AM_FORM_SCREEN_TEXT","Text");
define("_AM_FORM_SCREEN_PAGES","Pages");
define("_AM_FORM_SCREEN_ENTRIES_DISPLAY","Entrées à afficher");
define("_AM_FORM_SCREEN_HEADINGS_INTERFACE","En-tête et Interface");
define("_AM_FORM_SCREEN_ACTION_BUTTONS","Boutons actions");
define("_AM_FORM_SCREEN_CUSTOM_BUTTONS","Boutons personnalisés");
define("_AM_FORM_SCREEN_TEMPLATES","Mise en page");
define("_AM_SETTINGS_FORM_TITLE_QUESTION","Quel est le nom de ce formulaire?");
define("_AM_SETTINGS_FORM_TITLE","Titre du formulaire: ");
define("_AM_SETTINGS_MENU_ENTRY","Nom dans le menu: ");
define("_AM_SETTINGS_MENU_LEAVE","Laissez le champ ci dessus vide pour enlever ce formulaire du bloc de menu");
define("_AM_SETTINGS_FORM_HANDLE","Gestion du Formulaire");
define("_AM_SETTINGS_FORM_HANDLE_EXPLAIN","Optionnel. Le nom que vous définirez ici servira à appeler ce formulaire depuis la base de données dans les programmes de codage. Le réglage par défaut est le numéro ID du formulaire.");
define("_AM_SETTINGS_FORM_DATABASE","Vers quelle table de la base de données doit pointer ce formulaire?");
define("_AM_SETTINGS_FORM_DATABASE_EXPLAIN","Tapez le nom exact, incluant le préfixe, par exemple: mysite_groups");
define("_AM_SETTINGS_FORM_ENTRIES_ALLOWED","Combien d'entrées sont autorisées dans ce formulaire?");
define("_AM_SETTINGS_FORM_ENTRIES_ONEPERGROUP","Une entrée par <b>groupe</b>");
define("_AM_SETTINGS_FORM_ENTRIES_ONEPERUSER","Une entrée par <b>utilisateur</b>");
define("_AM_SETTINGS_FORM_ENTRIES_MORETHANONE","<b>Plus d'une entrée</b> par utilisateur");
define("_AM_SETTINGS_FORM_SHOWING_LIST","Quand la liste des entrées s'affiche pour ce formulaire, quels éléments souhaitez vous afficher par défaut?");
define("_AM_SETTINGS_FORM_APP_PART","De quel regroupement ce formulaire fait-il parti?");
define("_AM_SETTINGS_FORM_APPNEW","Créer un nouveau regroupement dont ce formulaire fera parti?");

/*mod Language for permissions*/
define("_AM_PERMISSIONS_CHOOSE_GROUPS","De quel(s) groupe(s) voulez vous définir les permissions?");
define("_AM_PERMISSIONS_LIST_GROUPS","Lister les groupes alphabétiquement ou dans l'ordre de leur création?");
define("_AM_PERMISSIONS_LIST_ALPHA","Alphabétiquement");
define("_AM_PERMISSIONS_LIST_CREATION","Ordre de création");
define("_AM_PERMISSIONS_LIST_ONCE","Sélectionnez en une fois une liste de groupe");
define("_AM_PERMISSIONS_LIST_SAVE","Sauver ces groupes comme une liste");
define("_AM_PERMISSIONS_LIST_REMOVE","Enlever la liste sélectionnée");
define("_AM_PERMISSIONS_SAME_CHECKBOX","Cocher la même case pour tous les groupes?");
define("_AM_PERMISSIONS_SAME_CHECKBOX_YES","Oui, quand je coche une case pour un groupe, la cocher pour tous les autres groupes");
define("_AM_PERMISSIONS_SAME_CHECKBOX_NO","Non, je définirai chaque groupe individuellement");
define("_AM_PERMISSIONS_SAME_CHECKBOX_EXPLAIN","Vous pouvez changer ce paramètre à tout moment lorsque vous éditez les permissions. Mettez le sur <b>Oui</b>, pour rapidement affecter une même permission à tous les groupes en même temps. Modifiez la en <b>Non</b> lorsque vous souhaitez n'affecter une permission qu'à certains groupes.");
define("_AM_PERMISSIONS_SELECT_GROUP","Sélectionnez des groupes pour voir leurs permissions");
define("_AM_PERMISSIONS_DEFINE_BASIC","Les basiques:");
define("_AM_PERMISSIONS_DEFINE_VIEWFORM","Voir le formulaire");
define("_AM_PERMISSIONS_DEFINE_CREATEOWNENTRIES","Créer sa propre entrée dans le formulaire");
define("_AM_PERMISSIONS_DEFINE_UPDATEOWNENTRIES","Mettre à jour <i>ses entrées</i>");
define("_AM_PERMISSIONS_DEFINE_UPDATEOTHERENTRIES","Mettre à jour les <i>entrées des autres</i>");
define("_AM_PERMISSIONS_DEFINE_DELETEOWNENTRIES","Effacer <i>ses entrées</i>");
define("_AM_PERMISSIONS_DEFINE_DELETEOTHERENTRIES","Effacer <i>les entrées des autres</i>");
define("_AM_PERMISSIONS_DEFINE_VISIBILITY","Visibilité:");
define("_AM_PERMISSIONS_DEFINE_VISIBILITY_PRIVATE","Voir les éléments du formulaire marqués comme 'privés'");
define("_AM_PERMISSIONS_DEFINE_VISIBILITY_THEIROWN","Voir leurs propres entrées (toujours actif)");
define("_AM_PERMISSIONS_DEFINE_VISIBILITY_VIEWALL","Voir les entrées de tous les utilisateurs dans tous les groupes");
define("_AM_PERMISSIONS_DEFINE_VISIBILITY_VIEWOTHERGROUPONLY","Voir seulement les entrées des utilisateurs appartenant à ce(s) groupe(s):");
define("_AM_PERMISSIONS_DEFINE_VISIBILITY_VIEWOTHERGROUPISAMEMEBER","Tous les groupes qui peuvent voir ce formulaire et dont l'utilisateur est membre");
define("_AM_PERMISSIONS_DEFINE_VISIBILITY_DISABLED","désactivé");
define("_AM_PERMISSIONS_DEFINE_VISIBILITY_CONDITIONS","Voir seulement les entrées rencontrant ces conditions:");
define("_AM_PERMISSIONS_DEFINE_VIEW_CONDITIONS","Publier les 'Vues sauvegardées' des entrées du formulaire:");
define("_AM_PERMISSIONS_DEFINE_VIEW_THEIROWN","Créer, mettre à jour, effacer leurs propres vues 'sauvegardées' (toujours actif)");
define("_AM_PERMISSIONS_DEFINE_VIEW_INTHEIR","Publier les 'vues sauvegardées' pour les autres utilisateurs <i>dans leur(s) groupe(s)</i>");
define("_AM_PERMISSIONS_DEFINE_VIEW_FOROTHER","Publier les 'vues sauvegardées' pour les autres utilisateurs <i>dans n'importe quel(s) groupe(s)</i>");
define("_AM_PERMISSIONS_DEFINE_VIEW_UPDATE","Mettre à jour les 'vues sauvegardées' que d'autres personnes ont publiées");
define("_AM_PERMISSIONS_DEFINE_VIEW_DELETE","Effacer les 'vues sauvegardées' que d'autres personnes ont publiées");
define("_AM_PERMISSIONS_ADVANCED","Options avancées:");
define("_AM_PERMISSIONS_ADVANCED_IMPORT","Importer les données d'une feuille de calcul");
define("_AM_PERMISSIONS_ADVANCED_NOTIFICATIONS","Créer des notifications pouvant être envoyées à d'autres utilisateurs");
define("_AM_PERMISSIONS_ADVANCED_CREATEFOROTHER","Créer des entrées au nom d'autres utilisateurs");
define("_AM_PERMISSIONS_ADVANCED_CHANGEOWNER","Changer le propriétaire/créateur d'une entrée existante");
define("_AM_PERMISSIONS_ADVANCED_ALTER","Modifier ces réglages des configurations du formulaire");
define("_AM_PERMISSIONS_ADVANCED_DELETEFORM","Effacer ce formulaire");

/*mod Language for procedures*/
define("_AM_CALC_EXPLAIN","vous permet de créer une série de requêtes ou des suites logiques, qui seront effectuées sur les données que les utilisateurs ont soumis dans le formulaire. Vous pouvez utiliser des procédures pour de complexes, calculs en plusieurs étapes, ou toute autre situation où une seule requête ou seule opération ne suffit pas pour le résultat que vous voulez.");
define("_AM_CALC_CREATE_NEW"," Créer une nouvelle procédure");
define("_AM_CALC_CLONE"," Cloner");
define("_AM_CALC_DELETE"," Effacer");
define("_AM_CALC_CONFIRM_DELETE","Voulez vous vraiment effacer cette procédure?  Tous les réglages pour cette procédure seront perdus!");
define("_AM_CALC_PROCEDURE_NAME","Nom de la Procédure");
define("_AM_CALC_PROCEDURE_DESCR","Description de la Procédure:");
define("_AM_CALC_PROCEDURE_SETTINGS","Réglages de la Procédure: ");
define("_AM_CALC_PROCEDURE_FILTER_CLONE"," Cloner ce filtre et les options de groupe");
define("_AM_CALC_PROCEDURE_FILTER_DELETE"," Effacer ce filtre et les options de groupe");

/*mod Language for screens*/
define("_AM_SCREEN_EXPLAIN","<p><i>Screens</i> vous permet de montrer aux utilisateurs des versions différentes du formulaire, et des entrées qui ont été faites dans celui ci.  Un screen peut être une liste d'entrées, une autre peut être un panneau de contrôle pour les administrateurs qui peuvent avec celui ci effacer ou modifier facilement les entrées, un autre encore peut être une version du formulaire en plusieurs pages.  Vous pouvez avoir autant de screen différents, que vous le souhaitez, tous basés sur le même formulaire au départ.</p>
	<p>Chaque screen a sa propre URL, et peut être inclu dans n'importe quelle page ou élément de navigation de votre site. Les Screens peuvent aussi être incorporés dans une page PHP, n'importe où sur votre serveur, et même à l'intérieur d'un autre logiciel ou CMS comme Wordpress ou Drupal.  Voyez la page des <i>Réglages</i> de chaque screen pour les details.</p>
	<p>Lorsque quelqu'un visite un formulaire, mais qu'aucun screen spécifique n'a été défini, l'utilisateur aura accés à la liste des entrées par défaut, ou le screen par défaut du formulaire.  Formulize q'occupe de gérer ce que l'utilisateur peut voir, en fonction des permissions du formulaire que vous avez configurées.</p><br>");
define("_AM_SCREEN_CREATE"," Créer un nouveau Screen");
define("_AM_SCREEN_FORMSCREENS","Screen(s) du formulaire");
define("_AM_SCREEN_LISTSCREENS","Screen(s) de la liste des entrées");
define("_AM_SCREEN_DELETESCREENS","Etes vous sur de vouloir effacer ce screen? Tous les réglages de configuration seront perdus!");













/*end mod Language for admin side, by François T*/

define("_AM_ACTIVE","actif");
define("_AM_ADD","Ajout");
define("_AM_ADDMENUITEM","Ajout d'élément de menu");
define("_AM_ALL","tous les utilisateurs");
define("_AM_BOLD","gras");
define("_AM_CANCEL", "Annuler");
define("_AM_CATGENERAL", "Catégorie principale");
define("_AM_CATSHORT", "Categorie");
define("_AM_CHANGEMENUITEM","Modification/suppression d'élément");
define("_AM_CLEAR_DEFAULT", "Nettoyer les valeurs par défaut");
define("_AM_CONFIRM_DELCAT", "Vous allez effacer une catégorie du menu!  Merci de confirmer.");
define("_AM_CONVERT", "Convertir");
define("_AM_CONVERT_CONFIRM", "Voulez vous convertir cette boite de texte de simple ligne à multi-lignes (ou vice et versa)?");
define("_AM_CONVERT_HELP", "Convertir cette boite de texte de simple ligne à multi lignes (ou vice versa)");
define("_AM_COPIED","copier %s");
define("_AM_DBUPDATED","Base de données mise à jour avec succès!");
define("_AM_DELETE","Suppression");
define("_AM_DELETEMENUITEM","Suppression d'élément de menu");
define("_AM_EDIT","Edition");
define("_AM_EDITMENUITEM","Edition d'élément");
define("_AM_EDIT_ELEMENTS", "Edition des éléments du formulaire");
define("_AM_ELE_ADD_OPT","Ajout d'options %s");
define("_AM_ELE_ADD_OPT_SUBMIT","Ajouter");
define("_AM_ELE_BLEU","Bleu");
define("_AM_ELE_CANNOT_CONVERT", "Il n'y a pas d'option de conversion pour ce type d'élément");
define("_AM_ELE_CAPTION_DESC","<br /></b>{SEPAR} permet de ne pas afficher le nom de l'élément");
define("_AM_ELE_CHECK","Cases à cocher");
define("_AM_ELE_CHECKED","Vérifié");
define("_AM_ELE_CLR","en couleur");
define("_AM_ELE_COLHEAD","En tête de colonne (optionnel)");
define("_AM_ELE_COLHEAD_HELP","Si vous spécifiez un en-tête de colonne, alors ce texte sera utilisé à la place du titre de ligne, dans la page de la <b>Liste des Entrées</b>.  C'est trés utile si le titre de ligne est trés long, ou si le formulaire est rempli avec le point de vue d'un utilisateur, et le résultat est vu avec le point de vue d'un examinateur.");
define("_AM_ELE_COLORPICK","Couleur");
define("_AM_ELE_COLS","Colonnes");
define("_AM_ELE_CONFIRM_DELETE","Etes vous certain de désirer supprimer cet élément ?");
define("_AM_ELE_CONVERTED_TO_TEXTAREA", "Conversion simple ligne a multi lignes effectuée.");
define("_AM_ELE_CONVERTED_TO_TEXTBOX", "Conversion multi lignes à siumple ligne effectuée.");
define("_AM_ELE_CREATE","Création des éléments");
define("_AM_ELE_CTRE","Centré");
define("_AM_ELE_DATE","Date");
define("_AM_ELE_DEFAULT","Valeur par défaut");
define("_AM_ELE_DELIM_CHOICE",'Delimitation entre chaque option');
define("_AM_ELE_LEFTRIGHT_DESC","Any text or HTML code that you type here will appear on the right beside the caption.  You can use PHP code instead of text or HTML, just make sure it contains '&#36;value = &#36;something;' and Formulize will read this text as PHP code.");
define("_AM_ELE_LEFTRIGHT_TEXT","Contenu du coté droit");
define("_AM_ELE_OPT_CHANGEUSERVALUES", "Lorsque saving changes to these options, also change the values users have made in the form to match the new options (ie: where users had selected the old first option, replace their selection with the new first option instead)");
define("_AM_FORMULIZE_SCREEN_SECURITY", "Utiliser la securité XOOPS sur cet écran?");
define("_AM_FORMULIZE_SCREEN_SECURITY_DESC", "The XOOPS security token is a defense against cross-site scripting attacks.  However, it can cause problems if you are using an advanced Ajax-based UI in a List of Entries screen, and possibly other screen types.");

	
	// number options for textboxes
define("_AM_ELE_DERIVED", "Valeur dérivée venant d'autres éléments (calculs...)");
define("_AM_ELE_DERIVED_ADD", "Ajouter à la formule");
define("_AM_ELE_DERIVED_CAP", "Formule pour générer des valeurs dans cet élément");
define("_AM_ELE_DERIVED_DESC", "Selectionner un element ci dessous pour l'ajouter à votre formule.  You can also use element ID numbers or Framework handles in your formula, as long as they are inside double quotes.  The formula can have multiple lines, or steps, and you can use PHP code in the formula.  The last line should be of the format <i>\$value = \$something</i> where \$something is the final number or formula that you want use.<br /><br />Example:<br />\$value = \"Number of hits\" / \"Total shots\" * 100");
define("_AM_ELE_DERIVED_NUMBER_OPTS","Si cette formule produit un nombre ...");	
define("_AM_ELE_DESC","texte descriptif");
define("_AM_ELE_DESC_HELP","quoique vous tapiez ici, cela apparitra comme cette ligne de texte le fait.");
define("_AM_ELE_DETAIL","Détails");
define("_AM_ELE_DISABLED", "Désactiver cet élément pour un groupe?");
define("_AM_ELE_DISPLAY","Afficher");
define("_AM_ELE_EDIT","Edition de l'élément : %s");
define("_AM_ELE_FICH",'Fichier');
define("_AM_ELE_FORM","Eléments du formulaire");
define("_AM_ELE_FORMLINK", "Options liées à un autre formulaire");
define("_AM_ELE_FORMLINK_DESC","Selectionnez un champs d'un autre formulaire et utilisez ces entrées comme option pour la boîte de sélection. (ces paramètres remplaceront tout ceux effectués antérieurement.)");
define("_AM_ELE_FORMLINK_DESC_TEXTBOX","If you select another form element here, then text that users type into this element will be compared with values entered in the other element.  If a match is found then the text users type into this element will be clickable in the \"List of Entries\" screen, and will take users to the matching entry in the other form.");
define("_AM_ELE_FORMLINK_SCOPE", "If the options are linked -- or are {FULLNAMES} or {USERNAMES} -- limit them to values from the groups selected here.");
define("_AM_ELE_FORMLINK_SCOPEFILTER", "If the options are linked -- or are {FULLNAMES} OR {USERNAMES} -- filter them based on these properties of their entry in the source form.");
define("_AM_ELE_FORMLINK_SCOPEFILTER_ADDCON", "Ajouter une autre condition");
define("_AM_ELE_FORMLINK_SCOPEFILTER_ALL", "No filter in effect (select this to clear existing filters).");
define("_AM_ELE_FORMLINK_SCOPEFILTER_CON", "Filter the options based on this/these conditions:");
define("_AM_ELE_FORMLINK_SCOPEFILTER_DESC", "When you link to values in another form, you may wish to limit the values included in the list based on certain properties of the entries in the other form.  For example, if you are linking to the names of tasks in a task form, you might want to list only tasks that are incomplete.  If there's a question in the task form that asks if the task is complete, you could specify a filter like: \"Task is complete = No\".<br><br>If the options are {FULLNAMES} or {USERNAMES}, and you are using a custom profile form in conjunction with the Registration Codes module, you can filter the names based on the profile form.");
define("_AM_ELE_FORMLINK_SCOPEFILTER_REFRESHHINT", "(If the first list here is empty, click the 'Add another condition' button to refresh it.)");
define("_AM_ELE_FORMLINK_SCOPELIMIT_NO", "Utiliser tous ces groupes");
define("_AM_ELE_FORMLINK_SCOPELIMIT_YES", "Utiliser seulement les groupes dont l'utilisateur est actuellement membre");
define("_AM_ELE_FORMLINK_SCOPE_ALL", "Utiliser tous les groupes");
define("_AM_ELE_FORMLINK_SCOPE_DESC", "The groups you pick define the total possible options to be used.  Optionally, you can choose to have the current user's group memberships further limit the options.  In that case, groups you select will be ignored if the current user is not also a member of the group.");
define("_AM_ELE_FORMLINK_TEXTBOX", "Associer les valeurs avec un autre élément du formulaire");
define("_AM_ELE_GRAS","Gras");
define("_AM_ELE_GRID", "Table d'éléments existants(a placer AVANT les éléments qu'il contient)");
define("_AM_ELE_GRID_BACKGROUND", "Ombre d'arrière plan");
define("_AM_ELE_GRID_BACKGROUND_HOR", "Alterner les ombres dans chaque ligne du tableau");
define("_AM_ELE_GRID_BACKGROUND_VER", "Alterner les ombres dans chaque colonne du tableau");
define("_AM_ELE_GRID_COL_CAPTIONS", "Entrez les légendes pour les colonnes de ce tableau");
define("_AM_ELE_GRID_COL_CAPTIONS_DESC", "Each table is a grid of colums and rows.  The top side of the table has one caption in each cell to head each column.  Type in the text you want to use for the captions, separated by commas.  If your captions are long, it may work best visually to put each caption on its own line.");
define("_AM_ELE_GRID_HEADING", "Quel texte doit apparaître dans l'intitulé de ce tableau?");
define("_AM_ELE_GRID_HEADING_NONE", "Pas d'en-tête");
define("_AM_ELE_GRID_HEADING_SIDE", "L'intitulé doit être à coté comme un élément régulier");
define("_AM_ELE_GRID_HEADING_SIDEORTOP", "S'il y a un intitulé, ou doit-il apparaitre?");
define("_AM_ELE_GRID_HEADING_TOP", "L'intitulé devrait être au-dessus de la grille, et la grille aura une largeur de deux colonnes du formulaire");
define("_AM_ELE_GRID_HEADING_USE_CAPTION", "La légende tapée ci dessus");
define("_AM_ELE_GRID_HEADING_USE_FORM", "Le titre du formulaire");
define("_AM_ELE_GRID_ROW_CAPTIONS", "Entrez les légendes pour les lignes de ce tableau");
define("_AM_ELE_GRID_ROW_CAPTIONS_DESC", "Chaque tableau est une grille de colonnes et de rangées. Le côté gauche de la table a une légende dans chaque cellule au début de chaque ligne. Tapez le texte que vous souhaitez utiliser pour les légendes, séparées par des virgules. Si vos légendes sont longues, c'est mieux visuellement de mettre chaque légende sur sa propre ligne.");
define("_AM_ELE_GRID_START", "Choisissez le premier élément qui apparaitra dans le coin en haut à gauche du tableau");
define("_AM_ELE_GRID_START_DESC", "Each table will have a number of elements in it, equal to the rows times the columns.  ie: if you have three rows and four columns, you will have 12 elements in your table.  The first element appears in the upper left corner, and the next element after that appears in the next cell to the right.  Once the end of a row has been reached, the next element appears in the first cell of the next row.  Elements are drawn from the form according to the order currently assigned to them; if you have 12 elements in your table, then the next 11 elements after the first element will be used in your table.  Therefore, make sure all the elements you want to use in tables are consecutively ordered in your form.");
define("_AM_ELE_IB_CLASS","Classe CSS pour la rangée:");
define("_AM_ELE_IB_DESC","La légende ne s'affiche pas. Seul le texte de cette case apparaît sur l'écran, dans une ligne unique couvrant les deux colonnes du formulaire.");
define("_AM_ELE_INSERTBREAK","Contenu HTML pour cette ligne");
define("_AM_ELE_ITALIQ","Italique");
define("_AM_ELE_MAX_LENGTH","Longueur maximale");
define("_AM_ELE_MODIF","Aire de saisie de texte non modifiable");
define("_AM_ELE_MODIF_ONE","Texte a afficher (couvrant toutes les cellules)");
define("_AM_ELE_MULTIPLE","Autoriser la sélection multiple");
define("_AM_ELE_NOM_SEP","Nom de la séparation");
define("_AM_ELE_NUMBER_OPTS","Si un nombre est tapé...");
define("_AM_ELE_NUMBER_OPTS_DEC","Nombre de place pour les décimales:");
define("_AM_ELE_NUMBER_OPTS_DECSEP","Separer les décimales avec ce caractère (ie: '.'):");
define("_AM_ELE_NUMBER_OPTS_DESC","Utiliser cette option pour contrôler comment se comporteront les décimales et chiffres, et comment ils seront affichés sur cet écran.");
define("_AM_ELE_NUMBER_OPTS_PREFIX","Display numbers with this prefix (ie: '$'):");
define("_AM_ELE_NUMBER_OPTS_SEP","Separer les centaines avec ce caractère (ie: ','):");
define("_AM_ELE_OPT","Options");
define("_AM_ELE_OPT_DESC","Cocher la boîte de sélection avec des valeurs par défaut");
define("_AM_ELE_OPT_DESC1","<br />Seule la première sélectionnée sera utilisée si le mode de sélection multiple n'est pas autorisé");
define("_AM_ELE_OPT_DESC2","Choisissez la valeur par défaut en vérifiant les boutons radio");
define("_AM_ELE_OPT_DESC_CHECKBOXES","Tick the check boxes for selecting default values<br>Boxes with no text in them will be ignored when you click <i>Save</i>");
define("_AM_ELE_OPT_UITEXT", "The text visible to the user can be different from what is stored in the database.  This is useful if you want to have numbers saved in the database, but text visible to the user so they can make their selection.  To do this, use the \"pipe\" character (usually above the Enter key) like this:  \"10|It has been 10 days since I visited this website\"");
define("_AM_ELE_ORDER","Ordre");
define("_AM_ELE_OTHER", 'For an option of "Other", put {OTHER|*number*} in one of the text boxes. e.g. {OTHER|30} generates a text box with 30 chars width.');
define("_AM_ELE_PDS","poids");
define("_AM_ELE_PRIVATE","Privé");
define("_AM_ELE_RADIO","Boutons radio");
define("_AM_ELE_REQ","Requis");
define("_AM_ELE_REQ_USELESS","Non utilisable pour les boîtes de sélection, cases à cocher et boutons radio");
define("_AM_ELE_RGE","Rouge");
define("_AM_ELE_ROWS","Lignes");
define("_AM_ELE_SELECT","Boîte de sélection");
define("_AM_ELE_SELECTED","Sélection");
define("_AM_ELE_SELECT_NONE","aucun élément sélectionné");
define("_AM_ELE_SEP","Ligne de séparation");
define("_AM_ELE_SIZE","Taille");
define("_AM_ELE_SOUL","Souligné");
// subforms
define("_AM_ELE_SUBFORM", "Sous formulaire (venant d'un framework de formulaires)");
define("_AM_ELE_SUBFORM_BLANKS", "How many blank spaces should be shown for this subform when the page first loads?");
define("_AM_ELE_SUBFORM_DESC", "When you display the current form as part of a framework, the subform interface can be included in the form.  The subform interface allows users to create and modify entries in a related subform without leaving the main form.  The list here shows all the possible subforms from all frameworks that this form is part of.");
define("_AM_ELE_SUBFORM_ELEMENTS", "Which elements should be displayed as part of the subform interface?");
define("_AM_ELE_SUBFORM_ELEMENTS_DESC", "About three or four elements from the subform can be displayed comfortably as part of the main form.  More than four elements starts to make the interface cluttered.  You can choose which elements you want to display by selecting them from this list.  Users can always modify all elements by clicking a button next to each subform entry that it listed in the main form.<br><br>You do not need to choose the element that joins the subform to the mainform; Formulize will automatically populate that element with the correct values for you.");
define("_AM_ELE_SUBFORM_FORM", "Quel formulaire voulez vous inclure en tant que sous formulaire?");
define("_AM_ELE_SUBFORM_HEADINGSORCAPTIONS", "Should each element be labeled with its column heading or caption?");
define("_AM_ELE_SUBFORM_HEADINGSORCAPTIONS_CAPTIONS", "Caption");
define("_AM_ELE_SUBFORM_HEADINGSORCAPTIONS_HEADINGS", "Column heading (captions will be used for elements with no column heading");
define("_AM_ELE_SUBFORM_NONE", "Pas de sous formulaires valides - définissez d'abord un Framework");
define("_AM_ELE_SUBFORM_REFRESH", "Refresh elements list to match selected form");
define("_AM_ELE_SUBFORM_VIEW", "Montrer les boutons<i>Voir</i> a coté de chaque entrée du sous formulaire?");
define("_AM_ELE_SUBFORM_VIEW_DESC", "The <i>View</i> buttons let users click through to the complete entry in the subform.  This may be useful when only some elements in the subform are visible in the main interface.");
define("_AM_ELE_TAILLEFICH","Taille maximale du fichier");
define("_AM_ELE_TAREA","Aire de saisie de texte");
define("_AM_ELE_TEXT","Boîte texte");
define("_AM_ELE_TEXT_DESC","{NAME} imprimera le nom complet;<br />{UNAME} imprimera le nom de l'utilisateur;<br />{EMAIL} imprimera l'email de l'utilisateur");
define("_AM_ELE_TEXT_DESC2","<br />PHP Code is the only situation where more than one line of this box will be read.");
define("_AM_ELE_TYPE","Que doivent écrire les gens dans cette aire de texte?");
define("_AM_ELE_TYPEMIME",'Types autorisés');
define("_AM_ELE_TYPE_DESC","Choisissez 'Nombres uniquement' pour enlever les caractères non numériques de l'entrée lorsque celle ce est sauvegardée. C'est particulièrement utile quand vous avez prévu de faire des opérations mathématiques avec ces données");
define("_AM_ELE_TYPE_NUMBER","Nombres uniquement");
define("_AM_ELE_TYPE_STRING","N'importe quoi");
define("_AM_ELE_UPLOAD","Joindre un fichier");
define("_AM_ELE_YN","Simple boutons radio oui/non");
define("_AM_FORM","Formulaire : ");
define("_AM_FORMLINK_NONE", "aucun lien -- options courante en action");
define("_AM_FORMLINK_NONE_TEXTBOX", "No association in effect");
define("_AM_FORMULIZE_ADD_NEW_SCREEN_OF_TYPE", "Add a new screen of this type:");
define("_AM_FORMULIZE_ADD_SCREEN_NOW", "Add it Now!");
define("_AM_FORMULIZE_CONFIRM_SCREEN_DELETE", "Are you sure you want to delete this screen?  Please confirm!");
define("_AM_FORMULIZE_CONFIRM_SCREEN_DELETE_PAGE", "Are you sure you want to delete this page?  Please confirm!");
define("_AM_FORMULIZE_DEFINED_SCREENS", "Defined Screens for This Form");
define("_AM_FORMULIZE_DELETE_SCREEN", "Delete");
define("_AM_FORMULIZE_DELETE_THIS_PAGE", "Delete this page");
define("_AM_FORMULIZE_SCREEN_ADDPAGE", "Add another page");
define("_AM_FORMULIZE_SCREEN_A_PAGE", "Form elements to display on page");
define("_AM_FORMULIZE_SCREEN_BUTTONTEXT", "The text of the link users get at the end of the form");
define("_AM_FORMULIZE_SCREEN_CONS_ADDCON", "Add an another condition");
define("_AM_FORMULIZE_SCREEN_CONS_HELP", "Conditions are useful if a page should only appear based on answers to questions in a previous page.  Select the questions from the previous page and specify the answers that should result in this page being displayed.");
define("_AM_FORMULIZE_SCREEN_CONS_NONE", "Always display this page");
define("_AM_FORMULIZE_SCREEN_CONS_PAGE", "Conditions in which to display page");
define("_AM_FORMULIZE_SCREEN_CONS_YES", "Only display when the following conditions are true:");
define("_AM_FORMULIZE_SCREEN_DONEDEST", "The URL for the link users get at the end of the form");
define("_AM_FORMULIZE_SCREEN_FORM", "Create or Modify a Screen");
define("_AM_FORMULIZE_SCREEN_INSERTPAGE", "Insert a new page here");
define("_AM_FORMULIZE_SCREEN_INTRO", "Introductory text for the first page of this form");
define("_AM_FORMULIZE_SCREEN_LOE_ADDCUSTOMBUTTON", "Add a new custom button");
define("_AM_FORMULIZE_SCREEN_LOE_ADDCUSTOMBUTTON_EFFECT", "Add an effect for this button");
define("_AM_FORMULIZE_SCREEN_LOE_BLANK_DEFAULTVIEW", "Use a blank default view (ie: display no entries)");
define("_AM_FORMULIZE_SCREEN_LOE_BOTTOMTEMPLATE", "Template for the bottom portion of the page, below the list:");
define("_AM_FORMULIZE_SCREEN_LOE_BUTTON1", "What text should be on the '");
define("_AM_FORMULIZE_SCREEN_LOE_BUTTON2", "' button?");
define("_AM_FORMULIZE_SCREEN_LOE_BUTTONINTRO", "Specify which buttons you want included on this screen:");
define("_AM_FORMULIZE_SCREEN_LOE_BUTTON_SECTION1", "You can change the text on the buttons below.  Also, if you use a custom Top or Bottom Template, these buttons will be available there.");
define("_AM_FORMULIZE_SCREEN_LOE_BUTTON_SECTION2", "You can change the text on the buttons below.  If you use a custom List Template, these buttons will not appear on the screen by default, but you can use a custom Top or Bottom Template to specifically include them.");
define("_AM_FORMULIZE_SCREEN_LOE_COLUMNWIDTH", "Largeur de toutes les colonnes en pixel?");
define("_AM_FORMULIZE_SCREEN_LOE_CONFIGINTRO", "Specify which configuration options you want to use:");
define("_AM_FORMULIZE_SCREEN_LOE_CONFIG_SECTION1", "The configuration options below have an effect regardless of whether you use a custom List Template.");
define("_AM_FORMULIZE_SCREEN_LOE_CONFIG_SECTION2", "Most configuration options below have NO effect if you use a custom List Template, except as noted.");
define("_AM_FORMULIZE_SCREEN_LOE_CURRENTVIEWLIST", "Quel texte d’introduction pour la liste des  'Vues en cours'");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON", "Bouton personnalisé");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTONINTRO", "Specify any custom buttons for this screen:");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTONINTRO2", "Les boutons personnalisés peuvent être ajoutés au-dessus, ci-dessous, ou à l'intérieur d'une liste, en utilisant les modèles (voir ci-dessous). Vous devez spécifier quels sont les effets que chaque bouton personnalisé devrait avoir. Par exemple, un bouton personnalisé portant la mention «Annuler l'abonnement» peut mettre à jour un élément de formulaire appelé «date de fin d'abonnement, et y mettre la date d'aujourd'hui comme valeur.");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_APPLYTO", "Quelles entrées doivent être modifiées lorsque ce bouton est cliqué?");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_APPLYTO_ALL", "Toutes les entrées dans ce formulaire");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_APPLYTO_CUSTOM_CODE", "Aucune.  Charger un code php lorsque ce bouton est cliqué.");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_APPLYTO_CUSTOM_HTML", "Aucune.  Utiliser le PHP pour générer du HTML precisement à l’endroit ou ce code apparait.");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_APPLYTO_INLINE", "Only the entry on the line where the button is (only works if this button appears on every line)");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_APPLYTO_NEW", "The button should create a new entry in this form");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_APPLYTO_NEWPERSELECTED", "The button should create a new entry in this form for each checkbox that's checked");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_APPLYTO_NEWPERSELECTED_OTHER", "' for each checkbox that's checked");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_APPLYTO_NEW_OTHER", "The button should create a new entry in the form '");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_APPLYTO_SELECTED", "Only the selected entries (only works if checkboxes are enabled above)");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_BUTTONTEXT", "Quel texte doit apparaitre sur ce bouton?");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_DELETE", "Effacer ce bouton");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_EFFECT", "Effect number");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_EFFECT_ACTION", "Effectuer cette action?");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_EFFECT_ACTION_APPEND", "Ajouter la valeur spécifiée à la fin de la valeur actuelle");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_EFFECT_ACTION_REMOVE", "Supprimer la valeur spécifiée de la valeur actuelle");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_EFFECT_ACTION_REPLACE", "Supprimer la valeur actuelle avec la valeur spécifiée");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_EFFECT_CUSTOM_CODE_DESC", "Entrez le code PHP devant être exécuté lors du clic sur le bouton.  Vous pouvez utiliser les variables globales type \$formulize_thisEntryId pour accéder au numéroID de l'entrée de la ligne sur laquelle le bouton a été cliqué, ou si le bouton n'apparait pas sur toutes les lignes le code sera effectué une fois pour toutes les lignes selectionnées, et \$formulize_thisEntryId contiendra l'ID des différentes boites cochées.  If the button is not inline and no checkboxes were checked, then the code will be run once and \$formulize_thisEntryId will be blank.  You can use <i>gatherHiddenValue('</i>handle<i>');</i> to retrieve the value of a specific field from a selected entry.  Use hidden elements above to send those values.");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_EFFECT_CUSTOM_HTML_DESC", "Entrez le code PHP that should be executed to render this \"button\".  This is useful in conjunction with the \"appear on every line\" setting, so you can insert some HTML into a column of the list.  Use <i>display(\$entry, \$handle);</i> to include the value of any field form the current entry.");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_EFFECT_DELETE", "Effacer cet effet");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_EFFECT_DESC", "Specify the element that should be affected, the action that should be performed on that element, and the value to use.  The value can contain PHP code, including <i>gatherHiddenValue('</i>handle<i>');</i> to retrieve the value of a specific field from a selected entry.  Use hidden elements above to send those values.  To use PHP code, the last line of the value should be <i>\$value = \$something;</i>");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_EFFECT_ELEMENT", "Affecter quel élément?");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_EFFECT_VALUE", "Utiliser cette valeur?");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_HANDLE", "Quelle nom ou numéro de référence est utilisé pour appeler ce bouton?");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_INLINE", "Est ce que ce bouton doit apparaitre sur chaque ligned de la liste des entrées?");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_INLINE_DESC", "If no, then the button will be available in the Top and Bottom Templates.  If yes, the button will appear in the list, or will be available in the List Template if you use one.");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_MESSAGETEXT", "Quel texte doit apparaitre en haut de l'écran lorsque ce bouton est cliqué?");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_NEW", "Nouveau bouton personnalisé");
define("_AM_FORMULIZE_SCREEN_LOE_DECOLUMNS", "Sélectionnez toutes les colonnes où vous souhaitez que les informations soient directement modifiables, plutôt que sous forme de texte statique:");
define("_AM_FORMULIZE_SCREEN_LOE_DEFAULTVIEW", "Which published view should be used as the default view?");
define("_AM_FORMULIZE_SCREEN_LOE_DEFAULTVIEWLIMIT", "Include all views");
define("_AM_FORMULIZE_SCREEN_LOE_DESAVETEXT", "If you have selected any columns to display as form elements, what text should be used on a 'Submit' button below the list of entries?");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_COLUMNWIDTH", "Set to '0' to have columns expand to their natural width.");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_DECOLUMNS", "<b>WARNING:</b> do not enable the checkboxes above if you are displaying any checkbox elements in the list!");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_DEFAULTVIEW", "If you are customizing the list template, the default view will still be used to control which entries are initially included in the list.");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_ENTRIESPERPAGE", "Set to '0' to have all entries appear on one page.");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_HIDDENCOLUMNS", "This option is useful if you need some text on the screen to be sent back in <i>\$_POST</i> as part of the next page load.  You can use <i>gatherHiddenValue('</i>handle<i>');</i> in a custom button access the values you receive.  Any columns you choose will still be displayed normally in the list, in addition to having the hidden form elements created.");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_LEAVEBLANK", "Leave blank to turn this button off");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_LEAVEBLANK_LIST", "Leave blank to turn off the list");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_LIMITVIEWS", "If you include the basic views (\"Entries by...\"), then the selected view will switch to a basic view when the user makes a change, such as a sort or Quicksearch.");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_LISTTEMPLATE", "If you specify a List Template, certain buttons and configuration options mentioned above may be unavailable.");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_REPEATHEADERS", "Repeating the headings makes it easier for users to know what column they are looking at when they scroll through the list.  Set to '0' to have headings only at the top of the list.");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_TEXTWIDTH", "Mettez à '0' pour aucune limite.");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_TOPTEMPLATE", "If you turn off the scrollbox, and do not use the Export buttons, then the code you type here and in the List and Bottom Templates, will all be drawn to the screen consecutively.  This means you can start a table in the Top Template, specify the &lt;tr&gt; tags in the List Template and close the  table in the Bottom Template.  Essentially, these three Templates give you control over the entire page layout.");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_TOPTEMPLATE2", "If you turn off the scrollbox, then these three templates will all be drawn to the screen consecutively.  This means you can start a table in the <i>top template</i>, specify the &lt;tr&gt; tags in the <i>list template</i> and close the table in the <i>bottom template</i>.  Essentially, these three templates give you control over the entire page layout.");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_USECHECKBOXES", "If you use a custom List Template, this option will control whether the <i>\$selectionCheckbox</i> variable is set for each row in the list.");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_USEHEADINGS", "Without headings at the top of columns, no one will be able to sort the entries in the view.");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_USESEARCH", "If the 'Quicksearch' boxes are turned off, they will still be available in the Top and Bottom Templates (see below).");
define("_AM_FORMULIZE_SCREEN_LOE_DESC_USEWORKING", "If the user is likely to click the back button in your interface, turning off this message may improve usability.");
define("_AM_FORMULIZE_SCREEN_LOE_DVALL", "Entrées par tous les groupes");
define("_AM_FORMULIZE_SCREEN_LOE_DVGROUP", "Entries by the current user's group(s)");
define("_AM_FORMULIZE_SCREEN_LOE_DVMINE", "Entries by the current user");
define("_AM_FORMULIZE_SCREEN_LOE_ENTRIESPERPAGE", "How many entries should appear on each page of the list?");
define("_AM_FORMULIZE_SCREEN_LOE_HIDDENCOLUMNS", "Sélectionnez toutes les colonnes où vous souhaitez que la valeur actuelle de chaque entrée soit inclus dans la liste comme un élément de formulaire cachés");
define("_AM_FORMULIZE_SCREEN_LOE_LIMITVIEWS", "If the 'Current View' list is in use, include these views:");
define("_AM_FORMULIZE_SCREEN_LOE_LISTTEMPLATE", "Template for each entry in the list portion of the page:");
define("_AM_FORMULIZE_SCREEN_LOE_LISTTEMPLATE_HELPINTRO_FORM", "Below is a list of element IDs for all the elements in this form. Use them with the <i>display</i> function.");
define("_AM_FORMULIZE_SCREEN_LOE_LISTTEMPLATE_HELPINTRO_FRAMEWORK", "Below is a list of handles for all the form elements in this Framework.  Use them with the <i>display</i> function.");
define("_AM_FORMULIZE_SCREEN_LOE_NOPUBDVIEWS", "Il n'y a pas de vue publiée pour ce formulaire");
define("_AM_FORMULIZE_SCREEN_LOE_NOVIEWSAVAIL", "Il n'y a aucune vue disponible");
define("_AM_FORMULIZE_SCREEN_LOE_REPEATHEADERS", "If you are using headings, how often should they repeat within the list of entries?");
define("_AM_FORMULIZE_SCREEN_LOE_TEMPLATEINTRO", "Specify any custom template options for this screen:");
define("_AM_FORM_DATATYPE_CHAR1","Stocker exactement comme le texte, ");
define("_AM_FORM_DATATYPE_CHAR2"," caractères de long (char)");
define("_AM_FORM_DATATYPE_CONTROLS","How should the data for this element by stored in the database?");
define("_AM_FORM_DATATYPE_CONTROLS_DESC","<b>Elements that will only contain numbers should use a numeric type, so that sorting and calculations work optimally.</b><br><br>This is an advanced option that you can use to control the MySQL datatype that is used in the underlying database field for this element.  The value in ( ) shows which datatype will be used.<br><br>If you don't know what all this means, then just accept the defaults.  Formulize can intelligently select appropriate values for regular text boxes based on the 'numbers only' setting, and the number formatting options.");
define("_AM_FORM_DATATYPE_DECIMAL1","Stocker comme un nombre avec ");
define("_AM_FORM_DATATYPE_DECIMAL2"," places pour les décimales (decimal)");
define("_AM_FORM_DATATYPE_INT","Stocker comme nombre avec <b>aucune</b> places pour les décimales (int)");
define("_AM_FORM_DATATYPE_OTHER","Continue using this datatype: ");
define("_AM_FORM_DATATYPE_TEXT","Peu importe (text)");
define("_AM_FORM_DATATYPE_TEXT_NEWTEXT","Let Formulize figure it out, based on the 'numbers only' setting, and the number formatting options");
define("_AM_FORM_DATATYPE_VARCHAR1","Stocker comme du texte, jusqu'à un maximum de ");
define("_AM_FORM_DATATYPE_VARCHAR2"," carractères (varchar)");
define("_AM_FORM_DISABLED_ALLGROUPS", "Désactivé pour tous les groupes");
define("_AM_FORM_DISABLED_EXTRA", "Use this option to make this element inactive for certain groups.  The element will still be shown to users according to the display option above, but you can use this option to disable the element so users cannot change its value.  This option currently works only for textboxes and textarea boxes.");
define("_AM_FORM_DISABLED_NOGROUPS", "Désactivé pour aucun groupe");
define("_AM_FORM_DISPLAY_ALLGROUPS", "Tous les groupes ayant une permission pour ce formulaire");
define("_AM_FORM_DISPLAY_EXTRA", "Use this list to display certain elements to only certain groups when the form is shown.  Meant for situations where users in different groups should see different parts of the same form.  Normally, you can leave this on 'All groups'.");
define("_AM_FORM_DISPLAY_MULTIPLE","Personnalisé");
define("_AM_FORM_DISPLAY_NOGROUPS", "Aucun groupe");
define("_AM_FORM_FORCEHIDDEN", "Include as a hidden element for users who can't see it");
define("_AM_FORM_FORCEHIDDEN_DESC", "Currently only affects radio buttons and textboxes.  This option will cause a hidden form element to be created instead of the radio button series or textbox, and the value of the hidden element will be the default value specified above.  Useful when you always need a default value set in every form entry, but not all groups normally see this element.");
define("_AM_FORM_PRIVATE", "The information that users enter in this element is private");
define("_AM_FORM_PRIVATE_DESC", "If this box is checked, the information that users enter in this element will only be visible to other users who have the view_private_elements permission.  This option is useful for making personal information only available to the appropriate managers.");
define("_AM_FORM_REQ","Requète soumise par ");
define("_AM_Font","Police :");
define("_AM_FORMULIZE_SCREEN_LOE_TEMPLATEINTRO2", "<span style=\"font-weight: normal\"><p><b>Top and Bottom Templates</b></p>\n<p>If you specify any PHP code in the Top or Bottom Templates, it will be used to control the appearance of the space either above or below the list of entries.</p>\n<p><b>WARNING:</b> if you include any checkbox elements in your templates, turn off the checkboxes that appear on the left side of the list!</p>\n<p>Use this PHP code to setup your preferred layout of buttons, or include custom instructions, etc.</p>\n<p>To include buttons and controls, use these variables:</p>
<table cellpadding=5 border=0>
<tr>
<td>
<ul>
<li>\$addButton</li>
<li>\$addMultiButton</li>
<li>\$addProxyButton</li>
<li>\$exportButton</li>
<li>\$importButton</li>
<li>\$notifButton</li>
<li>\$currentViewList</li>
<li>\$changeColsButton</li>
<li>\$saveButton (si une colonne est affichée comme un élément modifiable)</li>
</ul>
</td><td>
<ul>
<li>\$calcButton</li>
<li>\$advSearchButton</li>
<li>\$cloneButton</li>
<li>\$deleteButton</li>
<li>\$selectAllButton</li>
<li>\$clearSelectButton</li>
<li>\$resetViewButton</li>
<li>\$saveViewButton</li>
<li>\$deleteViewButton</li>
<li>\$pageNavControls (if there is more than one page of entries)</li>
</ul>
</td>
</tr>
</table>
<p>For Quicksearch boxes, use \"\$quickSearch<i>Column</i>\" where <i>Column</i> is either the element ID number, or the element handle if using a Framework.</p>\n<p>For Custom Buttons, use \"\$handle\" where <i>handle</i> is the handle you specified for that button.  You can use \"\$messageText\" to control where the clicked button's message will appear on the screen.  By default, the message appears centred at the top.</p>\n<p>If the current view list is available, you can determine which view was last selected from the list, by checking whether <i>\$The_view_name</i> is true or not.  You can also check <i>\$viewX</i> where X is a number corresponding to the position of the view in the list, 1 through n.  You can use this to put if..else clauses into your template, so it changes depending what view is selected.</p>\n<p><b>List Template</b></p>\n<p>If you specify any PHP code for the List Template, it will be used to draw in each row of the list.</p>\n<p>You do not need to create a foreach loop or any other loop structure in this template.  The PHP code you specify will be executed inside a loop that runs once for each entry.</p>\n<p>You have full access to XOOPS and Formulize objects, functions, variables and constants in this template, including <i>\$fid</i> for the form ID.  Use \$entry to refer to the current entry in the list.  For example:</p>\n<p style=\"font-family: courier\">&nbsp;&nbsp;&nbsp;display(\$entry, \"phonenumber\");</p>\n<p>That code will display the phone number recorded in that entry (assuming \"phonenumber\" is a valid element handle).</p><p>You can use \"\$selectionCheckbox\" to display the special checkbox used to select an entry.</p><p>You can use a special function called \"viewEntryLink\" to create a link to the entry so users can edit it.  This function takes one parameter, which is the text that will be clickable.  Examples:</p><p style=\"font-family: courier\">&nbsp;&nbsp;&nbsp;print viewEntryLink(\"Click to view this entry\");<br>&nbsp;&nbsp;&nbsp;print viewEntryLink(display(\$entry, \"taskname\"));<br>&nbsp;&nbsp;&nbsp;print viewEntryLink(\"&lt;img src='\" . XOOPS_ROOT_PATH . \"/images/button.jpg'&gt;\");</p></span>\n");
define("_AM_ELE_CAPTION","Affichage");
define("_AM_FORMULIZE_SCREENTYPE_LISTOFENTRIES", "Liste des entrées dans ce formulaire");
define("_AM_FORMULIZE_SCREENTYPE_MULTIPAGE", "Version multi pages du formulaire");
define("_AM_FORMULIZE_SCREEN_LOE_CUSTOMBUTTON_GROUPS", "Pour quel(s) groupe(s) ce bouton doit il apparaitre?");
define("_AM_FORMULIZE_SCREEN_LOE_TEXTWIDTH", "Combien de caractères du texte doivent être affichés dans chaque cellule?");
define("_AM_FORMULIZE_SCREEN_LOE_TOPTEMPLATE", "Template for the top portion of the page, above the list:");
define("_AM_FORMULIZE_SCREEN_LOE_UCHALL", "Oui, montrer les case à cocher pour toutes les entrées<br>");
define("_AM_FORMULIZE_SCREEN_LOE_UCHDEFAULT", "Oui, montrer les cases à cocher en se basant sur les permissions d'effacer les entrées données<br>");
define("_AM_FORMULIZE_SCREEN_LOE_UCHNONE", "Non, ne pas montrer les cases à cocher");
define("_AM_FORMULIZE_SCREEN_LOE_USCM_BOTH", "utiliser les deux<br>");
define("_AM_FORMULIZE_SCREEN_LOE_USCM_CALC", "just the 'Calculations' status");
define("_AM_FORMULIZE_SCREEN_LOE_USCM_NEITHER", "n'en utiliser aucun<br>");
define("_AM_FORMULIZE_SCREEN_LOE_USCM_SEARCH", "just the 'Advanced Search' status<br>");
define("_AM_FORMULIZE_SCREEN_LOE_USECHECKBOXES", "Should checkboxes appear to the left of each entry, so that they can be selected by the user?");
define("_AM_FORMULIZE_SCREEN_LOE_USEHEADINGS", "Est ce que l'en-tête doit apparaitre en haut de chaque colonne?");
define("_AM_FORMULIZE_SCREEN_LOE_USESCROLLBOX", "Est ce que la liste des entrées doit être contenue dans une boite déroulante?");
define("_AM_FORMULIZE_SCREEN_LOE_USESEARCH", "Est ce que le champ de recherche rapide doit apparaitre en haut de chaque colonne?");
define("_AM_FORMULIZE_SCREEN_LOE_USESEARCHCALCMSGS", "Should the 'Advanced Search' or 'Calculations' status messages appear at the top of the list?");
define("_AM_FORMULIZE_SCREEN_LOE_USEVIEWENTRYLINKS", "Should the 'magnifying glass links' appear to the left of each entry, so users can click through to the full details?");
define("_AM_FORMULIZE_SCREEN_LOE_USEWORKING", "Est ce que le message 'Working' doit apparaitre lorsque la page recharge?");
define("_AM_FORMULIZE_SCREEN_LOE_VIEWENTRYPAGEWORKS", "Page des pageworks");
define("_AM_FORMULIZE_SCREEN_LOE_VIEWENTRYSCREEN", "Quel écran par défaut doit être montré aux utilisateurs lorsqu'ils souhaitent rentrer une nouvelle entrée?");
define("_AM_FORMULIZE_SCREEN_LOE_VIEWENTRYSCREEN_DEFAULT", "Utilise la version par défault de ce formulaire");
define("_AM_FORMULIZE_SCREEN_LOE_VIEW_ONLY_IN_FRAME", "only avail. in framework: ");
define("_AM_FORMULIZE_SCREEN_LOE_VIEW_ONLY_NO_FRAME", "only avail. with no framework");
define("_AM_FORMULIZE_SCREEN_PAGETITLE", "Titre pour le numéro de page");
define("_AM_FORMULIZE_SCREEN_PARAENTRYFORM", "Est ce que les réponses d'une entrée précédente doivent être montrée comme partie du formulaire?  Si c'est le cas choisissez le formulaire.");
define("_AM_FORMULIZE_SCREEN_PARAENTRYFORM_FALSE", "Non, ne pas montrer les réponses précédentes.");
define("_AM_FORMULIZE_SCREEN_PARAENTRYRELATIONSHIP", "Si les réponses précédentes sont présentées, quelle est la relation de ce formulaire avec l'autre contenant les entrées précédentes?");
define("_AM_FORMULIZE_SCREEN_PARAENTRYREL_BYGROUP", "Les entrées appartiennnet au même groupe");
define("_AM_FORMULIZE_SCREEN_PRINTALL", "Faire le bouton 'Vue Imprimable - Toutes les Pages' valide à la fin du formulaire"); //nmc 2007.03.24
define("_AM_FORMULIZE_SCREEN_PRINTALL_N", "Non"); //nmc 2007.03.24
define("_AM_FORMULIZE_SCREEN_PRINTALL_NONE", "Non, et pas non plus l'habituel bouton 'Vue Imprimable'");
define("_AM_FORMULIZE_SCREEN_PRINTALL_Y", "Oui"); //nmc 2007.03.24
define("_AM_FORMULIZE_SCREEN_SAVE", "Sauvegarder cet écran");
define("_AM_FORMULIZE_SCREEN_SAVED", "The details for this screen have been saved in the database");
define("_AM_FORMULIZE_SCREEN_THANKS", "Texte de remerciement pour la dernière page de ce formulaire");
define("_AM_FORMULIZE_SCREEN_TITLE", "Titre de cet écran");
define("_AM_FORMULIZE_SCREEN_TYPE", "Type: ");
define("_AM_FORMULIZE_SELECT_FRAMEWORK", "Framework utilisé sur cet écran, s'il y en a un");
define("_AM_FORMULIZE_USE_NO_FRAMEWORK", "Utilisez ce formulaire seulement, pas de Framework");
define("_AM_FUNCTION","Fonction");
define("_AM_GOTO_MAIN", "Retourner au menu");
define("_AM_GOTO_MODFRAME", "Retourner à la première page<br> du Frameworks");
define("_AM_GOTO_PARAMS", "Editer les paramètres du formulaire");
define("_AM_ID","NÂ°");
define("_AM_INACTIVE","inactif");
define("_AM_INDENT","indentation gauche");
define("_AM_INDENT_SHORT","Ind.");
define("_AM_ITEMNAME","Nom");
define("_AM_ITEMURL","Hyperlien");
define("_AM_MAINMENUSTYLE","Style du Menu principal");
define("_AM_MARGINBOTTOM","Marge inférieure");
define("_AM_MARGINTOP","Marge supérieure");
define("_AM_MARGIN_BOTTOMSHORT","mrg. inf.");
define("_AM_MARGIN_TOPSHORT","mrg. sup.");
define("_AM_MEMBERS","membres uniquement");
define("_AM_MEMBERSONLY","Utilisateurs autorisés");
define("_AM_MEMBERSONLY_SHORT","Reg.<br>seulement");
define("_AM_MENUCATEGORIES", "Menu des Categories");
define("_AM_MENUCATLIST", "Categories:");
define("_AM_MENUCATNAME", "Nom:");
define("_AM_MENUDEL", "Effacer");
define("_AM_MENUEDIT", "Editer");
define("_AM_MENUNOCATS", "Pas de Categories");
define("_AM_MENUSAVEADD", "Ajouter/Sauver");
define("_AM_NO","Non");
define("_AM_NORMAL","normal");
define("_AM_PARAMS_EXTRA", "(Spécifier les élements qui devront apparaître<br>dans la page <i>de visualisation d'entrées</i>)");
define("_AM_PARA_FORM","Paramètres du formulaire");
define("_AM_POS","Position");
define("_AM_POS_SHORT","Pos.");
define("_AM_REORD","Réordonner");
define("_AM_REQ","Résultats du module formulaire : ");
define("_AM_SAVE","Sauvegarde");
define("_AM_SAVECHANG","Sauvegarder les modifications");
define("_AM_SAVE_CHANGES","Sauvegarde des modifications");
define("_AM_SAVING_CHANGES", "Sauvegarde des modifications");
define("_AM_SEPAR",'{SEPAR}');
define("_AM_SITENAMET","Nom de votre site :");
define("_AM_STATUS","Etats");
define("_AM_STATUST","Etats :");
define("_AM_TITLE","Administration du Menu");
define("_AM_URLT","Hyperlien :");
define("_AM_VERSION","1.0");
define("_AM_VIEW_FORM", "Visualiser le formulaire");
define("_AM_WANTDEL","Voulez-vous vraiment supprimer cet élément ?");
define("_AM_YES","Oui");
define("_AM_formulizeMENUSTYLE","Style de MyMenu");
define("_MI_formulize_DELIMETER_BR","Ligne de separation");
define("_MI_formulize_DELIMETER_CUSTOM","HTML customisé");
define("_MI_formulize_DELIMETER_SPACE","Espace blanc");
define("_formulize_CAPTION_GT", "Les légendes ne peuvent contenir de signe > . ces derniers ont été enlevés.");
define("_formulize_CAPTION_LT", "Les légendes ne peuvent contenir de signe < . ces derniers ont été enlevés.");
define("_formulize_CAPTION_MATCH", "La légende que vous avez saisie et déjà utilisée. un '2' a été suffixé à cette dernière.");
define("_formulize_CAPTION_QUOTES", "La légende ne peut contenir de quote. Ces derniers ont été enlevés.");
define("_formulize_CAPTION_SLASH", "La ne peut contenir d'anti slash. Ces derniers ont été enlevés.");


?>