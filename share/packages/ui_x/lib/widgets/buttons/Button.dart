import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../helpers/ColorPalette.dart';
import '../../helpers/TextStyl.dart';
import '../LoadingIcon.dart';

enum ButtonVariant { PRIMARY, SECONDARY, DANGER, SUCCESS, INFO, WARNING, DARK, LIGHT }

Map<ButtonVariant, Color> _btnColors = {
  ButtonVariant.PRIMARY: kcPrimary,
  ButtonVariant.SECONDARY: kcSecondary,
  ButtonVariant.DANGER: kcDanger,
  ButtonVariant.SUCCESS: kcSuccess,
  ButtonVariant.INFO: kcInfo,
  ButtonVariant.WARNING: kcWarning,
  ButtonVariant.DARK: kcDark,
  ButtonVariant.LIGHT: kcOffWhite,
};

class Button extends StatelessWidget {
  final String label;
  final void Function(ButtonController)? onTap;
  final bool outline;
  final Widget? leading;
  final Widget? loadingIcon;
  final bool block;
  final bool flat;
  final ButtonVariant variant;
  final EdgeInsets padding;

  Button({
    required Key key,
    required this.label,
    this.onTap,
    this.leading,
    this.loadingIcon,
    this.flat = false,
    this.padding = const EdgeInsets.symmetric(vertical: 12.0, horizontal: 20.0),
  })  : outline = false,
        block = false,
        variant = ButtonVariant.PRIMARY,
        super(key: key);

  Button.outline({
    required Key key,
    required this.label,
    this.onTap,
    this.leading,
    this.loadingIcon,
    this.flat = false,
    this.variant = ButtonVariant.PRIMARY,
    this.padding = const EdgeInsets.symmetric(vertical: 12.0, horizontal: 20.0),
  })  : block = false,
        outline = true,
        super(key: key);

  Button.outlineBlock({
    required Key key,
    required this.label,
    this.onTap,
    this.leading,
    this.loadingIcon,
    this.flat = false,
    this.variant = ButtonVariant.PRIMARY,
    this.padding = const EdgeInsets.symmetric(vertical: 12.0, horizontal: 20.0),
  })  : block = true,
        outline = true,
        super(key: key);

  Button.block({
    required Key key,
    required this.label,
    this.onTap,
    this.leading,
    this.loadingIcon,
    this.flat = false,
    this.variant = ButtonVariant.PRIMARY,
    this.padding = const EdgeInsets.symmetric(vertical: 12.0, horizontal: 20.0),
  })  : block = true,
        outline = false,
        super(key: key);

  Button.primary({
    required Key key,
    required this.label,
    this.onTap,
    this.leading,
    this.loadingIcon,
    this.flat = false,
    this.block = false,
    this.outline = false,
    this.padding = const EdgeInsets.symmetric(vertical: 12.0, horizontal: 20.0),
  })  : variant = ButtonVariant.PRIMARY,
        super(key: key);

  Button.secondary({
    required Key key,
    required this.label,
    this.onTap,
    this.leading,
    this.loadingIcon,
    this.flat = false,
    this.block = false,
    this.outline = false,
    this.padding = const EdgeInsets.symmetric(vertical: 12.0, horizontal: 20.0),
  })  : variant = ButtonVariant.SECONDARY,
        super(key: key);

  Button.success({
    required Key key,
    required this.label,
    this.onTap,
    this.leading,
    this.loadingIcon,
    this.flat = false,
    this.block = false,
    this.outline = false,
    this.padding = const EdgeInsets.symmetric(vertical: 12.0, horizontal: 20.0),
  })  : variant = ButtonVariant.SUCCESS,
        super(key: key);

  Button.danger({
    required Key key,
    required this.label,
    this.onTap,
    this.leading,
    this.loadingIcon,
    this.flat = false,
    this.block = false,
    this.outline = false,
    this.padding = const EdgeInsets.symmetric(vertical: 12.0, horizontal: 20.0),
  })  : variant = ButtonVariant.DANGER,
        super(key: key);

  Button.info({
    required Key key,
    required this.label,
    this.onTap,
    this.leading,
    this.loadingIcon,
    this.flat = false,
    this.block = false,
    this.outline = false,
    this.padding = const EdgeInsets.symmetric(vertical: 12.0, horizontal: 20.0),
  })  : variant = ButtonVariant.INFO,
        super(key: key);

  Button.warning({
    required Key key,
    required this.label,
    this.onTap,
    this.leading,
    this.loadingIcon,
    this.flat = false,
    this.block = false,
    this.outline = false,
    this.padding = const EdgeInsets.symmetric(vertical: 12.0, horizontal: 20.0),
  })  : variant = ButtonVariant.WARNING,
        super(key: key);

  Button.dark({
    required Key key,
    required this.label,
    this.onTap,
    this.leading,
    this.loadingIcon,
    this.flat = false,
    this.block = false,
    this.outline = false,
    this.padding = const EdgeInsets.symmetric(vertical: 12.0, horizontal: 20.0),
  })  : variant = ButtonVariant.DARK,
        super(key: key);

  Button.light({
    required Key key,
    required this.label,
    this.onTap,
    this.leading,
    this.loadingIcon,
    this.flat = false,
    this.block = false,
    this.outline = false,
    this.padding = const EdgeInsets.symmetric(vertical: 12.0, horizontal: 20.0),
  })  : variant = ButtonVariant.LIGHT,
        super(key: key);

  final ButtonController btnController = ButtonController.instance;

  @override
  Widget build(BuildContext context) {
    Color _btnBgColor = _btnColors[variant]!;
    Color _btnTxtColor = _btnBgColor.computeLuminance() > 0.6 ? kcDark : kcWhite;

    return Obx(
      () => GestureDetector(
        onTap: () => onTap!(btnController),
        child: block
            ? AnimatedContainer(
                duration: const Duration(milliseconds: 250),
                padding: padding,
                width: double.infinity,
                alignment: Alignment.center,
                decoration: !outline
                    ? BoxDecoration(
                        color: !btnController.isDisabled ? _btnBgColor : _btnBgColor.withOpacity(0.5),
                        borderRadius: BorderRadius.circular(!flat ? 8 : 0),
                      )
                    : BoxDecoration(
                        color: Colors.transparent,
                        borderRadius: BorderRadius.circular(!flat ? 8 : 0),
                        border: Border.all(
                          color: !btnController.isDisabled ? _btnBgColor : _btnBgColor.withOpacity(0.5),
                          width: 1,
                        ),
                      ),
                child: !btnController.isBusy
                    ? Row(
                        mainAxisSize: MainAxisSize.min,
                        crossAxisAlignment: CrossAxisAlignment.center,
                        children: [
                          if (leading != null) leading!,
                          if (leading != null) SizedBox(width: 6),
                          Text(
                            label,
                            style: TextStyl.button(context)?.copyWith(
                              fontWeight: !outline ? FontWeight.bold : FontWeight.w400,
                              color: !outline ? _btnTxtColor : _btnBgColor,
                            ),
                          ),
                        ],
                      )
                    : loadingIcon != null
                        ? SizedBox(height: 20, width: 20, child: loadingIcon)
                        : LoadingIcon(
                            color: !outline ? _btnTxtColor : _btnBgColor,
                            height: 16,
                          ),
              )
            : Row(
                crossAxisAlignment: CrossAxisAlignment.center,
                mainAxisSize: MainAxisSize.min,
                children: [
                  AnimatedContainer(
                    duration: const Duration(milliseconds: 250),
                    padding: padding,
                    alignment: Alignment.center,
                    decoration: !outline
                        ? BoxDecoration(
                            color: !btnController.isDisabled ? _btnBgColor : _btnBgColor.withOpacity(0.5),
                            borderRadius: BorderRadius.circular(!flat ? 8 : 0),
                            border: Border.all(
                              color: _btnBgColor,
                              width: 1,
                            ),
                          )
                        : BoxDecoration(
                            color: Colors.transparent,
                            borderRadius: BorderRadius.circular(!flat ? 8 : 0),
                            border: Border.all(
                              color: _btnBgColor,
                              width: 1,
                            ),
                          ),
                    child: !btnController.isBusy
                        ? Row(
                            mainAxisSize: MainAxisSize.min,
                            crossAxisAlignment: CrossAxisAlignment.center,
                            children: [
                              if (leading != null) leading!,
                              if (leading != null) SizedBox(width: 6),
                              Text(
                                label,
                                style: TextStyl.button(context)?.copyWith(
                                  fontWeight: !outline ? FontWeight.bold : FontWeight.w400,
                                  color: !outline ? _btnTxtColor : _btnBgColor,
                                ),
                              ),
                            ],
                          )
                        : loadingIcon != null
                            ? SizedBox(height: 20, width: 20, child: loadingIcon)
                            : LoadingIcon(
                                color: !outline ? _btnTxtColor : _btnBgColor,
                                height: 16,
                              ),
                  ),
                ],
              ),
      ),
    );
  }
}

class ButtonController extends GetxController {
  static ButtonController get instance {
    if (!Get.isRegistered<ButtonController>()) Get.create(() => ButtonController());
    return Get.find<ButtonController>();
  }

  RxBool _isBusy = RxBool(false);
  RxBool _isDisabled = RxBool(false);

  bool get isBusy => _isBusy.value;

  bool get isDisabled => _isDisabled.value;

  ButtonController setBusy(bool val) {
    _isBusy(val);
    return this;
  }

  ButtonController setDisabled(bool val) {
    _isDisabled(val);
    return this;
  }
}
