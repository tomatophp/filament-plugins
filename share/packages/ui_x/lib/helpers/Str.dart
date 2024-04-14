class Str {
  /// Create first letter of any string uppercase
  static String ucFirst(String str) {
    return str[0].toUpperCase() + str.substring(1);
  }

  /// Create slug from given string
  static String slug(String str) {
    return str.trim().replaceAll("  ", " ").replaceAll(" ", "-").toLowerCase();
  }

  /// Covert given String to lowercase
  static String toLower(String str) {
    return str.toLowerCase();
  }

  /// Covert given String to uppercase
  static String toUpper(String str) {
    return str.toUpperCase();
  }

  /// Covert given String to snack case
  static String snack(String str) {
    return str.replaceAll(" ", "_").toLowerCase();
  }

  /// Covert given String to title case
  static String title(String str) {
    return str
        .replaceAll("_", " ")
        .replaceAll("-", " ")
        .split(" ")
        .map((s) => ucFirst(s))
        .join(" ");
  }
}
