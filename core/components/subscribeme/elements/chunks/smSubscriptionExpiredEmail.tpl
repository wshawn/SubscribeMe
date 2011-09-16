<p>Dear [[+user.fullname]],</p>

<p>This automated e-mail was sent to inform you that your "[[+product.name]]" subscription has <strong>expired</strong> (Subscription ID: [[+subscription.sub_id]]).</p>
<p>As a result of that, any (digital) benefits you may have had, have been removed and you have lost access to the product you subscribed to. You will need to re-subscribe if you want to gain access again.</p>

<p><strong>Subscription information</strong><br />
    Subscription ID: [[+subscription.sub_id]]<br />
    Active: [[+subscription.active:eq=`1`:then=`Yes`:else=`No`]]<br />
    Product: [[+product.name]] (#[[+product.product_id]])<br />
    Started: [[+subscription.start]]<br />
    Expiry Date: [[+subscription.expires]]<br />
    PayPal Profile ID*: [[+subscription.pp_profileid]]<br />
    Username**: [[+user.username]]
</p>

<p>Please contact us via <a href="[[+site_url]]">our website</a> with any questions you may have.</p>

<p>Kind regards,<br />
    [[+settings.site_name]] Administration</p>

<p style="font-size: 80%">* These details only apply with automatic recurring payments via PayPal.<br />
** In case this is a digital subscription, this username has been affected by the transaction.</p>
<p style="font-size: 80%">This is an automated email. If you wish to discontinue your subscription, you can cancel it via your PayPal profile.</p>