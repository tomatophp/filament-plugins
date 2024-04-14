import '/app/models/ApiResponse.dart';
import '/app/helpers/Request.dart';
import 'ProfileService.dart';

class AppProfileService implements ProfileService {
  late Request _request;
  AppProfileService() {
    _request = new Request();
  }

  /// Start the server request
  @override
  void init(String client) => _request.start(client);

  /// Stop the server request
  @override
  void close(String client) => _request.close(client);

  @override
  Future<ApiResponse> getData(client) async {
    return await _request.get('/profile', client: client, authenticate: true);
  }

  @override
  Future<ApiResponse> update(client, body) async {
    return await _request.post('/profile', body: body, client: client, authenticate: true);
  }

  @override
  Future<ApiResponse> password(client, body) async {
    return await _request.post('/profile/password', body: body, client: client, authenticate: true);
  }
}
