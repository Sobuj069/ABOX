import 'package:flutter/material.dart';
import 'package:audioplayers/audioplayers.dart';
import '../models/voice.dart';
import '../services/api_service.dart';
import '../services/native_audio_service.dart';

class VoiceProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();
  final AudioPlayer _audioPlayer = AudioPlayer();

  List<Voice> _voices = [];
  Voice? _selectedVoice;
  int? _playingVoiceId;
  bool _isLoading = false;
  bool _isLiveServiceRunning = false;
  bool _isImoIntegrationEnabled = true;
  int _userCredits = 50;
  bool _isUserVip = false;

  List<Voice> get voices => _voices;
  Voice? get selectedVoice => _selectedVoice;
  int? get playingVoiceId => _playingVoiceId;
  bool get isLoading => _isLoading;
  bool get isLiveServiceRunning => _isLiveServiceRunning;
  bool get isImoIntegrationEnabled => _isImoIntegrationEnabled;
  int get userCredits => _userCredits;
  bool get isUserVip => _isUserVip;

  VoiceProvider() {
    _init();
  }

  Future<void> _init() async {
    await loadVoices();
    _audioPlayer.onPlayerComplete.listen((_) {
      _playingVoiceId = null;
      notifyListeners();
    });
  }

  Future<void> loadVoices() async {
    _isLoading = true;
    notifyListeners();

    _voices = await _apiService.fetchVoices();
    if (_voices.isNotEmpty) {
      // Default to AI Male4 (matches screenshot)
      _selectedVoice = _voices.firstWhere(
        (v) => v.name.toLowerCase().contains('male4'),
        orElse: () => _voices.first,
      );
    }

    _isLoading = false;
    notifyListeners();
  }

  // Play natural voice preview
  Future<void> playVoicePreview(Voice voice) async {
    if (_playingVoiceId == voice.id) {
      await _audioPlayer.stop();
      _playingVoiceId = null;
      notifyListeners();
      return;
    }

    _playingVoiceId = voice.id;
    notifyListeners();

    try {
      final audioUrl = ApiService.getFullAssetUrl(voice.previewAudio);
      await _audioPlayer.stop();
      await _audioPlayer.play(UrlSource(audioUrl));
    } catch (_) {
      _playingVoiceId = null;
      notifyListeners();
    }
  }

  // Select and apply voice
  Future<void> selectVoice(Voice voice) async {
    if (voice.isVip && !_isUserVip) {
      // VIP required
      return;
    }

    _selectedVoice = voice;
    notifyListeners();

    // Notify backend
    await _apiService.applyVoice(voice.id);

    // If live service is active, update pitch immediately
    if (_isLiveServiceRunning) {
      await NativeAudioService.updatePitch(voice.pitchShift);
    }
  }

  // Toggle imo HD integration switch
  void toggleImoIntegration() {
    _isImoIntegrationEnabled = !_isImoIntegrationEnabled;
    notifyListeners();
  }

  // Toggle the Android Native live audio hook service
  Future<bool> toggleLiveService() async {
    if (_selectedVoice == null) return false;

    if (_isLiveServiceRunning) {
      final stopped = await NativeAudioService.stopLiveService();
      if (stopped) {
        _isLiveServiceRunning = false;
        notifyListeners();
      }
      return !stopped;
    } else {
      final started = await NativeAudioService.startLiveService(
        pitchShift: _selectedVoice!.pitchShift,
        voiceName: _selectedVoice!.name,
      );
      if (started) {
        _isLiveServiceRunning = true;
        notifyListeners();
      }
      return started;
    }
  }

  void addCredits(int amount) {
    _userCredits += amount;
    notifyListeners();
  }

  void setVip(bool vip) {
    _isUserVip = vip;
    notifyListeners();
  }

  @override
  void dispose() {
    _audioPlayer.dispose();
    super.dispose();
  }
}
