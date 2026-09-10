import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class AppColors {
  static const Color darkBg = Color(0xFF07060F);
  static const Color darkCard = Color(0xFF0E0D1B);
  static const Color darkCardLight = Color(0xFF131224);
  static const Color darkBorder = Color(0xFF1B1933);
  static const Color darkBorderLight = Color(0xFF262447);

  // Neon Accents
  static const Color purple = Color(0xFF9333EA);
  static const Color purpleLight = Color(0xFFA855F7);
  static const Color pink = Color(0xFFEC4899);
  static const Color cyan = Color(0xFF06B6D4);
  static const Color gold = Color(0xFFF59E0B);
  static const Color emerald = Color(0xFF10B981);

  // Gradients
  static const LinearGradient primaryGradient = LinearGradient(
    colors: [Color(0xFFEC4899), Color(0xFF9333EA)],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  static const LinearGradient emeraldGradient = LinearGradient(
    colors: [Color(0xFF10B981), Color(0xFF0D9488)],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );
}

class AppTheme {
  static ThemeData get darkTheme {
    return ThemeData(
      brightness: Brightness.dark,
      scaffoldBackgroundColor: AppColors.darkBg,
      primaryColor: AppColors.purple,
      colorScheme: const ColorScheme.dark(
        primary: AppColors.purple,
        secondary: AppColors.pink,
        surface: AppColors.darkCard,
        background: AppColors.darkBg,
      ),
      textTheme: GoogleFonts.outfitTextTheme(ThemeData.dark().textTheme),
      appBarTheme: const AppBarTheme(
        backgroundColor: Colors.transparent,
        elevation: 0,
      ),
    );
  }
}
