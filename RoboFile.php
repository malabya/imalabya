<?php

// @codingStandardsIgnoreStart

/**
 * Run deployment processes.
 *
 * @class RoboFile
 */
class RoboFile extends \Robo\Tasks
{

    /**
     * Command to check for Drupal's Coding Standards.
     *
     * @return \Robo\Result
     *   The result of the collection of tasks.
     */
    public function jobDrupalUpdate()
    {
        $collection = $this->collectionBuilder();
        $collection->addTask($this->taskComposerInstall());
        $collection->addTask($this->deploy());
        $collection->addTask($this->runBuildFrontEnd());
        $collection->addTask($this->runClearRebuild());
        return $collection->run();
    }

    /**
     * Run the Drush deploy command.
     *
     * @return \Robo\Task\Base\Exec
     *   A task to deployment command.
     */
    protected function deploy() {
      return $this->drush()
      ->arg('deploy')
      ->option('ansi')
      ->option('yes');
    }

    /**
     * Build frontend dependencies.
     *
     * @return \Robo\Task\Base\Exec
     *   A task to run db updates.
     */
    protected function runBuildFrontEnd() {
      return $this->taskExecStack()
      ->dir('web/themes/imalabya')
      ->exec('yarn install')
      ->exec('yarn build');
    }

    /**
     * Clear Drupal cache.
     * @return \Robo\Task\Base\Exec
     *   A task to run db updates.
     */
    protected function runClearRebuild() {
      return $this->drush()
      ->arg('cache-rebuild');
    }

    /**
     * Return drush with default arguments.
     *
     * @return \Robo\Task\Base\Exec
     *   A drush exec command.
     */
    protected function drush()
    {
        return $this->taskExec('drush');
    }

}
