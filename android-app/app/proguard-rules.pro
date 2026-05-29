# Keep the JS bridge so it's not stripped by R8
-keepclassmembers class com.utangnilamo.app.MainActivity$PrintBridge {
    public *;
}
