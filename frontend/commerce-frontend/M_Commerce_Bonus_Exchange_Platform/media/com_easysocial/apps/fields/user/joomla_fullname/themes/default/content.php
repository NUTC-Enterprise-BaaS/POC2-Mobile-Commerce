<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div data-field-joomla_fullname data-name-format="<?php echo $params->get('format', 1); ?>" data-max="<?php echo $params->get('max'); ?>">

    <ul class="input-vertical list-unstyled">
        <?php if ($params->get('format', 1) == 1 || $params->get('format', 1) == 4){ ?>
        <li>
            <input type="text"
                size="30"
                class="form-control input-sm"
                id="first_name"
                name="first_name"
                value="<?php echo $firstName; ?>"
                data-field-jname-first
                placeholder="<?php echo $params->get('firstname_placeholder', JText::_('PLG_FIELDS_JOOMLA_FULLNAME_PLACEHOLDER_FIRST_NAME', true));?>"
                <?php if ($field->isRequired()) { ?>data-check-required<?php } ?>
                <?php if ($params->get('regex_validate')) { ?>
                data-check-validate
                data-check-format="<?php echo $params->get('regex_format'); ?>"
                data-check-modifier="<?php echo $params->get('regex_modifier'); ?>"
                <?php } ?>
                />
        </li>
        <?php } ?>

        <?php if ($params->get('format', 1) == 2 || $params->get('format', 1) == 5){ ?>
        <li>
            <input type="text"
                size="30"
                class="form-control input-sm"
                id="last_name"
                name="last_name"
                value="<?php echo $lastName; ?>"
                data-field-jname-last
                placeholder="<?php echo $params->get('lastname_placeholder', JText::_('PLG_FIELDS_JOOMLA_FULLNAME_PLACEHOLDER_LAST_NAME', true));?>"
                <?php if ($params->get('regex_validate')) { ?>
                data-check-validate
                data-check-format="<?php echo $params->get('regex_format'); ?>"
                data-check-modifier="<?php echo $params->get('regex_modifier'); ?>"
                <?php } ?>
                />
        </li>
        <?php } ?>

        <?php if ($params->get('format', 1) == 3){ ?>
        <li>
            <input type="text"
                size="30"
                class="form-control input-sm"
                id="first_name"
                name="first_name"
                value="<?php echo $name; ?>"
                data-field-jname-name
                placeholder="<?php echo $params->get('singlename_placeholder', JText::_('PLG_FIELDS_JOOMLA_FULLNAME_PLACEHOLDER_YOUR_NAME', true));?>"
                <?php if ($params->get('regex_validate')) { ?>
                data-check-validate
                data-check-format="<?php echo $params->get('regex_format'); ?>"
                data-check-modifier="<?php echo $params->get('regex_modifier'); ?>"
                <?php } ?>
                />
        </li>
        <?php } ?>

        <?php if ($params->get('format', 1) < 3){ ?>
        <li>
            <input type="text"
                size="30"
                class="form-control input-sm"
                id="middle_name"
                name="middle_name"
                value="<?php echo $middleName; ?>"
                data-field-jname-middle
                placeholder="<?php echo $params->get('middlename_placeholder', JText::_('PLG_FIELDS_JOOMLA_FULLNAME_PLACEHOLDER_MIDDLE_NAME', true));?>"
                <?php if ($params->get('regex_validate')) { ?>
                data-check-validate
                data-check-format="<?php echo $params->get('regex_format'); ?>"
                data-check-modifier="<?php echo $params->get('regex_modifier'); ?>"
                <?php } ?>
                />
        </li>
        <?php } ?>

        <?php if ($params->get('format', 1) == 1 || $params->get('format', 1) == 4){ ?>
        <li>
            <input type="text"
                size="30"
                class="form-control input-sm"
                id="last_name"
                name="last_name"
                value="<?php echo $lastName; ?>"
                placeholder="<?php echo $params->get('lastname_placeholder', JText::_('PLG_FIELDS_JOOMLA_FULLNAME_PLACEHOLDER_LAST_NAME', true));?>"
                <?php if ($params->get('regex_validate')) { ?>
                data-check-validate
                data-check-format="<?php echo $params->get('regex_format'); ?>"
                data-check-modifier="<?php echo $params->get('regex_modifier'); ?>"
                <?php } ?>
                />
        </li>
        <?php } ?>

        <?php if ($params->get('format', 1) == 2 || $params->get('format', 1) == 5){ ?>
        <li>
            <input type="text"
                size="30"
                class="form-control input-sm"
                id="first_name"
                name="first_name"
                value="<?php echo $firstName; ?>"
                data-field-jname-first
                placeholder="<?php echo $params->get('firstname_placeholder', JText::_('PLG_FIELDS_JOOMLA_FULLNAME_PLACEHOLDER_FIRST_NAME', true));?>"<?php echo $field->isRequired() ? ' data-check-required' : '';?>
                <?php if ($params->get('regex_validate')) { ?>
                data-check-validate
                data-check-format="<?php echo $params->get('regex_format'); ?>"
                data-check-modifier="<?php echo $params->get('regex_modifier'); ?>"
                <?php } ?>
                />
        </li>
        <?php } ?>

    </ul>
</div>
