<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

FD::import('admin:/includes/fields/dependencies');

class SocialFieldsEventStartend extends SocialFieldItem
{
    public function onRegister(&$post, &$session)
    {
        $startString = '';
        $endString = '';

        // Get the start datetime
        $startDatetime = !empty($post['startDatetime']) ? $post['startDatetime'] : '';

        // Get the date object for the start date time
        $startDatetimeObj = $this->getDatetimeObject($startDatetime);

        if ($startDatetimeObj->isValid()) {
            $startString = $startDatetimeObj->toSql();
        } else {

            if ($this->params->get('default_start') == 'nexthour') {

                $now = FD::date();
                $now->setTime($now->format('G', true) + 1, 0, 0);
                $startString = $now->toSql(true);
            }

            if ($this->params->get('default_start') == 'custom') {
                $now = FD::date();

                $startString = $now->format(JText::_($this->params->get('default_start_format')), true);
            }
        }

        
        // Get the end datetime
        $endDatetime = !empty($post['endDatetime']) ? $post['endDatetime'] : '';
        $endDatetimeObj = $this->getDatetimeObject($endDatetime);

        if ($endDatetimeObj->isValid()) {
            $endString = $endDatetimeObj->toSql();
        } else {

            if ($this->params->get('require_end') && $this->params->get('default_start') == 'nexthour') {
                $now = FD::date();
                $now->setTime($now->format('G', true) + 2, 0, 0);
                $endString = $now->toSql(true);
            }
        }

        // Get the timezone
        if ($this->params->get('allow_timezone')) {
            $this->set('timezones', $this->getTimezones());
            $timezone = !empty($post['startendTimezone']) ? $post['startendTimezone'] : $this->getUserTimezone();
            $this->set('timezone', $timezone);
        }

        // Get the date format
        $dateFormat = $this->params->get('date_format') . ' ' . $this->params->get('time_format');

        $allday = false;

        // Check if there is an all day field and its value
        if (isset($post['event_allday'])) {
            $allday = $post['event_allday'];
        }

        // Get any errors on this field.
        $error = $session->getErrors($this->inputName);

        $this->set('error', $error);
        $this->set('dateFormat', $dateFormat);
        $this->set('startDatetime', $startString);
        $this->set('endDatetime', $endString);
        $this->set('allday', $allday);

        return $this->display();
    }

    public function onEdit(&$post, &$node, $errors)
    {
        // Get the start datetime
        $startDatetime = !empty($post['startDatetime']) ? $post['startDatetime'] : $node->getEventStart()->toSql();
        $startDatetimeObj = $this->getDatetimeObject($startDatetime);
        $this->set('startDatetime', $startDatetimeObj->isValid() ? $startDatetimeObj->toSql() : '');

        // Get the end datetime
        $endDatetime = !empty($post['endDatetime']) ? $post['endDatetime'] : ($node->hasEventEnd() ? $node->getEventEnd()->toSql() : '');
        $endDatetimeObj = $this->getDatetimeObject($endDatetime);
        $this->set('endDatetime', $endDatetimeObj->isValid() ? $endDatetimeObj->toSql() : '');

        // Get the timezone
        if ($this->params->get('allow_timezone')) {
            $this->set('timezones', $this->getTimezones());
            $timezone = !empty($post['startendTimezone']) ? $post['startendTimezone'] : $node->getMeta('timezone');
            $this->set('timezone', $timezone);
        }

        // Get the date format
        $dateFormat = $this->params->get('date_format') . ' ' . $this->params->get('time_format');
        $this->set('dateFormat', $dateFormat);

        // Get the error
        $error = $this->getError($errors);
        $this->set('error', $error);

        $allday = $node->isAllDay();

        // Check if there is an all day field and its value
        if (isset($post['event_allday'])) {
            $allday = $post['event_allday'];
        }

        $this->set('allday', $allday);

        return $this->display();
    }

    /**
     * Displays the start and end date in the event
     *
     * @since   1.3
     * @access  public
     * @param   string
     * @return
     */
    public function onDisplay($node)
    {
        // TODO: Need to check for $node->isAllDay()
        $dateFormat = $this->params->get('date_format');

        if ($this->params->get('allow_time')) {
            $dateFormat = JText::_(FD::config()->get('events.timeformat') == '12h' ? 'COM_EASYSOCIAL_DATE_DMY12H' : 'COM_EASYSOCIAL_DATE_DMY24H');
        }

        $this->set('dateFormat', $dateFormat);

        $startDatetime = $node->getEventStart()->toSql();

        $startDatetimeObj = $this->getDatetimeObject($startDatetime);


        $end = $node->getEventEnd();

        if ($node->meta->hasEnd()) {
            $endDatetime = $node->getEventEnd()->toSql();

            $endDatetimeObj = $this->getDatetimeObject($endDatetime);
        } else {
            $endDatetimeObj = false;
        }

        if ($this->params->get('allow_timezone')) {
            $this->set('timezones', $this->getTimezones());

            $timezone = $node->getMeta('timezone') ? $node->getMeta('timezone') : $this->getUserTimezone();

            $this->set('timezone', $timezone);

            if ($startDatetimeObj->isValid()) {
                $startDatetimeObj->setTimezone($timezone);
            }

            if ($node->meta->hasEnd() && $end && $endDatetimeObj->isValid()) {
                $endDatetimeObj->setTimezone($timezone);
            }
        }

        $this->set('startDatetime', $startDatetimeObj);

        $this->set('endDatetime', $endDatetimeObj);

        return $this->display();
    }

    public function onSample()
    {
        return $this->display();
    }

    public function onRegisterValidate(&$post, &$session)
    {
        return $this->validate($post);
    }

    public function onEditValidate(&$post, &$node)
    {
        // Support for recurring event
        // If this is a recurring event, and is coming from applyRecurring, then we do not want to process this
        if ($node->isRecurringEvent() && !empty($post['applyRecurring'])) {
            return true;
        }

        return $this->validate($post);
    }

    public function validate(&$post)
    {
        // Start and end is always required

        $startDatetime = !empty($post['startDatetime']) ? $post['startDatetime'] : '';

        $startDatetimeObj = $this->getDatetimeObject($startDatetime);

        $endDatetime = !empty($post['endDatetime']) ? $post['endDatetime'] : '';

        $endDatetimeObj = $this->getDatetimeObject($endDatetime);

        if ($startDatetimeObj->isEmpty()) {
            $this->setError(JText::_('FIELDS_EVENT_STARTEND_VALIDATION_DATETIME_START_REQUIRED'));
            return false;
        }

        if (!$startDatetimeObj->isValid()) {
            $this->setError(JText::_('FIELDS_EVENT_STARTEND_VALIDATION_DATETIME_INVALID_START'));
            return false;
        }

        if ($this->params->get('require_end') && $endDatetimeObj->isEmpty()) {
            $this->setError(JText::_('FIELDS_EVENT_STARTEND_VALIDATION_DATETIME_END_REQUIRED'));
            return false;
        }

        if (!$endDatetimeObj->isEmpty() && !$endDatetimeObj->isValid()) {
            $this->setError(JText::_('FIELDS_EVENT_STARTEND_VALIDATION_DATETIME_INVALID_END'));
            return false;
        }

        // End datetime must be > than start datetime
        if (!$endDatetimeObj->isEmpty() && $endDatetimeObj->toUnix() < $startDatetimeObj->toUnix()) {
            $this->setError(JText::_('FIELDS_EVENT_STARTEND_VALIDATION_END_MUST_BE_LATER_THAN_START'));
            return false;
        }

        return true;
    }

    public function onRegisterBeforeSave(&$post, &$node)
    {
        return $this->beforeSave($post, $node);
    }

    public function onEditBeforeSave(&$post, &$node)
    {
        // Support for recurring event
        // If this is a recurring event, and is coming from applyRecurring, then we do not want to process this
        if ($node->isRecurringEvent() && !empty($post['applyRecurring'])) {
            return true;
        }

        return $this->beforeSave($post, $node);
    }

    public function beforeSave(&$post, &$node)
    {
        // Timezone issue
        // Timezone disabled: Always assume everything is in UTC regardless of server config because user won't be able to change timezone on the event itself. Stored date is in UTC so that it can be used "as is".
        // Timezone enabled: Datetime input corresponds with the timezone, but since we need to store in UTC, we need to get what the input timezone is, and reverse the time back to UTC.

        $startDatetime = !empty($post['startDatetime']) ? $post['startDatetime'] : '';

        $endDatetime = !empty($post['endDatetime']) ? $post['endDatetime'] : '';

        $hasEndDatetime = !empty($endDatetime);

        $timezone = !empty($post['startendTimezone']) ? $post['startendTimezone'] : '';

        $allday = $node->isAllDay();

        // Check if there is an all day field and its value
        if (isset($post['event_allday'])) {
            $allday = $post['event_allday'];
        }

        if ($allday) {
            if (! $hasEndDatetime) {
                $segments = explode(' ', $startDatetime);
                $endDatetime = $segments[0] . ' 23:59:59';
            }
            $endDatetime = str_replace('00:00:00', '23:59:59', $endDatetime);
        }

        // We get the joomla timezone
        $original_TZ = new DateTimeZone(JFactory::getConfig()->get('offset')); 

        // Get the date with timezone
        $tempStartDate = JFactory::getDate($startDatetime, $original_TZ);
        $tempEndDate = JFactory::getDate($endDatetime, $original_TZ);

        // Check for timezone. If the timezone has been changed, get the new startend date
        if ((!empty($timezone) && $timezone !== 'UTC') && $timezone != $node->getEventTimezone()) {
            $dtz = new DateTimeZone($timezone);

            // Creates a new datetime string with user input timezone as predefined timezone
            $newStartDatetime = JFactory::getDate($startDatetime, $dtz);
            $newEndDatetime = JFactory::getDate($endDatetime, $dtz);

            // Reverse the timezone back to UTC
            $startDatetime = $newStartDatetime->toSql();
            $endDatetime = $newEndDatetime->toSql();
        }

        $startDatetimeObj = $this->getDatetimeObject($startDatetime);
        $endDatetimeObj = $this->getDatetimeObject($endDatetime);

        // Convert the date to non-offset date
        $nonOffsetStartDate = $tempStartDate->toSql();
        $nonOffsetEndDate = $tempEndDate->toSql();
        
        $tempStartDatetimeObj = $this->getDatetimeObject($nonOffsetStartDate);
        $tempEndDatetimeObj = $this->getDatetimeObject($nonOffsetEndDate);

        // We do not need these data to be in fields_data
        unset($post['startDatetime']);
        unset($post['endDatetime']);
        unset($post['startendTimezone']);

        $startString = $startDatetimeObj->toSql();
        $endString = $endDatetime ? $endDatetimeObj->toSql() : '0000-00-00 00:00:00';

        $tempStartString = $tempStartDatetimeObj->toSql();
        $tempEndString = $endDatetime ? $tempEndDatetimeObj->toSql() : '0000-00-00 00:00:00';

        // If allday is set to true, then we reform to the time
        if ($allday) {
            list($startYMD, $startHMS) = explode(' ', $startString);

            $startHMS = '00:00:00';

            $startString = $startYMD . ' ' . $startHMS;

            if ($endDatetime) {
                list($endYMD, $endHMS) = explode(' ', $endString);
                
                $endHMS = '23:59:59';
                $endString = $endYMD . ' ' . $endHMS;
            }
        }

        $node->setMeta('start_gmt', $startString);
        $node->setMeta('end_gmt', $endString);
        
        // if no timezone, we need to use the non-offset for the start_gmt column
        // This column used when checking for upcoming event reminder
        if (empty($timezone)) {
            $node->setMeta('start_gmt', $tempStartString);
            $node->setMeta('end_gmt', $tempEndString);
        }

        $node->setMeta('start', $startString);
        $node->setMeta('end', $endString);

        $node->setMeta('timezone', $timezone);

        return true;
    }

    protected function getTimezones()
    {
        static $timezones = array();

        if (empty($timezones)) {
            $zones = DateTimeZone::listIdentifiers();

            foreach ($zones as $zone) {
                $key = strstr($zone, '/', true);

                if (!empty($key)) {
                    $timezones[$key][] = $zone;
                }
            }
        }

        return $timezones;
    }

    protected function getUserTimezone()
    {
        $tz = FD::user()->getParam('timezone');

        if (empty($tz)) {
            $tz = JFactory::getConfig()->get('offset', 'UTC');
        }

        return $tz;
    }

    public function getDatetimeObject($data = null)
    {
        $dateObj = new SocialFieldsEventStartendObject;

        if (empty($data)) {
            return $dateObj;
        }

        $dateObj->load($data);

        return $dateObj;
    }
}

class SocialFieldsEventStartendObject
{
    public $year;
    public $month;
    public $day;
    public $hour = '00';
    public $minute = '00';
    public $second = '00';

    private $date;

    public function __construct()
    {
        $args = func_get_args();

        if (empty($args)) {
            return true;
        }

        return call_user_func_array(array($this, 'load'), $args);
    }

    public function load()
    {
        $args = func_get_args();

        $count = func_num_args();

        if ($count === 1 && is_string($args[0]) && !empty($args[0])) {
            $json = FD::json();

            if ($json->isJsonString($args[0])) {
                $args[0] = $json->decode($args[0]);
            } else {
                if (strtotime($args[0])) {
                    $args[0] = FD::date($args[0], false);
                }
            }
        }

        $keys = array('year', 'month', 'day', 'hour', 'minute', 'second');

        if ($count === 1 && (is_object($args[0]) || is_array($args[0]))) {
            $date = (object) $args[0];

            foreach ($keys as $key) {
                if (isset($date->$key)) {
                    $this->$key = $date->$key;
                }
            }
        }

        if ($count === 1 && $args[0] instanceof SocialDate) {
            $date = $args[0];

            $this->year = $date->toFormat('Y');
            $this->month = $date->toFormat('m');
            $this->day = $date->toFormat('d');
            $this->hour = $date->toFormat('H');
            $this->minute = $date->toFormat('i');
            $this->second = $date->toFormat('s');
        }

        if ($count > 1) {
            foreach ($args as $i => $arg) {
                $this->{$keys[$i]} = $arg;
            }
        }

        $this->date = $this->toDate();

        return true;
    }

    public function isEmpty()
    {
        foreach ($this->toArray() as $k => $v) {
            // we do not want to test against the private 'date'
            if ($k == 'date') {
                continue;
            }
            if (empty($v)) {
                return true;
            }
        }

        return false;
    }

    public function isValid()
    {
        return !$this->isEmpty() && strtotime($this->day . '-' . $this->month . '-' . $this->year . ' ' . $this->hour . ':' . $this->minute . ':' . $this->second);
    }

    public function toJSON()
    {
        return FD::json()->encode($this->toArray());
    }

    public function toDate()
    {
        if (empty($this->date)) {
            if ($this->isEmpty()) {
                $this->date = FD::date();
            } else {
                $this->date = FD::date($this->year . '-' . $this->month . '-' . $this->day . ' ' . $this->hour . ':' . $this->minute . ':' . $this->second, false);
            }
        }

        return $this->date;
    }

    public function toArray($publicOnly = true)
    {
        if ($publicOnly) {
            return call_user_func('get_object_vars', $this);
        }

        return get_object_vars($this);
    }

    public function toFormat($format, $local = true)
    {
        return $this->toDate()->toFormat($format);
    }

    public function toSql()
    {
        return $this->toDate()->toSql();
    }

    public function toString()
    {
        return $this->day . ' ' . JText::_($this->toFormat('F')) . ' ' . $this->year;
    }

    public function toUnix()
    {
        return $this->toDate()->toUnix();
    }

    public function toAge()
    {
        $now = FD::date();

        $years = floor(($now->toFormat('U') - $this->toFormat('U')) / (60*60*24*365));

        return $years;
    }

    public function setTimezone($dtz)
    {
        if (empty($dtz)) {
            return $this;
        }

        if (is_string($dtz)) {
            $dtz = new DateTimeZone($dtz);
        }

        $this->date->setTimezone($dtz);
    }

    public function __toString()
    {
        return $this->isValid() ? $this->toString() : '';
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array(array($this->toDate(), $method), $arguments);
    }
}

