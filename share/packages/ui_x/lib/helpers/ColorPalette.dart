import 'package:flutter/material.dart';

/// Neutral Colors
const Color kcWhite = Color(0xffffffff);
const Color kcOffWhite = Color(0xfff1f1f1);
const Color kcBlack = Color(0xff151515);

/// GRAY Colors
const Color kcGray = Color(0xffcdcdcd);
const Color kcLightGray = Color(0xffeeeeee);

/// All Dark Colors
const Color kcDark = Color(0xFF414141);
const Color kcDarkAlt = Color(0xFF7E7E7E);
const Color kcDarker = Color(0xFF252929);
const Color kcDarkest = Color(0xFF191C1C);

/// Theme colors
const Color kcPrimary = Color(0xffFF5850);
const Color kcPrimaryLight = Color(0xffff8179);
const Color kcSecondary = Color(0xFF252929);
const Color kcAccent = Color(0xffdddd2e);
const Color kcDanger = Color(0xFFEB3349);
const Color kcSuccess = Color(0xFF46db43);
const Color kcInfo = Color(0xFF2775C8);
const Color kcWarning = Color(0xFFF59E0B);

/// Design specific colors
const Color kcAppbarBackground = kcPrimary;

Color getContrastColor(Color color) {
  int d = 0;

// Counting the perceptive luminance - human eye favors green color...
  double luminance = (0.299 * color.red + 0.587 * color.green + 0.114 * color.blue) / 255;

  if (luminance > 0.7)
    d = 0; // bright colors - black font
  else
    d = 255; // dark colors - white font

  return Color.fromARGB(color.alpha, d, d, d);
}


Color tintColor(Color baseColor, int shade) {
  final int red = baseColor.red;
  final int green = baseColor.green;
  final int blue = baseColor.blue;

  final double shadeRatio = (shade - 500) / 1000.0;

  final int tintedRed = (red + (red * shadeRatio)).round().clamp(0, 255);
  final int tintedGreen = (green + (green * shadeRatio)).round().clamp(0, 255);
  final int tintedBlue = (blue + (blue * shadeRatio)).round().clamp(0, 255);

  return Color.fromARGB(
    baseColor.alpha,
    tintedRed,
    tintedGreen,
    tintedBlue,
  );
}


Color lightenColor(Color baseColor, double amount) {
  final int red = (baseColor.red + ((255 - baseColor.red) * amount)).round().clamp(0, 255);
  final int green = (baseColor.green + ((255 - baseColor.green) * amount)).round().clamp(0, 255);
  final int blue = (baseColor.blue + ((255 - baseColor.blue) * amount)).round().clamp(0, 255);

  return Color.fromARGB(
    baseColor.alpha,
    red,
    green,
    blue,
  );
}