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
        $collection->addTask($this->runDbUpdates());
        $collection->addTask($this->runConfigImport());
        $collection->addTask($this->runBuildFrontEnd());
        return $collection->run();
    }

    /**
     * Run database updates.
     *
     * @return \Robo\Task\Base\Exec
     *   A task to run db updates.
     */
    protected function runDbUpdates() {
      return $this->drush()
      ->arg('updatedb')
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
    protected function runClearCache() {
      return $this->drush()
      ->arg('clear-cache');
    }

    /**
     * Install pending configurations.
     *
     * @return \Robo\Task\Base\Exec
     *   A task to import pending configs.
     */
    protected function runConfigImport() {
      return $this->drush()
      ->arg('config-import')
      ->option('yes');
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
