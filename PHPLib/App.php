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
            UtilityFunctions::CreateOctree($this->scene);

             // Create a node for the Earth
             $this->rootNode = $this->scene->CreateChild();
             $this->rootNode->Position = new Vector3(0, 0, 20);

             $this->earthNode = $this->rootNode->CreateChild();
             $this->earthNode->SetScale(5);
             $this->earthNode->Rotation = new Quaternion(0, 180, 0);
        }

        public function createEarthTexture() {
            // Create a static model component - Sphere:
            $earthSphere = UtilityFunctions::CreateSphereComponent($this->earthNode);
            $earthSphere->SetMaterial(Material::FromImage("Textures/Earth.jpg"));
        }

        public function createMoon() {
            $this->moonNode = $this->earthNode->CreateChild();
            $this->moonNode->SetScale(0.27); // Relative size of the Moon is 1738.1km/6378.1km
            $this->moonNode->Position = new Vector3(1.2, 0, 0);
            
            $moon = UtilityFunctions::CreateSphereComponent($this->moonNode);
            $moon->SetMaterial(Material::FromImage("Textures/Moon.jpg"));
        }
}