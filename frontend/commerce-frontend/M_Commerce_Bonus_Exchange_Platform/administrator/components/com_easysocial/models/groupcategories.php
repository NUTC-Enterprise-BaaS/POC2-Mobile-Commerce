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

FD::import('admin:/includes/model');

class EasySocialModelGroupCategories extends EasySocialModel
{
    public function __construct($config = array())
    {
        parent::__construct('groupcategories', $config);
    }

    public function initStates()
    {
        // Ordering, direction, search, limit and limistart is handled by parent::initStates();
        parent::initStates();

        $state = $this->getUserStateFromRequest('state', 'all');
        $this->setState('state', $state);
    }

    public function getItems()
    {
        $db = FD::db();
        $sql = $db->sql();

        $sql->select('#__social_clusters_categories');

        $search = $this->getState('search');

        if (!empty($search)) {
            $sql->where('title', '%' . $search . '%', 'LIKE');
        }

        $state = $this->getState('state');

        if (isset($state) && $state !== 'all') {
            $sql->where('state', $state);
        }

        $sql->where('type', SOCIAL_TYPE_GROUP);

        $ordering = $this->getState('ordering');
        $direction = $this->getState('direction');

        $sql->order($ordering, $direction);

        $this->setTotal($sql->getTotalSql());

        $result = $this->getData($sql->getSql());

        $categories = $this->bindTable('GroupCategory', $result);

        return $categories;
    }

    public function getCategories($options = array())
    {
        $db = FD::db();
        $sql = $db->sql();

        $sql->select('#__social_clusters_categories', 'a');
        $sql->column('a.*');

        if (isset($options['search'])) {
            $sql->where('a.title', '%' . $search . '%', 'LIKE');
        }

        if (isset($options['state']) && $options['state'] !== 'all') {
            $sql->where('a.state', $options['state']);
        }

        if (isset($options['profile_id'])) {
            $sql->join('#__social_clusters_categories_access', 'c');
            $sql->on('a.id', 'c.category_id');
            $sql->on('c.type', 'create');

            $sql->where('c.profile_id', $options['profile_id']);
        }

        $sql->where('a.type', SOCIAL_TYPE_GROUP);

        $ordering   = isset($options['ordering']) ? $options['ordering'] : 'ordering';

        if ($ordering == 'title') {
            $sql->order('a.title', 'ASC');
        }

        // Order by total number of groups
        if ($ordering == 'groups') {
            $sql->join('#__social_clusters', 'b');
            $sql->on('b.category_id', 'a.id');
            $sql->on('b.state', SOCIAL_CLUSTER_PUBLISHED);
            $sql->order('COUNT(b.id)', 'DESC');
            $sql->group('a.id');
        }

        if ($ordering == 'ordering') {
            $sql->order('a.ordering');
        }

        if (isset($options['limit'])) {
            $limitstart = isset($options['limitstart']) ? $options['limitstart'] : 0;

            $sql->limit($limitstart, $options['limit']);
        }

        $db->setQuery($sql);

        $result = $db->loadObjectList();

        $categories = $this->bindTable('GroupCategory', $result);

        return $categories;
    }

    public function updateGroupCategory($uid, $categoryId)
    {
        $cluster = FD::table('Cluster');
        $cluster->load($uid);

        $cluster->category_id = $categoryId;

        $cluster->store();

        $db = FD::db();
        $sql = $db->sql();

        $sql->update('#__social_fields_data', 'a');
        $sql->leftjoin('#__social_fields', 'b');
        $sql->on('a.field_id', 'b.id');
        $sql->leftjoin('#__social_fields', 'c');
        $sql->on('b.unique_key', 'c.unique_key');
        $sql->leftjoin('#__social_fields_steps', 'd');
        $sql->on('c.step_id', 'd.id');
        $sql->set('a.field_id', 'c.id', false);
        $sql->where('a.uid', $uid);
        $sql->where('a.type', 'group');
        $sql->where('d.type', 'clusters');
        $sql->where('d.uid', $categoryId) ;

        $db->setQuery($sql);

        return $db->query();
    }
}
