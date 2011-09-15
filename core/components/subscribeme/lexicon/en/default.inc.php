<?php
/* General */
$_lang['subscribeme'] = 'SubscribeMe';
$_lang['subscribeme.desc'] = 'Manage on- and offline subscriptions.';

/* Objects */
$_lang['sm.subscriber'] = 'Subscriber';
$_lang['sm.transaction'] = 'Transaction';
$_lang['sm.subscription'] = 'Subscription';
$_lang['sm.product'] = 'Product';
$_lang['sm.productpermission'] = 'Product Permission';
$_lang['sm.subscribers'] = 'Subscribers';
$_lang['sm.transactions'] = 'Transactions';
$_lang['sm.subscriptions'] = 'Subscriptions';
$_lang['sm.products'] = 'Products';
$_lang['sm.productpermissions'] = 'Product Permissions';
$_lang['sm.subscr'] = 'Subscr.';

$_lang['sm.freesubscription'] = 'Complimentary Subscription';
$_lang['sm.freesubscription.text'] = 'By adding a new Complimentary Subscription to a User account they will receive access to your product for free for a certain period.
    Doing this will create a new complimentary transaction linked to your user account.';
$_lang['sm.subscriber.account'] = 'Account Information';
$_lang['sm.subscriber.profile'] = 'User Profile';
$_lang['sm.subscription.paypalprofile'] = 'View PayPal Profile Data';
$_lang['sm.subscription.paypalprofile.text'] = 'The PayPal Profile is identified by the PayPal Profile ID, and is being retrieved from PayPal directly.
    Below you can review all information PayPal has regarding this Recurring Payments Profile. Note: it may take a few seconds to collect all the data.';

/* Buttons & Actions */
$_lang['sm.button.add'] = 'Add new [[+what]]';
$_lang['sm.button.update'] = 'Update [[+what]]';
$_lang['sm.button.remove'] = 'Remove [[+what]]';
$_lang['sm.button.exportsubs'] = 'Export Subscribers';
$_lang['sm.button.clearfilter'] = 'Reset Filter';
$_lang['sm.combo.filter_on'] = 'Filter on [[+what]]';
$_lang['sm.combo.method'] = 'Payment Method';
$_lang['sm.combo.paypal'] = 'PayPal';
$_lang['sm.combo.manual'] = 'Manual';
$_lang['sm.combo.complimentary'] = 'Complimentary';
$_lang['sm.combo.D'] = 'day';
$_lang['sm.combo.W'] = 'week';
$_lang['sm.combo.M'] = 'month';
$_lang['sm.combo.Y'] = 'year';
$_lang['sm.combo.Ds'] = 'days';
$_lang['sm.combo.Ws'] = 'weeks';
$_lang['sm.combo.Ms'] = 'months';
$_lang['sm.combo.Ys'] = 'years';
$_lang['sm.combo.day'] = 'Day';
$_lang['sm.combo.week'] = 'Week';
$_lang['sm.combo.month'] = 'Month';
$_lang['sm.combo.year'] = 'Year';
$_lang['sm.combo.current'] = 'Currently Subscribed';
$_lang['sm.combo.all'] = 'All User Accounts';
$_lang['sm.search'] = 'Search';
$_lang['sm.search...'] = 'Search...';
$_lang['sm.transaction.markaspaid'] = 'Mark As Paid';
$_lang['sm.transaction.markaspaid.text'] = 'Please enter a reference to be added to the transaction. The transaction will be marked as paid with "manual" as method to indicate it was manually accepted. Any related subscriptions will also be processed after clicking save.';
$_lang['sm.transaction.transactiondetails'] = 'View PayPal Transaction Details';
$_lang['sm.transaction.transactiondetails.text'] = 'You can retrieve additional information on transactions via PayPal. Collecting the data may take a few seconds.';
$_lang['sm.subscription.viewtransactions'] = 'View Related Transactions';
$_lang['sm.subscription.viewtransactions.text'] = 'These are the transactions related to this subscription, and may include manual and complimentary transactions as well.';
$_lang['sm.subscription.manualtransaction'] = 'Add Manual Transaction';
$_lang['sm.subscription.manualtransaction.text'] = 'Manual transactions are filed to properly record transactions that were paid in cash.';
$_lang['sm.subscription.cancel'] = 'Cancel PayPal Subscription';
$_lang['sm.subscription.docancel'] = 'Confirm: Cancel Subscription';
$_lang['sm.subscription.cancelcancel'] = 'Never Mind';
$_lang['sm.subscription.confirmcancel'] = 'Are you sure you want to cancel this subscription? Once this subscription has been cancelled it cannot be undone.<br /><br />Details of this subscription are shown below.';
$_lang['sm.remove'] = 'Remove [[+what]]';
$_lang['sm.remove_successful'] = 'Successfully removed [[+what]]';
$_lang['sm.removed'] = 'Removed [[+what]]';
$_lang['sm.back'] = 'Back';
$_lang['sm.nooptions'] = 'No options available';
$_lang['sm.col1'] = 'Profile Data';
$_lang['sm.col2'] = 'Profile Data (2)';
$_lang['sm.newpass.confirm'] = 'Are you sure you want to create a new password for this user? They will receive an email with the new password and their old one will no longer work.';

/* Fields */
$_lang['sm.fullname'] = 'Full Name';
$_lang['sm.email'] = 'E-mail Address';
$_lang['sm.active'] = 'Active';
$_lang['sm.username'] = 'Username';
$_lang['sm.limit'] = 'Limit';
$_lang['sm.createdon'] = 'Created On';
$_lang['sm.updatedon'] = 'Updated On';
$_lang['sm.reference'] = 'Reference';
$_lang['sm.method'] = 'Method';
$_lang['sm.amount'] = 'Amount';
$_lang['sm.start'] = 'Start';
$_lang['sm.expires'] = 'Expires';
$_lang['sm.name'] = 'Name';
$_lang['sm.description'] = 'Description';
$_lang['sm.sortorder'] = 'Sort Order';
$_lang['sm.price'] = 'Price';
$_lang['sm.amount_shipping'] = 'Shipping';
$_lang['sm.amount_vat'] = 'Taxes';
$_lang['sm.periods'] = 'Cycle';
$_lang['sm.period'] = 'Period';
$_lang['sm.permissions'] = 'Permissions';
$_lang['sm.usergroup'] = 'User Group';
$_lang['sm.role'] = 'Role';
$_lang['sm.completed'] = 'Completed';
$_lang['sm.pp_profileid'] = 'PayPal Profile ID';

/* Errors */
$_lang['sm.error.sendmailfailed'] = 'An error occurred sending email.';
$_lang['sm.error.savefailed'] = 'An error occurred attempting to save your changes.';
$_lang['sm.error.savefailed.user'] = 'An error occurred attempting to save the user account.';
$_lang['sm.error.savefailed.userprofile'] = 'An error occurred attempting to save the user profile.';
$_lang['sm.error.notspecified'] = 'An error occurred: No [[+what]] specified.';
$_lang['sm.error.invalidobject'] = 'An error occurred: The requested object is invalid.';
$_lang['sm.error.noresults'] = 'No results matching your criteria found.';
$_lang['sm.error.removefail'] = 'An error occured attempting to remove the object.';
$_lang['sm.error.savefail'] = 'An error occured attempting to save the object.';
$_lang['sm.error.processtransfail'] = 'An error occured attempting to process the transaction: [[+result]]';
$_lang['sm.error.cantremoveproductinuse'] = 'There are subscriptions for this Product, thus it cannot be removed. Please deactivate it instead of removing if you wish to prevent future subscriptions.';
$_lang['sm.error.notfound'] = 'Requested object not found.';
$_lang['sm.error.exportnotfound'] = 'Requested export could not be found.';
$_lang['sm.error.export_openfile'] = 'Error opening file [[+file]] for writing.';
$_lang['sm.error.export_writeheader'] = 'Error writing header to [[+file]].';
$_lang['sm.error.export_writeentry'] = 'Error writing entry to [[+file]].';
$_lang['sm.error.cancelsubscription.notactive'] = 'Can not cancel this subscription, it is not active (or suspended). Status: [[+status]].';
$_lang['sm.notification.admincancelledsubscription'] = 'An administrator has cancelled your subscription. For more information, please get in touch via our website.';
$_lang['sm.passwordchanged'] = 'A new password has been sent to the user, on email [[+email]].';

/* System Settings */
$_lang['setting_subscribeme.currencycode'] = 'Currency Code';
$_lang['setting_subscribeme.currencycode_desc'] = 'The 3-character Currency Code for the currency in which you want to receive payments.';
$_lang['setting_subscribeme.currencysign'] = 'Currency Sign';
$_lang['setting_subscribeme.currencysign_desc'] = 'The symbol matching your currency.';
$_lang['setting_subscribeme.debug'] = 'Output Debug';
$_lang['setting_subscribeme.debug_desc'] = 'When enabling debug, as much data as possible will be displayed in Snippet output, IPN requests received will be logged extensively to the Error Log and the plugin will also log information to the Error Log. Can be overriden for snippets with &debug in the snippet calls.';
$_lang['setting_subscribeme.paypal.api_username'] = 'PayPal API Username';
$_lang['setting_subscribeme.paypal.api_username_desc'] = 'Your Live PayPal API Username.';
$_lang['setting_subscribeme.paypal.api_password'] = 'PayPal API Password';
$_lang['setting_subscribeme.paypal.api_password_desc'] = 'Your Live PayPal API Password';
$_lang['setting_subscribeme.paypal.api_signature'] = 'PayPal API Signature';
$_lang['setting_subscribeme.paypal.api_signature_desc'] = 'Your Live PayPal API Signature, if you are unsure where to get it from consult the SubscribeMe documentation on the RTFM.';
$_lang['setting_subscribeme.paypal.sandbox_username'] = 'PayPal Sandbox Username';
$_lang['setting_subscribeme.paypal.sandbox_username_desc'] = 'Your PayPal Sandbox Username for testing the implementation.';
$_lang['setting_subscribeme.paypal.sandbox_password'] = 'PayPal Sandbox Password';
$_lang['setting_subscribeme.paypal.sandbox_password_desc'] = 'Your PayPal Sandbox Password for testing the implementation.';
$_lang['setting_subscribeme.paypal.sandbox_signature'] = 'PayPal Sandbox Signature';
$_lang['setting_subscribeme.paypal.sandbox_signature_desc'] = 'Your PayPal Sandbox Signature for testing the implementation. Refer to the SubscribeMe documentation if you need help finding it.';
$_lang['setting_subscribeme.paypal.cancel_id'] = 'Cancel Resource ID';
$_lang['setting_subscribeme.paypal.cancel_id_desc'] = 'The ID of the Resource to redirect to when the action was canceled.';
$_lang['setting_subscribeme.paypal.completed_id'] = 'Completed Resource ID';
$_lang['setting_subscribeme.paypal.completed_id_desc'] = 'The ID of a Resource to redirect to when the action was completed.';
$_lang['setting_subscribeme.paypal.fail_id'] = 'Failure Resource ID';
$_lang['setting_subscribeme.paypal.fail_id_desc'] = 'The ID of a Resource to redirect to when the action failed.';
$_lang['setting_subscribeme.paypal.return_id'] = 'Return Resource ID';
$_lang['setting_subscribeme.paypal.return_id_desc'] = 'The ID of a Resource to return to from PayPal.';
$_lang['setting_subscribeme.paypal.sandbox'] = 'Use Sandbox?';
$_lang['setting_subscribeme.paypal.sandbox_desc'] = 'Enabled by default, this switch decides whether you are using the Sandbox or LIVE implementation of PayPal. IT IS STRONGLY RECOMMENDED TO TRY SANDBOX FIRST! It is your responsibility to get this working, and I will not be liable for  any (financial) damage resulting from improper setup, nor faults in the code.';
$_lang['setting_subscribeme.email.confirmtransaction'] = 'Confirm Transaction Email Chunk';
$_lang['setting_subscribeme.email.confirmtransaction_desc'] = 'The name of a chunk to use as email template for confirming a transaction has been processed to a user. You can use all user, subscription, product and transaction fields in the chunk. For example [ [+user.fullname]] and [ [+product.name]].';
$_lang['setting_subscribeme.email.confirmtransaction.subject'] = 'Confirm Transaction Email Subject';
$_lang['setting_subscribeme.email.confirmtransaction.subject_desc'] = 'The subject to use for the email confirming a transaction has been processed. You can use transid and product as placeholder.';
$_lang['setting_subscribeme.email.confirmcancel'] = 'Confirm Cancellation Email Chunk';
$_lang['setting_subscribeme.email.confirmcancel_desc'] = 'The name of a chunk to use as email template for confirming a recurring payments profile has been cancelled by a user. You can use all user, subscription and product fields in the chunk. For example [ [+user.fullname]] and [ [+subscription.name]].';
$_lang['setting_subscribeme.email.confirmcancel.subject'] = 'Confirm Cancellation Email Subject';
$_lang['setting_subscribeme.email.confirmcancel.subject_desc'] = 'The subject to use for the email confirming a recurring payments profile has been cancelled by a user. You can use product as placeholder.';
$_lang['setting_subscribeme.email.notifyskippedpayment'] = 'Nofiy Skipped Payment Chunk';
$_lang['setting_subscribeme.email.notifyskippedpayment_desc'] = 'The name of a chunk to use as email template for notifying a user that a recurring payment was skipped. You can use all user, subscription, product and transaction fields in the chunk. For example [ [+user.fullname]] and [ [+product.name]].';
$_lang['setting_subscribeme.email.notifyskippedpayment.subject'] = 'Notify Skipped Payment Subject';
$_lang['setting_subscribeme.email.notifyskippedpayment.subject_desc'] = 'The subject to use for the email notifying a user a recurring payment has been skipped. You can use product as placeholder.';

?>