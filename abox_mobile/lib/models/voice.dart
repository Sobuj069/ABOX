class Voice {
  final int id;
  final String name;
  final String slug;
  final String category;
  final String avatar;
  final String previewAudio;
  final double pitchShift;
  final bool isVip;
  final int popularity;

  Voice({
    required this.id,
    required this.name,
    required this.slug,
    required this.category,
    required this.avatar,
    required this.previewAudio,
    required this.pitchShift,
    required this.isVip,
    required this.popularity,
  });

  factory Voice.fromJson(Map<String, dynamic> json) {
    return Voice(
      id: json['id'] is int ? json['id'] : int.parse(json['id'].toString()),
      name: json['name'] ?? '',
      slug: json['slug'] ?? '',
      category: json['category'] ?? 'General',
      avatar: json['avatar'] ?? '',
      previewAudio: json['preview_audio'] ?? '',
      pitchShift: json['pitch_shift'] != null
          ? double.tryParse(json['pitch_shift'].toString()) ?? 0.0
          : 0.0,
      isVip: json['is_vip'] == 1 || json['is_vip'] == true,
      popularity: json['popularity'] is int
          ? json['popularity']
          : int.tryParse(json['popularity']?.toString() ?? '0') ?? 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'slug': slug,
      'category': category,
      'avatar': avatar,
      'preview_audio': previewAudio,
      'pitch_shift': pitchShift,
      'is_vip': isVip,
      'popularity': popularity,
    };
  }
}
