<?php
return [
    'fields' =>
        [
            'id' =>
                [
                    'field' => 'id',
                    'type' => 'int(11)',
                    'primary' => true,
                    'default' => null,
                    'auto_increment' => true,
                ],
            'id_event' =>
                [
                    'field' => 'id_event',
                    'type' => 'int(11)',
                    'primary' => false,
                    'default' => null,
                    'auto_increment' => false,
                ],
            'id_volunteer' =>
                [
                    'field' => 'id_volunteer',
                    'type' => 'int(11)',
                    'primary' => false,
                    'default' => null,
                    'auto_increment' => false,
                ],
            'hash' =>
                [
                    'field' => 'hash',
                    'type' => 'varchar(50)',
                    'primary' => false,
                    'default' => null,
                    'auto_increment' => false,
                ],
            'first_name' =>
                [
                    'field' => 'first_name',
                    'type' => 'varchar(255)',
                    'primary' => false,
                    'default' => null,
                    'auto_increment' => false,
                ],
            'last_name' =>
                [
                    'field' => 'last_name',
                    'type' => 'varchar(255)',
                    'primary' => false,
                    'default' => null,
                    'auto_increment' => false,
                ],
            'email' =>
                [
                    'field' => 'email',
                    'type' => 'varchar(255)',
                    'primary' => false,
                    'default' => null,
                    'auto_increment' => false,
                ],
            'phone' =>
                [
                    'field' => 'phone',
                    'type' => 'varchar(255)',
                    'primary' => false,
                    'default' => null,
                    'auto_increment' => false,
                ],
            'address' =>
                [
                    'field' => 'address',
                    'type' => 'varchar(255)',
                    'primary' => false,
                    'default' => null,
                    'auto_increment' => false,
                ],
            'city' =>
                [
                    'field' => 'city',
                    'type' => 'varchar(255)',
                    'primary' => false,
                    'default' => null,
                    'auto_increment' => false,
                ],
            'dob' =>
                [
                    'field' => 'dob',
                    'type' => 'date',
                    'primary' => false,
                    'default' => null,
                    'auto_increment' => false,
                ],
            'volunteer_notes' =>
                [
                    'field' => 'volunteer_notes',
                    'type' => 'text',
                    'primary' => false,
                    'default' => null,
                    'auto_increment' => false,
                ],
            'registered' =>
                [
                    'field' => 'registered',
                    'type' => 'datetime',
                    'primary' => false,
                    'default' => null,
                    'auto_increment' => false,
                ],
        ],
    'indexes' =>
        [
            'PRIMARY' =>
                [
                    'fields' =>
                        [
                            0 => 'id',
                        ],
                    'unique' => true,
                    'fulltext' => false,
                    'type' => 'BTREE',
                ],
            'id_event_id_volunteer' =>
                [
                    'fields' =>
                        [
                            0 => 'id_event',
                            1 => 'id_volunteer',
                        ],
                    'unique' => true,
                    'fulltext' => false,
                    'type' => 'BTREE',
                ],
            'hash' =>
                [
                    'fields' =>
                        [
                            0 => 'hash',
                        ],
                    'unique' => true,
                    'fulltext' => false,
                    'type' => 'BTREE',
                ],
        ],
];
