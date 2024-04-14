import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '/app/helpers/Global.dart';
import '/firebase_options.dart';
import 'app/shared/services/NotificationService.dart';

class FireConnect {
  /// Private constructor to prevent instantiation from outside the class
  FireConnect._();

  /// Instance of the DataManager
  static final FireConnect _instance = FireConnect._();

  /// Factory constructor to return the same instance every time
  factory FireConnect() => _instance;

  /// Initialize [FireConnect]

  Future<void> init() async {
    await Firebase.initializeApp(
      options: DefaultFirebaseOptions.currentPlatform,
    );

    _firebaseToken();

    FirebaseMessaging.onMessage.listen((RemoteMessage message) {
      if (kDebugMode) {
        print('Handling a foreground message: ${message.messageId}');
        print('Message data: ${message.data}');
        print('Message notification: ${message.notification?.title}');
        print('Message notification: ${message.notification?.body}');
      }

      // Use this method to automatically convert the push data, in case you gonna use it
      NotificationService _notificationService = NotificationService();

      _notificationService.show(message.messageId.hashCode,
          title: message.notification!.title ?? "",
          body: message.notification!.body ?? "");

      Get.snackbar(
        message.notification!.title ?? "",
        message.notification!.body ?? "",
        icon: Icon(Icons.notifications),
        backgroundColor: Colors.green,
        colorText: Colors.white70,
        margin: EdgeInsets.all(20),
      );
    });
  }

  Future<String>? _firebaseToken() async {
    final messaging = FirebaseMessaging.instance;

    final settings = await messaging.requestPermission(
      alert: true,
      announcement: false,
      badge: true,
      carPlay: false,
      criticalAlert: false,
      provisional: false,
      sound: true,
    );

    if (kDebugMode) {
      print('Permission granted: ${settings.authorizationStatus}');
    }

    const vapidKey = "";

    // use the registration token to send messages to users from your trusted server environment
    String? token;

    if (DefaultFirebaseOptions.currentPlatform == DefaultFirebaseOptions.web) {
      token = await messaging.getToken(
        vapidKey: vapidKey,
      );
      storage.write("fcm-web", token);
    } else {
      token = await messaging.getToken();
      storage.write("fcm-api", token);
    }

    if (kDebugMode) {
      print('Registration Token=$token');
    }

    return token.toString();
  }
}
