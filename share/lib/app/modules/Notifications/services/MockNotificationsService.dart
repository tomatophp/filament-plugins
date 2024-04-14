import '/app/models/ApiResponse.dart';
import '/app/shared/services/Services.dart';
import 'NotificationsService.dart';

class MockNotificationsService extends BaseService
    implements NotificationsService {
  @override
  void init(String client) => null;

  /// Stop the server request
  @override
  void close(String client) => null;

  @override
  Future<ApiResponse> index(String client, String? page) async {
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> show(String client, String id) async {
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> delete(String client) async {
    throw UnimplementedError();
  }
}
