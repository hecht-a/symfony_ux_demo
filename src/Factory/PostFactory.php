<?php

namespace App\Factory;

use App\Entity\Post;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Post>
 */
final class PostFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Post::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'title' => self::faker()->text(20),
            'description' => self::faker()->text(255),
            'image' => 'https://picsum.photos/450/250',
        ];
    }

    protected function initialize(): static
    {
        return $this
        ;
    }
}
