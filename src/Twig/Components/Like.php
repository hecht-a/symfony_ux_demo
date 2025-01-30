<?php

namespace App\Twig\Components;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Like
{
    use DefaultActionTrait;

    #[LiveProp]
    public Post $post;

    #[LiveProp]
    public bool $isLiked;

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly Security $security)
    {
    }

    #[LiveAction]
    public function toggleLike(#[LiveArg] string $type): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if ($type === 'like') {
            $this->post->addLike($user);
            $this->isLiked = true;
        } else {
            $this->post->removeLike($user);
            $this->isLiked = false;
        }
        $this->entityManager->flush();
    }
}
