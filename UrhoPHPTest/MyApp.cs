using System;
using System.Diagnostics;
using Urho;
using Urho.Actions;
using Urho.Gui;
using Urho.Shapes;

namespace UrhoPHPTest
{
    public class MyApp : Application
    {
        Text helloText;
        Camera camera;
        Node cameraNode;
        Node earthNode;
        Node rootNode;
        Node cloudsNode;
        Scene scene;
        Viewport viewport;
        Skybox skybox;
        float yaw, pitch;

        App phpApp;

        //An utility function making direct references from C# project to the classes created in PHP.
        private void CreatePHPReferences()
        {
            helloText = ((Text)phpApp.helloText.AsObject());
            scene = ((Scene)phpApp.scene.AsObject());
            rootNode = ((Node)phpApp.rootNode.AsObject());
            earthNode = ((Node)phpApp.earthNode.AsObject());
            cloudsNode = ((Node)phpApp.cloudsNode.AsObject());
            viewport = ((Viewport)phpApp.viewport.AsObject());
            camera = ((Camera)phpApp.camera.AsObject());
            cameraNode = ((Node)phpApp.cameraNode.AsObject());
            skybox = ((Skybox)phpApp.skybox.AsObject());
        }

        private void CreateSkybox()
        {
            // Stars (Skybox)
            var skyboxNode = scene.CreateChild();
            var skybox = skyboxNode.CreateComponent<Skybox>();
            skybox.Model = CoreAssets.Models.Box;
            skybox.SetMaterial(Material.SkyboxFromImage("Textures/Space.png"));
        }

        [Preserve]
        public MyApp(ApplicationOptions options) : base(options) { }

        static MyApp()
        {
            UnhandledException += (s, e) =>
            {
                if (Debugger.IsAttached)
                    Debugger.Break();
                e.Handled = true;
            };
        }

        protected override async void Start()
        {
            base.Start();

            //creating empty PHP context for utility use
            var ctx = Pchp.Core.Context.CreateEmpty();
           
            //The PHP app class containing all the application 
            phpApp = new App(ctx, "Hey from C#");
            phpApp.Start();

            // Earth and Moon
            phpApp.createEarthTexture();
            phpApp.createMoon();

            //Clouds material...texture needs to be loaded from here
            var cloudsMaterial = new Material();
            cloudsMaterial.SetTexture(TextureUnit.Diffuse, ResourceCache.GetTexture2D("Textures/Earth_Clouds.jpg"));
            cloudsMaterial.SetTechnique(0, CoreAssets.Techniques.DiffAddAlpha);

            //Create assets rom PHP
            phpApp.createClouds(cloudsMaterial);
            phpApp.createLight();
            phpApp.createCameraAndView();
                
            //Create the references to objects created in PHP inside MyApp UrhoSharp Application
            CreatePHPReferences();

            // Text created in php proj updated here
            helloText.SetColor(new Color(0.5f, 1.0f, 1.0f, 1.0f));
            helloText.SetFont(font: CoreAssets.Fonts.AnonymousPro, size: 30);
            // Necessary to call from here because of UI belonging to extended Application class
            UI.Root.AddChild(helloText);

            //// Viewport
            var viewport = new Viewport(scene, camera, null);
            Renderer.SetViewport(0, viewport);
            ////viewport.RenderPath.Append(CoreAssets.PostProcess.FXAA2);
            
            // Setting Application properties
            Input.Enabled = true;
            // FPS
            new MonoDebugHud(this).Show(Color.Green, 25);
            CreateSkybox();

            // Run a an action to spin the Earth (7 degrees per second)
            phpApp.runRotations(-7, 1);
            await rootNode.RunActionsAsync(new EaseOut(new MoveTo(2f, new Vector3(0, 0, 12)), 1));

            phpApp.AddCity(0, 0, "(0, 0)", earthNode.Scale.Y / 2, Vector3.Up);
            phpApp.AddCity(53.9045f, 27.5615f, "Minsk", earthNode.Scale.Y / 2f, Vector3.Up);
            phpApp.AddCity(51.5074f, 0.1278f, "London", earthNode.Scale.Y / 2f, Vector3.Up);
            phpApp.AddCity(40.7128f, -74.0059f, "New-York", earthNode.Scale.Y / 2f, Vector3.Up);
            phpApp.AddCity(37.7749f, -122.4194f, "San Francisco", earthNode.Scale.Y / 2f, Vector3.Up);
            phpApp.AddCity(39.9042f, 116.4074f, "Beijing", earthNode.Scale.Y / 2f, Vector3.Up);
            phpApp.AddCity(-31.9505f, 115.8605f, "Perth", earthNode.Scale.Y / 2f, Vector3.Up);
        }

        protected override void OnUpdate(float timeStep)
        {
            MoveCameraByTouches(timeStep);
            SimpleMoveCamera3D(timeStep);
            base.OnUpdate(timeStep);
        }

        /// <summary>
        /// Move camera for 3D samples
        /// </summary>
        protected void SimpleMoveCamera3D(float timeStep, float moveSpeed = 10.0f)
        {
            if (!Input.GetMouseButtonDown(MouseButton.Left))
                return;

            const float mouseSensitivity = .1f;
            var mouseMove = Input.MouseMove;
            yaw += mouseSensitivity * mouseMove.X;
            pitch += mouseSensitivity * mouseMove.Y;
            pitch = MathHelper.Clamp(pitch, -90, 90);
            cameraNode.Rotation = new Quaternion(pitch, yaw, 0);
        }

        protected void MoveCameraByTouches(float timeStep)
        {
            const float touchSensitivity = 2f;

            var input = Input;
            for (uint i = 0, num = input.NumTouches; i < num; ++i)
            {
                TouchState state = input.GetTouch(i);
                if (state.Delta.X != 0 || state.Delta.Y != 0)
                {
                    var camera = cameraNode.GetComponent<Camera>();
                    if (camera == null)
                        return;

                    yaw += touchSensitivity * camera.Fov / Graphics.Height * state.Delta.X;
                    pitch += touchSensitivity * camera.Fov / Graphics.Height * state.Delta.Y;
                    cameraNode.Rotation = new Quaternion(pitch, yaw, 0);
                }
            }
        }
    }
}
