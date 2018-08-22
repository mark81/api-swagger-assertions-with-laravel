<?php

namespace App\Contracts;
use Illuminate\Support\Collection;

interface RepositoryInterface {

    public function get(): Collection;
}