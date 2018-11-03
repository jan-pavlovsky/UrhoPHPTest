<?php

namespace Urho;

class App
{
    public $helloText;
    public $scene;

    public $earthNode;
    public $rootNode;
    public $cloudsNode;
    public $cameraNode;
    public $lightNode;

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

        $this->rootNode = $this->scene->CreateChild();
        $this->rootNode->Position = new Vector3(0, 0, 20);
        
        createEarthNode();
    }

    // Create a node for the Earth
    public function createEarthNode()
    {
        $this->earthNode = $this->rootNode->CreateChild();
        $this->earthNode->SetScale(5);
        $this->earthNode->Rotation = new Quaternion(0, 180, 0);
    }

    // Create a static model component - Sphere
    public function createEarthTexture()
    {
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
        $light = UtilityFunctions::createLight($this->lightNode);
        $light->LightType = LightType::Directional;
        $light->Range = 20;
        $light->Brightness = 1;
        $this->lightNode->SetDirection(new Vector3(1, -0.25, 1.0));
    }
    
    public function createCameraAndView()
    {
        // Camera
        $this->cameraNode = $this->scene->CreateChild();
        $camera = UtilityFunctions::CreateCamera($this->cameraNode);
        //$rp = new RenderPath();
        //$viewport = new Viewport($this->scene, $camera, $rp);
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
    public function AddCity(float $lat, float $lon, string $name, float $height, Vector3 $up)
    {
        $lat = (float)((pi() * $lat / 180.0) - (pi() / 2.0));
        $lon = (float)(pi() * $lon / 180.0);
        (float)$x = $height * sin($lat) * cos($lon);
        (float)$z = $height * sin($lat) * sin($lon);
        (float)$y = $height * cos($lat);
        $markerNode = $this->rootNode->CreateChild();
        $markerNode->Scale = new Vector3(0.1, 0.1, 0.1);
        $markerNode->Position = new Vector3($x, $y, $z);
        
        UtilityFunctions::CreateSphereComponent($markerNode);
        UtilityFunctions::TintColor($markerNode);
        ///necessary for normalizing coordinates
        $length = sqrt($x*$x + $y*$y + $z*$z);
        $textNode = $markerNode->CreateChild();
        $textNode->Position = new Vector3(2.0* (float)($x / $length),2.0 * ($y / $length),2.0 * (float)($z / $length));
        $textNode->SetScale(3.0);
        $textNode->LookAt(new Vector3(0,0,0), $up, TransformSpace::Parent);          
        //Because PHP does not know generics and there is problem with loading fonts, we create the actual text in a C# utility function
        UtilityFunctions::CreateCityText($textNode, $name);
    }
}