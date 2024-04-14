import '/app/models/UserModel.dart';
import 'package:get/get.dart';

import '/data/AuthData.dart';
import '/data/UsersData.dart';
import '/app/models/ApiResponse.dart';
import 'AuthService.dart';

class MockAuthService implements AuthService {
  /// Start the server request
  @override
  void init(String client) => null;

  /// Stop the server request
  @override
  void close(String client) => null;

  @override
  Future<ApiResponse> login({required String client, required Map<String, dynamic> body}) async {
    return await 300.milliseconds.delay(() {
      bool _userNotFound = UsersData.users.where((user) {
        String? _identifier = body['identifier'].contains('@')
            ? user.email
            : (new RegExp(r'(^(?:[+0]9)?[0-9]{10,12}$)')).hasMatch(body['identifier'])
                ? user.phone
                : user.username;
        return body['identifier'] == _identifier && body['password'] == user.password;
      }).isEmpty;

      if (_userNotFound) {
        return AuthData.loginError;
      }

      return AuthData.loginSuccess;
    });
  }

  @override
  Future<ApiResponse> forget({required String client, required Map<String, dynamic> body}) async {
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> verifyOtpAndActivate({required String client, required Map<String, dynamic> body}) async {
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> password({required String client, required Map<String, dynamic> body}) async {
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> resend({required String client, required Map<String, dynamic> body}) async {
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> google({required String client}) {
    // TODO: implement googleLogin
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> facebook({required String client}) {
    // TODO: implement facebook
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> github({required String client}) {
    // TODO: implement github
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> register({required String client, required Map<String, dynamic> body}) async {
    UsersData.users.add(UserModel.fromJson(body).copyWith(
      id: UsersData.users.last.id! + 1,
      avatar: "https://random.imagecdn.app/500/500",
    ));
    return await 300.milliseconds.delay(() => AuthData.registerSuccess);
  }

  @override
  Future<ApiResponse> verifyOtp({required String client, required Map<String, dynamic> body}) async {
    return await 300.milliseconds.delay(() => AuthData.loginSuccess);
  }

  @override
  Future<ApiResponse> user(String client) async {
    return await 300.milliseconds.delay(() => AuthData.loginSuccess);
  }
}
