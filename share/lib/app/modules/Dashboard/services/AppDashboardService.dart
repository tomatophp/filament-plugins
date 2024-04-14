import '/app/helpers/Request.dart';
import '/app/models/ApiResponse.dart';
import '/app/shared/services/Services.dart';
import 'DashboardService.dart';

class AppDashboardService extends BaseService implements DashboardService {
  late Request _request;
  AppDashboardService() {
    _request = new Request();
  }

  /// Start the server request
  @override
  void init(String client) => _request.start(client);

  /// Stop the server request
  @override
  void close(String client) => _request.close(client);

  @override
  Future<ApiResponse> updateNotificationToken(String client, String token, String type) async {
    return await _request.post('/notifications/toggle',body: {
      'token': token,
      'provider': type
    }, client: client, authenticate: true);
  }
}
