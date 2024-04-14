import 'dart:convert';
import 'dart:io';
import 'package:path_provider/path_provider.dart';

import 'package:get/get.dart';
import 'package:http/http.dart' as http;
import 'package:ui_x/ui_x.dart';

import '/config/Config.dart';
import '/app/models/ApiResponse.dart';
import 'Global.dart';

class Request {
  static const int TIME_OUT_DURATION = 300; // [ In Seconds ]

  final List<HttpClient> _clients = [];

  void start(String client) {
    if(_clients.length > 0){
      HttpClient? _client = _clients.firstWhere((element) => element.id == client, orElse: null);
      if(_client != null){
       return;
      }
    }

    _clients.add(HttpClient(id: client, client: http.Client()));
  }

  void close(String client) {
    HttpClient _client = _clients.firstWhere((element) => element.id == client);

    _client.client.close();

    _clients.remove(_client);
  }

  ///====================
  /// GET Request
  ///====================
  Future<dynamic> get(String url, {required String client, Map<String, dynamic>? params, Map<String, String>? headers, bool authenticate = false}) async {
    HttpClient _httpClient = _clients.firstWhere((element) => element.id == client);
    http.Response response =
        await _httpClient.client.get(_sanitizedUri(url, params), headers: _getHeaders(token: authenticate, userHeaders: headers)).timeout(Duration(seconds: TIME_OUT_DURATION));
    return _processResponse(response);
  }

  ///====================
  /// POST Request
  ///====================
  Future<dynamic> post(String url, {required String client, Map<String, dynamic>? params, Map<String, String>? headers, dynamic body, bool authenticate = false}) async {
    HttpClient _httpClient = _clients.firstWhere((element) => element.id == client);
    String payload = json.encode(body);
    http.Response response = await _httpClient.client
        .post(_sanitizedUri(url, params), body: payload, headers: _getHeaders(token: authenticate, userHeaders: headers))
        .timeout(Duration(seconds: TIME_OUT_DURATION));

    return _processResponse(response);
  }

  ///====================
  /// MULTIPART Request
  ///====================
  Future<dynamic> multipart(String url,
      {String method = 'POST',
      required String client,
      required Map<String, dynamic> body,
      Map<String, dynamic>? params,
      Map<String, String>? headers,
      bool authenticate = false}) async {
    assert(body.containsKey('files'), "The body must contain [files] list");
    assert(body['files'] != null, "[files] list can not be null or empty");
    assert(body['files'] is Map<String, File> || body['files'] is Map<String, List<File>>, "[files] list must be [Map<String, File>] or [Map<String, List<File>>].");
    assert(method.toUpperCase() == "POST" || method.toUpperCase() == "PUT", "[method] can be either [POST] or [PUT].");
    HttpClient _httpClient = _clients.firstWhere((element) => element.id == client);
    http.MultipartRequest request = http.MultipartRequest("$method", _sanitizedUri(url, params));

    body.keys.forEach((key) {
      if (key != 'files') {
        request.fields['$key'] = body["$key"];
      }
    });

    Map<String, dynamic> fileMap = body['files'];

    fileMap.keys.forEach((key) async {
      if (fileMap["$key"] is List<File>) {
        for (File _file in fileMap["$key"]) {
          request.files.add(await http.MultipartFile.fromPath(key + "[]", _file.path));
        }
      } else if (fileMap["$key"] is File) {
        request.files.add(await http.MultipartFile.fromPath("$key", fileMap["$key"].path));
      }
    });

    /// Set Headers
    request.headers.addAll(_getHeaders(token: authenticate));

    http.Response response = await http.Response.fromStream(await _httpClient.client.send(request));
    return _processResponse(response);
  }

  ///====================
  /// PUT Request
  ///====================
  Future<dynamic> put(String url, {required String client, Map<String, dynamic>? params, Map<String, String>? headers, dynamic body, bool authenticate = false}) async {
    String payload = json.encode(body);
    HttpClient _httpClient = _clients.firstWhere((element) => element.id == client);
    http.Response response = await _httpClient.client
        .put(_sanitizedUri(url, params), body: payload, headers: _getHeaders(token: authenticate, userHeaders: headers))
        .timeout(Duration(seconds: TIME_OUT_DURATION));

    return _processResponse(response);
  }

  ///====================
  /// DELETE Request
  ///====================
  Future<dynamic> delete(String url, {required String client, Map<String, dynamic>? params, Map<String, String>? headers, dynamic body, bool authenticate = false}) async {
    String payload = json.encode(body);

    HttpClient _httpClient = _clients.firstWhere((element) => element.id == client);
    http.Response response = await _httpClient.client
        .delete(_sanitizedUri(url, params), body: payload, headers: _getHeaders(token: authenticate, userHeaders: headers))
        .timeout(Duration(seconds: TIME_OUT_DURATION));

    return _processResponse(response);
  }


  ///====================
  /// DOWNLOAD [File] Request
  ///====================
  Future<dynamic> download(String url, {required String client, String? fileName, bool authenticate = false}) async {


    HttpClient _httpClient = _clients.firstWhere((element) => element.id == client);
    http.Response response = await _httpClient.client.get(_sanitizedUri(url, {}), headers: _getHeaders(token: authenticate, userHeaders: {})).timeout(Duration(seconds: TIME_OUT_DURATION));

    String _dir = (await getApplicationDocumentsDirectory()).path;
    File _file = new File('$_dir/$fileName');
    await _file.writeAsBytes(response.bodyBytes);
    return _file;
  }

  ///======================================
  /// Prepare Header for requests
  ///
  /// @var bool token = true
  ///======================================
  static Map<String, String> _getHeaders({bool token = true, Map<String, String>? userHeaders}) {
    Map<String, String> headers = {
      "Accept": "application/json",
      "Content-type": "application/json",
    };

    if (token) {
      var _token = storage.read('token');
      headers = {
        "Content-type": "application/json",
        "Accept": "application/json",
        "Authorization": "Bearer $_token",
      };
    }
    if (userHeaders != null) headers.assignAll(userHeaders);

    return headers;
  }

  ///======================
  /// Process the Response
  ///======================
  static dynamic _processResponse(http.Response response) {
    print(response.body);
    print(response.statusCode);
    ApiResponse body = ApiResponse.fromJson(jsonDecode(response.body));

    switch (response.statusCode) {
      case 200:
        return body;
      case 201:
        return body;
      case 400:
        return body;
      case 401:
      case 403:
        Toastr.show(message: "You are logged out!");
        auth.logoutSilently();
        return;
      case 422:
        return body;
      case 404:
      case 500:
        return body;
      default:
        return body;
    }
  }

  ///======================
  /// Sanitize the API uri
  ///======================
  static dynamic _sanitizedUri(String uri, Map<String, dynamic>? params) {
    if (uri[0] != "/") {
      uri = "/$uri${_handleParams(params)}";
    } else {
      uri = "$uri${_handleParams(params)}";
    }
    return Uri.parse("${Config.apiBaseUrl}$uri");
  }

  ///======================
  /// Parse the url parameters
  ///======================
  static String _handleParams(Map<String, dynamic>? params) {
    String _params = '';

    params?.keys.forEach((key) {
      if (params.keys.first == key) {
        _params += "?$key=${params[key]}";
      } else {
        _params += "&$key=${params[key]}";
      }
    });
    return _params;
  }
}

/// HttpClient Model Class
class HttpClient {
  HttpClient({
    required this.id,
    required this.client,
  });

  String id;
  http.Client client;
}
