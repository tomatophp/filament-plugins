import 'package:flutter_local_notifications/flutter_local_notifications.dart';

class NotificationService {
  //Handle displaying of notifications.
  static final NotificationService _notificationService = NotificationService._internal();
  final FlutterLocalNotificationsPlugin _flutterLocalNotificationsPlugin = FlutterLocalNotificationsPlugin();
  final AndroidInitializationSettings _androidInitializationSettings = const AndroidInitializationSettings('ic_launcher');

  factory NotificationService() {
    return _notificationService;
  }

  NotificationService._internal() {
    init();
  }

  void init() async {
    final InitializationSettings initializationSettings = InitializationSettings(
      android: _androidInitializationSettings,
    );
    await _flutterLocalNotificationsPlugin.initialize(initializationSettings);
  }

  void show(int id, {required String title, required String body, String? payload}) {
    print(id);
    print(title);
    print(body);
    _flutterLocalNotificationsPlugin.show(
      id,
      title,
      body,
      NotificationDetails(
        android: AndroidNotificationDetails(
          'static_notification_channel_id',
          'static_notification_channel',
          channelDescription: 'static_notification_channel_description',
        ),
      ),
      payload: payload,
    );
  }

  void showProgressed(int id, {required int progress, required String title, required String body, String? payload}) {
    //show the notifications.
    var androidPlatformChannelSpecifics = AndroidNotificationDetails(
      'progress channel',
      'progress channel',
      channelDescription: 'progress channel description',
      channelShowBadge: false,
      importance: Importance.max,
      priority: Priority.high,
      onlyAlertOnce: true,
      showProgress: true,
      maxProgress: 100,
      progress: progress,
    );
    var platformChannelSpecifics = NotificationDetails(android: androidPlatformChannelSpecifics);
    _flutterLocalNotificationsPlugin.show(
      id,
      title,
      body,
      platformChannelSpecifics,
      payload: payload,
    );
  }
}
