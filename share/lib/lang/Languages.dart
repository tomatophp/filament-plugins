import 'package:get/get.dart';

class Languages extends Translations {
  final Map<String, Map<String, String>> _translationKeys;

  Languages(this._translationKeys);

  @override
  Map<String, Map<String, String>> get keys => _translationKeys;
}
