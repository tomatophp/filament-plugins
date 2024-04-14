import '/app/helpers/Request.dart';

class BaseService  {
  /// Create a request class instance
  late Request api;

  /// Instantiate Service
  BaseService() {
    api = new Request();
  }

  /// Start the server [Request]
  void init(String client) => api.start(client);

  /// Stop the server [Request]
  void close(String client) => api.close(client);
}
