import 'package:flutter/cupertino.dart';
import 'package:get/get.dart';
import 'package:ui_x/helpers/Toastr.dart';

import '/app/models/ApiResponse.dart';
import '/app/models/PaginationModel.dart';
import '/app/modules/Modules.dart';
import '/app/shared/controllers/AppController.dart';

class NotificationsIndexController extends AppController {
  /// Create and get the instance of the controller
  static NotificationsIndexController get instance {
    if (!Get.isRegistered<NotificationsIndexController>())
      Get.put(NotificationsIndexController());
    return Get.find<NotificationsIndexController>();
  }

  /// Initialise [NotificationsModule] service
  final NotificationsService _notificationsService =
      NotificationsService.instance;

  /// Observables
  final _index = PaginationModel().obs;

  /// Getters
  List get data => _index.value.data?.data ?? [];

  late var _counter = 0;

  /// Inputs
  GlobalKey<FormState> formKey =
      GlobalKey<FormState>(debugLabel: 'searchUserNotifications');
  final TextEditingController searchInput = TextEditingController();

  @override
  void onInit() {
    super.onInit();
    index();
  }

  Future<void> index() async {
    String _client = "indexUserNotifications";

    setBusy(true);

    _notificationsService.init(_client);

    ApiResponse response = await _notificationsService.index(_client, null);

    _notificationsService.close(_client);

    PaginationModel data = PaginationModel.fromJson(response.toJson());

    _index.value = data;

    setBusy(false);
  }

  Future<void> makeRead(String id) async {
    setBusy(true);

    _notificationsService.init('show');

    ApiResponse response = await _notificationsService.show('show', id);

    setBusy(false);

    _notificationsService.close('show');

    Toastr.show(message: "${response.message}");
  }

  Future<void> clearAll() async {
    _notificationsService.init('delete');

    ApiResponse response = await _notificationsService.delete('delete');

    _notificationsService.close('delete');

    Toastr.show(message: "${response.message}");

    Get.offAllNamed(NotificationsRoutes.index);
  }

  Future<void> loadMore() async {
    String _client = "indexUserNotifications";

    var currentPage = _index.value.data!.currentPage ?? 1;

    if (currentPage != _index.value.data!.lastPage) {
      setBusy(true);

      currentPage++;

      _notificationsService.init(_client + (currentPage).toString());

      ApiResponse response = await _notificationsService.index(
          _client + (currentPage).toString(), (currentPage).toString());

      _notificationsService.close(_client + (currentPage).toString());

      PaginationModel data = PaginationModel.fromJson(response.toJson());

      _index.value..data!.currentPage = currentPage;

      _index.value.data!.data!.addAll(data.data!.data!);

      setBusy(false);
    }
  }
}
