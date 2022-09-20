<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComponentTestController extends Controller
{
    // public $message = 'メッセージ';
    public function showComponent1() {
        $message = 'メッセージ123';
        return view('tests.component-test1', compact('message'));
        // return view('tests.component-test1', ['message' => $this->message]);
    }
    public function showComponent2() {
        return view('tests.component-test2');
    }
}
