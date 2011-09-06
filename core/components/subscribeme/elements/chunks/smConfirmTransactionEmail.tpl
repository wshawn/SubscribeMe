<p>Dear [[+user.fullname]],</p>

<p>This automated e-mail was sent to inform you of a processed transactions for your "[[+product.name]]" subscription (ID: [[+subscription.sub_id]]).</p>

<p><strong>Transaction Information</strong><br />
    Method: [[+transaction.method]]<br />
    Reference: [[+transaction.reference]]<br />
    Amount: [[+transaction.amount]]<br />
    Processed at: [[+transaction.updatedon]]
</p>

<p><strong>Updated Subscription information</strong><br />
    Subscription ID: [[+subscription.sub_id]]<br />
    Active: [[+subscription.active:eq=`1`:then=`Yes`:else=`No`]]<br />
    Product: [[+product.name]] (#[[+product.product_id]])<br />
    Started: [[+subscription.start]]<br />
    Expiry Date: [[+subscription.expires]]<br />
    PayPal Profile ID*: [[+subscription.pp_profileid]]<br />
    Username**: [[+user.username]]
</p>

<p>Thank you for your (continued) purchase.</p>

<p>Kind regards,<br />
    [[+settings.site_name]] Administration</p>

<p style="font-size: 80%">* These details only apply with automatic recurring payments via PayPal.<br />
** In case this is a digital subscription, this username has been affected by the transaction</p>
<p style="font-size: 80%">If you have set up an recurring payments profile via PayPal, your subscription will run indefinitely until canceled.
You will be able to cancel your subscription via your profile at www.paypal.com or via the website.</p>