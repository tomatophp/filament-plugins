import 'package:flutter/cupertino.dart';
import 'package:geocoding/geocoding.dart';
import 'package:geolocator/geolocator.dart';
import 'package:get/get.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';

import '/app/shared/controllers/AppController.dart';

class LocationController extends AppController {
  /// Create and get the instance of the controller
  static LocationController get instance {
    if (!Get.isRegistered<LocationController>()) Get.put(LocationController());
    return Get.find<LocationController>();
  }

  late LatLng _gpsactual;
  LatLng _initialposition = LatLng(-12.122711, -77.027475);
  var activegps = true.obs;

  TextEditingController locationController = TextEditingController();
  late GoogleMapController _mapController;

  LatLng get gpsPosition => _gpsactual;

  LatLng get initialPos => _initialposition;
  final Set<Marker> _markers = Set();

  Set<Marker> get markers => _markers;

  GoogleMapController get mapController => _mapController;

  @override
  void onInit() {
    super.onInit();
    getUserLocation();
  }

  void getMoveCamera() async {
    List<Placemark> placemark = await placemarkFromCoordinates(
        _initialposition.latitude, _initialposition.longitude,
        localeIdentifier: "en_US");
    locationController.text = placemark[0].name!;
  }

  void getUserLocation() async {
    if (!(await Geolocator.isLocationServiceEnabled())) {
      activegps.value = false;
    } else {
      activegps.value = true;
      LocationPermission permission;
      permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
        if (permission == LocationPermission.denied) {
          return Future.error('Location permissions are denied');
        }
      }
      if (permission == LocationPermission.deniedForever) {
        // Permissions are denied forever, handle appropriately.
        return Future.error(
            'Location permissions are permanently denied, we cannot request permissions.');
      }

      Position position = await Geolocator.getCurrentPosition(
          desiredAccuracy: LocationAccuracy.high);
      List<Placemark> placemark =
          await placemarkFromCoordinates(position.latitude, position.longitude);
      _initialposition = LatLng(position.latitude, position.longitude);
      print(
          "the latitude is: ${position.longitude} and th longitude is: ${position.longitude} ");
      locationController.text = placemark[0].name!;
      _addMarker(_initialposition, placemark[0].name!);
      _mapController.moveCamera(CameraUpdate.newLatLng(_initialposition));
      print("initial position is : ${placemark[0].name}");
    }
  }

  void onCreated(GoogleMapController controller) {
    _mapController = controller;
  }

  void _addMarker(LatLng location, String address) {
    _markers.add(Marker(
        markerId: MarkerId(location.toString()),
        position: location,
        infoWindow: InfoWindow(title: address, snippet: "go here"),
        icon: BitmapDescriptor.defaultMarker));
  }

  void onCameraMove(CameraPosition position) async {
    print(position.target);
    position = CameraPosition(target: initialPos, zoom: 18.0);
    _initialposition = position.target;
  }
}
