import 'dart:io';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:get/get.dart';
import 'package:get_storage/get_storage.dart';
import 'package:translations_loader/translations_loader.dart';

import 'app/shared/controllers/AuthState.dart';
import 'app/shared/views/widgets/ThemeBuilder.dart';
import 'config/Config.dart';
import 'config/common/MyHttpOverrides.dart';
import 'config/theme/AppTheme.dart';
import 'lang/Languages.dart';
import 'routes/Router.dart';
import 'routes/Routes.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  HttpOverrides.global = MyHttpOverrides();

  // if(Config.fireConnectActive){
  //   FireConnect _fireConnect = FireConnect();
  //   await _fireConnect.init();
  // }

  /// Initialize the storage
  await GetStorage.init();

  /// Initialize [AuthState]
  Get.put<AuthState>(AuthState(), permanent: true);

  /// Set and lock device Orientation
  SystemChrome.setPreferredOrientations([DeviceOrientation.portraitUp]);
  final lang = await TranslationsLoader.loadTranslations("assets/lang");
  runApp(App(lang));
}

class App extends StatelessWidget {
  late final _lang;
  App(Map<String, Map<String, String>> lang) {
    this._lang = lang;
  }
  @override
  Widget build(BuildContext context) {
    SystemChrome.setSystemUIOverlayStyle(
      SystemUiOverlayStyle(
        statusBarColor: Theme.of(context).brightness == Brightness.dark
            ? Colors.white
            : Colors.black,
        statusBarBrightness: Theme.of(context).brightness,
      ),
    );

    return ThemeBuilder(
      builder: (context, _themeMode) {
        return GetMaterialApp(
          debugShowCheckedModeBanner: false,
          defaultTransition: Transition.fadeIn,
          title: "${Config.appName}",
          theme: AppTheme.lightTheme,
          darkTheme: AppTheme.darkTheme,
          themeMode: _themeMode,
          initialRoute: Routes.splash,
          getPages: routes,
          translations: Languages(_lang),
          locale: Get.deviceLocale,
          fallbackLocale: const Locale('en', 'US'),
          localizationsDelegates: [
            GlobalMaterialLocalizations.delegate,
            GlobalWidgetsLocalizations.delegate,
            GlobalCupertinoLocalizations.delegate,
          ],
          supportedLocales: <Locale>[Locale('en', 'US'), Locale('ar', 'EG')],
        );
      },
    );
  }
}
