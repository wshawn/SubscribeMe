<?php
/**
 * SubscribeMe
 *
 * Copyright 2011 by Mark Hamstra <business@markhamstra.nl>
 *
 * This file is part of SubscribeMe, a subscriptions management extra for MODX Revolution
 *
 * SubscribeMe is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * SubscribeMe is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * SubscribeMe; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
*/

$snips = array(
    'smCheckout' => 'Standalone snipppet that will output the different payment options.',
    'smCompletePayPalSubscription' => 'FormIt hook that, based on posted "token" and "PayerID" values finishes the payment process.',
    'smGetUserDataFromPayPal' => 'FormIt pre-hook that will fetch user details from their profile, and if available overrides that with data from PayPal. Requires a token.',
    'smCancelPayPalProfile' => 'FormIt hook to cancel a Recurring Payment Profile with PayPal. Requires a hidden field with sub_id.',
    'smCancelPayPalProfilePre' => 'FormIt hook to check data and set some values.',
    'smListProducts' => 'Standalone snippet that will output a list of the products.',
    'smNewSubscription' => 'FormIt hook that will create a new subscription object for the current user, and redirects to the payment options screen.',
    'smUpdateUserProfile' => 'FormIt hook that will save POST data to a users profile.',
    'smGetSubscriptions' => 'Snippet to fetch the subscriptions of the currently logged in user.'
);

$snippets = array();
$idx = 0;

foreach ($snips as $sn => $sdesc) {
    $idx++;
    $snippets[$idx] = $modx->newObject('modSnippet');
    $snippets[$idx]->fromArray(array(
       'id' => $idx,
       'name' => $sn,
       'description' => $sdesc . ' (Part of SubscribeMe)',
       'snippet' => getSnippetContent($sources['snippets'].$sn.'.snippet.php')
    ));

    $snippetProperties = array();
    $props = include $sources['snippets'].$sn.'.properties.php';
    foreach ($props as $key => $value) {
        if (is_string($value) || is_int($value)) { $type = 'textfield'; }
        elseif (is_bool($value)) { $type = 'combo-boolean'; }
        else { $type = 'textfield'; }
        $snippetProperties[] = array(
            'name' => $key,
            'desc' => 'subscribeme.prop_desc.'.$key,
            'type' => $type,
            'options' => '',
            'value' => ($value != null) ? $value : '',
            'lexicon' => 'subscribeme:properties'
        );
    }
    
    if (count($snippetProperties) > 0)
        $snippets[$idx]->setProperties($snippetProperties);
}

return $snippets;

