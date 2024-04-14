import 'package:flutter/material.dart';
import '/app/helpers/Global.dart';
import 'package:ui_x/ui_x.dart';

class LoadingIconWidget extends StatelessWidget {
  LoadingIconWidget({Key? key, this.message = "Loading..."}) : super(key: key);
  final String message;
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Container(
          height: screen.height,
          width: double.maxFinite,
          padding: const EdgeInsets.symmetric(horizontal: 32),
          decoration: BoxDecoration(
            color: Colors.black.withOpacity(0.75),
          ),
          child: Center(
            child: Container(
              padding: const EdgeInsets.all(16),
              width: screen.width * 0.5,
              decoration: BoxDecoration(
                color: Theme.of(context).backgroundColor,
                borderRadius: BorderRadius.circular(8),
                boxShadow: [
                  BoxShadow(
                      color: Colors.black.withOpacity(0.15), blurRadius: 5),
                ],
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  SizedBox(height: 12),
                  Image.asset(
                    "assets/icons/loading-circular.gif",
                    height: 56,
                  ),
                  SizedBox(height: 20),
                  Text(
                    "$message",
                    style: TextStyl.body(context),
                    textAlign: TextAlign.center,
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
