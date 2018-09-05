# UrhoPHPTest

## A Xamarin application using OpenGL through UrhoSharp Framework written mostly in PHP and compiled with The Peachpie Compiler into .NET

### Build Instructions

1. Navigate to the PHPLib folder in terminal
2. Run `dotnet restore` command to restore used packages
3. Run `dotnet build` to build in default configuration, currently debug
  - *UtilityFunctions project referenced in PHPLib is automatically built as well*
4. Open UrhoPHPTest.sln in Visual Studio, build the UrhoPHPTest app and deploy to an Android device or an emulator
