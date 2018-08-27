using System;
using Pchp.Core;
using Pchp.Core.Reflection;

namespace PHPLib
{
    public class Class1
    {
        public Context ctx;

        public Class1()
        {
            ctx = Context.CreateEmpty();
        }

        public string GetGreet()
        {
            return "GREETINGS!'";
        }

    }
}
