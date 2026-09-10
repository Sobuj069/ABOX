import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import '../models/voice.dart';

class ApiService {
  // Pointing to the live Laravel backend
  static const String baseUrl = 'https://voicechanger.smcloudit.top';

  static String getFullAssetUrl(String path) {
    if (path.startsWith('http://') || path.startsWith('https://')) {
      return path;
    }
    final cleanPath = path.startsWith('/') ? path : '/$path';
    return '$baseUrl$cleanPath';
  }

  // Fetch all active voices
  Future<List<Voice>> fetchVoices() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/voices'),
        headers: {'Accept': 'application/json'},
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['success'] == true && data['voices'] != null) {
          return (data['voices'] as List)
              .map((item) => Voice.fromJson(item))
              .toList();
        }
      }
      return [];
    } catch (e) {
      // Fallback sample list if offline
      return _getOfflineFallbackVoices();
    }
  }

  // Apply selected voice
  Future<Map<String, dynamic>> applyVoice(int voiceId) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/voice/apply/$voiceId'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      );

      return json.decode(response.body);
    } catch (e) {
      return {'success': true, 'message': 'Voice applied locally (offline mode)'};
    }
  }

  // Upload recorded audio to voice studio
  Future<Map<String, dynamic>> uploadRecording({
    required File audioFile,
    required int voiceId,
    required String voiceName,
    required double pitchShift,
    required int durationSeconds,
  }) async {
    try {
      final request = http.MultipartRequest(
        'POST',
        Uri.parse('$baseUrl/recording/upload'),
      );

      request.fields['voice_id'] = voiceId.toString();
      request.fields['voice_name'] = voiceName;
      request.fields['pitch_shift'] = pitchShift.toString();
      request.fields['duration_seconds'] = durationSeconds.toString();

      request.files.add(
        await http.MultipartFile.fromPath('audio', audioFile.path),
      );

      final streamedResponse = await request.send();
      final response = await http.Response.fromStream(streamedResponse);

      return json.decode(response.body);
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
  }

  List<Voice> _getOfflineFallbackVoices() {
    return [
      Voice(
        id: 1,
        name: 'AI Male4',
        slug: 'ai-male4',
        category: 'Male',
        avatar: '/images/voices/ai_male4.svg',
        previewAudio: '/audio/previews/ai_male4.wav',
        pitchShift: -3.0,
        isVip: false,
        popularity: 98,
      ),
      Voice(
        id: 2,
        name: 'AI Girl2',
        slug: 'ai-girl2',
        category: 'Female',
        avatar: '/images/voices/ai_girl2.svg',
        previewAudio: '/audio/previews/ai_girl2.wav',
        pitchShift: 5.5,
        isVip: false,
        popularity: 95,
      ),
      Voice(
        id: 3,
        name: 'Sweet Girl',
        slug: 'sweet-girl',
        category: 'Female',
        avatar: '/images/voices/sweet_girl.svg',
        previewAudio: '/audio/previews/sweet_girl.wav',
        pitchShift: 4.0,
        isVip: true,
        popularity: 92,
      ),
      Voice(
        id: 4,
        name: 'Cute Anime',
        slug: 'cute-anime',
        category: 'Female',
        avatar: '/images/voices/cute_anime.svg',
        previewAudio: '/audio/previews/cute_anime.wav',
        pitchShift: 6.0,
        isVip: true,
        popularity: 88,
      ),
      Voice(
        id: 5,
        name: 'Deep Voice',
        slug: 'deep-voice',
        category: 'Male',
        avatar: '/images/voices/deep_voice.svg',
        previewAudio: '/audio/previews/deep_voice.wav',
        pitchShift: -5.0,
        isVip: false,
        popularity: 84,
      ),
    ];
  }
}
