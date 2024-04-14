library fire_connect;

part './helpers/FireService.dart';

class FireConnect {
  /// Private constructor to prevent instantiation from outside the class
  FireConnect._();

  /// Instance of the DataManager
  static final FireConnect _instance = FireConnect._();

  /// Factory constructor to return the same instance every time
  factory FireConnect() => _instance;

  /// Initialize [FireConnect]

  Future<void> init() async {}
}
