import 'dart:math';

import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:get_storage/get_storage.dart';
import 'package:logger/logger.dart';

import '/app/shared/views/widgets/ThemeBuilder.dart';
import '/app/shared/controllers/AuthState.dart';

///======================================
/// Get Instance of [AuthState]
///======================================
AuthState authState = AuthState.instance;
AuthState auth = AuthState.instance;

///======================================
/// Get storage Helper
///======================================
GetStorage storage = new GetStorage();

///======================================
/// Get screen size helper
///======================================
Size screen = Get.size;

///======================================
/// Image Helper
///======================================
String image(name) {
  return "assets/images/$name";
}

///======================================
/// String UCFirst Helper
///======================================
String capitalize(String text) {
  return "${text[0].toUpperCase()}${text.substring(1).toLowerCase()}";
}

///======================================
/// Icon Helper
///======================================
String assetIcon(name) {
  return "assets/icons/$name";
}

///======================================
/// Current Theme
///======================================
ThemeMode? currentTheme(){
  var context = Get.context;
  return ThemeBuilder.of(context!)?.getCurrentTheme();
}

///======================================
/// Check current route
///======================================
bool routeIs(name) {
  return Get.routing.current == name;
}

///======================================
/// Console Logger
///======================================
var log = Logger(
  printer: PrettyPrinter(),
);

///======================================
/// Generate and get MaterialColor From a Color
///======================================
MaterialColor generateMaterialColor(Color color) {
  return MaterialColor(color.value, {
    50: _tintColor(color, 0.9),
    100: _tintColor(color, 0.8),
    200: _tintColor(color, 0.6),
    300: _tintColor(color, 0.4),
    400: _tintColor(color, 0.2),
    500: color,
    600: _shadeColor(color, 0.1),
    700: _shadeColor(color, 0.2),
    800: _shadeColor(color, 0.3),
    900: _shadeColor(color, 0.4),
  });
}

int _tintValue(int value, double factor) =>
    max(0, min((value + ((255 - value) * factor)).round(), 255));

Color _tintColor(Color color, double factor) => Color.fromRGBO(
    _tintValue(color.red, factor),
    _tintValue(color.green, factor),
    _tintValue(color.blue, factor),
    1);

int _shadeValue(int value, double factor) =>
    max(0, min(value - (value * factor).round(), 255));

Color _shadeColor(Color color, double factor) => Color.fromRGBO(
  _shadeValue(color.red, factor),
  _shadeValue(color.green, factor),
  _shadeValue(color.blue, factor),
  1,
);
