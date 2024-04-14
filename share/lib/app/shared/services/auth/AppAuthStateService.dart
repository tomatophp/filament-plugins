import '/app/models/ApiResponse.dart';

import '/app/helpers/Global.dart';
import '/app/helpers/Request.dart';
import '/app/shared/services/Services.dart';

class AppAuthStateService extends BaseService implements AuthStateService {
  late Request _request;
  AppAuthStateService() {
    _request = new Request();
  }

  @override
  Future<ApiResponse> getUser() async {
    if (storage.read("token") != null) {
      return await _request.get('/profile', client: 'getUser', authenticate: true);
    } else {
      return ApiResponse(status: false, message: "Something went wrong");
    }
  }

  @override
  Future<ApiResponse> logout() async {
    return await _request.get('/logout', client: 'logout', authenticate: true);
  }
}
