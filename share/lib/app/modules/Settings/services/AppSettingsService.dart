import '/app/helpers/Request.dart';
import '/app/models/ApiResponse.dart';
import 'SettingsService.dart';

class AppSettingsService implements SettingsService {
  late Request _request;
  AppSettingsService() {
    _request = new Request();
  }

  /// Start the server request
  @override
  void init(String client) => _request.start(client);

  /// Stop the server request
  @override
  void close(String client) => _request.close(client);

  @override
  Future<ApiResponse> closeAccount(client) async {
    return await _request.delete('/profile/destroy', client: client, authenticate: true);
  }
}
