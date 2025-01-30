<?php

namespace App\Twig\Components;

use App\Entity\Post;
use App\Service\PostCollection;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent('PostGrid')]
final class PostGrid
{
    use ComponentToolsTrait;
    use DefaultActionTrait;

    private const int PER_PAGE = 3;

    #[LiveProp]
    public int $page = 1;

    #[LiveProp]
    public ?Post $modalPost = null;

    #[LiveProp]
    public bool $isModalOpened = false;

    public function __construct(private readonly PostCollection $posts)
    {
    }

    #[LiveAction]
    public function more(): void
    {
        ++$this->page;
    }

    public function hasMore(): bool
    {
        return $this->posts->count() > ($this->page * self::PER_PAGE);
    }

    public function getPosts(): array
    {
        $posts = $this->posts->paginate($this->page, self::PER_PAGE);

        $items = [];
        foreach ($posts->getIterator() as $i => $post) {
            $items[] = [
                'id' => ($this->page - 1) * self::PER_PAGE + $i,
                'post' => $post,
            ];
        }

        return $items;
    }

    #[ExposeInTemplate('per_page')]
    public function getPerPage(): int
    {
        return self::PER_PAGE;
    }

    #[LiveAction]
    public function openModal(#[LiveArg] Post $post = null): void
    {
        $this->isModalOpened = !$this->isModalOpened;
        $this->modalPost = $post;
    }
}
