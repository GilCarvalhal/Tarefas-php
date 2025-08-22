<?php

namespace App\Entities;

final class Tarefa
{
    public int $id;
    public string $titulo;
    public ?string $descricao = null;
    public ?string $categoriaNome = null;
    public string $criadaEm;
}
