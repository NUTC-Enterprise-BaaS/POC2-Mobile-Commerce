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

// Include the datetime field
FD::import('fields:/user/datetime/datetime');

/**
 * Field application for Birthday
 * Extends from datetime field since 1.2
 *
 * @since   1.0
 * @author  Jason Rey <jasonrey@stackideas.com>
 */
class SocialFieldsUserBirthday extends SocialFieldsUserDateTime
{
    public function onRegisterValidate(&$post)
    {
        $value = isset($post[$this->inputName]) ? $post[$this->inputName] : '';

        $json = FD::json();

        $value = $json->isJsonString($value) ? $json->decode($value) : (object) array();

        $date = isset($value->date) ? $value->date : '';

        if (!$this->checkAge($date)) {
            return false;
        }

        return parent::onRegisterValidate($post);
    }

    public function onEditValidate(&$post, &$user)
    {
        $value = isset($post[$this->inputName]) ? $post[$this->inputName] : '';

        $json = FD::json();

        $value = $json->isJsonString($value) ? $json->decode($value) : (object) array();

        $date = isset($value->date) ? $value->date : '';

        if (!$this->checkAge($date)) {
            return false;
        }

        $state = parent::onEditValidate($post, $user);

        return $state;
    }

    private function checkAge($value)
    {
        if ($this->params->get('age_limit') < 1  || empty($value)) {
            return true;
        }

        $data = $this->getDatetimeValue($value);

        // We don't throw validity error here, leave it up to the parent function to do it
        if (!$data->isValid()) {
            return true;
        }

        $now = FD::date()->toUnix();
        $birthDate = $data->toDate()->toUnix();

        $diff = floor(($now - $birthDate) / (60*60*24*365));

        if ($diff < $this->params->get('age_limit')) {
            $this->setError(JText::sprintf('PLG_FIELDS_BIRTHDAY_VALIDATION_AGE_LIMIT', $this->params->get('age_limit')));
            return false;
        }

        return true;
    }

    public function onRegisterOAuthBeforeSave(&$post, $client)
    {
        if (empty($post['birthday'])) {
            return;
        }

        // Facebook format is M/D/Y, we reformat it to Y-M-D
        $date = explode('/', $post['birthday']);

        $reformedDate = FD::date($date[2] . '-' . $date[0] . '-' . $date[1]);

        $post[$this->inputName] = array('date' => $reformedDate->toSql());
    }

    public function onOAuthGetUserPermission(&$permissions)
    {
        $permissions[] = 'user_birthday';
    }

    public function onOAuthGetMetaFields(&$fields)
    {
        $fields[] = 'birthday';
    }

    /**
     * Checks if this field is complete.
     *
     * @author Jason Rey <jasonrey@stackideas.com>
     * @since  1.2
     * @access public
     * @param  SocialUser    $user The user being checked.
     */
    public function onFieldCheck($user)
    {
        if (!$this->checkAge($this->value)) {
             return false;
        }

        return parent::onFieldCheck($user);
    }

    /**
     * Override datetime class onDisplay to show age as well.
     *
     * @author Jason Rey <jasonrey@stackideas.com>
     * @since  1.2
     * @access public
     * @param  SocialUser    $user The user object being viewed.
     * @return string              The html string display.
     */
    public function onDisplay($user)
    {
        if (empty($this->value)) {
            return;
        }

        if (!$this->allowedPrivacy($user)) {
            return;
        }

        if (empty($this->value['date'])) {
            return;
        }

        $data = $this->getDatetimeValue($this->value['date']);

        if ($data->isEmpty()) {
            return;
        }

        $allowYear = true;

        if ($this->params->get('year_privacy')) {
            $allowYear = $this->allowedPrivacy($user, 'year');
        }

        $format = $allowYear ? 'd M Y' : 'd M';

        switch($this->params->get('date_format')) {
            case 2:
            case '2':
                $format = $allowYear ? 'M d Y' : 'M d';
                break;
            case 3:
            case '3':
                $format = $allowYear ? 'Y d M' : 'd M';
                break;
            case 4:
            case '4':
                $format = $allowYear ? 'Y M d' : 'M d';
                break;
        }

        $age = $allowYear && $this->params->get('show_age') ? $data->toAge() : '';

        // Push variables into theme.
        $this->set('date', $data->toFormat($format));

        $this->set('allowYearSettings', $this->params->get('year_privacy') && FD::user()->id === $user->id);

        $this->set('age', $age);

        // linkage to advanced search page.
        $field = $this->field;
        if ($field->type == SOCIAL_FIELDS_GROUP_USER && $allowYear && $field->searchable) {
            $date = $data->toFormat('Y-m-d');

            $params = array( 'layout' => 'advanced' );
            $params['criterias[]'] = $field->unique_key . '|' . $field->element;
            $params['operators[]'] = 'between';
            $params['conditions[]'] = $date . ' 00:00:00' . '|' . $date . ' 23:59:59';

            $advsearchLink = FRoute::search($params);
            $this->set( 'advancedsearchlink'    , $advsearchLink );
        }

        return $this->display();
    }
}
