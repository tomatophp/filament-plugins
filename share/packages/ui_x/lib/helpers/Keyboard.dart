import 'package:flutter/material.dart';

class Keyboard {
  static void hide(BuildContext context) {
    FocusScopeNode currentFocus = FocusScope.of(context);
    if (!currentFocus.hasPrimaryFocus) {
      currentFocus.unfocus();
    }
  }
}

hideKeyboard(BuildContext context) {
  return Keyboard.hide(context);
}
