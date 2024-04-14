import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '/app/helpers/Global.dart';
import '/app/modules/Dashboard/DashboardModule.dart';
import '/app/modules/Dashboard/routes/DashboardRoutes.dart';
import '/app/modules/Location/LocationModule.dart';
import '/config/theme/AppTheme.dart';

class SideMenu extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: ListView(
        // Important: Remove any padding from the ListView.
        padding: EdgeInsets.zero,
        children: [
          UserAccountsDrawerHeader(
            // <-- SEE HERE
            decoration: BoxDecoration(color: AppTheme.lightTheme.primaryColor),
            accountName: Text(
              auth.user.name ?? '',
              style: TextStyle(
                fontWeight: FontWeight.bold,
              ),
            ),
            accountEmail: Text(
              auth.user.email ?? '',
              style: TextStyle(
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
          ListTile(
            title: const Text('Dashboard'),
            leading: Icon(Icons.dashboard),
            onTap: () {
              Get.toNamed(DashboardRoutes.dashboard);
              // Navigator.pop(context);
            },
          ),
          ListTile(
            title: const Text('Location'),
            leading: Icon(Icons.location_city),
            onTap: () {
              Get.toNamed(LocationRoutes.location);
              // Navigator.pop(context);
            },
          )
        ],
      ),
    );
  }
}
