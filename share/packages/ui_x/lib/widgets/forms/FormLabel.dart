import 'package:flutter/material.dart';
import 'package:ui_x/ui_x.dart';

class FormLabel extends StatelessWidget {
  FormLabel({required this.label, this.color});

  final String label;
  final Color? color;

  @override
  Widget build(BuildContext context) {
    return Text(
      "$label",
      style: TextStyl.label(context)?.copyWith(color: color ?? kcDark),
    );
  }
}
