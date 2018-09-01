using System;
using Urho;
using Urho.Gui;
using Urho.Resources;
using Urho.Shapes;

namespace Urho
{
    public static class UtilityFunctions
    {
        public static Sphere CreateSphereComponent(Node node)
        {
            return node.CreateComponent<Sphere>();
        }

        public static Octree CreateOctree(Scene scene)
        {
            return scene.CreateComponent<Octree>();
        }

        public static Light CreateLight(Node node)
        {
            return node.CreateComponent<Light>();
        }

        public static Camera CreateCamera(Node node)
        {
            return node.CreateComponent<Camera>();
        }
    }
}
