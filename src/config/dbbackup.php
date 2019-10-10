<?php

/**
 * Db Backup configuration file to setup disk and folder name for db backup
 */

return [
    'disk' => 'local',
    'visibility' => 'public', // leave it null for private
    'folder' => 'dbbackup',
    'email' => null, //Enter your email here to get emails for dbbackup
];
