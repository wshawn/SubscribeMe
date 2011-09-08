<?php

$snips = array(
    'smCheckout' => 'Standalone snipppet that will output the different payment options.',
    'smCompletePayPalSubscription' => 'FormIt hook that, based on posted "token" and "PayerID" values finishes the payment process.',
    'smGetUserDataFromPayPal' => 'FormIt pre-hook that will fetch user details from their profile, and if available overrides that with data from PayPal. Requires a token.',
    'smListProducts' => 'Standalone snippet that will output a list of the products.',
    'smNewSubscription' => 'FormIt hook that will create a new subscription object for the current user, and redirects to the payment options screen.',
    'smUpdateUserProfile' => 'FormIt hook that will save POST data to a users profile.',
    'smGetSubscriptions' => 'Snippet to fetch the '
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

