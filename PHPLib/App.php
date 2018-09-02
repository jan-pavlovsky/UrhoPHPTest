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
    public $earthNode;
    public $rootNode;
    public $cloudsNode;
    public $cameraNode;

    public $skybox;
    public $camera;
    public $viewport;
    public $scene;
    public $lightNode;
    public $light;

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

        public function createClouds(Material $textureMaterial)
        {
            // Clouds
            $this->cloudsNode = $this->earthNode->CreateChild();
            $this->cloudsNode->SetScale(1.02);
            
            $clouds = UtilityFunctions::CreateSphereComponent($this->cloudsNode);

            $clouds->SetMaterial($textureMaterial);
        }

        public function createLight()
        {
            // Light
            $this->lightNode = $this->scene->CreateChild();
            $this->light = UtilityFunctions::createLight($this->lightNode);
            $this->light->LightType = LightType::Directional;
            $this->light->Range = 20;
            $this->light->Brightness = 1;
            $this->lightNode->SetDirection(new Vector3(1, -0.25, 1.0));
        }
        
        public function createCameraAndView()
        {
            // Camera
            $this->cameraNode = $this->scene->CreateChild();
            $this->camera = UtilityFunctions::CreateCamera($this->cameraNode);
            //$rp = new RenderPath();
            //$this->viewport = new Viewport($this->scene, $this->camera, $rp);
        }

        public function createSkybox(Material $skyboxMaterial)
        {
            // Stars (Skybox)
            $this->skyboxNode = $this->scene->CreateChild();
            $this->skybox = UtilityFunctions::CreateSkybox($this->skyboxNode);
            $this->skybox->SetMaterial($skyboxMaterial);
        }

        public function runRotations($earth, $clouds) {
            // Run a an action to spin the Earth (7 degrees per second)
            $this->rootNode->RunActions(new Actions\RepeatForever(new Actions\RotateBy(1, 0,$earth,0)));
            // Spin clouds:
            $this->cloudsNode->RunActions(new Actions\RepeatForever(new Actions\RotateBy(1, 0, $clouds, 0)));
            // Zoom effect:
            //await rootNode.RunActionsAsync(new EaseOut(new MoveTo(2f, new Vector3(0, 0, 12)), 1));
        }
}