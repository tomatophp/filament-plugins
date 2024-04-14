import 'package:flutter/material.dart';

import '/app/helpers/Global.dart';

class ThemeBuilder extends StatefulWidget {
  final Widget Function(BuildContext context, ThemeMode themeMode) builder;
  ThemeBuilder({required this.builder});

  @override
  _ThemeBuilderState createState() => _ThemeBuilderState();

  static _ThemeBuilderState? of(BuildContext context) {
    return context.findAncestorStateOfType<_ThemeBuilderState>();
  }
}

class _ThemeBuilderState extends State<ThemeBuilder> {
  late ThemeMode _themeMode;

  @override
  void initState() {
    super.initState();
    this.loadTheme();
    if (mounted) setState(() {});
  }

  void changeThemeTo(mode) {
    storage.write('theme_mode', mode);
    this.loadTheme();
  }

  void loadTheme() {
    var _mode = storage.read('theme_mode');
    if (_mode == null) {
      storage.write('theme_mode', 'system');
    }
    setState(() {
      _themeMode = _mode == 'system'
          ? ThemeMode.system
          : _mode == 'light'
              ? ThemeMode.light
              : _mode == 'dark'
                  ? ThemeMode.dark
                  : ThemeMode.system;
    });
  }

  ThemeMode getCurrentTheme() {
    return _themeMode;
  }

  @override
  Widget build(BuildContext context) {
    return widget.builder(context, _themeMode);
  }
}
