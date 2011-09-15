<p>Dear [[+user.fullname]],</p>

<p>This automated e-mail was sent to inform you that an administrator has cancelled your "[[+product.name]]" subscription payments. (Subscription ID: [[+subscription.sub_id]]). This was most likely requested. </p>
<p>Your subscription and related benefits will be active until [[+subscription.expires]].</p>

<p><strong> Subscription information</strong><br />
    Subscription ID: [[+subscription.sub_id]]<br />
    Active: [[+subscription.active:eq=`1`:then=`Yes`:else=`No`]]<br />
    Product: [[+product.name]] (#[[+product.product_id]])<br />
    Started: [[+subscription.start]]<br />
    Expiry Date: [[+subscription.expires]]<br />
    PayPal Profile ID*: [[+subscription.pp_profileid]]<br />
    Username**: [[+user.username]]
</p>

<p>If you did not request your profile to be cancelled and think this may be in error, please get in touch.</p>

<p>Kind regards,<br />
    [[+settings.site_name]] Administration</p>

<p style="font-size: 80%">* These details only apply with automatic recurring payments via PayPal.<br />
** In case this is a digital subscription, this username has been affected by the transaction</p>
<p style="font-size: 80%">You will receive another notification when your subscription has expired.</p>