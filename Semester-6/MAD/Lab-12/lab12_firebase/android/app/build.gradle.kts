plugins {
    id("com.android.application")
    id("kotlin-android")

    // Flutter plugin (must be after Android + Kotlin)
    id("dev.flutter.flutter-gradle-plugin")

    // Firebase Google Services plugin (STEP 10)
    id("com.google.gms.google-services")
}

android {
    namespace = "com.example.lab12_firebase"
    compileSdk = flutter.compileSdkVersion
    ndkVersion = flutter.ndkVersion

    compileOptions {
        sourceCompatibility = JavaVersion.VERSION_17
        targetCompatibility = JavaVersion.VERSION_17
    }

    kotlinOptions {
        jvmTarget = JavaVersion.VERSION_17.toString()
    }

    defaultConfig {
        applicationId = "com.example.lab12_firebase"
        minSdk = flutter.minSdkVersion
        targetSdk = flutter.targetSdkVersion
        versionCode = flutter.versionCode
        versionName = flutter.versionName
    }

    buildTypes {
        release {
            signingConfig = signingConfigs.getByName("debug")
        }
    }
}

flutter {
    source = "../.."
}
apply(plugin = "com.google.gms.google-services")