<?php

/**
 * Redirect to the public directory
 * This file ensures that when someone accesses the root folder, they are redirected to the public directory
 */

header('Location: public/');
exit;
