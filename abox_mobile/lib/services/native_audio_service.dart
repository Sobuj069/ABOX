import 'package:flutter/services.dart';

class NativeAudioService {
  static const MethodChannel _channel = MethodChannel('com.abox.voicechanger/audio');

  // Starts the Android Background Foreground Service & Low-latency Audio DSP Loop
  static Future<bool> startLiveService({
    required double pitchShift,
    required String voiceName,
  }) async {
    try {
      final bool? result = await _channel.invokeMethod('startVoiceService', {
        'pitch': pitchShift,
        'voiceName': voiceName,
      });
      return result ?? false;
    } on PlatformException catch (_) {
      return false;
    }
  }

  // Stops the Android Background Service
  static Future<bool> stopLiveService() async {
    try {
      final bool? result = await _channel.invokeMethod('stopVoiceService');
      return result ?? false;
    } on PlatformException catch (_) {
      return false;
    }
  }

  // Dynamically adjusts pitch in real-time while call is active
  static Future<void> updatePitch(double pitchShift) async {
    try {
      await _channel.invokeMethod('updatePitch', {'pitch': pitchShift});
    } on PlatformException catch (_) {}
  }

  // Checks if the foreground hook service is currently active
  static Future<bool> isServiceRunning() async {
    try {
      final bool? result = await _channel.invokeMethod('isServiceRunning');
      return result ?? false;
    } on PlatformException catch (_) {
      return false;
    }
  }
}
