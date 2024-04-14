import 'package:get/get.dart';

extension StringUtils on dynamic {
  bool get isNull => GetUtils.isLengthLessOrEqual(this, 0);
  bool get isAlphabetOnly => GetUtils.isAlphabetOnly(this);
  bool get isApk => GetUtils.isAPK(this);
  bool get isBlank => GetUtils.isBlank(this)!;
  bool get isEmail => GetUtils.isEmail(this);
}
