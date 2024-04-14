import 'package:flutter/material.dart';
import '/app/helpers/Global.dart';

class NotConnectedErrorPage extends StatelessWidget {
  NotConnectedErrorPage(
      {Key? key,
      this.message = "You are not connected to internet!",
      this.action,
      this.actionLabel = "Retry"})
      : super(key: key);

  final String? message;
  final VoidCallback? action;
  final String actionLabel;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        width: double.infinity,
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            Spacer(),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 32.0),
              child: Image.asset(
                "${image('no-internet.png')}",
                width: MediaQuery.of(context).size.width * 0.65,
              ),
            ),
            SizedBox(height: 24.0),
            Text('Oops!', style: Theme.of(context).textTheme.headline3),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 32.0)
                  .copyWith(top: 8.0),
              child: Text(
                '$message',
                style: Theme.of(context).textTheme.subtitle1,
                textAlign: TextAlign.center,
              ),
            ),
            Spacer(),
          ],
        ),
      ),
    );
  }
}
