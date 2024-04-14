import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:ui_x/ui_x.dart';

import '/app/helpers/Global.dart';
import '/app/modules/Splash/controllers/SplashController.dart';

class SplashPage extends StatelessWidget {
  SplashPage({Key? key}) : super(key: key);

  final SplashController controller = Get.put(SplashController());

  @override
  Widget build(BuildContext context) {
    final Size screen = Get.size;
    return Scaffold(
      backgroundColor: kcPrimary,
      body: Container(
        color: kcPrimary,
        height: screen.height,
        width: double.infinity,
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            Spacer(),
            Image.asset(
              image('logo.png'),
              width: screen.width * 0.5,
              fit: BoxFit.cover,
              filterQuality: FilterQuality.high,
              color: Colors.white,
            ),
            Spacer(),
            Padding(
              padding: const EdgeInsets.only(bottom: 32.0),
              child: LoadingIcon(height: spacer, color: kcWhite),
            ),
          ],
        ),
      ),
    );
  }
}
