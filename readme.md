# SubscribeMe
SubscribeMe is a subscriptions extra for MODX Revolution that, well, seems to work but probably needs more testers!

The key features of SubscribeMe include:

- Ability to set up different recurring subscription plans
- Payments done via PayPal subscriptions (Only PayPal or manual - NO other payment gateways supported or planned)
- Component overview of users on different subscription plans and export of their details to CSV format
- Manually provide users with a free subscription for a certain period (can be used for offline payments)
- Read-only overview of transactions
- Provides a FormIt hook which processes the registration and forwards user to Paypal or offline payment instructions

# Documentation
Documentation can be found on the "MODX RTFM":http://rtfm.modx.com/display/ADDON/SubscribeMe.

# License
SubscribeMe has been released as open source under the GPL v2 (or later) license. This means that while I hope this is useful,
I am not responsible for the effects of using it and can not be held liable for any (financial) damage incurred from using it.

I welcome people taking this addon and customizing it to their needs. A pull request for any improvements would be great!

# Installation Instructions
See the "RTFM":http://rtfm.modx.com/display/ADDON/SubscribeMe.Setting+up+the+Payment+Flow for instructions on setting up the payment flow.

The package can be found in the MODX Revolution Package Manager and has been tested on (and developed for) Revolution 2.1.

1. Install the package, and in Components -> SubscribeMe set up some products.
2. List all products using the smListProducts snippet. The default chunks use a FormIt call in the outer template
   which calls the smNewSubscription hook. The smNewSubscriptions hook creates a new inactive subscription, and then
   redirects to the resource as specified in the FormIt's &optionsResource property, with &subid=16 appended to the
   link. "More information on the RTFM":http://rtfm.modx.com/display/ADDON/SubscribeMe.Listing+Products+with+the+smListProducts+Snippet
3. Set up the resource to display the payment options. PayPal is included, but it could be possible to add other
   methods (like manual payments or other payment options you add yourself) should you want to. The payment options
   resource needs to call the smCheckout snippet. This snippet will set up a token with PayPal, so you could use any
   image/link to get the PayPal checkout started from there. The token is valid for +- 3 hours.
   See the smcheckout.paymentoptions chunk for the default.
   The smCheckout snippet accepts return_id, cancel_id and fail_id properties (if not given defaults to the subscribeme.paypal.<property>
   system setting instead) which will be used to decide where to point the visitor back to.
4. You will need to set your PayPal details in the System Settings, see PayPal instructions below.
5. Set up a confirmation resource (referenced in the smCheckout return_id property) that contains information on
   the subscription and allows you to verify the shipping address, based on the data retrieved from PayPal.
   This page will need a FormIt form with the smCompletePayPalSubscription hook to process the recurring payments
   profile amd confirm the subscription. When succesful will redirect to the resource specified by the completedResource
   property, and when something went wrong it will go to the errorResource property. 
   See example code below.
6. Set up a Completed / Thank you page. Make sure to note that depending on the time it takes for PayPal to process
   the new subscription it could take up to 24hrs for the first payment to clear out, and the subscription being
   activated and the user receiving access to their premium content. Tests in the sandbox gave results of 3-15 mins
   for confirmed accounts.
7. Set up a failed and cancel resource for use in the smCheckout snippet.
8. The various hooks and snippets are user-specific so you will need to be logged in. Make sure you have set up your
   security stuff properly for that, and a dedicated "Unauthorized" resource instead of the default "Error" resource
   would be a good idea, complete with a login form.

## Example code step 5

    <h3>Confirm Shipping Address</h3>
    <p>The following data was retrieved from user profile, and updated based on your PayPal account. If your subscription includes shipping, <strong>make sure all details below are correct</strong>. Your details will then be updated on your user profile (PayPal will not be changed). </p>

    [[!Formit?
      &preHooks=`smGetUserDataFromPayPal`
      &hooks=`smUpdateUserProfile,smCompletePayPalSubscription`
      &completedResource=`8`
      &errorResource=`7`
    ]]
    <form action="[[~[[*id]]]]" method="post">
    <input type="text" name="fullname" value="[[!+fi.fullname]]" /> Name<br />
    <input type="text" name="address" value="[[!+fi.address]]" /> Address<br />
    <input type="text" name="zip" value="[[!+fi.zip]]" /> Zip (Postcode)<br />
    <input type="text" name="city" value="[[!+fi.city]]" /> City<br />
    <input type="text" name="state" value="[[!+fi.state]]" /> State<br />
    <input type="text" name="country" value="[[!+fi.country]]" /> Country<br />

    <input type="hidden" name="token" value="[[!+fi.pp_token]]" />
    <input type="hidden" name="PayerID" value="[[!+fi.pp_payerid]]" />

    <p>By clicking the button below I confirm that my shipping details as stated above are correct. Furthermore I grant [[++site_name]] permission to create a recurring payments profile on PayPal which will automatically pay for the requested subscription. Bla, bla, bla, bla.</p>

    <input type="submit" value="Subscribe!" />
    </form>

# PayPal Instructions

"See the RTFM":http://rtfm.modx.com/display/ADDON/SubscribeMe.Configuring+API+Credentials%2C+IPN+and+going+Live

# Developer
**Mark Hamstra**  
Email: hello@markhamstra.com  
Website: www.markhamstra.com

# Development Funded By
**Jared Loman Creative**  
www.jaredloman.com