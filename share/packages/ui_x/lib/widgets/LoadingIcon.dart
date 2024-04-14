import 'package:flutter/material.dart';

class LoadingIcon extends StatelessWidget {
  final double? height;
  final Color? color;

  LoadingIcon({Key? key, this.height, this.color}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Image.asset(
      'assets/icons/loading.gif',
      height: height ?? 14.0,
      color: color ?? null,
    );
  }
}
