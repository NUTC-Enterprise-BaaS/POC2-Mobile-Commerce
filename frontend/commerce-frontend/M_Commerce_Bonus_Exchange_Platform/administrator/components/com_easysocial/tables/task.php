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

// Import main table to assis this table.
FD::import('admin:/tables/table');

/**
 * Tasks object relation mapper.
 *
 * @since   1.2
 * @author  Mark Lee <mark@stackideas.com>
 */
class SocialTableTask extends SocialTable
{
    /**
     * The unique task id.
     * @var int
     */
    public $id = null;

    /**
     * The unique task id.
     * @var int
     */
    public $milestone_id = null;

    /**
     * The unique task id.
     * @var int
     */
    public $uid = null;

    /**
     * The unique task id.
     * @var int
     */
    public $type = null;

    /**
     * The owner of the task.
     * @var int
     */
    public $user_id = null;

    /**
     * The task title.
     * @var string
     */
    public $title = null;

    /**
     * The task title.
     * @var string
     */
    public $description = null;

    /**
     * The state of the task
     * @var string
     */
    public $state = null;

    /**
     * The date time this task has been created.
     * @var datetime
     */
    public $created = null;

    /**
     * The due date of this task
     * @var datetime
     */
    public $due = null;
    /**
     * Class Constructor.
     *
     * @since   1.0
     * @access  public
     */
    public function __construct(& $db)
    {
        parent::__construct('#__social_tasks' , 'id' , $db);
    }

    /**
     * Marks a task as resolved.
     *
     * @since   1.0
     * @access  public
     */
    public function resolve()
    {
        $this->state = SOCIAL_TASK_RESOLVED;

        $state = $this->store();

        return $state;
    }

    /**
     * Marks a task as resolved.
     *
     * @since   1.0
     * @access  public
     */
    public function unresolve()
    {
        $this->state = SOCIAL_TASK_UNRESOLVED;

        $state = $this->store();

        return $state;
    }

    /**
     * Determines if there's a due date set for the milestone.
     *
     * @since   1.2
     * @access  public
     * @return  boolean
     */
    public function hasDueDate()
    {
        return $this->due !== '0000-00-00 00:00:00';
    }

    /**
     * Determines if the task is completed.
     *
     * @since   1.2
     * @access  public
     * @return  boolean True if the task is resolved.
     */
    public function isCompleted()
    {
        return $this->state == SOCIAL_TASK_RESOLVED;
    }

    /**
     * Alias method for isCompleted().
     * @return  boolean True if the task is resolved.
     */
    public function isResolved()
    {
        return $this->isCompleted();
    }

    /**
     * Returns the milestone SocialTable object.
     * @return SocialTableMilestone The milestone SocialTable object of this task.
     */
    public function getMilestone()
    {
        static $milestones = array();

        if (!isset($milestones[$this->milestone_id])) {
            $milestone = FD::table('Milestone');
            $milestone->load($this->milestone_id);

            $milestones[$this->milestone_id] = $milestone;
        }

        return $milestones[$this->milestone_id];
    }

    /**
     * Central method to create stream for this task.
     * @param  string   $verb       The verb for the stream.
     * @param  integer  $actorId    The actor user id.
     */
    public function createStream($verb, $actorId = null)
    {
        $stream = FD::stream();
        $tpl = $stream->getTemplate();
        $actor = FD::user($actorId);

        $registry = FD::registry();

        // We set it to array because it is possible that 1 stream contains many tasks that are created from story form
        $registry->set('tasks', array($this));

        $registry->set('milestone', $this->getMilestone());

        if ($this->type == SOCIAL_TYPE_USER) {
            $user = FD::user($this->uid);

            // Cache the user data into the params
            $registry->set('user', $user);
        } else {
            // Get the cluster depending on the type
            $cluster = FD::cluster($this->type, $this->uid);

            // this is a cluster stream and it should be viewable in both cluster and user page.
            $tpl->setCluster($cluster->id, $this->type, $cluster->type);

            // Cache the cluster data into the params
            $registry->set($this->type, $cluster);
        }

        // Set the actor
        $tpl->setActor($actor->id, SOCIAL_TYPE_USER);

        // Set the context
        $tpl->setContext($this->id, 'tasks');

        // Set the verb
        $tpl->setVerb($verb);

        // Set the params to cache the group data
        $tpl->setParams($registry);

        // since this is a cluster and user stream, we need to call setPublicStream
        // so that this stream will display in unity page as well
        // This stream should be visible to the public
        $tpl->setAccess('core.view');

        $stream->add($tpl);
    }

    /**
     * Central method to remove previously created stream.
     * @param  string   $verb   The verb for the stream.
     */
    public function removeStream($verb)
    {
        FD::stream()->delete($this->id, 'tasks', '', $verb);
    }

    public function delete($pk = null)
    {
        $state = parent::delete($pk);

        if ($state) {
            $this->removeStream('createTask');
        }

        return $state;
    }
}
