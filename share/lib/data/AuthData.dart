import 'package:faker/faker.dart';

import '/app/models/ApiResponse.dart';
import '/app/models/UserModel.dart';

class AuthData {
  static Faker _faker = new Faker();

  /// Create a user model to use in whole file.
  static UserModel _userObject = UserModel(
    id: 1,
    name: "Shoaib khan",
    email: "info@3x1.io",
    phone: "01207860084",
    avatar: "https://random.imagecdn.app/500/500",
    gender: "male",
  );

  /// Login Responses
  static ApiResponse get loginError => ApiResponse(
        status: false,
        message: "Credentials do not match in our records.",
        data: null,
      );

  static ApiResponse get loginSuccess => ApiResponse(
        status: true,
        message: "Logged in successfully.",
        data: {"user": _userObject.toJson(), "token": _faker.jwt.secret},
      );

  /// Register responses
  static ApiResponse get registerSuccess => ApiResponse(
        status: true,
        message: "Welcome aboard! You are now registered with us.",
      );

  /// Get The User Data
  static ApiResponse get getUserSuccess => ApiResponse(
        status: true,
        message: "Got the user.",
        data: _userObject.toJson(),
      );

  /// Logout response
  static ApiResponse get logoutSuccess => ApiResponse(status: true, message: "You are logged out");
}
