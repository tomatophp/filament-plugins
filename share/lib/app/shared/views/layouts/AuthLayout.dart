import 'package:flutter/material.dart';
import 'package:get/get.dart';

class AuthLayout extends StatelessWidget {
  final Widget body;
  final Color? backgroundColor;

  AuthLayout({Key? key, required this.body, this.backgroundColor})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Directionality(
        textDirection: Get.locale.toString() == 'ar_EG' ?
          TextDirection.rtl :
          TextDirection.ltr,
        child: Scaffold(
          backgroundColor: backgroundColor,
          body: body,
        )
    );
  }
}
