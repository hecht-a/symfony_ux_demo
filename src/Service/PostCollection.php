<?php

namespace App\Service;

use App\Repository\PostRepository;

class PostCollection
{
    private array $posts;

    public function __construct(private readonly PostRepository $postRepository, array $posts = [])
    {
        $this->posts = count($posts) > 0 ? $posts : $postRepository->findAll();
    }

    public function paginate(int $page, int $perPage): self
    {
        return new self($this->postRepository, \array_slice($this->posts, ($page - 1) * $perPage, $perPage));
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->posts);
    }

    public function count(): int
    {
        return \count($this->posts);
    }
}
