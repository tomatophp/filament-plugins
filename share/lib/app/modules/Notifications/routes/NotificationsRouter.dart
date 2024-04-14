import 'package:get/get.dart';

import '/app/modules/Notifications/views/NotificationsPageIndex.dart';

List<GetPage> notificationsRoutes = [
  GetPage(name: '/user-notifications', page: () => NotificationsPageIndex())
];
