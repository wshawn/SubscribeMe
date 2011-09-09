<?php
/* @var modX $modx
 * @var array $scriptProperties
 */

if (!$scriptProperties['user_id'] || !is_numeric($scriptProperties['user_id']))
    return $modx->error->failure($modx->lexicon('sm.error.notspecified',array('what' => 'ID')));

/* As the processTransaction function will add one entire period later, we will need to take that off the expires
period now. Therefore we fetch the product and the period and deduct that from that given expiry date.
*/
/* @var smProduct $product */
$expires = $scriptProperties['expires']; // Get the filled in expiry date
$product = $modx->getObject('smProduct',$scriptProperties['product_id']); // Get the product
$productArray = $product->toArray(); // And put it in an array
$periodUsable = array('D' => 'day', 'W' => 'week', 'M' => 'month', 'Y' => 'year'); // Set up array to transform
$prodPeriod = $productArray['periods'].' '.$periodUsable[$productArray['period']];
$prodPeriod = strtotime($prodPeriod,0);
$expires = strtotime($scriptProperties['expires'].' 23:59:00') - $prodPeriod;
$expires = date('Y-m-d H:i:s',$expires);

/* @var smSubscription $subscription */
$subscription = $modx->newObject('smSubscription');
$subscription->fromArray(
    array(
        'user_id' => $scriptProperties['user_id'],
        'product_id' => $scriptProperties['product_id'],
        'start' => date('Y-m-d H:i:s'),
        'expires' => $expires,
        'active' => true
    )
);
if(!$subscription->save())
    return $modx->error->failure($modx->lexicon('sm.error.savefail'));

/* @var smTransaction $transaction*/
$transaction = $modx->newObject('smTransaction');
$transaction->fromArray(
    array(
        'sub_id' => $subscription->getPrimaryKey(),
        'user_id' => $scriptProperties['user_id'],
        'reference' => $scriptProperties['reference'],
        'method' => 'complimentary',
        'amount' => 0,
    )
);


if (!$transaction->save())
    return $modx->error->failure($modx->lexicon('sm.error.savefail'));

$processTrans = $modx->sm->processTransaction($transaction);
if ($processTrans !== true)
    return $modx->error->failure($modx->lexicon('sm.error.processtransfail',array('result' => $processTrans)));

return $modx->error->success();

?>