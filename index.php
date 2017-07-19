<?php

/**
 * @defgroup plugins_pubIds_urndnb URNdnb Pub ID Plugin
 */

/**
 * @file plugins/pubIds/urndnb/index.php
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * DNB-Mod 2017 by Philipp Franck / DAI
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_pubIds_urndnb
 * @brief Wrapper for urndnb plugin.
 *
 */
require_once('URNdnbPubIdPlugin.inc.php');

return new URNdnbPubIdPlugin();

?>
