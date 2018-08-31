<?php

namespace Urho;

class BB {
    public $bb;

    public function __construct($aa) {
        $this->bb = $aa;
    }
}

class App
{
    public $myText;
    public $urhoApp;

    public $UrhoContext;

    public $helloText;
    public $cameraNode;
    public $earthNode;
    public $rootNode;
    public $scene;
    public $yaw;
    public $pitch;

    

    public function __construct(string $text) {
        $this->myText = $text;
    }

        public function Start()
        {
            // UI text 
            $this->helloText = new Gui\Text();
            $this->helloText->Value = "Yaay, hello from php";
            $this->helloText->HorizontalAlignment = Gui\HorizontalAlignment::Center;
            $this->helloText->VerticalAlignment = Gui\VerticalAlignment::Top;

            $this->scene = new Scene();

            
        }
}