<?php
/* @var array $_lang */
$_lang['subscribeme.prop.debug'] = 'Enable Debug';
$_lang['subscribeme.prop_desc.debug'] = 'By enabling debug (globally via the system setting or per-snippet) all available data will be dumped into output.';
$_lang['subscribeme.prop.redirect'] = 'Enable Redirect';
$_lang['subscribeme.prop_desc.redirect'] = 'When enabled, the snippet will directly redirect to PayPal and not offer the payment options screen.';
$_lang['subscribeme.prop.tpl'] = 'Template Chunk';
$_lang['subscribeme.prop_desc.tpl'] = 'Name of a Chunk to use for the output of the snippet.';
$_lang['subscribeme.prop.toPlaceholder'] = 'To Placeholder';
$_lang['subscribeme.prop_desc.toPlaceholder'] = 'The name of a placeholder to assign the output to. The snippet will, when set, output nothing.';
$_lang['subscribeme.prop.return_id'] = 'Return Resource ID';
$_lang['subscribeme.prop_desc.return_id'] = 'The ID of a Resource to be used for the next checkout step. This will be passed to PayPal.';
$_lang['subscribeme.prop.cancel_id'] = 'Cancel Resource ID';
$_lang['subscribeme.prop_desc.cancel_id'] = 'The ID of a Resource to be displayed when the PayPal authorization was cancelled.';
$_lang['subscribeme.prop.fail_id'] = 'Failure Resource ID';
$_lang['subscribeme.prop_desc.fail_id'] = 'The ID of a Resource to be displayed when the PayPal authorization or transaction failed.';

$_lang['subscribeme.prop.completedResource'] = 'Completed Resource ID';
$_lang['subscribeme.prop_desc.completedResource'] = 'The ID of a Resource to redirect to upon completion. (Note: set as property on the FormIt call)';
$_lang['subscribeme.prop.errorResource'] = 'Error Resource ID';
$_lang['subscribeme.prop_desc.errorResource'] = 'The ID of a Resource to redirect to in case of an error. (Note: set as property on the FormIt call)';
$_lang['subscribeme.prop.optionsResource'] = 'Payment Options Resource ID';
$_lang['subscribeme.prop_desc.optionsResource'] = 'The ID of the Resource containing the smCheckout snippet to give the payment options. (Note: set as property on the FormIt call)';

$_lang['subscribeme.prop.start'] = 'Start (Offset)';
$_lang['subscribeme.prop_desc.start'] = 'An index number indicating where to start (could be used with pagination).';
$_lang['subscribeme.prop.limit'] = 'Limit';
$_lang['subscribeme.prop_desc.limit'] = 'Maximum number of items to display.';
$_lang['subscribeme.prop.sort'] = 'Sort Field';
$_lang['subscribeme.prop_desc.sort'] = 'The Field to sort by. Accepts all fields of the object, such as sortorder, name, description and price.';
$_lang['subscribeme.prop.sortdir'] = 'Sort Direction';
$_lang['subscribeme.prop_desc.sortdir'] = 'Direction to sort on, either ASC or DESC.';
$_lang['subscribeme.prop.tplOuter'] = 'Outer Template Chunk';
$_lang['subscribeme.prop_desc.tplOuter'] = 'Name of a Chunk to use to wrap the entire result set in.';
$_lang['subscribeme.prop.tplRow'] = 'Row Template Chunk';
$_lang['subscribeme.prop_desc.tplRow'] = 'Name of a Chunk to use to wrap individual results in.';
$_lang['subscribeme.prop.activeOnly'] = 'Active Only';
$_lang['subscribeme.prop_desc.activeOnly'] = 'Only show products marked as Active in the component.';
$_lang['subscribeme.prop.separator'] = 'Row Separator';
$_lang['subscribeme.prop_desc.separator'] = 'Separator to use between individual results. Defaults to \n';


?>