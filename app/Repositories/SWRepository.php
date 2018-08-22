<?php

namespace App\Repositories;
use App\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

class SWRepository implements RepositoryInterface {

    public function get(): Collection {

        return collect(
            [
                [
                    "id" => 1,
                    "title" => "Episode IV: A New Hope."
                ],
                [
                    "id" => 2,
                    "title" => "Episode V: The Empire Strikes Back."
                ],
                [
                    "id" => 3,
                    "title" => "Episode VI: Return of the Jedi."
                ],
                [
                    "id" => 4,
                    "title" => "Episode I: The Phantom Menace."
                ],
                [
                    "id" => 5,
                    "title" => "Episode II: Attack of the Clones."
                ],
                [
                    "id" => 6,
                    "title" => "Episode III: Revenge of the Sith."
                ],
                [
                    "id" => 7,
                    "title" => "X-Wing."
                ],
                [
                    "id" => 8,
                    "title" => "Rebel Assault."
                ]
            ]
        );
    }
}