using Android.App;
using Android.Widget;
using Android.OS;
using Urho.Droid;
using Android.Views;
using Urho;

namespace UrhoPHPTest
{
    [Activity(Label = "UrhoPHPTest", MainLauncher = true, Icon = "@mipmap/icon")]
    public class MainActivity : Activity
    {
        UrhoSurfacePlaceholder surface;
        MyApp app;

        protected override async void OnCreate(Bundle bundle)
        {   
            base.OnCreate(bundle);
            var mLayout = new FrameLayout(this);
            surface = UrhoSurface.CreateSurface(this);
            mLayout.AddView(surface);
            SetContentView(mLayout);
            app = await surface.Show<MyApp>(new ApplicationOptions("MyData"));
        }

        protected override void OnResume()
        {
            UrhoSurface.OnResume();
            base.OnResume();
        }

        protected override void OnPause()
        {
            UrhoSurface.OnPause();
            base.OnPause();
        }

        public override void OnLowMemory()
        {
            UrhoSurface.OnLowMemory();
            base.OnLowMemory();
        }

        protected override void OnDestroy()
        {
            UrhoSurface.OnDestroy();
            base.OnDestroy();
        }

        public override bool DispatchKeyEvent(KeyEvent e)
        {
            if (e.KeyCode == Android.Views.Keycode.Back)
            {
                this.Finish();
                return false;
            }

            if (!UrhoSurface.DispatchKeyEvent(e))
                return false;
            return base.DispatchKeyEvent(e);
        }

        public override void OnWindowFocusChanged(bool hasFocus)
        {
            UrhoSurface.OnWindowFocusChanged(hasFocus);
            base.OnWindowFocusChanged(hasFocus);
        }
    }
}

