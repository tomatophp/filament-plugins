import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../helpers/ColorPalette.dart';
import '../LoadingIcon.dart';

class ButtonIcon extends StatelessWidget {
  final Widget icon;
  final Widget? loadingIcon;
  final Color? backgroundColor;
  final Color? color;
  final void Function(ButtonIconController)? onTap;
  final bool outline;
  final num radius;

  ButtonIcon({
    required Key key,
    required this.icon,
    this.onTap,
    this.outline = false,
    this.backgroundColor = kcPrimary,
    this.color,
    this.loadingIcon,
    this.radius = 16,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final ButtonIconController btnController =
        Get.put(ButtonIconController(), tag: key.toString());
    return Obx(
      () => GestureDetector(
        onTap: () {
          if (!btnController.isBusy && !btnController.isDisabled) {
            onTap!(btnController);
          }
        },
        child: Row(
          mainAxisSize: MainAxisSize.min,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            AnimatedContainer(
              duration: const Duration(milliseconds: 250),
              padding: EdgeInsets.symmetric(vertical: 12, horizontal: 12),
              constraints:
                  BoxConstraints(minHeight: radius * 2, minWidth: radius * 2),
              alignment: Alignment.center,
              decoration: !outline
                  ? BoxDecoration(
                      color: !btnController.isDisabled
                          ? backgroundColor
                          : backgroundColor!.withOpacity(0.5),
                      borderRadius:
                          BorderRadius.circular(radius.toDouble() * 2),
                    )
                  : BoxDecoration(
                      color: Colors.transparent,
                      borderRadius:
                          BorderRadius.circular(radius.toDouble() * 2),
                      border: Border.all(
                        color: !btnController.isDisabled
                            ? getContrastColor(backgroundColor!)
                            : getContrastColor(backgroundColor!)
                                .withOpacity(0.5),
                        width: 1,
                      ),
                    ),
              child: !btnController.isBusy
                  ? icon
                  : loadingIcon != null
                      ? SizedBox(height: 20, width: 20, child: loadingIcon)
                      : LoadingIcon(
                          color: !outline
                              ? getContrastColor(backgroundColor!)
                              : backgroundColor!,
                          height: 16,
                        ),
            ),
          ],
        ),
      ),
    );
  }
}

class ButtonIconController extends GetxController {
  RxBool _isBusy = RxBool(false);
  RxBool _isDisabled = RxBool(false);

  bool get isBusy => _isBusy.value;

  bool get isDisabled => _isDisabled.value;

  ButtonIconController setBusy(bool val) {
    _isBusy(val);
    return this;
  }

  ButtonIconController setDisabled(bool val) {
    _isDisabled(val);
    return this;
  }
}
