<?php

namespace Gitonomy\Git;

/**
 * Representation of a Blob commit.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Blob
{
    protected $repository;
    protected $hash;
    protected $initialized = false;
    protected $content;

    public function __construct(Repository $repository, $hash)
    {
        $this->repository = $repository;
        $this->hash = $hash;
    }

    protected function initialize()
    {
        if (true === $this->initialized) {
            return;
        }

        ob_start();
        system(sprintf(
            'cd %s && git cat-file -p %s',
            escapeshellarg($this->repository->getPath()),
            escapeshellarg($this->hash)
        ), $return);

        $this->content = ob_get_clean();

        if (0 !== $return) {
            throw new \RuntimeException('Error while getting content of a commit');
        }
    }

    public function getContent()
    {
        $this->initialize();

        return $this->content;
    }
}
