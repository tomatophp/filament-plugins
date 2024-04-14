import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:ui_x/ui_x.dart';

import '/app/helpers/Global.dart';

class NoDataWidget extends StatelessWidget {
  NoDataWidget(
      {Key? key,
      this.message = 'No Data Yet!',
      this.action,
      this.showTitle = true,
      this.title = 'Oops!',
      this.icon})
      : super(key: key);

  final String message;
  final String title;
  final Widget? action;
  final Widget? icon;
  final bool showTitle;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        width: double.maxFinite,
        alignment: Alignment.center,
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            icon != null
                ? icon!
                : Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 32.0),
                    child: SvgPicture.asset(
                      assetIcon('not_found.svg'),
                      width: MediaQuery.of(context).size.width * 0.65,
                    ),
                  ),
            const SizedBox(height: spacer),
            if (showTitle)
              Text('$title', style: Theme.of(context).textTheme.headline3),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 32.0)
                  .copyWith(top: 8.0),
              child: Text(
                '$message',
                style: Theme.of(context).textTheme.subtitle1,
                textAlign: TextAlign.center,
              ),
            ),
            action != null ? action! : SizedBox.shrink(),
          ],
        ),
      ),
    );
  }
}
