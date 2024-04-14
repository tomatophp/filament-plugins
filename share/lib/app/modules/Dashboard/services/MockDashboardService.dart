import '/app/models/ApiResponse.dart';
import '/app/shared/services/Services.dart';
import 'DashboardService.dart';

class MockDashboardService extends BaseService implements DashboardService {
  @override
  Future<ApiResponse> updateNotificationToken(String client, String token, String type) async {
    // TODO: implement googleLogin
    throw UnimplementedError();
  }
}
