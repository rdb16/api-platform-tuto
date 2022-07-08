<?php

namespace App\Controller;

use App\Repository\PostRepository;

class PostCountController
{

    public function __construct(private PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function __invoke(): int
    {
        return $this->postRepository->count([]);
    }
}
