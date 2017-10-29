<?php

namespace CrispySystem\Controllers;

interface ICrudController
{
    public function index();

    public function new();

    public function create();

    public function edit();

    public function update();

    public function delete();

    public function destroy();
}