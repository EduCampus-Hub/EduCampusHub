<?php
namespace App\Infrastructure;
class JsonUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private string $filepath
    ){}
    
}