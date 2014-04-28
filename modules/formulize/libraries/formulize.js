// formulize.js
// 

if (typeof xoopsGetElementById != 'function') {
    // the 'xoopsGetElementById' function is included with xoops, so when it is missing, Formulize is embedded in another CMS

    // This block contains javascript used by Formulize when embedded in an external CMS such
    //   as Drupal, Joomla or Wordpress. The integration plugins should include this file.

    jQuery(document).ready(function() {
        // for grouped checkbox elements, this performs the 'check all' behaviour
        jQuery('input.checkemall').click(function() {
            if (jQuery(this).is(":checked")) {
                jQuery(this).parents(".grouped").find("input").attr("checked", true);
            } else {
                jQuery(this).parents(".grouped").find("input").attr("checked", false);
            }
        });
    });
}

jQuery(document).ready(function() {
    // set formulizechanged when the 'check all' checkbox is clicked, or an autocomplete changes
    jQuery('input.checkemall, .formulize_autocomplete').click(function() {
        formulizechanged = 1;
    });
});

// check for jquery
if (typeof jQuery == 'undefined' || jQuery.fn.jquery != "1.11.0") { 
    // if jquery is not included or out of date
    var head = document.getElementsByTagName('head')[0];
    script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'http://code.jquery.com/jquery-1.11.0.min.js';
    head.appendChild(script);
}

// check for jquery migrate
if (typeof jQuery.migrateWarnings == 'undefined') {
    var jqmig = document.createElement('script');
    jqmig.type = 'text/javascript';
    jqmig.src = 'http://code.jquery.com/jquery-migrate-1.2.1.min.js';
    document.getElementsByTagName('head')[0].appendChild(jqmig);
}

// check for jquery ui 
if (!jQuery.ui || jQuery.ui.version != "1.8.2") {
    var jqui = document.createElement('script');
    jqui.type = 'text/javascript';
    jqui.src = "".XOOPS_URL."/modules/formulize/libraries/jquery/jquery-ui-1.8.2.custom.min.js";
    document.getElementsByTagName('head')[0].appendChild(jqui);

    jquicss = document.createElement("link");
    jquicss.type = 'test/css';
    jquicss.href = "".XOOPS_URL."/modules/formulize/libraries/jquery/css/start/jquery-ui-1.8.2.custom.css";
    document.getElementsByTagName('head')[0].appendChild(jquicss);
}