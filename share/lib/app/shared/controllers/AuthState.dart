import 'package:get/get.dart';
import 'package:ui_x/ui_x.dart';

import '/app/helpers/Global.dart';
import '/app/models/ApiResponse.dart';
import '/app/models/UserModel.dart';
import '/app/modules/Modules.dart';
import '/app/shared/services/Services.dart';
import 'AppController.dart';

/// Manages authentication states and logics
/// for entire application
class AuthState extends AppController {
  /// Static Getter for [AuthState]
  ///
  /// Can be accessed by calling `AuthState.instance`
  static AuthState get instance {
    if (!Get.isRegistered<AuthState>()) Get.put(AuthState());
    return Get.find<AuthState>();
  }

  /// Get [AuthService] instance
  final AuthStateService _authStateService = AuthStateService.instance;

  /// Observables
  var _user = UserModel().obs;

  /// Getters
  UserModel get user => _user.value;

  @override
  void onInit() {
    super.onInit();
    getUser();
  }

  /// Refreshes User data on every launch of the application
  Future<void> getUser() async {
    if (storage.read("token") != null) {
      ApiResponse response = await _authStateService.getUser();

      if (response.hasError()) {
        Toastr.show(message: "${response.message}");
        return;
      }

      if (response.hasData()) {
        setUserData(response.data);
      }
    }
  }

  /// Logout the user + Server
  Future<void> logout() async {
    ApiResponse response = await _authStateService.logout();
    if (response.hasError()) {
      Toastr.show(message: "${response.message}");
      return;
    }
    Toastr.show(message: "${response.message}");
    await storage.remove('token');
    await storage.remove('user');
    Get.offAllNamed(AuthRoutes.login);
  }

  /// Logout the user
  Future<void> logoutSilently() async {
    await storage.remove('token');
    await storage.remove('user');
    Get.offAllNamed(AuthRoutes.login);
  }

  /// Setter for user data [setUserData(String)]
  Future<void> setUserData(Map<String, dynamic> userData) async {
    await storage.write("user", userData);
    _user(UserModel.fromJson(userData));
  }

  /// Setter for user auth token [setUserToken(String)]
  Future<void> setUserToken(String token) async {
    await storage.write("token", token);
  }

  /// Checks if user is logged in by validating the token
  Future<bool> check() async {
    if (storage.read('token') != null) {
      /// TODO: Add api call here to validate token
      return true;
    }

    return false;
  }

  Future<bool> isLoggedIn() => check();
}
