<?php

/**
* $Id: common.php 9766 2010-01-23 01:06:19Z skenow $
*/

if (!defined("XOOPS_ROOT_PATH")) {
 	die("ImpressCMS root path not defined");
}

define('_CO_ICMS_FILTER', "Filter");
define('_CO_ICMS_NONE', "None");
define('_CO_ICMS_SHOW_ONLY', 'Show only');
define('_CO_ICMS_SORT_BY', "Sort by");
define('_CO_ICMS_ACTIONS', 'Actions');
define('_CO_ICMS_ADMIN_PAGE', ':: Admin page ::');
define('_CO_ICMS_ALL', "All");
define('_CO_ICMS_APPROVE', 'Approve');
define('_CO_ICMS_AUTHOR_WORD', "The Author's Word");
define('_CO_ICMS_BODY_DEFAULT', "Here is an interesting link I found on %s :%s");
define('_CO_ICMS_CANCEL', 'Cancel');
define('_CO_ICMS_CURRENCY_ISO4217', 'ISO 4217 Code');
define('_CO_ICMS_CURRENCY_ISO4217_DSC', 'Official currency code. More info: <a href="http://en.wikipedia.org/wiki/ISO_4217" rel="external">ISO 4217 on Wikipedia</a>');
define('_CO_ICMS_CURRENCY_NAME', 'Name');
define('_CO_ICMS_CURRENCY_NAME_DSC', '');
define('_CO_ICMS_CURRENCY_SYMBOL', 'Symbol');
define('_CO_ICMS_CURRENCY_SYMBOL_DSC', '');
define('_CO_ICMS_CURRENCY_RATE', 'Conversion rate');
define('_CO_ICMS_CURRENCY_RATE_DSC', '');
define('_CO_ICMS_CURRENCY_DEFAULT', 'Default currency');
define('_CO_ICMS_CURRENCY_DEFAULT_DSC', '');
define('_CO_ICMS_CATEGORY_CREATE', 'Create a category');
define('_CO_ICMS_CATEGORY_CREATE_SUCCESS', 'The category was successfully created.');
define('_CO_ICMS_CATEGORY_DESCRIPTION', 'Description');
define('_CO_ICMS_CATEGORY_DESCRIPTION_DSC', 'Description of this category');
define('_CO_ICMS_CATEGORY_EDIT', 'Category information');
define('_CO_ICMS_CATEGORY_EDIT_INFO', 'Complete this form in order to edit this category.');
define('_CO_ICMS_CATEGORY_IMAGE', 'Image');
define('_CO_ICMS_CATEGORY_IMAGE_DSC', 'Category Image');
define('_CO_ICMS_CATEGORY_MODIFY_SUCCESS', 'The category was successfully modified.');
define('_CO_ICMS_CATEGORY_NAME', 'Category name');
define('_CO_ICMS_CATEGORY_NAME_DSC', 'Name of this category');
define('_CO_ICMS_CATEGORY_PARENTID', 'Parent category');
define('_CO_ICMS_CATEGORY_PARENTID_DSC', 'Category to which belongs this category.');
define('_CO_ICMS_CLOSE_WINDOW', "Click here to close this window.");
define('_CO_ICMS_COUNTER_FORM_CAPTION', 'Hit counter');
define('_CO_ICMS_CREATE', 'Create');
define('_CO_ICMS_CREATINGNEW', 'Creating');
define('_CO_ICMS_CUSTOM_CSS', 'Custom CSS');
define('_CO_ICMS_CUSTOM_CSS_DSC', 'You can specify custom CSS information here. This CSS shall be outputed when this object is displayed on the user side.');
define('_CO_ICMS_DELETE', 'Delete');
define('_CO_ICMS_DELETE_CONFIRM', "Do you really want to delete '<em>%s</em>' ?");
define('_CO_ICMS_DELETE_ERROR', 'An error occured while deleting the object.');
define('_CO_ICMS_DELETE_SUCCESS', 'The object was successfully deleted.');
define('_CO_ICMS_DEVELOPER_CONTRIBUTOR', 'Contributor(s)');
define('_CO_ICMS_DEVELOPER_CREDITS', 'Credits');
define('_CO_ICMS_DEVELOPER_EMAIL', 'Email');
define('_CO_ICMS_DEVELOPER_WEBSITE', 'Website');
define('_CO_ICMS_DISPLAY_OPTIONS', "Display options ");
define('_CO_ICMS_DOBR_FORM_CAPTION', ' Enable linebreak');
define('_CO_ICMS_DOHTML_FORM_CAPTION', ' Enable HTML tags');
define('_CO_ICMS_DOHTML_FORM_DSC', "");
define('_CO_ICMS_DOIMAGE_FORM_CAPTION', ' Enable images');
define('_CO_ICMS_DOIMAGE_FORM_DCS', "");
define('_CO_ICMS_DOSMILEY_FORM_CAPTION', ' Enable smiley icons');
define('_CO_ICMS_DOSMILEY_FORM_DSC', "");
define('_CO_ICMS_DOXCODE_FORM_CAPTION', ' Enable ImpressCMS codes');
define('_CO_ICMS_DOXCODE_FORM_DSC', "");
define('_CO_ICMS_EDITING', 'Editing');
define('_CO_ICMS_EMAIL', 'Send this link');
define('_CO_ICMS_EMAIL_BODY', 'Here is something interesting I found at %s');
define('_CO_ICMS_EMAIL_SUBJECT', 'Have a look at this page at %s');
define('_CO_ICMS_GOTOMODULE', 'Go to module');
define('_CO_ICMS_LANGUAGE_CAPTION', "Language");
define('_CO_ICMS_LANGUAGE_DSC', "Language related to this object");
define('_CO_ICMS_LIMIT', "Display ");
define('_CO_ICMS_LIMIT_ALL', 'All ');
define('_CO_ICMS_LINK_BODY', "Body");
define('_CO_ICMS_LINK_BODY_DSC', "");
define('_CO_ICMS_LINK_DATE', "Date");
define('_CO_ICMS_LINK_FROM_EMAIL', "From email");
define('_CO_ICMS_LINK_FROM_EMAIL_DSC', "");
define('_CO_ICMS_LINK_FROM_NAME', "From name");
define('_CO_ICMS_LINK_FROM_NAME_DSC', "");
define('_CO_ICMS_LINK_FROM_UID', "From user");
define('_CO_ICMS_LINK_FROM_UID_DSC', "");
define('_CO_ICMS_LINK_LINK', "Link");
define('_CO_ICMS_LINK_LINK_DSC', "");
define('_CO_ICMS_LINK_MID', "Module ID");
define('_CO_ICMS_LINK_MID_DSC', "");
define('_CO_ICMS_LINK_MID_NAME', "Module name");
define('_CO_ICMS_LINK_MID_NAME_DSC', "Name of the module from where the request originated");
define('_CO_ICMS_LINK_SUBJECT', "Subject");
define('_CO_ICMS_LINK_SUBJECT_DSC', "");
define('_CO_ICMS_LINK_TO_EMAIL', "To email");
define('_CO_ICMS_LINK_TO_EMAIL_DSC', "");
define('_CO_ICMS_LINK_TO_NAME', "To name");
define('_CO_ICMS_LINK_TO_NAME_DSC', "");
define('_CO_ICMS_LINK_TO_UID', "To user");
define('_CO_ICMS_LINK_TO_UID_DSC', "");
define('_CO_ICMS_MAKE_SELECTION', 'Make a selection...');
define('_CO_ICMS_META_DESCRIPTION', 'Meta Description');
define('_CO_ICMS_META_DESCRIPTION_DSC', 'In order to help Search Engines, you can customize the meta description you would like to use for this article. If you leave this field empty when creating a category, it will automatically be populated with the Summary field of this article.');
define('_CO_ICMS_META_KEYWORDS', 'Meta Keywords');
define('_CO_ICMS_META_KEYWORDS_DSC', 'In order to help Search Engines, you can customize the keywords you would like to use for this article. If you leave this field empty when creating an article, it will automatically be populated with words from the Summary field of this article.');
define('_CO_ICMS_MODIFY', 'Edit');
define('_CO_ICMS_MODULE_BUG', 'Report a bug for this module');
define('_CO_ICMS_MODULE_DEMO', 'Demo Site');
define('_CO_ICMS_MODULE_DISCLAIMER', 'Disclaimer');
define('_CO_ICMS_MODULE_FEATURE', 'Suggest a new feature for this module');
define('_CO_ICMS_MODULE_INFO', 'Module Development Informations');
define('_CO_ICMS_MODULE_RELEASE_DATE', 'Release date');
define('_CO_ICMS_MODULE_STATUS', 'Status');
define('_CO_ICMS_MODULE_SUBMIT_BUG', 'Submit a bug');
define('_CO_ICMS_MODULE_SUBMIT_FEATURE', 'Request a feature');
define('_CO_ICMS_MODULE_SUPPORT', 'Official support site');
define('_CO_ICMS_NO_OBJECT', 'No items to display.');
define('_CO_ICMS_NOT_SELECTED', 'No object selected.');
define('_CO_ICMS_PRINT', 'Print');
define('_CO_ICMS_QUICK_SEARCH', 'Quick search');
define('_CO_ICMS_RATING_DATE', 'Date');
define('_CO_ICMS_RATING_DIRNAME', 'Module');
define('_CO_ICMS_RATING_ITEM', 'Item');
define('_CO_ICMS_RATING_ITEMID', 'Item ID');
define('_CO_ICMS_RATING_NAME', 'User name');
define('_CO_ICMS_RATING_RATE', 'Rate');
define('_CO_ICMS_RATING_UID', 'User');
define('_CO_ICMS_SAVE_ERROR', 'An error occured while storing the information.');
define('_CO_ICMS_SAVE_SUCCESS', 'The information was successfully saved.');
define('_CO_ICMS_SEND_EMAIL', 'Send an email');
define('_CO_ICMS_SEND_ERROR', "A problem occured when sending the message. We apologize for this. Please contact our webmaster at %s.");
define('_CO_ICMS_SEND_LINK_FORM', "Send this link to a friend");
define('_CO_ICMS_SEND_LINK_FORM_DSC', "Simply fill the following form in order to share this link with a friend.");
define('_CO_ICMS_SEND_PM', 'Send a private message');
define('_CO_ICMS_SEND_SUCCESS', "The message has been sent successfully.");
define('_CO_ICMS_SEND_SUCCESS_INFO', "Thank you for sharing your interest for our site with your contacts.");
define('_CO_ICMS_SHORT_URL', 'Short URL');
define('_CO_ICMS_SHORT_URL_DSC', 'When using the SEO features of this module, you can specify a Short URL for this category. This field is optional.');
define('_CO_ICMS_SORT', "Sort by :");
define('_CO_ICMS_SORT_ASC', 'Ascending ');
define('_CO_ICMS_SORT_DESC', 'Descending ');
define('_CO_ICMS_SUBJECT_DEFAULT', "A link from %s");
define('_CO_ICMS_SUBMIT', 'Submit');
define('_CO_ICMS_TAG_DESCRIPTION_CAPTION', "Description");
define('_CO_ICMS_TAG_DESCRIPTION_DSC', "Description of this tag (where will it be used, etc...)");
define('_CO_ICMS_TAG_TAGID_CAPTION', "Tag name");
define('_CO_ICMS_TAG_TAGID_DSC', "Name that uniquely identifies this tag ");
define('_CO_ICMS_TAG_VALUE_CAPTION', "Value");
define('_CO_ICMS_TAG_VALUE_DSC', "Value of this tag, ie what will be displayed to the user");
define('_CO_ICMS_UPDATE_MODULE', 'Update module');
define('_CO_ICMS_UPLOAD_IMAGE', 'Upload a new image :');
define('_CO_ICMS_VERSION_HISTORY', 'Version History');
define('_CO_ICMS_WARNING_BETA', "This module comes as is, without any guarantees whatsoever. This module is BETA, meaning it is still under active development. This release is meant for <b>testing purposes only</b> and we <b>strongly</b> recommend that you do not use it on a live website or in a production environment.");
define('_CO_ICMS_WARNING_FINAL', "This module comes as is, without any guarantees whatsoever. Although this module is not beta, it is still under active development. This release can be used in a live website or a production environment, but its use is under your own responsibility, which means the author is not responsible.");
define('_CO_ICMS_WARNING_RC', "This module comes as is, without any guarantees whatsoever. This module is a Release Candidate and should not be used on a production web site. The module is still under active development and its use is under your own responsibility, which means the author is not responsible.");
define('_CO_ICMS_WEIGHT_FORM_CAPTION', 'Weight');
define('_CO_ICMS_WEIGHT_FORM_DSC', "");

define('_CO_ICMS_ADMIN_VIEW', "View");
define('_CO_ICMS_EXPORT', "Export");
define('_CO_ICMS_UPDATE_ALL', "Update all");
define('_CO_ICMS_NO_RECORDS_TO_UPDATE', "No records to update");
define('_CO_ICMS_NO_RECORDS_UPDATED', "Objects successfully updated !");

define('_CO_ICMS_CLONE', "Clone this object");

define('_CO_ICMS_CATEGORY_VIEW', "Category view");

define('_CO_ICMS_CURRENT_FILE', "Current file: ");
define('_CO_ICMS_URL_FILE_DSC', "Alternatively, you can use an URL. If you select a file via 'Browse' button, URL will be ignored. You can use the tag {ICMS_URL} to print ".ICMS_URL);
define('_CO_ICMS_URL_FILE', "URL: ");
define('_CO_ICMS_UPLOAD', "Select a file to upload: ");

define('_CO_ICMS_CHANGE_FILE', "<hr/><strong>Change File</strong><br/>");
define('_CO_ICMS_CAPTION', "Caption: ");
define('_CO_ICMS_URLLINK_URL', "URL: ");
define('_CO_ICMS_DESC', "Description");
define('_CO_ICMS_URLLINK_TARGET', "Open link in:");
define('_CO_ICMS_URLLINK_SELF', "Same window");
define('_CO_ICMS_URLLINK_BLANK', "new window");

define('_CO_ICMS_ANY', "Any");
define('_CO_ICMS_EDITOR', "Prefered text editor");
define('_CO_ICMS_WITH_SELECTED', "With selected: ");
// Added Since ImpressCMS 1.2
define('_CO_ICMS_UPDATE_DBVERSION', "Database migrate to version '<em>%s</em>' <br /> ");
define('_CO_ICMS_ON', "On");
define('_CO_ICMS_OFF', "Off");
define('_CO_ICMS_WARNING_ALPHA', "This module comes as is, without any guarantees whatsoever. This module is ALPHA, meaning it is still under active development. This release is meant for <b>development and testing purposes only</b>, and we <b>strongly</b> recommend that you do not use it on a live website or in a production environment.");
define('_CO_ICMS_UPDATE_NEEDED','Your system module needs updating');
?>