# ABox - Real-time AI Voice Changer (Flutter & Android Native)

Next-Gen AI Real-time Voice Changer mobile app built with **Flutter** (Frontend UI & API State) and **Android Native Kotlin** (Low-latency Audio DSP Hook & Foreground Service), designed for live calling on **imo HD**, WhatsApp, and gaming.

---

## 📱 Features

1. **Exact ABox Cyberpunk Dark UI:**
   - Matches the original ABox screenshot.
   - 5-column Voice Grid with AI Male4 (default active with green checkmark), AI Girl2, Sweet Girl, Cute Anime, etc.
   - Dynamic Apply button with glowing neon gradients.
   - "My Apps" card containing **imo HD** with quick toggle switch.
   - VIP Diamond credits tracker synced with Laravel database.
2. **Live Background Audio Hook (For imo HD Calls):**
   - Android Foreground Service (`VoiceChangerService.kt`) runs continuously in the background.
   - Real-time linear interpolation & formant DSP (`AudioDspProcessor.kt`) shifts voice pitch on the fly without changing speech speed.
   - Low latency (~10ms-20ms) allows talking naturally during live calls.
3. **Voice Studio & Recording:**
   - Record voice, preview with pitch transposition, and send directly to imo contacts with one click.
4. **Laravel 11 Backend Sync:**
   - Connects to `https://voicechanger.smcloudit.top` for real-time voice lists, preview audio files, and VIP status.

---

## 🛠️ How to Build the Android APK

### Prerequisites:
- [Flutter SDK](https://flutter.dev/docs/get-started/install) (version >= 3.0.0)
- Android Studio / Android SDK (API level 34)

### Build Steps:

1. **Navigate to the mobile app folder:**
   ```bash
   cd abox_mobile
   ```

2. **Install Flutter dependencies:**
   ```bash
   flutter pub get
   ```

3. **Build the Release APK:**
   ```bash
   flutter build apk --release
   ```

4. **Locate the APK file:**
   The output APK will be generated at:
   ```
   abox_mobile/build/app/outputs/flutter-apk/app-release.apk
   ```

5. **Install on your Android Phone:**
   - Connect your phone via USB with USB Debugging enabled:
     ```bash
     adb install build/app/outputs/flutter-apk/app-release.apk
     ```
   - Or simply send `app-release.apk` to your phone via WhatsApp/Telegram/Google Drive and tap **Install**.

---

## 📞 How to Use with imo HD

1. Open **ABox** on your Android phone.
2. Grant **Microphone** and **Notification** permissions when prompted.
3. Choose your desired voice (e.g. `AI Male4` or `AI Girl2`) and tap **Apply to Calls**.
4. In the "Automatic Call Mode" card, tap **Start Live Call Mode**.
   - A persistent notification *"ABox Live Voice Active"* will appear in your notification bar.
5. Open **imo HD** and call anyone!
   - Your speech through the phone microphone will automatically be transformed and sent to the other person on the call.
6. When done, open ABox and tap **Stop Background Hook**.

---

## 📂 File Architecture

```
abox_mobile/
├── pubspec.yaml                  # Flutter dependencies & assets
├── README.md                     # Documentation & build instructions
├── lib/
│   ├── main.dart                 # App entry point & bottom navigation
│   ├── constants/
│   │   └── theme.dart            # Cyberpunk dark theme colors & styling
│   ├── models/
│   │   └── voice.dart            # Voice data model
│   ├── providers/
│   │   └── voice_provider.dart   # Central state management
│   ├── services/
│   │   ├── api_service.dart      # Laravel 11 REST API client
│   │   └── native_audio_service.dart # MethodChannel to Android Kotlin
│   └── screens/
│       ├── home_screen.dart      # Main voice selector & imo HD card
│       └── studio_screen.dart    # Voice recording & sharing studio
└── android/
    └── app/src/main/
        ├── AndroidManifest.xml   # Foreground service & audio permissions
        └── kotlin/com/abox/voicechanger/
            ├── MainActivity.kt       # MethodChannel bridge
            ├── VoiceChangerService.kt # Android Foreground Audio Service
            └── AudioDspProcessor.kt  # Real-time DSP pitch shifting engine
```
