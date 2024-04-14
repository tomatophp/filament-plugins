import '/app/helpers/Request.dart';
import '/app/models/ApiResponse.dart';
import '/app/shared/services/Services.dart';
import 'NotificationsService.dart';

class AppNotificationsService extends BaseService
    implements NotificationsService {
  late Request _request;
  AppNotificationsService() {
    _request = new Request();
  }

  /// Start the server request
  @override
  void init(String client) => _request.start(client);

  /// Stop the server request
  @override
  void close(String client) => _request.close(client);

  @override
  Future<ApiResponse> index(String client, String? page) async {
    var getPage = page ?? "1";
    if (int.parse(getPage) > 1) {
      return await _request.get('/notifications?page=' + getPage,
          client: client, authenticate: true);
    } else {
      return await _request.get('/notifications',
          client: client, authenticate: true);
    }
  }

  @override
  Future<ApiResponse> show(String client, String id) async {
    return await _request.post('/notifications/' + id + '/read',
        client: client, authenticate: true);
  }

  @override
  Future<ApiResponse> delete(String client) async {
    return await _request.post('/notifications/clear',
        body: [], client: client, authenticate: true);
  }
}
