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
$xpdo_meta_map['smProduct']= array (
  'package' => 'subscribeme',
  'table' => 'sm_product',
  'fields' => 
  array (
    'product_id' => NULL,
    'name' => 'A New SubscribeMe Subscription',
    'description' => '',
    'sortorder' => 0,
    'price' => 0,
    'amount_shipping' => 0,
    'amount_vat' => 0,
    'periods' => 1,
    'period' => 'M',
    'active' => 1,
  ),
  'fieldMeta' => 
  array (
    'product_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
      'generated' => 'native',
      'attributes' => 'unsigned',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '256',
      'phptype' => 'string',
      'null' => false,
      'default' => 'A New SubscribeMe Subscription',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'default' => '',
    ),
    'sortorder' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'default' => 0,
    ),
    'price' => 
    array (
      'dbtype' => 'numeric',
      'precision' => '7,2',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'amount_shipping' => 
    array (
      'dbtype' => 'numeric',
      'precision' => '7,2',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'amount_vat' => 
    array (
      'dbtype' => 'numeric',
      'precision' => '7,2',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'periods' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'period' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '1',
      'phptype' => 'string',
      'null' => false,
      'default' => 'M',
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 1,
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'product_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'Subscriptions' => 
    array (
      'class' => 'smSubscription',
      'local' => 'product_id',
      'foreign' => 'product_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'composites' => 
  array (
    'Permissions' => 
    array (
      'class' => 'smProductPermissions',
      'local' => 'product_id',
      'foreign' => 'product_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
