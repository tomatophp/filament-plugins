import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';

import '/app/helpers/Global.dart';

class NotFoundErrorPage extends StatelessWidget {
  NotFoundErrorPage(
      {Key? key,
      required this.message,
      this.action,
      this.actionLabel = "Retry"})
      : super(key: key);

  final String message;
  final VoidCallback? action;
  final String actionLabel;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Spacer(flex: 1),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 32.0),
            child: SvgPicture.asset(
              assetIcon('not_found.svg'),
              width: MediaQuery.of(context).size.width * 0.65,
            ),
          ),
          SizedBox(height: 16.0),
          Text('Oops!', style: Theme.of(context).textTheme.headline3),
          Padding(
            padding:
                const EdgeInsets.symmetric(horizontal: 32.0).copyWith(top: 8.0),
            child: Text(
              '$message',
              style: Theme.of(context).textTheme.subtitle1,
              textAlign: TextAlign.center,
            ),
          ),
          Spacer(flex: 1),
          action != null
              ? Padding(
                  padding: EdgeInsets.symmetric(horizontal: 32.0)
                      .copyWith(bottom: 32.0),
                  child: TextButton(
                    onPressed: action,
                    child: Text(
                      '$actionLabel',
                      style: Theme.of(context)
                          .textTheme
                          .button
                          ?.copyWith(color: Colors.white),
                    ),
                    style: TextButton.styleFrom(
                      backgroundColor: Colors.grey[800],
                      minimumSize:
                          Size(MediaQuery.of(context).size.width, 40.0),
                      padding: const EdgeInsets.symmetric(horizontal: 32.0),
                    ),
                  ),
                )
              : SizedBox.shrink(),
        ],
      ),
    );
  }
}
