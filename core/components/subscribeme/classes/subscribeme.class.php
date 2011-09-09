<?php

/**
 * SubscribeMe class 
 */
class SubscribeMe {
    public $modx;
    public $config = array();
    private $chunks = array();
    public $periodUsable = array('D' => 'day', 'W' => 'week', 'M' => 'month', 'Y' => 'year');

    /**
     * @param \modX $modx
     * @param array $config
     * @return \SubscribeMe
     */
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
 
        $basePath = $this->modx->getOption('subscribeme.core_path',$config,$this->modx->getOption('core_path').'components/subscribeme/');
        $assetsUrl = $this->modx->getOption('subscribeme.assets_url',$config,$this->modx->getOption('assets_url').'components/subscribeme/');
        $assetsPath = $this->modx->getOption('subscribeme.assets_path',$config,$this->modx->getOption('assets_path').'components/subscribeme/');
        $this->config = array_merge(array(
            'base_bath' => $basePath,
            'core_path' => $basePath,
            'model_path' => $basePath.'model/',
            'processors_path' => $basePath.'processors/',
            'elements_path' => $basePath.'elements/',
            'assets_path' => $assetsPath,
            'js_url' => $assetsUrl.'js/',
            'css_url' => $assetsUrl.'css/',
            'assets_url' => $assetsUrl,
            'connector_url' => $assetsUrl.'connector.php',
            'debug' => $this->modx->getOption('subscribeme.debug',null,false)
        ),$config);

        $this->modx->addPackage('subscribeme',$this->config['model_path']);
        $this->modx->lexicon->load('subscribeme:default');
    }

    /**
     * @param string $ctx Context name
     * @return bool
     */
    public function initialize($ctx = 'web') {
        return true;
    }

    /* getChunk & _GetTplChunk by splittingred */
    /**
    * Gets a Chunk and caches it; also falls back to file-based templates
    * for easier debugging.
    *
    * @access public
    * @param string $name The name of the Chunk
    * @param array $properties The properties for the Chunk
    * @return string The processed content of the Chunk
    */
    public function getChunk($name,$properties = array()) {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            $chunk = $this->_getTplChunk($name);
            if (empty($chunk)) {
                $chunk = $this->modx->getObject('modChunk',array('name' => $name),true);
                if ($chunk == false) return false;
            }
            $this->chunks[$name] = $chunk->getContent();
        } else {
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }

    /**
    * Returns a modChunk object from a template file.
    *
    * @access private
    * @param string $name The name of the Chunk. Will parse to name.chunk.tpl
    * @param string $postFix The postfix to append to the name
    * @return modChunk/boolean Returns the modChunk object if found, otherwise
    * false.
    */
    private function _getTplChunk($name,$postFix = '.tpl') {
        $chunk = false;
        $f = $this->config['elements_path'].'chunks/'.$name.$postFix;
        if (file_exists($f)) {
            $o = file_get_contents($f);
            /* @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name',$name);
            $chunk->setContent($o);
        }
        return $chunk;
    }

    /**
     * @param \smTransaction $trans
     * @return bool|string Returns true if succesful, or an error string when something went wrong.
     */
    public function processTransaction(smTransaction $trans) {
        // Get the subscription
        $sub = $trans->getOne('Subscription');
        if (!($sub instanceof smSubscription))
            return 'Unable to find the subscription belonging to the transaction.';

        // Get the product
        $product = $sub->getOne('Product');
        if (!($product instanceof smProduct))
            return 'Unable to find the product belonging to the subscription.';

        // We'll need the period from the product
        $periodUsable = array('D' => 'day', 'W' => 'week', 'M' => 'month', 'Y' => 'year');
        $prodPeriod = $product->get('periods'); // the number of periods
        $prodPeriod .= ' '.$periodUsable[$product->get('period')]; // the actual period entity (day)

        // Recalculate the expires column.
        $subExpCur = $sub->get('expires');                  // Get the current expires time in format 2011-08-30 14:17:22
        $subExp = strtotime($subExpCur);                    // Parse the time into a unix timestamp
        if ($subExp < time()) $subExp = time();             // Make sure that the current expiry date is at least the same as now.
        $subExp = strtotime('+' . $prodPeriod,$subExp);     // Take the "+2 week" from the product, and add it.
        if ($subExp < $subExpCur)                           // Do a simple check to make sure the new expires date is larger than earlier
            return 'Error calculating the new expiring timestamp.';

        // Update the expires column
        $subExp = date('Y-m-d H:i:s',$subExp);          // First change it to a format MySQL will surely understand.
        $sub->set('expires', $subExp);                  // Change it
        $sub->set('active', true);                      // Make sure the subscription is set to active
        if (!$sub->save())                              // Save & if that failed return an error.
            return 'Error updating subscription with new expires timestamp.';

        // Get the permissions to set from the product
        $pperms = $product->getMany('Permissions');

        foreach ($pperms as $pp) {
            // Only process if it's the right object type
            if ($pp instanceof smProductPermissions) {
                /* @var smProductPermissions $pp */
                $ppArray = $pp->toArray();
                // Check if the user is already a member of this group.
                $ugTest = $this->modx->getObject('modUserGroupMember',array(
                    'user_group' => $ppArray['usergroup'],
                    'role' => $ppArray['role'],
                    'member' => $sub->get('user_id')
                ));
                // If no user group with the requirements was found..
                if (!($ugTest instanceof modUserGroupMember)) {
                    // Create a new user group membership
                    /* @var modUserGroupMember $ug */
                    $ug = $this->modx->newObject('modUserGroupMember');
                    $ug->fromArray(
                        array(
                            'user_group' => $ppArray['usergroup'],
                            'role' => $ppArray['role'],
                            'member' => $sub->get('user_id')
                        )
                    );
                    if (!$ug->save())
                        return 'Error saving user group '.$ppArray['usergroup'].' with role '.$ppArray['role'];
                }
            }
        }

        // If we got here all things went as intended.
        $trans->set('completed',true);
        $trans->set('updatedon',date('Y-m-d H:i:s'));
        if ($trans->save()) {
            // We'll need to send an email to the user too, to confirm we received the payment.
            /* @var modUser $user */
            $user = $this->modx->getObject('modUser',$sub->get('user_id'));
            if ($user instanceof modUser) {
                $up = $user->getOne('Profile');
                $upa = array();
                if ($up instanceof modUserProfile)
                    $upa = $up->toArray();
                $chunk = $this->modx->getOption('subscribeme.email.confirmtransaction',null,'smConfirmTransactionEmail');
                $phs = array(
                    'user' => array_merge($user->toArray(),$upa),
                    'subscription' => $sub->toArray(),
                    'transaction' => $trans->toArray(),
                    'product' => $product->toArray(),
                    'settings' => $this->modx->config,
                );
                $msg = $this->getChunk($chunk,$phs);
                $subject =  $this->modx->getOption('subscribeme.email.confirmtransaction.subject',null,'Transaction processed for [[+product]] subscription');
                $subject = str_replace(
                    array('[[+transid]]','[[+product]]'),
                    array($trans->get('trans_id'),$product->get('name')),
                    $subject
                );
                if ($user->sendEmail($msg,array('subject' => $subject)) !== true)
                    return 'Error sending payment confirmation email.';
                return true;
            }
            else {
                return 'Error fetching user to send transaction confirmation email.';
            }
        }
        else
            return 'Error marking transaction as completed.';
    }

    /**
     * Used to send a notification email to a user for IPN notification.
     *
     * @param string $type Type of notification email to send.
     * @param \smSubscription $subscription The relevant Subscription object
     * @param \modUser $user The relevant User object.
     * @param \smProduct $product The relevant Product object
     * @param string|\smTransaction $transaction If a transaction is involved, the transaction object.
     * @return bool|string True if successful, an error message if not.
     */
    public function sendNotificationEmail($type = '', smSubscription $subscription, modUser $user, smProduct $product, $transaction = '') {
        $chunk = ''; $subject = ''; $phs = array();
        if (!($user instanceof modUser) || !($subscription instanceof smSubscription) || !($product instanceof smProduct)) {
            $this->modx->log(MODX_LEVEL_ERROR,'Error: invalid parameter(s) in SubscribeMe::sendNotificationEmail');
            return 'Invalid parameter(s) in SubscribeMe::sendNotificationEmail';
        }

        $up = $user->getOne('Profile');
        $userarray = $user->toArray();
        if ($up instanceof modUserProfile)
            $userarray = array_merge($userarray,$up->toArray());
        
        $phs = array(
            'user' => $userarray,
            'subscription' => $subscription->toArray(),
            'product' => $product->toArray(),
            'settings' => $this->modx->config,
        );
        if ($transaction instanceof smTransaction) {
            $phs['transaction'] = $transaction->toArray();
        }

        switch ($type) {
            case 'recurring_payment_profile_cancel':
                    $chunk = $this->modx->getOption('subscribeme.email.confirmcancel',null,'smConfirmCancelEmail');
                    $subject = $this->modx->getOption('subscribeme.email.confirmcancel.subject',null,'Your recurring payments profile for [[+product]] has been canceled.');
                break;

            case 'recurring_payment_skipped':
                    $chunk = $this->modx->getOption('subscribeme.email.notifyskippedpayment',null,'smNotifySkippedPaymentEmail');
                    $subject = $this->modx->getOption('subscribeme.email.notifyskippedpayment.subject',null,'A payment for your [[+product]] subscription has been skipped.');
                break;

            case 'recurring_payment_expired':
                    $chunk = $this->modx->getOption('subscribeme.email.paymentexpired',null,'smPaymentExpiredEmail');
                    $subject = $this->modx->getOption('subscribeme.email.paymentexpired.subject',null,'Your Recurring Payment for [[+product]] has expired.');
                break;
        }

        $msg = $this->getChunk($chunk,$phs);
        $subject = str_replace(
            array('[[+product]]'),
            array($product->get('name')),
            $subject
        );
        if ($transaction instanceof smTransaction) {
            $subject = str_replace(
                array('[[+transid]]','[[+transaction.method]]'),
                array($transaction->get('id'),$transaction->get('method')),
                $subject
            );
        }
        if ($user->sendEmail($msg,array('subject' => $subject)) !== true)
            return 'Error sending email to user.';
        return true;
    }


    /**
     * @param $userid The User ID to check.
     * @return bool True/false depending on success of checking. Does not imply something changed or not.
     */
    public function checkForExpiredSubscriptions($userid) {
        if (!is_numeric($userid) || $userid < 0) { $this->modx->log(MODX_LEVEL_WARNING,'SubscribeMe::checkForExpired fired without valid user ID.'); return false; }

        $debug = $this->config['debug'];

        // Let's make a fun query! :)

        // We'll start looking from the subscription perspective...
        $c = $this->modx->newQuery('smSubscription');
        // ... joining the subscription with the product permissions associated with it ...
        $c->rightJoin('smProductPermissions','ProdPerms','smSubscription.product_id = ProdPerms.product_id');
        // ... where the user id of the subscription matches the user we're looking for
        $c->where(
            array(
                 'smSubscription.user_id' => $userid,
                 'UserGroupMember.id:>' => 0,
            )
        );

        $c->leftJoin('modUserGroupMember','UserGroupMember','(
            UserGroupMember.member = smSubscription.user_id AND
            UserGroupMember.user_group = ProdPerms.usergroup AND
            UserGroupMember.role = ProdPerms.role)'
        );

        // ... but we'll only need a few fields
        $c->select(
            array(
                 'smSubscription.sub_id as sub_id',
                 'smSubscription.expires as expires',
                 'ProdPerms.usergroup as usergroup',
                 'ProdPerms.role as role',
                 'UserGroupMember.id as ugm_id'
            )
        );

        // We're using the same query up to this point in the sub query to filter out duplicate subscriptions not set
        // to expire yet.
        $c->prepare();
        $subc = $c->toSQL();
        $subc .= 'AND smSubscription.expires > NOW()';
        // Add the condition
        $c->andCondition(
            array(
                'NOT EXISTS('.$subc.')'
            )
        );

        // Check if there's any results
        $count = $this->modx->getCount('smSubscription',$c);

        // Debug SQL - shouldn't be needed with normal debugging but only for development.
        $c->prepare();
        if ($debug) $this->modx->log(1,$c->toSQL());

        if (($count < 1) || !is_numeric($count)) {
            if ($debug) $this->modx->log(MODX_LEVEL_ERROR,'Nothing needs to be taken care of!');
            return '';
        }

        // We found something that needs to be unset.
        if ($debug) $this->modx->log(MODX_LEVEL_ERROR,'Found a user group that needs to be unset.');

        $permissions = $this->modx->getCollection('smSubscription',$c);
        foreach ($permissions as $p) {
            if ($p instanceof smSubscription) {
                //$ta = $p->get(array('sub_id','expires','ug','rl'));
                $ta = array(
                    'usergroup' => $p->get('usergroup'),
                    'role' => $p->get('role'),
                    'sub_id' => $p->get('sub_id'),
                    'expires' => $p->get('expires'),
                    'ugm_id' => $p->get('ugm_id'),
                );

                /* Remove the user group membership */
                $ugm = $this->modx->getObject('modUserGroupMember',$ta['ugm_id']);
                if ($ugm instanceof modUserGroupMember) {
                    if (!$ugm->remove())
                        if ($debug) $this->modx->log(MODX_LEVEL_ERROR,'Error removing user group membership '.$ta['ugm_id'].' with regards to subscription '.$ta['sub_id']);
                    else {
                        if ($debug) $this->modx->log(MODX_LEVEL_ERROR,'Removed user group membership '.$ta['ugm_id'].' related to subscription '.$ta['sub_id']);
                        $subObj = $this->modx->getObject('smSubscription',$ta['sub_id']);
                        if ($subObj instanceof smSubscription) {
                            $subObj->set('active',false);
                            if (!$subObj->save())
                                $this->modx->log(MODX_LEVEL_ERROR,'Error marking subscription as inactive.');

                            // @todo send expiring email.

                        }
                    }
                }
            }
        }
        return true;
    }


}
        
?>