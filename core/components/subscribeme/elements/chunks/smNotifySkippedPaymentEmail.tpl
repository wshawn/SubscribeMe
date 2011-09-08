<p>Dear [[+user.fullname]],</p>

<p>This automated e-mail was sent to inform you that a recurring payment has been skipped on PayPal, related to your "[[+product.name]]" subscription. (Subscription ID: [[+subscription.sub_id]]).</p>
<p>In order to continue your subscription, please check if your PayPal account has enough balance. PayPal will automatically try to collect the amount due a few times at different intervals, however if that fails continually your subscription will expire.</p>
<p>Your subscription and related benefits is currently set to expire on [[+subscription.expires]]. If no payment has been received before that, you will not be able to access your benefits after that time.</p>

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
<p style="font-size: 80%">You will receive this email on every skipped payment notification received by PayPal. If you wish to discontinue your subscription, you can cancel it via your PayPal profile.</p>